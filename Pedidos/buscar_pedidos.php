<?php
session_start();

if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.html");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "bd_pizzaria");

if ($mysqli->connect_error) {
    die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idclientes = $_SESSION['cliente_id'];

    $sql = 'SELECT 
    v.idvendas AS vendas_idvendas,
    t.nome,
    v.data_venda,
    v.total,
    f.tipo,
    s.nome_status
    FROM vendas_pizzas vp
    INNER JOIN vendas v ON v.idvendas = vp.vendas_idvendas
    INNER JOIN forma_entrega f ON v.forma_entrega_id = f.idforma_entrega
    INNER JOIN clientes c ON v.cliente_id = c.idclientes
    INNER JOIN status_venda s ON v.status_id = s.idstatus
    INNER JOIN tamanho t ON vp.tamanho_idtamanho = t.idtamanho
    WHERE v.cliente_id = ?
    GROUP BY v.idvendas
    ORDER BY data_venda DESC;';

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('i', $idclientes);
        $stmt->execute();
        $result = $stmt->get_result();

        $response = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $response[] = $row;
            }
        }

        // Garante que sempre retorna um JSON válido
        header('Content-Type: application/json');
        echo json_encode($response);

        $stmt->close();
    } else {
        echo json_encode(array('error' => 'Erro ao preparar a consulta.'));
    }
}

$mysqli->close();
