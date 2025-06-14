<?php
header('Content-Type: application/json');
include("../conexao.php");

$sql = "SELECT 
            s.id,
            s.data_saida,
            pr.nomeProduto, 
            p.nomePizza, 
            s.motivo, 
            s.idlote AS lote, 
            s.quantidade,
            s.venda_id
        FROM saidas_estoque s 
        JOIN pizzas p ON s.pizza_id = p.idpizzas 
        JOIN produtos pr ON pr.idprodutos = s.produto_id";

$result = $conn->query($sql);

$saidas = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $saidas[] = $row;
    }
}

$conn->close();

echo json_encode(array_values($saidas));
