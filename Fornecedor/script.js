document.addEventListener('DOMContentLoaded', () => {
    const fornecedorSelect = document.getElementById('idfornecedor');
    const produtosContainer = document.getElementById('produtos-container');
    let produtosDisponiveis = [];

    // Carrega fornecedores
    fetch('compra.php?action=fornecedores')
        .then(res => res.json())
        .then(data => {
            data.forEach(f => {
                const opt = document.createElement('option');
                opt.value = f.idfornecedor;
                opt.textContent = f.nome;
                fornecedorSelect.appendChild(opt);
            });
        });

    // Quando trocar o fornecedor, carrega produtos
    fornecedorSelect.addEventListener('change', () => {
        produtosContainer.innerHTML = '';
        const idfornecedor = fornecedorSelect.value;
        if (!idfornecedor) return;

        fetch(`compra.php?action=produtos_por_fornecedor&idfornecedor=${idfornecedor}`)
            .then(res => res.json())
            .then(produtos => {
                produtosDisponiveis = produtos;
                if (produtos.length === 0) {
                    produtosContainer.innerHTML = '<p>Nenhum produto disponível para este fornecedor.</p>';
                    return;
                }
                // Monta tabela de produtos
                let html = `<table>
                    <thead>
                        <tr>
                            <th>Selecionar</th>
                            <th>Produto</th>
                            <th>UM</th>
                            <th>Data Validade</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                        </tr>
                    </thead>
                    <tbody>
                `;
                produtos.forEach(prod => {
                    html += `
                        <tr class="produto-row">
                            <td><input type="checkbox" class="selecionar-produto" data-id="${prod.idprodutos}"></td>
                            <td>${prod.nomeProduto}</td>
                            <td>${prod.unidadeMedida}</td>
                            <td><input type="text" class="data" data-id="${prod.idprodutos}" placeholder="DD/MM/AAAA" disabled></td>
                            <td><input type="text" class="preco_unitario" data-id="${prod.idprodutos}" placeholder="R$ 0,00" disabled></td>
                            <td><input type="text" class="quantidade" data-id="${prod.idprodutos}" placeholder="0,00" disabled></td>
                        </tr>
                    `;
                });
                html += '</tbody></table>';
                produtosContainer.innerHTML = html;
                aplicarMascaraNosCamposData();

                // Habilita/desabilita inputs ao selecionar produto
                document.querySelectorAll('.selecionar-produto').forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        const id = this.dataset.id;
                        document.querySelector(`.data[data-id="${id}"]`).disabled = !this.checked;
                        document.querySelector(`.preco_unitario[data-id="${id}"]`).disabled = !this.checked;
                        document.querySelector(`.quantidade[data-id="${id}"]`).disabled = !this.checked;
                    });
                });
            });
    });

    // Envio do formulário
    document.getElementById('formCompra').addEventListener('submit', async (e) => {
        e.preventDefault();
        const idfornecedor = fornecedorSelect.value;
        const observacoes = e.target.observacoes.value;

        // Monta array de produtos selecionados
        const produtosSelecionados = [];
        document.querySelectorAll('.selecionar-produto:checked').forEach(checkbox => {
            const id = checkbox.dataset.id;
            const data = document.querySelector(`.data[data-id="${id}"]`).value;
            if (data && !/^\d{2}\/\d{2}\/\d{4}$/.test(data)) {
                Toastify({
                    text: "Data inválida. Use o formato DD/MM/AAAA.",
                    duration: 4000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#FF3333"
                }).showToast();
                return;
            }
            const dataFormatada = data.split('/').reverse().join('-');
            let quantidade = document.querySelector(`.quantidade[data-id="${id}"]`).value;
            let preco_unitario = document.querySelector(`.preco_unitario[data-id="${id}"]`).value;

            // Remove R$, pontos e troca vírgula por ponto
            preco_unitario = preco_unitario.replace('R$', '').replace(/\./g, '').replace(',', '.').trim();
            quantidade = quantidade.replace(/\./g, '').replace(',', '.').trim();

            if (
                quantidade && preco_unitario &&
                !isNaN(parseFloat(quantidade)) && !isNaN(parseFloat(preco_unitario)) &&
                parseFloat(quantidade) > 0 && parseFloat(preco_unitario) > 0
            ) {
                produtosSelecionados.push({
                    idproduto: id,
                    quantidade: quantidade,
                    preco_unitario: preco_unitario,
                    dataValidade: dataFormatada
                });
            }
        });

        if (produtosSelecionados.length === 0) {
            Toastify({
                text: "Selecione pelo menos um produto e preencha quantidade/preço.",
                duration: 4000,
                gravity: "top",
                position: "right",
                backgroundColor: "#FF3333"
            }).showToast();
            return;
        }

        // Envia para o PHP
        const res = await fetch('compra.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                idfornecedor,
                observacoes,
                produtos: produtosSelecionados
            })
        });
        let data = {};
        try {
            data = await res.json();
        } catch {
            Toastify({
                text: "Erro inesperado no servidor.",
                duration: 4000,
                gravity: "top",
                position: "right",
                backgroundColor: "#FF3333"
            }).showToast();
            return;
        }
        Toastify({
            text: data.mensagem || "Erro desconhecido.",
            duration: 4000,
            gravity: "top",
            position: "right",
            backgroundColor: data.sucesso ? "#4BB543" : "#FF3333"
        }).showToast();
        if (data.sucesso) {
            produtosContainer.innerHTML = '';
            e.target.reset();
        }
    });

    // Função para aplicar máscara DD/MM/AAAA
    function aplicarMascaraData(input) {
        input.addEventListener('input', function (e) {
            let v = input.value.replace(/\D/g, '').slice(0, 8);
            if (v.length >= 5) {
                input.value = v.replace(/(\d{2})(\d{2})(\d{0,4})/, '$1/$2/$3');
            } else if (v.length >= 3) {
                input.value = v.replace(/(\d{2})(\d{0,2})/, '$1/$2');
            } else {
                input.value = v;
            }
        });
        // Impede digitação além de 10 caracteres (incluindo as barras)
        input.setAttribute('maxlength', '10');
    }

    // Aplica a máscara sempre que a tabela de produtos for montada
    function aplicarMascaraNosCamposData() {
        document.querySelectorAll('.data').forEach(input => {
            aplicarMascaraData(input);
        });
    }

    // Máscara para preço: R$ 0,00
    function aplicarMascaraPreco(input) {
        input.addEventListener('input', function () {
            let v = input.value.replace(/\D/g, '');
            v = (parseInt(v, 10) / 100).toFixed(2) + '';
            v = v.replace('.', ',');
            v = v.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            input.value = 'R$ ' + v;
        });
        input.addEventListener('focus', function () {
            if (!input.value) input.value = 'R$ 0,00';
        });
        input.setAttribute('maxlength', '15');
    }

    // Máscara para quantidade: 0,00
    function aplicarMascaraQuantidade(input) {
        input.addEventListener('input', function () {
            let v = input.value.replace(/\D/g, '');
            v = (parseInt(v, 10) / 100).toFixed(2) + '';
            v = v.replace('.', ',');
            v = v.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            input.value = v;
        });
        input.setAttribute('maxlength', '10');
    }

    // Aplica as máscaras após montar a tabela
    function aplicarMascarasNosCampos() {
        document.querySelectorAll('.preco_unitario').forEach(input => aplicarMascaraPreco(input));
        document.querySelectorAll('.quantidade').forEach(input => aplicarMascaraQuantidade(input));
    }

    // Chame após montar a tabela de produtos
    aplicarMascarasNosCampos();
});