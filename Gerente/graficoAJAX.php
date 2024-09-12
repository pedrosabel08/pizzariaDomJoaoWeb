<?php
include('conexao.php');

$sql = "SELECT s.nome_status, COUNT(v.idvendas) AS total
        FROM vendas v
        INNER JOIN status_venda s ON v.status_id = s.idstatus
        GROUP BY s.nome_status";

$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>