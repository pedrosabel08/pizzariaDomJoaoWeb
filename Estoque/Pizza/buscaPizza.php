<?php
include '../conexao.php';

if (isset($_GET['id'])) {
    $idPizza = $_GET['id'];

    // Dados principais da pizza
    $stmt = $conn->prepare("SELECT nomePizza, tipoPizza FROM pizzas WHERE idpizzas = ?");
    $stmt->bind_param("i", $idPizza);
    $stmt->execute();
    $stmt->bind_result($nome, $tipo);
    $stmt->fetch();
    $stmt->close();

    // Ingredientes da pizza
    $ingredientes = [];
    $sql = "SELECT produto_id, quantidade FROM pizzas_produtos WHERE pizza_id = ?";
    $stmt2 = $conn->prepare($sql);
    $stmt2->bind_param("i", $idPizza);
    $stmt2->execute();
    $result = $stmt2->get_result();

    while ($row = $result->fetch_assoc()) {
        $ingredientes[] = $row;
    }

    $stmt2->close();

    // Lista de todos os produtos para popular o <select>
    $produtos = [];
    $sqlProdutos = "SELECT idprodutos, nomeProduto FROM produtos";
    $resultProdutos = $conn->query($sqlProdutos);

    while ($row = $resultProdutos->fetch_assoc()) {
        $produtos[] = [
            "id" => (int)$row['idprodutos'],
            "nome" => $row['nomeProduto']
        ];
    }

    // Resposta JSON
    echo json_encode([
        "nome" => $nome,
        "tipo" => $tipo,
        "ingredientes" => $ingredientes,
        "produtos" => $produtos
    ]);
}
