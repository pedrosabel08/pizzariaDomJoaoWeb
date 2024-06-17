<?php
session_start();
include("conexao.php");

if (!isset($_SESSION['cliente_id'])) {
    header("Location: index.php");
    exit();
}

$clienteId = $_SESSION['cliente_id'];

$sql = "SELECT idendereco, rua, numero, cidade, estado, cep FROM enderecos WHERE cliente_id = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $clienteId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $idendereco, $rua, $numero, $cidade, $estado, $cep);

    $enderecos = [];
    while (mysqli_stmt_fetch($stmt)) {
        $enderecos[] = [
            'idendereco' => $idendereco,
            'rua' => $rua,
            'numero' => $numero,
            'cidade' => $cidade,
            'estado' => $estado,
            'cep' => $cep
        ];
    }
    mysqli_stmt_close($stmt);
}

echo json_encode($enderecos);