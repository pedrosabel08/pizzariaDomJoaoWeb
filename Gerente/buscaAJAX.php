<?php

header("Access-Control-Allow-Origin: *"); // Allows all domains
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow specific methods
header("Access-Control-Allow-Headers: Content-Type");

include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $idPedidoSelecionado = $_GET['ajid'];

    // Proteção contra SQL Injection
    $idPedidoSelecionado = $conn->real_escape_string($idPedidoSelecionado);

    $sql = "SELECT 
    v.idvendas AS vendas_idvendas,
    GROUP_CONCAT(p.nomePizza) AS pizzas, 
    t.nome, 
    b.nome, 
    c.nome as nome_cliente,
    c.telefone,
    v.data_venda,
    v.total,
    s.nome_status
    FROM vendas_pizzas vp
    INNER JOIN vendas v ON v.idvendas = vp.vendas_idvendas
    INNER JOIN clientes c ON v.cliente_id = c.idclientes
    INNER JOIN status_venda s ON v.status_id = s.idstatus
    INNER JOIN pizzas p ON vp.pizzas_idpizzas = p.idpizzas
    INNER JOIN tamanho t ON vp.tamanho_idtamanho = t.idtamanho
    INNER JOIN bordas_pizza b ON vp.borda_idbordas_pizza = b.idbordas_pizza
    WHERE v.idvendas = $idPedidoSelecionado";

    $result = $conn->query($sql);

    $response = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
