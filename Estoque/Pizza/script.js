document.addEventListener("DOMContentLoaded", function () {
    atualizarTabela()
});

function atualizarTabela() {
    fetch('get_pizzas.php')
        .then(response => response.json())
        .then(pizzas => {
            const tbody = document.getElementById('corpoTabela');
            tbody.innerHTML = ""; // Limpa as linhas antigas
            pizzas.forEach(pizza => {
                const tr = document.createElement('tr');
                tr.classList.add('linha-tabela');
                tr.setAttribute('data-id', pizza.idpizzas);

                const tdId = document.createElement('td');
                tdId.textContent = pizza.idpizzas;

                const tdNome = document.createElement('td');
                tdNome.innerHTML = `<strong>${pizza.nomePizza}</strong>`;

                const tdIngredientes = document.createElement('td');
                if (pizza.ingredientes.length > 0) {
                    const ul = document.createElement('ul');
                    pizza.ingredientes.forEach(ingrediente => {
                        const li = document.createElement('li');
                        li.innerHTML = `<strong>${ingrediente.nomeProduto}</strong> - ${ingrediente.quantidade} ${ingrediente.unidadeMedida}`;
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
}

document.addEventListener("DOMContentLoaded", function () {
    const tbody = document.getElementById("corpoTabela");
    const container = document.getElementById("ingredients-container");

    tbody.addEventListener("click", function (event) {
        const linha = event.target.closest(".linha-tabela");

        if (linha) {
            // Remove a classe "selecionada" da linha clicada,
            // se já estiver selecionada, limpa os campos
            // de exclusão e retorna para não chamar o fetch
            if (linha.classList.contains("selecionada")) {
                document.getElementById("idPizzaExcluir").value = "";
                document.getElementById("pizza_name").value = "";
                document.getElementById("tipoPizza").value = "";
                container.innerHTML = '';
                criarIngrediente();
                linha.classList.remove("selecionada");
                return;
            }

            tbody.querySelectorAll(".linha-tabela").forEach(l => l.classList.remove("selecionada"));
            linha.classList.add("selecionada");

            const idPizzaSelecionada = linha.getAttribute("data-id");
            document.getElementById("idPizzaExcluir").value = idPizzaSelecionada;

            const modal = document.getElementById("pizza-modal");
            modal.style.display = "flex";

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

    let produtosDisponiveis = [];

    // Função para criar um novo bloco de ingrediente
    function criarIngrediente(produtoSelecionado = null) {
        const div = document.createElement('div');
        div.className = 'ingredient-row';

        // Cria o select com os produtos disponíveis
        const select = document.createElement('select');
        select.name = 'ingredients[]';
        select.className = 'ingredient-select';

        produtosDisponiveis.forEach(prod => {
            const option = document.createElement('option');
            option.value = prod.idprodutos;
            option.textContent = prod.nomeProduto;
            if (produtoSelecionado && prod.id === produtoSelecionado) {
                option.selected = true;
            }
            select.appendChild(option);
        });

        // Campo de quantidade
        const input = document.createElement('input');
        input.type = 'number';
        input.name = 'quantities[]';
        input.className = 'ingredient-quantity';
        input.placeholder = 'Quantidade';
        input.min = '1';
        input.value = '';

        // Botão para remover ingrediente
        const botao = document.createElement('button');
        botao.type = 'button';
        botao.className = 'remove-ingredient';
        botao.textContent = 'Remover';
        botao.addEventListener('click', () => div.remove());

        // Adiciona os elementos ao bloco
        div.appendChild(select);
        div.appendChild(input);
        div.appendChild(botao);
        container.appendChild(div);
    }

    // Carrega os produtos antes de permitir adicionar ingredientes
    fetch('get_produtos.php')
        .then(response => response.json())
        .then(data => {
            produtosDisponiveis = data.produtos;
        })
        .catch(error => {
            console.error('Erro ao carregar produtos:', error);
        });

    // Evento do botão para adicionar ingrediente
    document.getElementById('add-ingredient').addEventListener('click', () => {
        if (produtosDisponiveis.length === 0) {
            alert('Produtos ainda não carregados. Tente novamente em instantes.');
            return;
        }
        criarIngrediente();
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

const modal = document.getElementById("pizza-modal");


document.getElementById("save-pizza").addEventListener("click", function () {
    const pizzaName = document.getElementById("pizza_name").value;
    const tipoPizza = document.getElementById("tipoPizza").value;

    // Pega todas as linhas de ingrediente
    const rows = document.querySelectorAll("#ingredients-container .ingredient-row");
    const ingredientes = [];

    rows.forEach(row => {
        const select = row.querySelector("select.ingredient-select");
        const quantityInput = row.querySelector("input.ingredient-quantity");

        const produtoId = select.value;
        const quantidade = parseInt(quantityInput.value);

        if (produtoId && quantidade > 0) {
            ingredientes.push({ produto_id: produtoId, quantidade: quantidade });
        }
    });

    if (!pizzaName || ingredientes.length === 0) {
        alert("Preencha o nome da pizza e pelo menos um ingrediente com quantidade.");
        return;
    }

    // Monta o JSON para enviar
    const dados = {
        idpizza: document.getElementById("idPizzaExcluir").value,
        nome: pizzaName,
        tipo: tipoPizza,
        ingredientes: ingredientes
    };

    fetch("adicionar_pizza.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(dados)
    })
        .then(response => response.json())
        .then(resposta => {
            if (resposta.sucesso) {
                Toastify({
                    text: "Pizza salva com sucesso!",
                    duration: 3000,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#4BB543"
                }).showToast();

                modal.style.display = "none";
                const tbody = document.getElementById("corpoTabela");
                tbody.querySelectorAll(".linha-tabela").forEach(l => l.classList.remove("selecionada"));
                document.getElementById("idPizzaExcluir").value = "";
                document.getElementById("pizza_name").value = "";
                document.getElementById("tipoPizza").value = "";
                const container = document.getElementById("ingredients-container");
                container.innerHTML = '';

                atualizarTabela();
            } else {
                Toastify({
                    text: "Erro: " + resposta.mensagem,
                    duration: 4000,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#FF3333"
                }).showToast();
            }
        })
        .catch(erro => {
            console.error("Erro:", erro);
            alert("Erro ao salvar pizza.");
        });
});

document.getElementById('open-modal').addEventListener('click', function () {
    document.getElementById('pizza-modal').style.display = 'flex';
});

const tooltip = document.getElementById('tooltip');

document.querySelectorAll('.btn').forEach(btn => {
    btn.addEventListener('mouseenter', (event) => {
        const tooltipText = btn.getAttribute('data-tooltip');
        if (tooltipText) {
            tooltip.textContent = tooltipText;
            tooltip.style.display = 'block';
            tooltip.style.left = event.clientX + 'px';
            tooltip.style.top = event.clientY - 30 + 'px';
        }
    });

    btn.addEventListener('mouseleave', () => {
        tooltip.style.display = 'none';
    });

    btn.addEventListener('mousemove', (event) => {
        tooltip.style.left = event.clientX + 'px';
        tooltip.style.top = event.clientY - 30 + 'px';
    });
});

window.onclick = function (event) {
    const modal = document.getElementById("pizza-modal");
    if (event.target === modal) {
        modal.style.display = "none";
        const tbody = document.getElementById("corpoTabela");
        tbody.querySelectorAll(".linha-tabela").forEach(l => l.classList.remove("selecionada"));
        document.getElementById("idPizzaExcluir").value = "";
        document.getElementById("pizza_name").value = "";
        document.getElementById("tipoPizza").value = "";
        const container = document.getElementById("ingredients-container");

        container.innerHTML = '';
        criarIngrediente();
    }
};

// Fechar modal com tecla Escape
document.addEventListener('keydown', function (event) {
    const modal = document.getElementById("pizza-modal");
    if (event.key === "Escape" && modal.style.display === "flex") {
        modal.style.display = "none";
        const tbody = document.getElementById("corpoTabela");
        tbody.querySelectorAll(".linha-tabela").forEach(l => l.classList.remove("selecionada"));
        document.getElementById("idPizzaExcluir").value = "";
        document.getElementById("pizza_name").value = "";
        document.getElementById("tipoPizza").value = "";
        const container = document.getElementById("ingredients-container");

        container.innerHTML = '';
        criarIngrediente();
    }
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