<?php
header('Content-Type: application/json');

// Conectar ao banco de dados
$conn = new mysqli('localhost',  'root', '', 'bd_pizzaria');

// Verificar a conexão
if ($conn->connect_error) {
    die(json_encode(["error" => "Falha na conexão: " . $conn->connect_error]));
}

$conn->set_charset('utf8mb4');

$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Prepare a consulta SQL
$sql = "SELECT p.nomePizza AS nome_pizza, COUNT(vp.pizzas_idpizzas) AS qtd_vendida 
FROM pizzas p
INNER JOIN vendas_pizzas vp ON p.idpizzas = vp.pizzas_idpizzas
INNER JOIN vendas v ON vp.vendas_idvendas = v.idvendas";

if ($startDate) {
    $sql .= " AND data_venda >= ?";
}
if ($endDate) {
    $sql .= " AND data_venda <= ?";
}

// Agrupando por sabor
$sql .= " GROUP BY nomePizza";

$stmt = $conn->prepare($sql);

$bindParams = [];
$types = '';

if ($startDate) {
    $types .= 's';
    $bindParams[] = $startDate;
}
if ($endDate) {
    $types .= 's';
    $bindParams[] = $endDate;
}

if ($bindParams) {
    $stmt->bind_param($types, ...$bindParams);
}

$stmt->execute();
$result = $stmt->get_result();

$sabores = [];
while ($row = $result->fetch_assoc()) {
    $sabores[] = $row;
}

echo json_encode($sabores);

$stmt->close();
$conn->close();