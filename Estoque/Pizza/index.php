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
        <button class="btn" onclick="window.location.href='../produtos.html'">Voltar</button>
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
                    <tbody id="corpoTabela">
                        <!-- Os dados serão preenchidos aqui via JS -->
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
                        </select>
                        <input type="number" name="quantities[]" class="ingredient-quantity" placeholder="Quantidade"
                            min="1">
                        <button type="button" class="remove-ingredient">Remover</button>
                    </div>
                </div>
                <div class="buttons">
                    <button type="button" id="add-ingredient">Adicionar Ingrediente</button>
                    <button type="button" id="save-pizza">Salvar Pizza</button>
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
    <script src="script.js"></script>
</body>

</html>