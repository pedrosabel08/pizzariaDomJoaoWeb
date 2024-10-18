document.addEventListener("DOMContentLoaded", function () {
    function atualizarTabela() {
        fetch('atualizar_tabela.php')
            .then(response => response.json())
            .then(data => {
                const tabela = document.getElementById('tablePedidos').querySelector('tbody');
                tabela.innerHTML = ''; // Limpa o conteúdo atual do corpo da tabela

                data.forEach(produto => {
                    const tr = document.createElement('tr');
                    tr.classList.add('linha-tabela');
                    tr.setAttribute('data-id', produto.vendas_idvendas);
                    tr.setAttribute('data-status', produto.status_id); // Armazena o status atual na linha

                    tr.innerHTML = `
                        <td>${produto.tamanho}</td>
                        <td>${produto.borda}</td>
                        <td>${produto.sabores}</td>
                        <td>${produto.data_venda}</td>
                        <td>${produto.nome_status}</td>
                        <td>${produto.tempo_espera} minutos</td>
                    `;

                    // Atualiza o estilo da linha com base no status
                    atualizarEstiloLinha(tr, produto.nome_status);

                    // Adiciona o evento de clique à linha
                    tr.addEventListener('click', () => {
                        const currentStatus = parseInt(tr.getAttribute('data-status')); // Obtém o status atual
                        let newStatus = currentStatus + 1; // Muda para o próximo status

                        // Verifica se o novo status é válido (por exemplo, se está entre 1 e 3)
                        if (newStatus > 3) {
                            alert('Status não pode ser atualizado além de "Concluída".');
                            return;
                        }

                        // Atualiza o status no servidor via AJAX
                        fetch('atualizar_status.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `id=${produto.vendas_idvendas}&status=${newStatus}`
                        })
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    // Atualiza a tabela após sucesso
                                    atualizarTabela();
                                } else {
                                    alert('Erro ao atualizar status: ' + result.message);
                                }
                            })
                            .catch(error => console.error('Erro ao atualizar status:', error));
                    });

                    tabela.appendChild(tr);
                });
            })
            .catch(error => console.error('Erro ao atualizar a tabela:', error));
    }

    // Função para atualizar o estilo da linha com base no status
    function atualizarEstiloLinha(linha, status) {
        linha.classList.remove('linha-pendente', 'linha-andamento', 'linha-concluida'); // Remove todas as classes
    
        switch (status) {
            case 'Não começou': // Não começou
                linha.classList.add('linha-pendente');
                break;
            case 'Em andamento': // Em andamento
                linha.classList.add('linha-andamento');
                break;
            case 'Finalizado': // Concluída
                linha.classList.add('linha-concluida');
                break;
        }
    }

    // Chama a função para atualizar a tabela
    atualizarTabela();

    // Caso queira atualizar a tabela periodicamente (ex: a cada 10 segundos)
    setInterval(atualizarTabela, 10000); // Atualiza a cada 10 segundos
});