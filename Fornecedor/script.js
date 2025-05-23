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
                            <th>Unidade</th>
                            <th>Estoque</th>
                            <th>Preço Médio</th>
                            <th>Quantidade</th>
                            <th>Preço Unitário</th>
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
                            <td>${prod.quantidade}</td>
                            <td>${Number(prod.preco_medio).toFixed(2)}</td>
                            <td><input type="number" min="1" class="quantidade" data-id="${prod.idprodutos}" disabled></td>
                            <td><input type="number" min="0" step="0.01" class="preco_unitario" data-id="${prod.idprodutos}" disabled></td>
                        </tr>
                    `;
                });
                html += '</tbody></table>';
                produtosContainer.innerHTML = html;

                // Habilita/desabilita inputs ao selecionar produto
                document.querySelectorAll('.selecionar-produto').forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        const id = this.dataset.id;
                        document.querySelector(`.quantidade[data-id="${id}"]`).disabled = !this.checked;
                        document.querySelector(`.preco_unitario[data-id="${id}"]`).disabled = !this.checked;
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
            const quantidade = document.querySelector(`.quantidade[data-id="${id}"]`).value;
            const preco_unitario = document.querySelector(`.preco_unitario[data-id="${id}"]`).value;
            if (quantidade && preco_unitario) {
                produtosSelecionados.push({
                    idproduto: id,
                    quantidade: quantidade,
                    preco_unitario: preco_unitario
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
});