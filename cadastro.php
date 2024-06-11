<?php
include ('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $checkEmailSql = "SELECT idclientes FROM clientes WHERE email = ?";
    $checkStmt = $conn->prepare($checkEmailSql);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "<script>alert('Email já cadastrado. Por favor, use outro email.');window.location.href='login.html';</script>";
    } else {
        $sql = "INSERT INTO clientes (nome, sobrenome, telefone, email, senha) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nome, $sobrenome, $telefone, $email, $senha);

        if ($stmt->execute()) {
            header("Location: login.html?status=sucess&message=" . urlencode("Cadastro realizado com sucesso!"));
        } else {
            header("Location: login.html?status=error&message=" . urlencode("Cadastro não efetuado!"));
        }

        $stmt->close();
    }

    $checkStmt->close();
    $conn->close();
}
