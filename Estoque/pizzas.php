
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylePizza.css">
    <title>Adicionar Pizza</title>
</head>

<body>
    <div class="buttons">
        <button class="btn" onclick="window.location.href='produtos.html'">Voltar</button>
    </div>

    <main>
        <div class="pizzas">
            <h2>Pizzas Cadastradas</h2>
            <input type="text" id="filterInput" onkeyup="filterTable()" placeholder="Nome da Pizza:">
            <div class="table-wrapper">
                <table id="tabelaPizzas">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome da Pizza</th>
                            <th>Ingredientes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pizzas as $pizzaId => $pizza): ?>
                            <tr class="linha-tabela" data-id="<?php echo htmlspecialchars($pizzaId); ?>">
                                <td><?php echo htmlspecialchars($pizza['idpizzas']); ?></td>
                                <td><?php echo htmlspecialchars($pizza['nomePizza']); ?></td>
                                <td>
                                    <?php if (count($pizza['ingredientes']) > 0): ?>
                                        <ul>
                                            <?php foreach ($pizza['ingredientes'] as $ingrediente): ?>
                                                <li>
                                                    <?php echo htmlspecialchars($ingrediente['nomeProduto']) . " - " . htmlspecialchars($ingrediente['quantidade']) . " " . htmlspecialchars($ingrediente['unidadeMedida']); ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        Nenhum ingrediente cadastrado.
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="addPizza">
            <h2>Adicionar Pizza</h2>
            <form id="pizza-form" method="post" action="adicionar_pizza.php">
                <div class="Nome">
                    <label for="pizza_name">Nome da Pizza:</label>
                    <input type="text" id="pizza_name" name="pizza_name" required>
                    <select id="tipoPizza" name="tipoPizza">
                        <option value="salgada">Salgada</option>
                        <option value="doce">Doce</option>
                    </select>
                </div>
                <div id="ingredients-container">
                    <div class="ingredient-row">
                        <select name="ingredients[]" class="ingredient-select">
                            <?php
                            $conn = new mysqli($servername, $username, $password, $dbname, $port);
                            if ($conn->connect_error) {
                                die("Conexão falhou: " . $conn->connect_error);
                            }

                            $sql = "SELECT idprodutos, nomeProduto FROM produtos ORDER BY nomeProduto asc";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row["idprodutos"] . "'>" . $row["nomeProduto"] . "</option>";
                                }
                            }

                            $conn->close();
                            ?>
                        </select>
                        <input type="number" name="quantities[]" class="ingredient-quantity" placeholder="Quantidade"
                            min="1">
                        <button type="button" class="remove-ingredient">Remover</button>
                    </div>
                </div>
                <div class="buttons">
                    <button type="button" id="add-ingredient">Adicionar Ingrediente</button>
                    <button type="submit">Salvar Pizza</button>
                </div>
            </form>
            <form id="formExcluirPizza" action="excluirPizza.php" method="POST">
                <input type="hidden" name="idpizzas" id="idPizzaExcluir">
                <button type="button" id="botaoExcluir">Excluir Item</button>
            </form>
        </div>
    </main>
    <footer>
        <p>&copy; Arthur, Pedro e Vitor</p>
    </footer>
</body>
<script src="scriptPizza.js"></script>

</html>