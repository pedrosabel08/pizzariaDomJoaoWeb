<?php
include '../conexao.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$marca_id = intval($_POST['marca_id']);
$categoria_id = intval($_POST['categoria_id']);
$tamanho_id = intval($_POST['tamanho_id']);
$quantidade = floatval($_POST['quantidade']);
$validade = $_POST['validade'];
$preco = floatval($_POST['preco']);
echo $validade;
if ($id > 0) {
    // UPDATE
    $sql = "UPDATE bebidas SET marca_id=?, categoriabebidas_idcategoriaBebidas=?, tamanhobebidas_idtamanhoBebidas=?, quantidade=?, validade=?, preco=? WHERE idbebidas=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiissi", $marca_id, $categoria_id, $tamanho_id, $quantidade, $validade, $preco, $id);
} else {
    // INSERT
    $sql = "INSERT INTO bebidas (marca_id, categoriabebidas_idcategoriaBebidas, tamanhobebidas_idtamanhoBebidas, quantidade, validade, preco) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiiss", $marca_id, $categoria_id, $tamanho_id, $quantidade, $validade, $preco);
}

if ($stmt->execute()) {
    header("Location: estoque.php?tipo=bebidas");
    exit;
} else {
    echo "Erro ao salvar bebida!";
}

$sql = "SELECT bebidas.idbebidas as id, bebidas.nomeBebida as nome, bebidas.quantidade, bebidas.validade, bebidas.preco, bebidas.marca_id, marcabebidas.nome as categoria
        FROM bebidas
        INNER JOIN marcabebidas ON bebidas.marca_id = marcabebidas.idmarcaBebidas
        ORDER BY bebidas.nomeBebida ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>