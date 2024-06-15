<?php
include("conexao.php");

function inserirVenda($conn, $formaEntregaId, $total, $clienteId) {
    $sqlCheckCliente = "SELECT COUNT(*) FROM clientes WHERE idclientes = ?";
    $stmtCheckCliente = mysqli_prepare($conn, $sqlCheckCliente);
    mysqli_stmt_bind_param($stmtCheckCliente, "i", $clienteId);
    mysqli_stmt_execute($stmtCheckCliente);
    mysqli_stmt_bind_result($stmtCheckCliente, $clienteCount);
    mysqli_stmt_fetch($stmtCheckCliente);
    mysqli_stmt_close($stmtCheckCliente);

    if ($clienteCount == 0) {
        $sqlInsertCliente = "INSERT INTO clientes (nome, email, telefone) VALUES ('Cliente Fictício', 'teste@teste.com', '0000-0000')";
        if (mysqli_query($conn, $sqlInsertCliente)) {
            $clienteId = mysqli_insert_id($conn);
        } else {
            header("Location: index.php?status=error&message=" . urlencode("Erro ao inserir cliente fictício: " . mysqli_error($conn)));
            exit();
        }
    }

    $sql = "INSERT INTO vendas (forma_entrega_id, total, data_venda, cliente_id) VALUES (?, ?, NOW(), ?)";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "idi", $formaEntregaId, $total, $clienteId);
        if (mysqli_stmt_execute($stmt)) {
            return mysqli_insert_id($conn);
        } else {
            header("Location: index.php?status=error&message=" . urlencode("Erro ao inserir venda: " . mysqli_error($conn)));
            exit();
        }
    } else {
        header("Location: index.php?status=error&message=" . urlencode("Erro ao preparar a declaração SQL para inserir venda: " . mysqli_error($conn)));
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["cartItems"]) && is_array($_POST["cartItems"]) && isset($_POST["total_price"])) {
        $totalPrice = $_POST["total_price"];
        $formaEntregaId = 1;  // Ajuste conforme necessário
        $clienteId = 1;  // Ajuste conforme necessário

        $vendaId = inserirVenda($conn, $formaEntregaId, $totalPrice, $clienteId);

        if ($vendaId !== false) {
            foreach ($_POST["cartItems"] as $item) {
                $size = $item["size"];
                $border = $item["border"];
                $flavors = $item["flavors"];
                $price = $item["price"];

                // Garantir que flavors seja um array
                if (!is_array($flavors)) {
                    $flavors = explode(',', $flavors);
                }

                // Obter ID da borda
                $sqlBorda = "SELECT idbordas_pizza FROM bordas_pizza WHERE nome = ?";
                if ($stmtBorda = mysqli_prepare($conn, $sqlBorda)) {
                    mysqli_stmt_bind_param($stmtBorda, "s", $border);
                    if (mysqli_stmt_execute($stmtBorda)) {
                        mysqli_stmt_bind_result($stmtBorda, $bordaId);
                        mysqli_stmt_fetch($stmtBorda);
                        mysqli_stmt_close($stmtBorda);
                    } else {
                        header("Location: index.php?status=error&message=" . urlencode("Erro ao executar a consulta SQL para obter o ID da borda: " . mysqli_error($conn)));
                        continue;
                    }
                } else {
                    header("Location: index.php?status=error&message=" . urlencode("Erro ao preparar a declaração SQL para obter o ID da borda: " . mysqli_error($conn)));
                    continue;
                }

                // Obter ID do tamanho
                $sqlTamanho = "SELECT idtamanho FROM tamanho WHERE nome = ?";
                if ($stmtTamanho = mysqli_prepare($conn, $sqlTamanho)) {
                    mysqli_stmt_bind_param($stmtTamanho, "s", $size);
                    if (mysqli_stmt_execute($stmtTamanho)) {
                        mysqli_stmt_bind_result($stmtTamanho, $tamanhoId);
                        mysqli_stmt_fetch($stmtTamanho);
                        mysqli_stmt_close($stmtTamanho);
                    } else {
                        header("Location: index.php?status=error&message=" . urlencode("Erro ao executar a consulta SQL para obter o ID do tamanho: " . mysqli_error($conn)));
                        continue;
                    }
                } else {
                    header("Location: index.php?status=error&message=" . urlencode("Erro ao preparar a declaração SQL para obter o ID do tamanho: " . mysqli_error($conn)));
                    continue;
                }

                foreach ($flavors as $flavor) {
                    // Obter ID da pizza
                    $sqlPizza = "SELECT idpizzas FROM pizzas WHERE nomePizza = ?";
                    if ($stmtPizza = mysqli_prepare($conn, $sqlPizza)) {
                        mysqli_stmt_bind_param($stmtPizza, "s", $flavor);
                        if (mysqli_stmt_execute($stmtPizza)) {
                            mysqli_stmt_bind_result($stmtPizza, $pizzaId);
                            mysqli_stmt_fetch($stmtPizza);
                            mysqli_stmt_close($stmtPizza);
                        } else {
                            header("Location: index.php?status=error&message=" . urlencode("Erro ao executar a consulta SQL para obter o ID da pizza: " . mysqli_error($conn)));
                            continue;
                        }
                    } else {
                        header("Location: index.php?status=error&message=" . urlencode("Erro ao preparar a declaração SQL para obter o ID da pizza: " . mysqli_error($conn)));
                        continue;
                    }

                    if ($pizzaId !== null) {
                        // Inserir item na tabela vendas_pizzas
                        $sqlVendaPizza = "INSERT INTO vendas_pizzas (vendas_idvendas, pizzas_idpizzas, tamanho_idtamanho, borda_idbordas_pizza) VALUES (?, ?, ?, ?)";
                        if ($stmtVendaPizza = mysqli_prepare($conn, $sqlVendaPizza)) {
                            mysqli_stmt_bind_param($stmtVendaPizza, "iiii", $vendaId, $pizzaId, $tamanhoId, $bordaId);
                            if (mysqli_stmt_execute($stmtVendaPizza)) {
                                // Atualizar estoque
                                $sqlUpdateEstoque = "UPDATE produtos p
                                                     JOIN pizzas_produtos pp ON p.idprodutos = pp.produto_id
                                                     SET p.quantidade = p.quantidade - pp.quantidade
                                                     WHERE pp.pizza_id = ?";
                                if ($stmtUpdateEstoque = mysqli_prepare($conn, $sqlUpdateEstoque)) {
                                    mysqli_stmt_bind_param($stmtUpdateEstoque, "i", $pizzaId);
                                    if (!mysqli_stmt_execute($stmtUpdateEstoque)) {
                                        header("Location: index.php?status=error&message=" . urlencode("Erro ao atualizar estoque: " . mysqli_error($conn)));
                                    }
                                    mysqli_stmt_close($stmtUpdateEstoque);
                                } else {
                                    header("Location: index.php?status=error&message=" . urlencode("Erro ao preparar a declaração SQL para atualizar estoque: " . mysqli_error($conn)));
                                }
                            } else {
                                header("Location: index.php?status=error&message=" . urlencode("Erro ao inserir item do carrinho na tabela vendas_pizzas: " . mysqli_error($conn)));
                            }
                            mysqli_stmt_close($stmtVendaPizza);
                        } else {
                            header("Location: index.php?status=error&message=" . urlencode("Erro ao preparar a declaração SQL para inserir item do carrinho na tabela vendas_pizzas: " . mysqli_error($conn)));
                        }
                    } else {
                        header("Location: index.php?status=error&message=" . urlencode("Erro: ID da pizza é nulo para o sabor '$flavor'."));
                    }
                }
            }

            header("Location: index.php?status=success&message=" . urlencode("Venda finalizada com sucesso!"));
        } else {
            header("Location: index.php?status=error&message=" . urlencode("Erro ao inserir venda."));
        }

        $conn->close();
    } else {
        header("Location: index.php?status=error&message=" . urlencode("Erro: Informações incompletas."));
    }
} else {
    header("Location: index.php?status=error&message=" . urlencode("Método de requisição inválido."));
}
?>
