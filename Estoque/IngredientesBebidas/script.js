function trocarTipo() {
    const tipo = $('#tipoFiltro').val();
    carregarTabela(tipo);
}

function abrirModalNovo() {
    const tipo = $('#tipoFiltro').val();
    if (tipo === 'ingredientes') {
        $('#formIngrediente')[0].reset();
        $('#idIngrediente').val('');
        $('#nomeIngrediente').prop('readonly', false);
        $('#tituloModalIngrediente').text('Novo Ingrediente');
        new bootstrap.Modal(document.getElementById('modalIngrediente')).show();
    } else {
        $('#formBebida')[0].reset();
        $('#idBebida').val('');
        $('#tituloModalBebida').text('Nova Bebida');
        new bootstrap.Modal(document.getElementById('modalBebida')).show();
    }
}

function abrirModalEditar(btn) {
    const tipo = $('#tipoFiltro').val();
    const tr = $(btn).closest('tr');
    const tds = tr.find('td');
    if (tipo === 'ingredientes') {
        $('#idIngrediente').val(tr.data('id'));
        $('#nomeIngrediente').val(tds.eq(0).text()).prop('readonly', true);
        $('#quantidadeIngrediente').val(tds.eq(1).text());
        $('#unidadeIngrediente option').filter(function () {
            return $(this).text() === tds.eq(2).text();
        }).prop('selected', true);

        // Preencher o tipo corretamente
        $('#tipoIngrediente').val(tr.data('tipo-id'));

        // Corrigir a validade
        let validade = tds.eq(4).text().replace(/\s+/g, '').trim();
        if (validade && validade.includes('/')) {
            let partes = validade.split('/');
            if (partes.length === 3) {
                validade = [partes[2], partes[1], partes[0]].join('-');
            } else {
                validade = '';
            }
        } else {
            validade = '';
        }
        $('#validadeIngrediente').val(validade);

        $('#tituloModalIngrediente').text('Editar Ingrediente');
        new bootstrap.Modal(document.getElementById('modalIngrediente')).show();
    } else {
        $('#idBebida').val(tr.data('id'));
        $('#marcaBebida').val(tr.data('marca-id'));
        $('#categoriaBebida').val(tr.data('categoria-id'));
        $('#tamanhoBebida').val(tr.data('tamanho-id'));
        $('#quantidadeBebida').val(tds.eq(3).text());
        let validade = tds.eq(4).text().trim();
        if (validade) {
            let partes = validade.split('/');
            if (partes.length === 3) {
                validade = [partes[2], partes[1], partes[0]].join('-');
            } else {
                validade = '';
            }
        }
        $('#validadeBebida').val(validade);
        $('#precoBebida').val(tds.eq(5).text());
        $('#tituloModalBebida').text('Editar Bebida');
        new bootstrap.Modal(document.getElementById('modalBebida')).show();
    }
}

function excluirLinha(btn) {
    if (!confirm('Deseja realmente excluir este item?')) return;
    const tipo = $('#tipoFiltro').val();
    const tr = $(btn).closest('tr');
    const id = tr.data('id');
    $.post('excluir.php', { tipo, id }, function (resp) {
        if (resp.trim() === 'ok') {
            tr.remove();
        } else {
            alert('Erro ao excluir!');
        }
    });
}
let quantidadeTexto = null;
let minimo = null;

