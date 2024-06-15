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
                url: "http://localhost/projeto_pizzariaSite/Estoque/buscaLinhaAJAX.php",
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