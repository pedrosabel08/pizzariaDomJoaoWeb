<?php
include('conexao.php');

$sql = "SELECT 
v.idvendas AS vendas_idvendas,
GROUP_CONCAT(p.nomePizza) AS pizzas, 
GROUP_CONCAT(t.nome) AS tamanhos, 
GROUP_CONCAT(b.nome) AS bordas, 
c.nome,
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
GROUP BY v.idvendas, c.nome, v.data_venda, v.total, s.nome_status";

$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Retorna os dados em formato JSON
echo json_encode($data);
