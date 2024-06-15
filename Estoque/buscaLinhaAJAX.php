<?php
    include 'conexao.php';

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $idProduto = $_GET['ajid'];
    }

    $sql = "SELECT produtos.idprodutos, produtos.nomeProduto, produtos.quantidade, unidademedida.idunidadeMedida as unidadeMedida, produtos.validade 
        FROM produtos 
        INNER JOIN unidademedida ON produtos.unidadeMedida = unidademedida.idunidadeMedida 
        WHERE produtos.idprodutos = $idProduto
        ORDER BY produtos.nomeProduto ASC;";
    $result = $conn->query($sql);

    $data = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    header('Content-Type: application/json');
    echo json_encode($data);
?>
