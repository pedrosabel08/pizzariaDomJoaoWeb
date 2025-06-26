<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylePizza.css">
    <link rel="icon" href="../assets/pizzaIcone.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="../../styleSidebar.css">

    <title>Adicionar Pizza</title>
</head>

<body>
    <?php

    include "../../sidebar.php";

    ?>

    <div id="tooltip"></div>

    <main>
        <div class="buttons-header">
            <button class="btn" data-tooltip="Voltar" onclick="window.location.href='../produtos.html'">
                <i class="fas fa-arrow-left"></i>
            </button>
            <button class="btn" data-tooltip="Adicionar Pizza" id="open-modal"><i class="fa-solid fa-plus"></i></button>
        </div>

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
    </main>
    <div id="pizza-modal" class="modal" style="display:none;">
        <div class="modal-content addPizza">
            <span class="close-modal" id="close-modal" style="cursor:pointer;float:right;font-size:24px;">&times;</span>
            <h2>Adicionar Pizza</h2>
            <form id="pizza-form" method="post" action="adicionar_pizza.php">
                <div class="Nome">
                    <input type="text" id="pizza_name" name="pizza_name" placeholder="Nome da pizza" required>
                    <select id="tipoPizza" name="tipoPizza">
                        <option value="salgada">Salgada</option>
                        <option value="doce">Doce</option>
                    </select>
                </div>
                <div id="ingredients-container">
                    <div class="ingredient-row">
                        <select name="ingredients[]" class="ingredient-select">
                        </select>
                        <input type="number" name="quantities[]" class="ingredient-quantity" placeholder="Quantidade" min="1">
                        <button type="button" class="remove-ingredient">Remover</button>
                    </div>
                </div>
                <div class="buttons">
                    <button class="btn" data-tooltip="Adicionar Ingrediente" type="button" id="add-ingredient" style="background-color: #1000ff;">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <button class="btn" data-tooltip="Salvar Pizza" type="button" id="save-pizza">
                        <i class="fa-solid fa-check"></i>
                    </button>
                </div>
            </form>
            <form id="formExcluirPizza" action="excluirPizza.php" method="POST">
                <input type="hidden" name="idpizzas" id="idPizzaExcluir">
                <button type="button" id="botaoExcluir">Excluir Pizza</button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
    <script src="../../sidebar.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</body>

</html>