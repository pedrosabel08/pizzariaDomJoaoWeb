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

                        // Inserir pizza na tabela vendas_pizzas
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

                            // Buscar ingredientes e quantidades necessárias para a pizza
                            $sqlSaida = "SELECT produto_id, quantidade FROM pizzas_produtos WHERE pizza_id = ?";
                            if ($stmtSelect = mysqli_prepare($conn, $sqlSaida)) {
                                mysqli_stmt_bind_param($stmtSelect, "i", $pizzaId);
                                mysqli_stmt_execute($stmtSelect);
                                mysqli_stmt_bind_result($stmtSelect, $produtoId, $quantidade);

                                $saidas = [];
                                while (mysqli_stmt_fetch($stmtSelect)) {
                                    $saidas[] = ['produto_id' => $produtoId, 'quantidade' => $quantidade];
                                }
                                mysqli_stmt_close($stmtSelect);

                                // Para cada ingrediente, dar baixa nos lotes
                                foreach ($saidas as $saida) {
                                    $produtoIdAtual = $saida['produto_id'];
                                    $quantidadeNecessaria = $saida['quantidade'];

                                    // Buscar lotes disponíveis do produto ordenados por validade
                                    $sqlLotes = "SELECT idlote, quantidade FROM estoque_lote WHERE idproduto = ? AND quantidade > 0 ORDER BY data_validade ASC";
                                    if ($stmtLotes = mysqli_prepare($conn, $sqlLotes)) {
                                        mysqli_stmt_bind_param($stmtLotes, "i", $produtoIdAtual);
                                        mysqli_stmt_execute($stmtLotes);
                                        mysqli_stmt_bind_result($stmtLotes, $loteId, $loteQtd);

                                        $saidasLotes = [];
                                        while (mysqli_stmt_fetch($stmtLotes)) {
                                            if ($quantidadeNecessaria <= 0) {
                                                break;
                                            }

                                            $usarQtd = min($quantidadeNecessaria, $loteQtd);
                                            $saidasLotes[] = [
                                                'loteId' => $loteId,
                                                'usarQtd' => $usarQtd,
                                                'produtoId' => $produtoIdAtual,
                                                'pizzaId' => $pizzaId,
                                                'vendaId' => $vendaId
                                            ];
                                            $quantidadeNecessaria -= $usarQtd;
                                        }
                                        mysqli_stmt_close($stmtLotes);

                                        // Agora sim, fazer os UPDATEs e INSERTs
                                        foreach ($saidasLotes as $saidaLote) {
                                            // Atualizar quantidade no lote
                                            $sqlBaixa = "UPDATE estoque_lote SET quantidade = quantidade - ? WHERE idlote = ?";
                                            if ($stmtBaixa = mysqli_prepare($conn, $sqlBaixa)) {
                                                mysqli_stmt_bind_param($stmtBaixa, "di", $saidaLote['usarQtd'], $saidaLote['loteId']);
                                                mysqli_stmt_execute($stmtBaixa);
                                                mysqli_stmt_close($stmtBaixa);
                                            } else {
                                                echo json_encode(['success' => false, 'message' => "Erro ao dar baixa no lote."]);
                                                exit;
                                            }

                                            // Registrar saída no histórico
                                            $sqlInsertSaida = "INSERT INTO saidas_estoque (produto_id, quantidade, pizza_id, venda_id, motivo, idlote) VALUES (?, ?, ?, ?, 'produção', ?)";
                                            if ($stmtInsert = mysqli_prepare($conn, $sqlInsertSaida)) {
                                                mysqli_stmt_bind_param($stmtInsert, "idiii", $saidaLote['produtoId'], $saidaLote['usarQtd'], $saidaLote['pizzaId'], $saidaLote['vendaId'], $saidaLote['loteId']);
                                                mysqli_stmt_execute($stmtInsert);
                                                mysqli_stmt_close($stmtInsert);
                                            } else {
                                                echo json_encode(['success' => false, 'message' => "Erro ao registrar saída no estoque."]);
                                                exit;
                                            }
                                        }
                                    } else {
                                        echo json_encode(['success' => false, 'message' => "Erro ao buscar lotes do produto."]);
                                        exit;
                                    }
                                }
                            } else {
                                echo json_encode(['success' => false, 'message' => "Erro ao buscar produtos da pizza."]);
                                exit;
                            }
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
