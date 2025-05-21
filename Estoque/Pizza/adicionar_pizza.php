<?php
include("../conexao.php");

// Recebe o JSON enviado via fetch
$dados = json_decode(file_get_contents('php://input'), true);

if (!$dados || !isset($dados['nome']) || !isset($dados['tipo']) || !isset($dados['ingredientes'])) {
    echo json_encode(["sucesso" => false, "mensagem" => "Dados inválidos."]);
    exit;
}

$nomePizza = $dados['nome'];
$tipoPizza = $dados['tipo'];
$ingredientes = $dados['ingredientes'];

// Insere ou atualiza pizza
$sqlPizza = "INSERT INTO pizzas (nomePizza, tipoPizza) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE tipoPizza = VALUES(tipoPizza)";

$stmtPizza = $conn->prepare($sqlPizza);
$stmtPizza->bind_param("ss", $nomePizza, $tipoPizza);

if (!$stmtPizza->execute()) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao salvar pizza."]);
    exit;
}

// Pega o id da pizza (novo ou existente)
$pizzaId = $stmtPizza->insert_id;
if ($pizzaId == 0) {
    // Busca o id da pizza pelo nome, caso não tenha sido inserido
    $stmtBusca = $conn->prepare("SELECT idpizzas FROM pizzas WHERE nomePizza = ?");
    $stmtBusca->bind_param("s", $nomePizza);
    $stmtBusca->execute();
    $stmtBusca->bind_result($pizzaId);
    $stmtBusca->fetch();
    $stmtBusca->close();
}

// Pega os ids dos ingredientes atuais no banco para essa pizza
$result = $conn->query("SELECT produto_id FROM pizzas_produtos WHERE pizza_id = $pizzaId");
$ingredientesNoBanco = [];
while ($row = $result->fetch_assoc()) {
    $ingredientesNoBanco[] = $row['produto_id'];
}

// Extrai os ids dos ingredientes que vieram no JSON
$ingredientesNovosIds = array_map(function ($ing) {
    return $ing['produto_id'];
}, $ingredientes);

// Ingredientes para deletar: os que estão no banco mas não no JSON
$ingredientesParaDeletar = array_diff($ingredientesNoBanco, $ingredientesNovosIds);

// Deleta os ingredientes removidos
if (!empty($ingredientesParaDeletar)) {
    $idsParaDeletarStr = implode(",", array_map('intval', $ingredientesParaDeletar));
    $conn->query("DELETE FROM pizzas_produtos WHERE pizza_id = $pizzaId AND produto_id IN ($idsParaDeletarStr)");
}

// Insere ou atualiza ingredientes (ON DUPLICATE KEY UPDATE)
$sqlIng = "INSERT INTO pizzas_produtos (pizza_id, produto_id, quantidade)
           VALUES (?, ?, ?)
           ON DUPLICATE KEY UPDATE quantidade = VALUES(quantidade)";
$stmtIng = $conn->prepare($sqlIng);

foreach ($ingredientes as $ing) {
    $produtoId = $ing['produto_id'];
    $quantidade = $ing['quantidade'];
    $stmtIng->bind_param("iii", $pizzaId, $produtoId, $quantidade);
    $stmtIng->execute();
}

$stmtIng->close();
$stmtPizza->close();
$conn->close();

echo json_encode(["sucesso" => true]);
