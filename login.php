<?php
session_start();
require 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $senha = htmlspecialchars(trim($_POST['senha']));

    $sql = "SELECT idclientes, nome FROM clientes WHERE email = ? AND senha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['cliente_id'] = $row['idclientes'];
        $_SESSION['nome'] = $row['nome'];
        echo "<script>alert('Login feito com sucesso!');</script>";
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Email ou senha incorretos.');window.location.href='login.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
