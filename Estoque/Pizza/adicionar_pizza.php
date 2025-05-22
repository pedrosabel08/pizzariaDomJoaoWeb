<?php
include("../conexao.php");

// Verifica conexão
if ($conn->connect_error) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro na conexão com o banco de dados: " . $conn->connect_error]);
    exit;
}

// Recebe o JSON enviado via fetch
$dados = json_decode(file_get_contents('php://input'), true);

if (
    !$dados ||
    !isset($dados['nome']) ||
    !isset($dados['tipo']) ||
    !isset($dados['ingredientes']) ||
    !is_array($dados['ingredientes'])
) {
    echo json_encode(["sucesso" => false, "mensagem" => "Dados inválidos ou incompletos."]);
    exit;
}

$nomePizza = trim($dados['nome']);
$tipoPizza = trim($dados['tipo']);
$ingredientes = $dados['ingredientes'];
$idpizza = isset($dados['idpizza']) ? (int) $dados['idpizza'] : 0;

// Validação básica
if (empty($nomePizza) || empty($tipoPizza)) {
    echo json_encode(["sucesso" => false, "mensagem" => "Nome ou tipo da pizza não podem estar vazios."]);
    exit;
}

if ($idpizza > 0) {
    // Atualiza pizza existente
    $sqlPizza = "UPDATE pizzas SET nomePizza = ?, tipoPizza = ? WHERE idpizzas = ?";
    $stmtPizza = $conn->prepare($sqlPizza);
    if (!$stmtPizza) {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro ao preparar atualização da pizza: " . $conn->error]);
        exit;
    }
    $stmtPizza->bind_param("ssi", $nomePizza, $tipoPizza, $idpizza);
    if (!$stmtPizza->execute()) {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro ao atualizar pizza: " . $stmtPizza->error]);
        exit;
    }
} else {
    // Insere nova pizza
    $sqlPizza = "INSERT INTO pizzas (nomePizza, tipoPizza) VALUES (?, ?)";
    $stmtPizza = $conn->prepare($sqlPizza);
    if (!$stmtPizza) {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro ao preparar inserção da pizza: " . $conn->error]);
        exit;
    }
    $stmtPizza->bind_param("ss", $nomePizza, $tipoPizza);
    if (!$stmtPizza->execute()) {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro ao inserir nova pizza: " . $stmtPizza->error]);
        exit;
    }
    $idpizza = $stmtPizza->insert_id;
}

$stmtPizza->close();

// Buscar ingredientes atuais
$sqlBuscaIng = "SELECT produto_id FROM pizzas_produtos WHERE pizza_id = ?";
$stmtBuscaIng = $conn->prepare($sqlBuscaIng);
if (!$stmtBuscaIng) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao preparar busca de ingredientes: " . $conn->error]);
    exit;
}
$stmtBuscaIng->bind_param("i", $idpizza);
$stmtBuscaIng->execute();
$result = $stmtBuscaIng->get_result();

$ingredientesNoBanco = [];
while ($row = $result->fetch_assoc()) {
    $ingredientesNoBanco[] = $row['produto_id'];
}
$stmtBuscaIng->close();

// Novos ingredientes
$ingredientesNovosIds = array_map(fn($ing) => $ing['produto_id'], $ingredientes);

// Ingredientes a remover
$ingredientesParaDeletar = array_diff($ingredientesNoBanco, $ingredientesNovosIds);

if (!empty($ingredientesParaDeletar)) {
    $placeholders = implode(",", array_fill(0, count($ingredientesParaDeletar), '?'));
    $tipos = str_repeat('i', count($ingredientesParaDeletar));
    $sqlDelete = "DELETE FROM pizzas_produtos WHERE pizza_id = ? AND produto_id IN ($placeholders)";
    $stmtDelete = $conn->prepare($sqlDelete);
    if (!$stmtDelete) {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro ao preparar exclusão de ingredientes: " . $conn->error]);
        exit;
    }
    $params = array_merge([$idpizza], $ingredientesParaDeletar);
    $stmtDelete->bind_param("i" . $tipos, ...$params);
    if (!$stmtDelete->execute()) {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro ao excluir ingredientes antigos: " . $stmtDelete->error]);
        exit;
    }
    $stmtDelete->close();
}

// Insere/atualiza ingredientes
$sqlIng = "INSERT INTO pizzas_produtos (pizza_id, produto_id, quantidade)
           VALUES (?, ?, ?)
           ON DUPLICATE KEY UPDATE quantidade = VALUES(quantidade)";
$stmtIng = $conn->prepare($sqlIng);
if (!$stmtIng) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao preparar inserção de ingredientes: " . $conn->error]);
    exit;
}

foreach ($ingredientes as $ing) {
    if (!isset($ing['produto_id'], $ing['quantidade'])) {
        continue;
    }

    $produtoId = (int) $ing['produto_id'];
    $quantidade = (int) $ing['quantidade'];

    if ($produtoId <= 0 || $quantidade < 0) {
        continue;
    }

    $stmtIng->bind_param("iii", $idpizza, $produtoId, $quantidade);
    if (!$stmtIng->execute()) {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro ao inserir ingrediente $produtoId: " . $stmtIng->error]);
        exit;
    }
}

$stmtIng->close();
$conn->close();

echo json_encode(["sucesso" => true, "mensagem" => "Pizza salva com sucesso."]);
