<?php
header('Content-Type: application/json');
include("../conexao.php");

$sql = "SELECT p.idpizzas, p.nomePizza, pr.nomeProduto, pp.quantidade, um.nome 
        FROM pizzas AS p
        INNER JOIN pizzas_produtos AS pp ON p.idpizzas = pp.pizza_id
        INNER JOIN produtos AS pr ON pp.produto_id = pr.idprodutos
        INNER JOIN unidademedida AS um ON pr.unidadeMedida = um.idunidadeMedida
        ORDER BY p.idpizzas, pr.nomeProduto";

$result = $conn->query($sql);

$pizzas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pizza_id = $row['idpizzas'];
        if (!isset($pizzas[$pizza_id])) {
            $pizzas[$pizza_id] = [
                'idpizzas' => $row['idpizzas'],
                'nomePizza' => $row['nomePizza'],
                'ingredientes' => []
            ];
        }

        if ($row['nomeProduto']) {
            $pizzas[$pizza_id]['ingredientes'][] = [
                'nomeProduto' => $row['nomeProduto'],
                'quantidade' => $row['quantidade'],
                'unidadeMedida' => $row['nome']
            ];
        }
    }
}

$conn->close();

echo json_encode(array_values($pizzas)); // array_values para reindexar
