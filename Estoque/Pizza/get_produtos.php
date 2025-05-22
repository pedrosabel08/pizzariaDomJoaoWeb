<?php
// Configurações do banco de dados

include("../conexao.php");

// Consulta os produtos
$sql = "SELECT idprodutos, nomeProduto FROM produtos ORDER BY nomeProduto ASC";
$result = $conn->query($sql);

// Verifica se há resultados
$produtos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
}

// Retorna os dados em JSON
header('Content-Type: application/json');
echo json_encode(['produtos' => $produtos]);

// Fecha a conexão
$conn->close();
