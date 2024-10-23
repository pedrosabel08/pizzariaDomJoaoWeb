<?php
include('conexao.php');

// Consulta SQL para buscar os dados dos pedidos
$sql = "SELECT 
    vp.vendas_idvendas, 
    t.nome AS tamanho, 
    b.nome AS borda, 
    GROUP_CONCAT(DISTINCT p.nomePizza SEPARATOR ', ') AS sabores, 
    v.data_venda, 
    s.nome_status, 
    v.tempo_espera,
    v.status_id
FROM vendas_pizzas vp
JOIN pizzas p ON vp.pizzas_idpizzas = p.idpizzas
JOIN tamanho t ON vp.tamanho_idtamanho = t.idtamanho
JOIN bordas_pizza b ON vp.borda_idbordas_pizza = b.idbordas_pizza
JOIN vendas v ON v.idvendas = vp.vendas_idvendas
JOIN status_venda s ON v.status_id = s.idstatus
JOIN clientes c ON v.cliente_id = c.idclientes
WHERE DATE(v.data_venda) = CURDATE()
GROUP BY 
    vp.vendas_idvendas, 
    t.nome, 
    b.nome, 
    v.data_venda, 
    s.nome_status,
    v.tempo_espera;";

$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Retorna os dados em formato JSON
header('Content-Type: application/json');
echo json_encode($data);
