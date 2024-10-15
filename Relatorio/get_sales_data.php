<?php
// Configuração do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd_pizzaria"; // Nome do banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Receber o período selecionado via GET
$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : 'ano';

// Preparar a query SQL com base no período
if ($periodo == 'ano') {
    $sql = "SELECT YEAR(data_venda) AS periodo, SUM(total) AS total_vendas 
    FROM vendas 
    GROUP BY YEAR(data_venda) 
    ORDER BY YEAR(data_venda) ASC";
} elseif ($periodo == 'semana') {
    $sql = "SELECT CONCAT(YEAR(data_venda), ' - Semana ', WEEK(data_venda)) AS periodo, SUM(total) AS total_vendas 
    FROM vendas 
    GROUP BY YEAR(data_venda), WEEK(data_venda) 
    ORDER BY YEAR(data_venda), WEEK(data_venda) ASC";
} else {
    $sql = "SELECT DATE_FORMAT(data_venda, '%Y-%m-%d') AS periodo, SUM(total) AS total_vendas 
            FROM vendas 
            GROUP BY DATE_FORMAT(data_venda, '%Y-%m-%d') 
            ORDER BY data_venda ASC";
}

$result = $conn->query($sql);

// Preparar os dados para enviar ao JavaScript
$labels = [];
$sales = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['periodo'];
        $sales[] = $row['total_vendas'];
    }
}

// Retornar os dados em formato JSON
echo json_encode([
    'labels' => $labels,
    'sales' => $sales
]);

$conn->close();
