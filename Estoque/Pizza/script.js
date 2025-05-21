fetch('get_pizzas.php')
    .then(response => response.json())
    .then(pizzas => {
        const tbody = document.getElementById('corpoTabela');
        pizzas.forEach(pizza => {
            const tr = document.createElement('tr');
            tr.classList.add('linha-tabela');
            tr.setAttribute('data-id', pizza.idpizzas);

            const tdId = document.createElement('td');
            tdId.textContent = pizza.idpizzas;

            const tdNome = document.createElement('td');
            tdNome.textContent = pizza.nomePizza;

            const tdIngredientes = document.createElement('td');
            if (pizza.ingredientes.length > 0) {
                const ul = document.createElement('ul');
                pizza.ingredientes.forEach(ingrediente => {
                    const li = document.createElement('li');
                    li.textContent = `${ingrediente.nomeProduto} - ${ingrediente.quantidade} ${ingrediente.unidadeMedida}`;
                    ul.appendChild(li);
                });
                tdIngredientes.appendChild(ul);
            } else {
                tdIngredientes.textContent = 'Nenhum ingrediente cadastrado.';
            }

            tr.appendChild(tdId);
            tr.appendChild(tdNome);
            tr.appendChild(tdIngredientes);
            tbody.appendChild(tr);
        });
    })
    .catch(error => {
        console.error('Erro ao carregar pizzas:', error);
    });


document.addEventListener("DOMContentLoaded", function () {
    const tbody = document.getElementById("corpoTabela");
    const container = document.getElementById("ingredients-container");

    tbody.addEventListener("click", function (event) {
        const linha = event.target.closest(".linha-tabela");

        if (linha) {
            tbody.querySelectorAll(".linha-tabela").forEach(l => l.classList.remove("selecionada"));
            linha.classList.add("selecionada");

            const idPizzaSelecionada = linha.getAttribute("data-id");
            document.getElementById("idPizzaExcluir").value = idPizzaSelecionada;

            fetch(`buscaPizza.php?id=${idPizzaSelecionada}`)
                .then(response => response.json())
                .then(data => {
                    // Preenche campos principais
                    document.getElementById("pizza_name").value = data.nome;
                    document.getElementById("tipoPizza").value = data.tipo;

                    // Limpa ingredientes atuais
                    container.innerHTML = '';

                    // Para cada ingrediente, cria o bloco com select e quantidade
                    data.ingredientes.forEach(ing => {
                        const div = document.createElement('div');
                        div.className = 'ingredient-row';

                        // Cria o select dinamicamente
                        const select = document.createElement('select');
                        select.name = 'ingredients[]';
                        select.className = 'ingredient-select';

                        data.produtos.forEach(prod => {
                            const option = document.createElement('option');
                            option.value = prod.id;
                            option.textContent = prod.nome;
                            if (prod.id === ing.produto_id) {
                                option.selected = true;
                            }
                            select.appendChild(option);
                        });

                        // Cria o input quantidade
                        const input = document.createElement('input');
                        input.type = 'number';
                        input.name = 'quantities[]';
                        input.className = 'ingredient-quantity';
                        input.placeholder = 'Quantidade';
                        input.min = '1';
                        input.value = ing.quantidade;

                        // Botão remover
                        const botao = document.createElement('button');
                        botao.type = 'button';
                        botao.className = 'remove-ingredient';
                        botao.textContent = 'Remover';
                        botao.addEventListener('click', () => div.remove());

                        // Adiciona os elementos ao container
                        div.appendChild(select);
                        div.appendChild(input);
                        div.appendChild(botao);

                        container.appendChild(div);
                    });
                })
                .catch(error => {
                    console.error("Erro ao buscar dados da pizza:", error);
                });
        }
    });

    // Exclusão
    const botaoExcluir = document.getElementById("botaoExcluir");
    botaoExcluir.addEventListener("click", function () {
        const linhaSelecionada = document.querySelector(".linha-tabela.selecionada");
        if (linhaSelecionada) {
            const idPizza = linhaSelecionada.getAttribute("data-id");
            document.getElementById("idPizzaExcluir").value = idPizza;
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

        if (tds[1].textContent.toLowerCase().indexOf(filter) > -1) {
            display = true;
        }

        row.style.display = display ? '' : 'none';
    }
}