<?php
include ("conexao.php");

function inserirVenda($conn, $formaEntregaId, $total, $clienteId, $enderecoId)
{
    $sql = "INSERT INTO vendas (forma_entrega_id, total, data_venda, cliente_id, endereco_id) VALUES (?, ?, NOW(), ?, ?)";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "idii", $formaEntregaId, $total, $clienteId, $enderecoId);
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

function inserirEndereco($conn, $bairro, $rua, $numero, $complemento, $clienteId)
{
    $sqlInsertEndereco = "INSERT INTO endereco (bairro, rua, numero, complemento, cliente_id) VALUES (?,?,?,?,?)";
    if ($stmt = mysqli_prepare($conn, $sqlInsertEndereco)) {
        mysqli_stmt_bind_param($stmt, "ssisi", $bairro, $rua, $numero, $complemento, $clienteId);
        if (mysqli_stmt_execute($stmt)) {
            return mysqli_insert_id($conn);
        } else {
            header("Location: index.php?status=error&message=" . urlencode("Erro ao inserir endereço: " . mysqli_error($conn)));
            exit();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST["cartItems"]) && is_array($_POST["cartItems"]) && isset($_POST["total_price"]) &&
        isset($_POST["bairro"]) && isset($_POST["rua"]) && isset($_POST["numero"]) && isset($_POST["complemento"])
    ) {
        $totalPrice = $_POST["total_price"];
        $formaEntregaId = $_POST["forma_entrega"];
        $clienteId = $_POST["cliente_id"];
        echo $cliente_id;
        $bairro = $_POST["bairro"];
        $rua = $_POST["rua"];
        $numero = $_POST["numero"];
        $complemento = $_POST["complemento"];

        if ($formaEntregaId == 2) {
            if ($bairro == "") {
                header("Location: index.php?status=error&message=" . urlencode("Informe o bairro."));
                return;
            }
            if ($rua == "") {
                header("Location: index.php?status=error&message=" . urlencode("Informe a rua."));
                return;
            }
            if ($numero == "") {
                header("Location: index.php?status=error&message=" . urlencode("Informe o numero da residência."));
                return;
            }
            $enderecoId = inserirEndereco($conn, $bairro, $rua, $numero, $complemento, $clienteId);
        }
        $vendaId = inserirVenda($conn, $formaEntregaId, $totalPrice, $clienteId, $enderecoId);

        if ($vendaId !== false) {
            foreach ($_POST["cartItems"] as $item) {
                $size = $item["size"];
                $border = $item["border"];
                $flavors = $item["flavors"];
                $price = $item["price"];

                if (!is_array($flavors)) {
                    $flavors = explode(',', $flavors);
                }

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
                        $sqlVendaPizza = "INSERT INTO vendas_pizzas (vendas_idvendas, pizzas_idpizzas, tamanho_idtamanho, borda_idbordas_pizza) VALUES (?, ?, ?, ?)";
                        if ($stmtVendaPizza = mysqli_prepare($conn, $sqlVendaPizza)) {
                            mysqli_stmt_bind_param($stmtVendaPizza, "iiii", $vendaId, $pizzaId, $tamanhoId, $bordaId);
                            if (mysqli_stmt_execute($stmtVendaPizza)) {
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