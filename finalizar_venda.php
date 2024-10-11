<?php
include("conexao.php");

header('Content-Type: application/json');

function inserirVenda($conn, $formaEntregaId, $total, $clienteId, $enderecoId, $formaPagamentoId, $valor_entrega, $tempo_espera)
{
    $sql = "INSERT INTO vendas (forma_entrega_id, total, data_venda, cliente_id, endereco_id, forma_pagamento_id, status_id, valor_entrega, tempo_espera) VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?)";
    $statusId = 1;

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "idiiiidi", $formaEntregaId, $total, $clienteId, $enderecoId, $formaPagamentoId, $statusId, $valor_entrega, $tempo_espera);
        return mysqli_stmt_execute($stmt) ? mysqli_insert_id($conn) : false;
    }
    return false;
}

function inserirEndereco($conn, $bairro, $rua, $numero, $complemento, $clienteId)
{
    $sqlInsertEndereco = "INSERT INTO endereco (bairro, rua, numero, complemento, cliente_id) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($conn, $sqlInsertEndereco)) {
        mysqli_stmt_bind_param($stmt, "ssisi", $bairro, $rua, $numero, $complemento, $clienteId);
        return mysqli_stmt_execute($stmt) ? mysqli_insert_id($conn) : false;
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clienteId = $_POST["cliente_id"];

    if (
        isset($_POST["cartItems"]) && is_array($_POST["cartItems"]) &&
        isset($_POST["total_price"]) &&
        isset($_POST["bairro"]) && isset($_POST["rua"]) &&
        isset($_POST["numero"]) && isset($_POST["complemento"])
    ) {
        $totalPrice = $_POST["total_price"];
        $formaEntregaId = $_POST["forma_entrega"];
        $bairro = $_POST["bairro"];
        $rua = $_POST["rua"];
        $numero = $_POST["numero"];
        $complemento = $_POST["complemento"];
        $formaPagamentoId = $_POST["forma_pagamento"];
        $valor_entrega = $_POST["calcTaxaEntrega"];
        $tempo_espera = $_POST["calcTempoDuracao"];

        if ($formaEntregaId == 2) {
            if (empty($bairro) || empty($rua) || empty($numero)) {
                echo json_encode(['success' => false, 'message' => "Preencha todos os campos de endereço."]);
                exit;
            }
            $enderecoId = inserirEndereco($conn, $bairro, $rua, $numero, $complemento, $clienteId);
            if ($enderecoId === false) {
                echo json_encode(['success' => false, 'message' => "Erro ao inserir endereço."]);
                exit;
            }
        } else {
            $enderecoId = null;
            $tempo_espera = 20;
        }

        $vendaId = inserirVenda($conn, $formaEntregaId, $totalPrice, $clienteId, $enderecoId, $formaPagamentoId, $valor_entrega, $tempo_espera);
        if ($vendaId === false) {
            echo json_encode(['success' => false, 'message' => "Erro ao inserir venda."]);
            exit;
        }

        foreach ($_POST["cartItems"] as $item) {
            $size = $item["size"];
            $border = $item["border"];
            $flavors = $item["flavors"];
            $price = $item["price"];

            if (!is_array($flavors)) {
                $flavors = explode(',', $flavors);
            }

            // Consultar ID da borda
            $sqlBorda = "SELECT idbordas_pizza FROM bordas_pizza WHERE nome = ?";
            if ($stmtBorda = mysqli_prepare($conn, $sqlBorda)) {
                mysqli_stmt_bind_param($stmtBorda, "s", $border);
                mysqli_stmt_execute($stmtBorda);
                mysqli_stmt_bind_result($stmtBorda, $bordaId);
                mysqli_stmt_fetch($stmtBorda);
                mysqli_stmt_close($stmtBorda);
            } else {
                echo json_encode(['success' => false, 'message' => "Erro ao buscar borda."]);
                exit;
            }

            // Consultar ID do tamanho
            $sqlTamanho = "SELECT idtamanho FROM tamanho WHERE nome = ?";
            if ($stmtTamanho = mysqli_prepare($conn, $sqlTamanho)) {
                mysqli_stmt_bind_param($stmtTamanho, "s", $size);
                mysqli_stmt_execute($stmtTamanho);
                mysqli_stmt_bind_result($stmtTamanho, $tamanhoId);
                mysqli_stmt_fetch($stmtTamanho);
                mysqli_stmt_close($stmtTamanho);
            } else {
                echo json_encode(['success' => false, 'message' => "Erro ao buscar tamanho."]);
                exit;
            }

            foreach ($flavors as $flavor) {
                // Consultar ID da pizza
                $sqlPizza = "SELECT idpizzas FROM pizzas WHERE nomePizza = ?";
                if ($stmtPizza = mysqli_prepare($conn, $sqlPizza)) {
                    mysqli_stmt_bind_param($stmtPizza, "s", $flavor);
                    mysqli_stmt_execute($stmtPizza);
                    mysqli_stmt_bind_result($stmtPizza, $pizzaId);
                    mysqli_stmt_fetch($stmtPizza);
                    mysqli_stmt_close($stmtPizza);
                } else {
                    echo json_encode(['success' => false, 'message' => "Erro ao buscar sabor."]);
                    exit;
                }

                if ($pizzaId !== null) {
                    $sqlVendaPizza = "INSERT INTO vendas_pizzas (vendas_idvendas, pizzas_idpizzas, tamanho_idtamanho, borda_idbordas_pizza) VALUES (?, ?, ?, ?)";
                    if ($stmtVendaPizza = mysqli_prepare($conn, $sqlVendaPizza)) {
                        mysqli_stmt_bind_param($stmtVendaPizza, "iiii", $vendaId, $pizzaId, $tamanhoId, $bordaId);
                        mysqli_stmt_execute($stmtVendaPizza);
                        mysqli_stmt_close($stmtVendaPizza);
                    } else {
                        echo json_encode(['success' => false, 'message' => "Erro ao inserir venda de pizza."]);
                        exit;
                    }

                    // Atualizar estoque
                    $sqlUpdateEstoque = "UPDATE produtos p
                                         JOIN pizzas_produtos pp ON p.idprodutos = pp.produto_id
                                         SET p.quantidade = p.quantidade - pp.quantidade
                                         WHERE pp.pizza_id = ?";
                    if ($stmtUpdateEstoque = mysqli_prepare($conn, $sqlUpdateEstoque)) {
                        mysqli_stmt_bind_param($stmtUpdateEstoque, "i", $pizzaId);
                        mysqli_stmt_execute($stmtUpdateEstoque);
                        mysqli_stmt_close($stmtUpdateEstoque);
                    }
                }
            }
        }
        echo json_encode(['success' => true, 'message' => "Venda finalizada com sucesso!"]);
    } else {
        echo json_encode(['success' => false, 'message' => "Informações incompletas."]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "Método de requisição inválido."]);
}

$conn->close();
