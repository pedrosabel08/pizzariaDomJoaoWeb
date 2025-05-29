function trocarTipo() {
    const tipo = $('#tipoFiltro').val();
    window.location.href = 'estoque.php?tipo=' + tipo;
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

function validarColunaQuantidade() {
    // Pega a tabela pelo id
    const tabela = document.getElementById('tabelaEstoque');

    // Pega todas as linhas do corpo da tabela (tbody)
    const linhas = tabela.tBodies[0].rows;

    // Percorre as linhas
    for (let i = 0; i < linhas.length; i++) {
        const linha = linhas[i];

        // Pega o valor da coluna 3 (quantidade), lembrando que o índice começa em 0
        const quantidadeTexto = linha.cells[1].textContent.trim();

        // Converte para número (se for numérico)
        const quantidade = parseInt(quantidadeTexto); // Correto


        // Faz a validação (exemplo: quantidade menor ou igual a zero)
        if (quantidade <= 2000 || isNaN(quantidade)) {
            // Aqui pode fazer alguma ação, como destacar a linha
            linha.classList.add('qtdBaixa'); // vermelho claro, por exemplo
        } else {
            // Se quiser, pode resetar o estilo para as linhas válidas
            linha.classList.remove('qtdBaixa'); // Remove se não for inválido
        }
    }
}

// Chama a função depois que a página carregar ou após atualizar a tabela
window.onload = function () {
    validarColunaQuantidade();
};