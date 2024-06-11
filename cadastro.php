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
            $_SESSION['message'] = "Cadastro de cliente realizado com sucesso!";
            header("Location: login.html");
            exit();
        } else {
            echo "Erro ao realizar cadastro: " . $stmt->error;
        }

        $stmt->close();
    }

    $checkStmt->close();
    $conn->close();
}
