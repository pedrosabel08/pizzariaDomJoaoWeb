<?php
session_start();

if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.html");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "bd_pizzaria");

if ($mysqli->connect_error) {
    die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idclientes = $_SESSION['cliente_id'];

    $nome = $mysqli->real_escape_string($_POST['nome']);
    $senha = $mysqli->real_escape_string($_POST['senha']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $telefone = $mysqli->real_escape_string($_POST['telefone']);
    $sobrenome = $mysqli->real_escape_string($_POST['sobrenome']);

    // Verifica se já existe um registro para o usuário
    $checkQuery = "SELECT COUNT(*) as count FROM clientes WHERE idclientes = ?";
    $stmt = $mysqli->prepare($checkQuery);
    if ($stmt === false) {
        die("Erro na preparação da query: " . $mysqli->error);
    }
    $stmt->bind_param("i", $idclientes);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        // UPDATE
        $updateQuery = "
            UPDATE clientes c
            SET 
                c.nome = ?, 
                c.senha = ?,
                c.email = ?, 
                c.telefone = ?, 
                c.sobrenome = ?
            WHERE c.idclientes = ?
        ";

        $stmt = $mysqli->prepare($updateQuery);
        if ($stmt === false) {
            die("Erro na preparação da query: " . $mysqli->error);
        }

        $stmt->bind_param(
            "sssssi",  // Adicione 'i' para o idclientes
            $nome,
            $senha,
            $email,
            $telefone,
            $sobrenome,
            $idclientes  // Certifique-se de passar o idclientes
        );
    } else {
        // INSERT - Executa as duas queries separadamente
        $insertQuery1 = "
            INSERT INTO clientes (nome, senha, email, telefone, sobrenome) 
            VALUES (?, ?, ?, ?, ?)
        ";

        $stmt1 = $mysqli->prepare($insertQuery1);
        if ($stmt1 === false) {
            die("Erro na preparação da query: " . $mysqli->error);
        }
        $stmt1->bind_param(
            "sssss",
            $nome,
            $senha,
            $email,
            $telefone,
            $sobrenome
        );
        $stmt1->execute();
        $stmt1->close();
    }

    if ($stmt->execute()) {
        header("Location: infos.php?status=success&message=Informações atualizadas com sucesso!");
    } else {
        header("Location: infos.php?status=error&message=Algo deu errado, tente novamente.");
    }

    $stmt->close();
    $mysqli->close();
}
