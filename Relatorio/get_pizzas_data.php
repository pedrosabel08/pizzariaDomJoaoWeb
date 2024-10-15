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
    $sql = "SELECT p.nomePizza AS nome_pizza, COUNT(DISTINCT v.idvendas) AS qtd_vendida
            FROM pizzas p
            INNER JOIN vendas_pizzas vp ON p.idpizzas = vp.pizzas_idpizzas
            INNER JOIN vendas v ON vp.vendas_idvendas = v.idvendas
            GROUP BY p.nomePizza, YEAR(v.data_venda)
            ORDER BY qtd_vendida DESC";
} elseif ($periodo == 'semana') {
    $sql = "SELECT p.nomePizza AS nome_pizza, COUNT(DISTINCT v.idvendas) AS qtd_vendida 
            FROM pizzas p
            INNER JOIN vendas_pizzas vp ON p.idpizzas = vp.pizzas_idpizzas
            INNER JOIN vendas v ON vp.vendas_idvendas = v.idvendas
            GROUP BY p.nomePizza, YEAR(v.data_venda), WEEK(v.data_venda)
            ORDER BY qtd_vendida DESC";
} else {
    $sql = "SELECT p.nomePizza AS nome_pizza, COUNT(DISTINCT v.idvendas) AS qtd_vendida 
            FROM pizzas p
            INNER JOIN vendas_pizzas vp ON p.idpizzas = vp.pizzas_idpizzas
            INNER JOIN vendas v ON vp.vendas_idvendas = v.idvendas
            GROUP BY p.nomePizza, DATE(v.data_venda)
            ORDER BY qtd_vendida DESC";
}

$result = $conn->query($sql);

// Preparar os dados para enviar ao JavaScript
$pizzas = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pizzas[] = [
            'nome_pizza' => $row['nome_pizza'],
            'qtd_vendida' => $row['qtd_vendida']
        ];
    }
}

// Retornar os dados em formato JSON
echo json_encode($pizzas);

$conn->close();
