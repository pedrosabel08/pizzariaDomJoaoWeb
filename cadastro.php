<?php
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['emailCad'];
    $senha = $_POST['senhaCad'];

    // Verifica se o email já está cadastrado
    $checkEmailSql = "SELECT idclientes FROM clientes WHERE email = ?";
    $checkStmt = $conn->prepare($checkEmailSql);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // Email já cadastrado
        echo "<script>alert('Email já cadastrado. Por favor, use outro email.');window.location.href='login.html';</script>";
    } else {
        // Insere os dados no banco de dados
        $sql = "INSERT INTO clientes (nome, sobrenome, telefone, email, senha) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nome, $sobrenome, $telefone, $email, $senha);

        if ($stmt->execute()) {
            // Cadastro efetuado com sucesso, redireciona para a página de login
            header("Location: login.html?status=success&message=" . urlencode("Cadastro efetuado com sucesso!"));
            exit;
        } else {
            // Falha no cadastro, redireciona para login com mensagem de erro
            header("Location: login.html?status=error&message=" . urlencode("Erro ao efetuar o cadastro."));
            exit;
        }

        $stmt->close();
    }

    $checkStmt->close();
    $conn->close();
}
