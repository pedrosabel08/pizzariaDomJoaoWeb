<?php
header('Content-Type: application/json');
require '../conexao.php';

// Retorna fornecedores
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'fornecedores') {
    $res = $conn->query("SELECT idfornecedor, nome FROM fornecedores");
    $fornecedores = [];
    while ($row = $res->fetch_assoc()) $fornecedores[] = $row;
    echo json_encode($fornecedores);
    exit;
}

// Retorna produtos por fornecedor
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'produtos_por_fornecedor' && isset($_GET['idfornecedor'])) {
    $idfornecedor = intval($_GET['idfornecedor']);
    $sqlTipos = "SELECT idtipo FROM fornecedores_tipos WHERE idfornecedor = ?";
    $stmtTipos = $conn->prepare($sqlTipos);
    $stmtTipos->bind_param("i", $idfornecedor);
    $stmtTipos->execute();
    $resultTipos = $stmtTipos->get_result();
    $tipos = [];
    while ($row = $resultTipos->fetch_assoc()) $tipos[] = $row['idtipo'];
    if (count($tipos) === 0) {
        echo json_encode([]);
        exit;
    }
    $placeholders = implode(',', array_fill(0, count($tipos), '?'));
    $sqlProdutos = "SELECT * FROM produtos p WHERE p.tipo_id IN ($placeholders)";
    $stmtProdutos = $conn->prepare($sqlProdutos);
    $types = str_repeat('i', count($tipos));
    $stmtProdutos->bind_param($types, ...$tipos);
    $stmtProdutos->execute();
    $resultProdutos = $stmtProdutos->get_result();
    $produtos = [];
    while ($row = $resultProdutos->fetch_assoc()) $produtos[] = $row;
    echo json_encode($produtos);
    exit;
}

// Registrar compra
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $idfornecedor = $input['idfornecedor'];
    $observacoes = $input['observacoes'];
    $produtos = $input['produtos'];
    $valor_total = 0;
    foreach ($produtos as $p) {
        $valor_total += floatval($p['quantidade']) * floatval($p['preco_unitario']);
    }
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("INSERT INTO compras (idfornecedor, data_compra, valor_total, observacoes) VALUES (?, CURDATE(), ?, ?)");
        $stmt->bind_param("ids", $idfornecedor, $valor_total, $observacoes);
        $stmt->execute();
        $idcompra = $conn->insert_id;

        $stmtItem = $conn->prepare("INSERT INTO itens_compra (idcompra, idproduto, quantidade, preco_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
        foreach ($produtos as $p) {
            $subtotal = floatval($p['quantidade']) * floatval($p['preco_unitario']);
            $stmtItem->bind_param("iidds", $idcompra, $p['idproduto'], $p['quantidade'], $p['preco_unitario'], $subtotal);
            $stmtItem->execute();
        }
        $conn->commit();
        echo json_encode(['sucesso' => true, 'mensagem' => 'Compra registrada com sucesso!']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao registrar compra.']);
    }
    exit;
}