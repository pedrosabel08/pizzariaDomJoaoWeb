<?php
include '../conexao.php';

$tipo = $_POST['tipo'];
$id = intval($_POST['id']);

if ($tipo == 'ingredientes') {
    $sql = "DELETE FROM produtos WHERE idprodutos=?";
} else {
    $sql = "DELETE FROM bebidas WHERE idbebidas=?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "ok";
} else {
    echo "erro";
}
?>