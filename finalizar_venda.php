<?php
include("conexao.php");

header('Content-Type: application/json');

function contarPedidosNaoFinalizados($conn)
{
    $sql = "SELECT COUNT(*) as totalPedidos FROM vendas WHERE status_id != 3"; // Assumindo que 3 é o status "Finalizado"
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['totalPedidos'];
}

function inserirVenda($conn, $formaEntregaId, $total, $clienteId, $enderecoId, $formaPagamentoId, $valor_entrega, $tempo_espera)
{
    $sql = "INSERT INTO vendas (forma_entrega_id, total, data_venda, cliente_id, endereco_id, forma_pagamento_id, status_id, valor_entrega, tempo_espera) VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?)";
    $statusId = 1; // 1 assumindo que este é o status de "Em andamento"

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "idiiiidi", $formaEntregaId, $total, $clienteId, $enderecoId, $formaPagamentoId, $statusId, $valor_entrega, $tempo_espera);
        return mysqli_stmt_execute($stmt) ? mysqli_insert_id($conn) : false;
    }
    return false;
}

function inserirEndereco($conn, $bairro, $rua, $numero, $complemento, $cidade, $clienteId)
{
    $sqlInsertEndereco = "INSERT INTO endereco (bairro, rua, numero, complemento, cidade, cliente_id) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($conn, $sqlInsertEndereco)) {
        mysqli_stmt_bind_param($stmt, "ssissi", $bairro, $rua, $numero, $complemento, $cidade, $clienteId);
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
        $cidade = $_POST["localidade"];
        $formaPagamentoId = $_POST["forma_pagamento"];
        $valor_entrega = $_POST["calcTaxaEntrega"];
        $tempo_espera = $_POST["calcTempoDuracao"];

        // Contar pedidos não finalizados
        $tempo_espera = isset($_POST["calcTempoDuracao"]) ? floatval($_POST["calcTempoDuracao"]) : 0; // Certifique-se de que seja um número

        // // Contar pedidos não finalizados
        // $pedidosPendentes = contarPedidosNaoFinalizados($conn);
        // $tempoAdicional = $pedidosPendentes * 5;
        // $tempo_espera += $tempoAdicional;

        if ($formaEntregaId == 2) {
            if (empty($bairro) || empty($rua) || empty($numero)) {
                echo json_encode(['success' => false, 'message' => "Preencha todos os campos de endereço."]);
                exit;
            }
            $enderecoId = inserirEndereco($conn, $bairro, $rua, $numero, $complemento, $cidade, $clienteId);
            if ($enderecoId === false) {
                echo json_encode(['success' => false, 'message' => "Erro ao inserir endereço."]);
                exit;
            }
        } else {
            $enderecoId = null;
            $pedidosPendentes = contarPedidosNaoFinalizados($conn);
            $tempoAdicional = $pedidosPendentes * 5;
            $tempo_espera = 20 + $tempoAdicional;
        }

        $vendaId = inserirVenda($conn, $formaEntregaId, $totalPrice, $clienteId, $enderecoId, $formaPagamentoId, $valor_entrega, $tempo_espera);
        if ($vendaId === false) {
            echo json_encode(['success' => false, 'message' => "Erro ao inserir venda."]);
            exit;
        }


        foreach ($_POST["cartItems"] as $item) {
            // Verificar borda, tamanho e sabores
            $size = isset($item["size"]) ? $item["size"] : null;
            $border = isset($item["border"]) ? $item["border"] : null;
            $flavors = isset($item["flavors"]) ? $item["flavors"] : null;
            $price = isset($item["price"]) ? $item["price"] : null;

            // Verificar bebidas
            $idBebida = isset($item["idBebida"]) ? $item["idBebida"] : null;
            $precoBebida = isset($item["precoBebida"]) ? $item["precoBebida"] : null;
            $quantity = isset($item["quantity"]) ? $item["quantity"] : null;

            // Verificar e tratar os sabores, separando-os se for uma string
            if (!is_array($flavors) && $flavors !== null) {
                $flavors = explode(',', $flavors);
            }

            // Processar borda e tamanho se forem informados
            if ($border !== null && $size !== null) {
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

                // Processar sabores de pizza, se forem informados
                if ($flavors !== null) {
                    foreach ($saidas as $saida) {
                        $produtoId = $saida['produto_id'];
                        $quantidadeNecessaria = $saida['quantidade'];

                        // Buscar lotes disponíveis do produto ordenados por validade
                        $sqlLotes = "SELECT idlote, quantidade, data_validade 
                                    FROM estoque_lote 
                                    WHERE idproduto = ? AND quantidade > 0 
                                    ORDER BY data_validade ASC";

                        if ($stmtLotes = mysqli_prepare($conn, $sqlLotes)) {
                            mysqli_stmt_bind_param($stmtLotes, "i", $produtoId);
                            mysqli_stmt_execute($stmtLotes);
                            mysqli_stmt_bind_result($stmtLotes, $loteId, $loteQtd, $validade);

                            while (mysqli_stmt_fetch($stmtLotes) && $quantidadeNecessaria > 0) {
                                $usarQtd = min($quantidadeNecessaria, $loteQtd);

                                // Dar baixa no lote
                                $sqlBaixa = "UPDATE estoque_lote SET quantidade = quantidade - ? WHERE idlote = ?";
                                $stmtBaixa = mysqli_prepare($conn, $sqlBaixa);
                                mysqli_stmt_bind_param($stmtBaixa, "di", $usarQtd, $loteId);
                                mysqli_stmt_execute($stmtBaixa);
                                mysqli_stmt_close($stmtBaixa);

                                // Registrar saída
                                $sqlInsertSaida = "INSERT INTO saidas_estoque (produto_id, quantidade, pizza_id, venda_id, motivo, idlote)
                               VALUES (?, ?, ?, ?, 'produção', ?)";
                                $stmtInsert = mysqli_prepare($conn, $sqlInsertSaida);
                                mysqli_stmt_bind_param($stmtInsert, "idiii", $produtoId, $usarQtd, $pizzaId, $vendaId, $loteId);
                                mysqli_stmt_execute($stmtInsert);
                                mysqli_stmt_close($stmtInsert);

                                $quantidadeNecessaria -= $usarQtd;
                            }

                            mysqli_stmt_close($stmtLotes);
                        }
                    }
                }
            }

            // Verificar e processar a bebida
            if ($idBebida !== null && $precoBebida !== null && $quantity !== null) {
                $sqlVendaBebida = "INSERT INTO vendas_bebidas (vendas_idvendas, bebidas_idbebidas, quantidade) VALUES (?, ?, ?)";
                if ($stmtVendaBebida = mysqli_prepare($conn, $sqlVendaBebida)) {
                    mysqli_stmt_bind_param($stmtVendaBebida, "iii", $vendaId, $idBebida, $quantity);
                    mysqli_stmt_execute($stmtVendaBebida);
                    mysqli_stmt_close($stmtVendaBebida);
                } else {
                    echo json_encode(['success' => false, 'message' => "Erro ao inserir venda de bebida."]);
                    exit;
                }
            }
        }

        // Se todas as operações forem bem-sucedidas
        echo json_encode(['success' => true, 'message' => "Venda finalizada com sucesso!"]);
    } else {
        echo json_encode(['success' => false, 'message' => "Informações incompletas."]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "Método de requisição inválido."]);
}

$conn->close();
