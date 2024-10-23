<?php
include('conexao.php');

$sql = "SELECT 
v.idvendas AS vendas_idvendas,
GROUP_CONCAT(DISTINCT p.nomePizza) AS pizzas, 
vb.bebidas, 
t.nome AS tamanho, 
b2.nome AS borda, 
c.nome,
v.data_venda,
v.total,
s.nome_status
FROM vendas v
LEFT JOIN vendas_pizzas vp ON v.idvendas = vp.vendas_idvendas
LEFT JOIN pizzas p ON vp.pizzas_idpizzas = p.idpizzas
LEFT JOIN tamanho t ON vp.tamanho_idtamanho = t.idtamanho
LEFT JOIN bordas_pizza b2 ON vp.borda_idbordas_pizza = b2.idbordas_pizza
LEFT JOIN (
SELECT 
    vb.vendas_idvendas, 
    GROUP_CONCAT(b.nomeBebida SEPARATOR ', ') AS bebidas, 
    SUM(vb.quantidade) AS quantidade_bebidas
FROM vendas_bebidas vb
LEFT JOIN bebidas b ON vb.bebidas_idbebidas = b.idbebidas
GROUP BY vb.vendas_idvendas
) vb ON v.idvendas = vb.vendas_idvendas
INNER JOIN clientes c ON v.cliente_id = c.idclientes
INNER JOIN status_venda s ON v.status_id = s.idstatus
WHERE DATE(v.data_venda) = CURDATE()  -- Ajuste para considerar só a data
GROUP BY v.idvendas, c.nome, v.data_venda, v.total, s.nome_status, t.nome, b2.nome
ORDER BY v.data_venda DESC;";

$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Retorna os dados em formato JSON
echo json_encode($data);
