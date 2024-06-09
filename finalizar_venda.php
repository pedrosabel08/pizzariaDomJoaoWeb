<?php
include("conexao.php");

// Verifica se os dados do carrinho foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os IDs dos pedidos e o preço total estão definidos
    if (isset($_POST["cartItems"]) && is_array($_POST["cartItems"]) && isset($_POST["total_price"])) {
        // Conecta-se ao banco de dados

        $totalPrice = $_POST["total_price"];


        // Insira o código para inserir os detalhes da venda na tabela de vendas
        $formaEntregaId = 1; // Exemplo de ID da forma de entrega
        $vendaId = inserirVenda($conn, $formaEntregaId, $totalPrice);

        if ($vendaId !== false) {
            // Insira os itens do carrinho na tabela 'vendas_pizzas'
            foreach ($_POST["cartItems"] as $item) {
                $size = $item["size"];
                $border = $item["border"];
                $flavors = implode(", ", $item["flavors"]);
                $price = $item["price"];

                // Consulta SQL para obter o ID da pizza com base no nome
                $sqlPizza = "SELECT idpizzas FROM pizzas WHERE nomePizza = ?";
                if ($stmtPizza = mysqli_prepare($conn, $sqlPizza)) {
                    mysqli_stmt_bind_param($stmtPizza, "s", $flavors);
                    if (mysqli_stmt_execute($stmtPizza)) {
                        mysqli_stmt_bind_result($stmtPizza, $pizzaId);
                        mysqli_stmt_fetch($stmtPizza);
                        mysqli_stmt_close($stmtPizza);
                    } else {
                        echo "Erro ao executar a consulta SQL para obter o ID da pizza: " . mysqli_error($conn);
                    }
                } else {
                    echo "Erro ao preparar a declaração SQL para obter o ID da pizza: " . mysqli_error($conn);
                }

                // Consulta SQL para obter o ID da borda com base no nome
                $sqlBorda = "SELECT idbordas_pizza FROM bordas_pizza WHERE nome = ?";
                if ($stmtBorda = mysqli_prepare($conn, $sqlBorda)) {
                    mysqli_stmt_bind_param($stmtBorda, "s", $border);
                    if (mysqli_stmt_execute($stmtBorda)) {
                        mysqli_stmt_bind_result($stmtBorda, $bordaId);
                        mysqli_stmt_fetch($stmtBorda);
                        mysqli_stmt_close($stmtBorda);
                    } else {
                        echo "Erro ao executar a consulta SQL para obter o ID da borda: " . mysqli_error($conn);
                    }
                } else {
                    echo "Erro ao preparar a declaração SQL para obter o ID da borda: " . mysqli_error($conn);
                }

                // Consulta SQL para obter o ID do tamanho com base no nome
                $sqlTamanho = "SELECT idtamanho FROM tamanho WHERE nome = ?";
                if ($stmtTamanho = mysqli_prepare($conn, $sqlTamanho)) {
                    mysqli_stmt_bind_param($stmtTamanho, "s", $size);
                    if (mysqli_stmt_execute($stmtTamanho)) {
                        mysqli_stmt_bind_result($stmtTamanho, $tamanhoId);
                        mysqli_stmt_fetch($stmtTamanho);
                        mysqli_stmt_close($stmtTamanho);
                    } else {
                        echo "Erro ao executar a consulta SQL para obter o ID do tamanho: " . mysqli_error($conn);
                    }
                } else {
                    echo "Erro ao preparar a declaração SQL para obter o ID do tamanho: " . mysqli_error($conn);
                }

                // Insere os detalhes do item na tabela 'vendas_pizzas'
                $sqlVendaPizza = "INSERT INTO vendas_pizzas (vendas_idvendas, pizzas_idpizzas, tamanho_idtamanho, borda_idbordas_pizza, sabores, preco) VALUES (?, ?, ?, ?, ?, ?)";
                if ($stmtVendaPizza = mysqli_prepare($conn, $sqlVendaPizza)) {
                    mysqli_stmt_bind_param($stmtVendaPizza, "iiiiid", $vendaId, $pizzaId, $tamanhoId, $bordaId, $flavors, $price);
                    if (!mysqli_stmt_execute($stmtVendaPizza)) {
                        echo "Erro ao inserir item do carrinho na tabela vendas_pizzas: " . mysqli_error($conn);
                    }
                    mysqli_stmt_close($stmtVendaPizza);
                } else {
                    echo "Erro ao preparar a declaração SQL para inserir item do carrinho na tabela vendas_pizzas: " . mysqli_error($conn);
                }
            }

            // Associa os pedidos à venda na tabela de associação
            if (!associarPedidosAVenda($conn, $vendaId, $pedidoIds)) {
                echo "Erro ao associar pedidos à venda.";
            } else {
                echo "Venda finalizada com sucesso!";
            }
        } else {
            echo "Erro ao inserir venda.";
        }

        // Feche a conexão com o banco de dados
        $conn->close();
    } else {
        echo "Erro: Informações incompletas.";
    }
} else {
    echo "Método de requisição inválido.";
}