function validarColunaQuantidade() {
    const tabela = document.getElementById('tabelaEstoque');
    const linhas = tabela.tBodies[0].rows;
    const tipo = document.getElementById('tipoFiltro').value;

    const itensAbaixoEstoque = [];
    let minimo;

    for (let i = 0; i < linhas.length; i++) {
        const linha = linhas[i];
        let quantidadeTexto, nomeItem, marca, categoria, tamanho, quantidade;

        if (tipo === 'ingredientes') {
            quantidadeTexto = linha.cells[1].textContent.trim();
            nomeItem = linha.cells[0].textContent.trim();
            minimo = 2000;

            quantidade = parseInt(quantidadeTexto);

            if (quantidade <= minimo || isNaN(quantidade)) {
                linha.classList.add('qtdBaixa');
                itensAbaixoEstoque.push(`${nomeItem} - Quantidade: ${quantidadeTexto}`);
            } else {
                linha.classList.remove('qtdBaixa');
            }
        } else if (tipo === 'bebidas') {
            // Marca, categoria, tamanho, quantidade
            marca = linha.cells[0].textContent.trim();
            categoria = linha.cells[1].textContent.trim();
            tamanho = linha.cells[2].textContent.trim();
            quantidadeTexto = linha.cells[3].textContent.trim();
            minimo = 7;

            quantidade = parseInt(quantidadeTexto);

            if (quantidade <= minimo || isNaN(quantidade)) {
                linha.classList.add('qtdBaixa');
                itensAbaixoEstoque.push(`${marca}, ${categoria}, ${tamanho} - Quantidade: ${quantidadeTexto}`);
            } else {
                linha.classList.remove('qtdBaixa');
            }
        } else {
            // Para outros tipos, apenas quantidade e nome na primeira coluna
            quantidadeTexto = linha.cells[1].textContent.trim();
            nomeItem = linha.cells[0].textContent.trim();
            minimo = 2000;

            quantidade = parseInt(quantidadeTexto);

            if (quantidade <= minimo || isNaN(quantidade)) {
                linha.classList.add('qtdBaixa');
                itensAbaixoEstoque.push(`${nomeItem} - : ${quantidadeTexto}`);
            } else {
                linha.classList.remove('qtdBaixa');
            }
        }
    }

    if (itensAbaixoEstoque.length > 0) {
        Swal.fire({
            title: `<h2>Itens abaixo do estoque mínimo</h2>`,
            html: `<ul style="text-align: left; font-size: 14px">${itensAbaixoEstoque.map(item => `<li style='padding: 10px 0'>${item}</li>`).join('')}</ul>`,
            icon: 'warning',
            confirmButtonText: 'OK'
        });
    } else {
        Swal.fire({
            title: 'Estoque OK',
            text: 'Todos os itens estão com estoque suficiente.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    }
}



function carregarTabela(tipo) {
    fetch(`tabela.php?tipo=${tipo}`)
        .then(response => response.json())
        .then(data => {
            console.log('Dados recebidos:', data.dados);

            const tabela = document.getElementById('tabelaEstoque');
            const thead = tabela.querySelector('thead');
            const tbody = tabela.querySelector('tbody');

            // Atualiza o cabeçalho da tabela
            thead.innerHTML = '';
            let headerRow = document.createElement('tr');

            if (tipo === 'ingredientes') {
                headerRow.innerHTML = `
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Unidade Medida</th>
                    <th>Tipo</th>
                    <th>Validade</th>
                `;
            } else {
                headerRow.innerHTML = `
                    <th>Marca</th>
                    <th>Categoria</th>
                    <th>Tamanho</th>
                    <th>Quantidade</th>
                    <th>Validade</th>
                    <th>Preço</th>
                `;
            }
            thead.appendChild(headerRow);

            // Limpa e preenche o corpo da tabela
            tbody.innerHTML = '';
            data.dados.forEach(item => {
                let row = document.createElement('tr');

                if (tipo === 'ingredientes') {
                    row.setAttribute('data-id', item.id);
                    row.setAttribute('data-tipo-id', item.tipo_id);
                    row.innerHTML = `
                        <td>${item.nome}</td>
                        <td>${item.quantidade}</td>
                        <td>${item.unidadeMedida || ''}</td>
                        <td>${item.tipo_nome || ''}</td>
                        <td>${item.data_validade ? formatarData(item.data_validade) : ''}</td>
                    `;
                } else {
                    row.setAttribute('data-id', item.id);
                    row.setAttribute('data-marca-id', item.marca_id);
                    row.setAttribute('data-categoria-id', item.categoria_id);
                    row.setAttribute('data-tamanho-id', item.tamanho_id);
                    row.innerHTML = `
                        <td>${item.marca}</td>
                        <td>${item.categoria}</td>
                        <td>${item.tamanho} (${item.volume}ml)</td>
                        <td>${item.quantidade}</td>
                        <td>${item.validade ? formatarData(item.validade) : ''}</td>
                        <td>${item.preco}</td>
                    `;
                }

                tbody.appendChild(row);
                validarColunaQuantidade();

            });
        });
}

// Função para formatar a data
function formatarData(dataStr) {
    const data = new Date(dataStr);
    const dia = String(data.getDate()).padStart(2, '0');
    const mes = String(data.getMonth() + 1).padStart(2, '0');
    const ano = data.getFullYear();
    return `${dia}/${mes}/${ano}`;
}

async function carregarDadosSelects() {
    try {
        const resposta = await fetch('tabela.php?tipo=ingredientes');
        const json = await resposta.json();

        // Ingredientes
        preencherSelect(json.unidades, 'unidadeIngrediente', 'idunidadeMedida', 'nome');
        preencherSelect(json.tipos, 'tipoIngrediente', 'idtipo_produtos', 'nome_tipo');

        // Bebidas
        preencherSelect(json.marcas, 'marcaBebida', 'idmarcaBebidas', 'nome');
        preencherSelect(json.categorias, 'categoriaBebida', 'idcategoriaBebidas', 'nome');
        preencherSelect(json.tamanhos, 'tamanhoBebida', 'idtamanhoBebidas', item => `${item.nome} (${item.volume}ml)`);

    } catch (erro) {
        console.error('Erro ao carregar dados:', erro);
    }
}

function preencherSelect(lista, idSelect, chaveValor, chaveTexto) {
    const select = document.getElementById(idSelect);
    select.innerHTML = '<option value="">Selecione</option>';
    lista.forEach(item => {
        const option = document.createElement('option');
        option.value = item[chaveValor];
        option.textContent = typeof chaveTexto === 'function' ? chaveTexto(item) : item[chaveTexto];
        select.appendChild(option);
    });
}


window.onload = function () {
    carregarTabela('ingredientes');
    // carregarDadosSelects();
}