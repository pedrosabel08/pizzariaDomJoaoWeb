<?php

include("conexao.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeProduto = $_POST['nomeProduto'];
    $quantidade = $_POST['quantidade'];
    $unidadeMedida = $_POST['unidadeMedida'];
    $validadeFormatada = date('Y-m-d', strtotime($_POST['validade']));

    $sql = "INSERT INTO produtos (nomeProduto, quantidade, unidadeMedida, validade) VALUES ('$nomeProduto', '$quantidade', '$validadeFormatada')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Novo produto inserido com sucesso!');window.location.href='estoque.php';</script>";
    } else {
        echo "Erro ao inserir produto: " . $conn->error;
    }
}
$conn->close();
