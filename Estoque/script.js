document.addEventListener("DOMContentLoaded", function () {
    var linhasTabela = document.querySelectorAll(".linha-tabela");

    linhasTabela.forEach(function (linha) {
        linha.addEventListener("click", function () {
            linhasTabela.forEach(function (outraLinha) {
                outraLinha.classList.remove("selecionada");
            });

            linha.classList.add("selecionada");
            var idProdutoSelecionado = linha.getAttribute("data-id");
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "http://localhost:8066/pizzariaDomJoaoWeb/Estoque/buscaLinhaAJAX.php",
                data: { ajid: idProdutoSelecionado },
                success: function (response) {
                    if (response.length > 0) {
                        document.getElementById('nomeProduto').value = response[0].nomeProduto;
                        document.getElementById('quantidade').value = response[0].quantidade;
                        document.getElementById('unidadeMedida').value = response[0].unidadeMedida;
                        document.getElementById('validade').value = response[0].validade;
                    } else {
                        console.log("Nenhum produto encontrado.");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error("Erro na requisição AJAX: " + textStatus, errorThrown);
                }
            });

            console.log("Linha selecionada: ID do produto = " + idProdutoSelecionado);
        });
    });

    var botaoExcluir = document.getElementById("botaoExcluir");

    botaoExcluir.addEventListener("click", function () {
        var linhaSelecionada = document.querySelector(".selecionada");

        if (linhaSelecionada) {
            var idProdutoSelecionado = linhaSelecionada.getAttribute("data-id");
            document.getElementById("idProdutoExcluir").value = idProdutoSelecionado;
            document.getElementById("formExcluirProduto").submit();
        } else {
            console.log("Nenhuma linha selecionada para exclusão.");
        }
    });

    var botaoAlterar = document.getElementById("botaoAlterar");

    botaoAlterar.addEventListener("click", function () {
        var linhaSelecionada = document.querySelector(".selecionada");

        if (linhaSelecionada) {
            var idProdutoSelecionado = linhaSelecionada.getAttribute("data-id");
            document.getElementById("idProdutoAlterar").value = idProdutoSelecionado;
            document.getElementById('nomeProdutoAlterar').value = document.getElementById('nomeProduto').value;
            document.getElementById('qtdeProdutoAlterar').value = document.getElementById('quantidade').value;
            document.getElementById('umProdutoAlterar').value = document.getElementById('unidadeMedida').value;
            document.getElementById('validadeProdutoAlterar').value = document.getElementById('validade').value;
            document.getElementById("formAlterarProduto").submit();
        } else {
            console.log("Nenhuma linha selecionada para alterar.");
        }
    });

    const quantidade = {
        'Temperos': {
            'Molho de tomate': 18000,
            'Alho': 300,
            'Alho Frito': 1500,
            'Orégano': 600,
            'Manjericão': 300,
            'Molho de pimenta': 1500,
            'Pimenta biquinho': 600,
            'Canela': 300,
            'Geleia de pimenta': 900
        },
        'Queijos': {
            'Queijo Mussarela': 45000,
            'Parmesão': 6000,
            'Catupiry': 4500,
            'Provolone': 3000,
            'Cream Cheese': 1500,
            'Cheddar': 1500,
            'Queijo brie': 1500,
            'Gorgonzola': 1500
        },
        'Carnes': {
            'Bacon': 6000,
            'Carne Moída': 9000,
            'Calabresa': 15000,
            'Chester': 3000,
            'Frango': 15000,
            'Lombo': 6000,
            'Peito de Peru': 4500,
            'Presunto': 9000,
            'Salame Italiano': 3000,
            'Pepperoni': 6000,
            'Coração': 3000,
            'Tiras de Carne': 6000,
            'Linguiça Blumenau': 3000,
            'Strogonoff de carne': 3000,
            'Strogonoff de frango': 3000,
            'Camarão': 1500,
            'Mignon': 1500,
            'Filé': 1500,
            'Carne seca desfiada': 1500,
            'Costelinha suína desfiada': 1500
        },
        'Enlatados': {
            'Atum': 4500,
            'Milho': 300,
            'Palmito': 3000,
            'Azeitona': 1500,
            'Azeitonas pretas': 1500,
            'Champignon': 1500
        },
        'Vegetais': {
            'Cebola': 6000,
            'Brócolis': 3000,
            'Tomate': 15000,
            'Tomate seco': 3000,
            'Rúcula': 1500,
            'Morango': 1500,
            'Banana': 1500,
            'Cereja': 1500
        },
        'Outros': {
            'Óleo': 3000,
            'Barbecue': 3000,
            'Creme de Leite': 6000,
            'Doritos': 1500,
            'Granulado': 600,
            'Confetti': 600,
            'Creme de coco': 1500,
            'Goiabada': 1500,
            'Sonho de valsa': 1500,
            'Capuccino': 1500,
            'Doce de leite': 1500,
            'Amendoim': 1500,
            'Coco ralado': 1500,
            'Marshmallow': 1500,
            'Leite Condensado': 6000,
            'Chocolate Preto': 3000,
            'Chocolate Branco': 3000,
            'Batata Palha': 800,
            'Ovo': 3000
        }
    };

    const rows = document.querySelectorAll('#tabelaEstoque .linha-tabela');

    rows.forEach(row => {
        const quantityCell = row.cells[1];
        const quantity = parseInt(quantityCell.textContent);
        const productName = row.cells[0].textContent.trim();
        let category = '';

        // Encontra a categoria do produto
        for (const cat in quantidade) {
            if (quantidade[cat].hasOwnProperty(productName)) {
                category = cat;
                break;
            }
        }

        const quantidadeTotal = quantidade[category][productName];
        const percentagemAtual = (quantity / quantidadeTotal) * 100;

        if (percentagemAtual <= 20) {
            row.classList.add('low-stock');
        } else {
            row.classList.add('normal-stock');
        }
    });
});

function filterTable() {
    const input = document.getElementById('filterInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('tabelaEstoque');
    const trs = table.getElementsByTagName('tr');

    for (let i = 1; i < trs.length; i++) {
        const tds = trs[i].getElementsByTagName('td');
        let display = false;

        for (let j = 0; j < tds.length; j++) {
            if (tds[j].textContent.toLowerCase().indexOf(filter) > -1) {
                display = true;
                break;
            }
        }

        trs[i].style.display = display ? '' : 'none';
    }
}