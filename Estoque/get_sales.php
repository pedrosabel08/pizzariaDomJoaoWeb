<?php
header('Content-Type: application/json');

if (isset($_GET['date'])) {
    $date = $_GET['date'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bd_pizzaria";
    $port = "3307";

    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "            SELECT 
    vp.vendas_idvendas,
    p.nomePizza AS 'Nome Pizza',
    t.nome AS Tamanho,
    b.nome AS Borda,
    v.total,
    v.data_venda AS Data
FROM 
    vendas_pizzas vp
INNER JOIN 
    vendas v ON vp.vendas_idvendas = v.idvendas
INNER JOIN 
    pizzas p ON vp.pizzas_idpizzas = p.idpizzas
INNER JOIN 
    tamanho t ON vp.tamanho_idtamanho = t.idtamanho
INNER JOIN 
    bordas_pizza b ON vp.borda_idbordas_pizza = b.idbordas_pizza
WHERE 
    DATE(v.data_venda) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $sales = array();
    while ($row = $result->fetch_assoc()) {
        $sales[] = $row;
    }

    $stmt->close();
    $conn->close();

    echo json_encode($sales);
} else {
    echo json_encode(array("error" => "No date provided"));
}
