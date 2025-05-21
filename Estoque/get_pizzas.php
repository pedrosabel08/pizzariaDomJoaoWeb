<?php
include 'conexao.php'; // substitua pelos dados da sua conexão

if (isset($_GET['id'])) {
    $idPizza = $_GET['id'];

    $stmt = $conn->prepare("SELECT nomePizza, tipoPizza FROM pizzas WHERE idpizzas = ?");
    $stmt->bind_param("i", $idPizza);
    $stmt->execute();
    $stmt->bind_result($nome, $tipo);
    $stmt->fetch();
    $stmt->close();

    $ingredientes = [];
    $sql = "SELECT produto_id, quantidade FROM pizzas_produtos WHERE pizza_id = ?";
    $stmt2 = $conn->prepare($sql);
    $stmt2->bind_param("i", $idPizza);
    $stmt2->execute();
    $result = $stmt2->get_result();

    while ($row = $result->fetch_assoc()) {
        $ingredientes[] = $row;
    }

    echo json_encode([
        "nome" => $nome,
        "tipo" => $tipo,
        "ingredientes" => $ingredientes
    ]);
}
