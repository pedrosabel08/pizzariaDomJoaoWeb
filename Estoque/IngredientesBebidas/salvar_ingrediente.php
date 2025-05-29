<?php
include '../conexao.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$nome = trim($_POST['nome']);
$quantidade = floatval($_POST['quantidade']);
$unidadeMedida = intval($_POST['unidadeMedida']);
$tipo_id = intval($_POST['tipo_id']);
$validade = $_POST['validade'];

if ($id > 0) {
    // UPDATE
    $sql = "UPDATE produtos SET quantidade=?, unidadeMedida=?, tipo_id=?, validade=? WHERE idprodutos=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("diisi", $quantidade, $unidadeMedida, $tipo_id, $validade, $id);
} else {
    // INSERT
    $sql = "INSERT INTO produtos (nomeProduto, quantidade, unidadeMedida, tipo_id, validade) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdiis", $nome, $quantidade, $unidadeMedida, $tipo_id, $validade);
}

if ($stmt->execute()) {
    header("Location: estoque.php?tipo=ingredientes");
    exit;
} else {
    echo "Erro ao salvar ingrediente!";
}
?>