<?php
session_start();
require 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $senha = htmlspecialchars(trim($_POST['senha']));

    $sql = "SELECT idclientes, nome, nivel FROM clientes WHERE email = ? AND senha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        $_SESSION['loggedin'] = true;
        $_SESSION['admin'] = $row['nivel'] == 0 ? false : true;
        $_SESSION['email'] = $email;
        $_SESSION['cliente_id'] = $row['idclientes'];
        $_SESSION['nome'] = $row['nome'];

        // Resposta em JSON para sucesso
        echo json_encode([
            'success' => true,
            'message' => 'Login feito com sucesso!'
        ]);
    } else {
        // Resposta em JSON para falha
        echo json_encode([
            'success' => false,
            'message' => 'Email ou senha incorretos.'
        ]);
    }

    $stmt->close();
    $conn->close();
}
