let todasSaidas = [];

function atualizarTabela() {
    fetch('get_saida.php')
        .then(response => response.json())
        .then(saidas => {
            todasSaidas = saidas; // guarda globalmente os dados originais

            popularFiltros(saidas);
            filtrarTabela(); // já renderiza tabela filtrada (sem filtro inicialmente)
        })
        .catch(error => {
            console.error('Erro ao carregar saídas:', error);
        });
}

function popularFiltros(saidas) {
    const produtosSet = new Set();
    const pizzasSet = new Set();

    saidas.forEach(s => {
        if (s.nomeProduto) produtosSet.add(s.nomeProduto);
        if (s.nomePizza) pizzasSet.add(s.nomePizza);
    });

    const produtoSelect = document.getElementById('produtoFiltro');
    const pizzaSelect = document.getElementById('pizzaFiltro');

    if (produtoSelect) {
        produtoSelect.innerHTML = '<option value="">Todos</option>';
        [...produtosSet].sort().forEach(produto => {
            const opt = document.createElement('option');
            opt.value = produto;
            opt.textContent = produto;
            produtoSelect.appendChild(opt);
        });
    }

    if (pizzaSelect) {
        pizzaSelect.innerHTML = '<option value="">Todas</option>';
        [...pizzasSet].sort().forEach(pizza => {
            const opt = document.createElement('option');
            opt.value = pizza;
            opt.textContent = pizza;
            pizzaSelect.appendChild(opt);
        });
    }
}

function filtrarTabela() {
    const dataInicio = document.getElementById('dataInicio').value;
    const dataFim = document.getElementById('dataFim').value;
    const produto = document.getElementById('produtoFiltro').value;
    const pizza = document.getElementById('pizzaFiltro').value;

    // Filtra as saídas com base nos filtros
    const filtrados = todasSaidas.filter(s => {
        const dataValida =
            (!dataInicio || s.data_saida >= dataInicio) &&
            (!dataFim || s.data_saida <= dataFim);

        const produtoValido = !produto || s.nomeProduto === produto;
        const pizzaValida = !pizza || s.nomePizza === pizza;

        return dataValida && produtoValido && pizzaValida;
    });

    renderizarTabela(filtrados);
}

function renderizarTabela(saidas) {
    const tbody = document.getElementById('bodySaida');
    tbody.innerHTML = "";

    let lastPizza = null;
    let lastVenda = null;
    let pizzaCount = {};
    let vendaCount = {};

    // Contagem para rowspan
    for (let i = 0; i < saidas.length; i++) {
        const pizza = saidas[i].nomePizza;
        const venda = saidas[i].venda_id;

        if (pizza === lastPizza) {
            pizzaCount[pizza]++;
        } else {
            pizzaCount[pizza] = 1;
            lastPizza = pizza;
        }

        if (venda === lastVenda) {
            vendaCount[venda]++;
        } else {
            vendaCount[venda] = 1;
            lastVenda = venda;
        }
    }

    lastPizza = null;
    lastVenda = null;

    for (let i = 0; i < saidas.length; i++) {
        const saida = saidas[i];
        const tr = document.createElement('tr');
        tr.classList.add('linha-tabela');
        tr.setAttribute('data-id', saida.id);

        tr.innerHTML += `<td class="data">${formatarDataCompleta(saida.data_saida)}</td>`;
        tr.innerHTML += `<td>${saida.nomeProduto}</td>`;

        if (saida.nomePizza !== lastPizza) {
            const tdPizza = document.createElement('td');
            tdPizza.rowSpan = pizzaCount[saida.nomePizza];
            tdPizza.textContent = saida.nomePizza;
            tdPizza.classList.add('pizza');
            tr.appendChild(tdPizza);
            lastPizza = saida.nomePizza;
        }

        if (saida.venda_id !== lastVenda) {
            const tdVenda = document.createElement('td');
            tdVenda.rowSpan = vendaCount[saida.venda_id];
            tdVenda.textContent = saida.venda_id;
            tdVenda.classList.add('venda');
            tr.appendChild(tdVenda);
            lastVenda = saida.venda_id;
        }

        tr.innerHTML += `
        <td class="motivo">${saida.motivo}</td>
        <td>${saida.lote}</td>
        <td>${saida.quantidade}</td>
      `;

        tbody.appendChild(tr);
    }
}

function formatarDataCompleta(dataISO) {
    const data = new Date(dataISO);
    const dia = String(data.getDate()).padStart(2, '0');
    const mes = String(data.getMonth() + 1).padStart(2, '0');
    const ano = data.getFullYear();
    const horas = String(data.getHours()).padStart(2, '0');
    const minutos = String(data.getMinutes()).padStart(2, '0');
    const segundos = String(data.getSeconds()).padStart(2, '0');

    return `${dia}/${mes}/${ano} ${horas}:${minutos}:${segundos}`;
}


// Adiciona eventos para filtrar ao mudar os filtros
document.getElementById('dataInicio').addEventListener('change', filtrarTabela);
document.getElementById('dataFim').addEventListener('change', filtrarTabela);
document.getElementById('produtoFiltro').addEventListener('change', filtrarTabela);
document.getElementById('pizzaFiltro').addEventListener('change', filtrarTabela);

// Inicializa tabela e filtros
atualizarTabela();