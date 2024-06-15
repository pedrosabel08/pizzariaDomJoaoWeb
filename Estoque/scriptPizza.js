document.addEventListener('DOMContentLoaded', () => {
    const ingredientsContainer = document.getElementById('ingredients-container');
    const addIngredientButton = document.getElementById('add-ingredient');

    function createIngredientRow() {
        const ingredientRow = document.createElement('div');
        ingredientRow.className = 'ingredient-row';

        const ingredientSelect = document.createElement('select');
        ingredientSelect.name = 'ingredients[]';
        ingredientSelect.className = 'ingredient-select';

        const firstSelect = document.querySelector('.ingredient-select');
        ingredientSelect.innerHTML = firstSelect.innerHTML;

        const ingredientQuantity = document.createElement('input');
        ingredientQuantity.type = 'number';
        ingredientQuantity.name = 'quantities[]';
        ingredientQuantity.className = 'ingredient-quantity';
        ingredientQuantity.placeholder = 'Quantidade';
        ingredientQuantity.min = '1';

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'remove-ingredient';
        removeButton.textContent = 'Remover';
        removeButton.addEventListener('click', () => {
            ingredientRow.remove();
        });

        ingredientRow.appendChild(ingredientSelect);
        ingredientRow.appendChild(ingredientQuantity);
        ingredientRow.appendChild(removeButton);

        return ingredientRow;
    }

    addIngredientButton.addEventListener('click', () => {
        const newIngredientRow = createIngredientRow();
        ingredientsContainer.appendChild(newIngredientRow);
    });
});

document.addEventListener("DOMContentLoaded", function () {
    var linhasTabela = document.querySelectorAll(".linha-tabela");

    linhasTabela.forEach(function (linha) {
        linha.addEventListener("click", function () {
            linhasTabela.forEach(function (outraLinha) {
                outraLinha.classList.remove("selecionada");
            });

            linha.classList.add("selecionada");

            var idPizzaSelecionada = linha.getAttribute("data-id");
            console.log("Linha selecionada: ID da pizza = " + idPizzaSelecionada);
        });
    });

    var botaoExcluir = document.getElementById("botaoExcluir");

    botaoExcluir.addEventListener("click", function () {
        var linhaSelecionada = document.querySelector(".selecionada");

        if (linhaSelecionada) {
            var idPizzaSelecionada = linhaSelecionada.getAttribute("data-id");

            document.getElementById("idPizzaExcluir").value = idPizzaSelecionada;

            console.log("ID da pizza a ser excluída: " + idPizzaSelecionada);
            console.log("Campo hidden valor: " + document.getElementById("idPizzaExcluir").value);

            document.getElementById("formExcluirPizza").submit();
        } else {
            console.log("Nenhuma linha selecionada para exclusão.");
        }
    });
});

function filterTable() {
    const input = document.getElementById('filterInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('tabelaPizzas');
    const rows = table.querySelectorAll('tbody tr');

    for (const row of rows) {
        const tds = row.getElementsByTagName('td');
        let display = false;

        if (tds[0].textContent.toLowerCase().indexOf(filter) > -1) {
            display = true;
        }

        row.style.display = display ? '' : 'none';
    }
}