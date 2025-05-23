<?php
include '../conexao.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$nome = trim($_POST['nome']);
$quantidade = floatval($_POST['quantidade']);
$unidadeMedida = intval($_POST['unidadeMedida']);
$validade = $_POST['validade'];

if ($id > 0) {
    // UPDATE
    $sql = "UPDATE produtos SET quantidade=?, unidadeMedida=?, validade=? WHERE idprodutos=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("diss", $quantidade, $unidadeMedida, $validade, $id);
} else {
    // INSERT
    $sql = "INSERT INTO produtos (nomeProduto, quantidade, unidadeMedida, validade) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdss", $nome, $quantidade, $unidadeMedida, $validade);
}

if ($stmt->execute()) {
    header("Location: estoque.php?tipo=ingredientes");
    exit;
} else {
    echo "Erro ao salvar ingrediente!";
}
?>