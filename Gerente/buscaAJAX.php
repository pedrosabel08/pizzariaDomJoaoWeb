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
        GROUP_CONCAT(DISTINCT p.nomePizza) AS pizzas, 
        GROUP_CONCAT(DISTINCT b.nomeBebida SEPARATOR ', ') AS bebidas,  -- Adicionado para pegar as bebidas
        t.nome AS tamanho, 
        b2.nome AS borda, 
        c.nome AS nome_cliente,
        c.telefone,
        v.data_venda,
        v.total,
        s.nome_status,
        v.tempo_espera
    FROM vendas v
    LEFT JOIN vendas_pizzas vp ON v.idvendas = vp.vendas_idvendas
    LEFT JOIN pizzas p ON vp.pizzas_idpizzas = p.idpizzas
    LEFT JOIN tamanho t ON vp.tamanho_idtamanho = t.idtamanho
    LEFT JOIN bordas_pizza b2 ON vp.borda_idbordas_pizza = b2.idbordas_pizza
    LEFT JOIN vendas_bebidas vb ON v.idvendas = vb.vendas_idvendas
    LEFT JOIN bebidas b ON vb.bebidas_idbebidas = b.idbebidas
    INNER JOIN clientes c ON v.cliente_id = c.idclientes
    INNER JOIN status_venda s ON v.status_id = s.idstatus
    WHERE v.idvendas = $idPedidoSelecionado
    GROUP BY v.idvendas, v.data_venda, v.total, s.nome_status, c.nome, c.telefone, t.nome, b2.nome, v.tempo_espera";

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