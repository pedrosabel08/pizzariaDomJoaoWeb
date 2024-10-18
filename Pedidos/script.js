document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("modalPedido");
    const spanClose = document.querySelector(".close");

    function atualizarPedidos() {
        fetch('buscar_pedidos.php', {
            method: 'POST'
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta do servidor.');
                }
                return response.json();
            })
            .then(data => {
                const containerPedidos = document.querySelector('main');
                containerPedidos.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(pedido => {
                        const divPedido = document.createElement('div');
                        divPedido.classList.add('pedido');

                        let statusClass = '';
                        if (pedido.nome_status === 'Não começou') {
                            statusClass = 'status-vermelho';
                        } else if (pedido.nome_status === 'Em andamento') {
                            statusClass = 'status-laranja';
                        } else if (pedido.nome_status === 'Finalizado') {
                            statusClass = 'status-verde';
                        }

                        let pizzaImage = '';
                        switch (pedido.nome) {
                            case 'Baby':
                                pizzaImage = '../assets/pizzaBaby.jpg';
                                break;
                            case 'Média':
                                pizzaImage = '../assets/pizzaMedia.jpg';
                                break;
                            case 'Grande':
                                pizzaImage = '../assets/pizzaGrande.jpg';
                                break;
                            case 'Gigante':
                                pizzaImage = '../assets/pizzaGigante.jpg';
                                break;
                        }

                        divPedido.innerHTML = `
                            <div class="imagem">
                                <img src="${pizzaImage}" alt="Pizza ${pedido.nome}">
                            </div>
                            <div class="desc">
                                <div>
                                    <span>ID Pedido: ${pedido.vendas_idvendas}</span>
                                </div>
                                <div>
                                    <input type="text" name="data" id="data" value="${pedido.data_venda}" readonly>
                                </div>
                                <div>
                                    <input type="text" name="total" id="total" value="R$ ${pedido.total}" readonly>
                                </div>
                                <div>
                                    <input type="text" name="tipo" id="tipo" value="${pedido.tipo}" readonly>
                                </div>
                                <div>
                                    <input type="text" name="nome_status" id="nome_status" value="${pedido.nome_status}" readonly class="${statusClass}">
                                </div>
                            </div>
                        `;

                        divPedido.addEventListener('click', function () {
                            console.log('ID do Pedido:', pedido.vendas_idvendas);
                            fetch('pedido_detalhado.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({ vendas_idvendas: pedido.vendas_idvendas })
                            })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Erro ao buscar detalhes do pedido.');
                                    }
                                    return response.json();
                                })
                                .then(detalhes => {

                                    console.log(detalhes)
                                    document.getElementById('modal-idPedido').textContent = detalhes.vendas_idvendas;
                                    document.getElementById('modal-dataPedido').textContent = detalhes.data_venda;
                                    document.getElementById('modal-tipoPedido').textContent = detalhes.tipo;
                                    document.getElementById('modal-statusPedido').textContent = detalhes.nome_status;

                                    const statusElement = document.getElementById('modal-statusPedido');
                                    statusElement.className = ''; 

                                    switch (detalhes.nome_status) {
                                        case 'Não começou':
                                            statusElement.classList.add('status-vermelho');
                                            break;
                                        case 'Em Andamento':
                                            statusElement.classList.add('status-laranja');
                                            break;
                                        case 'Finalizado':
                                            statusElement.classList.add('status-verde');
                                            break;
                                        case 'Cancelado':
                                            statusElement.classList.add('status-cancelado');
                                            break;

                                    }

                                    document.getElementById('modal-totalPedido').textContent = `R$ ${detalhes.total}`;
                                    const totalPizza = detalhes.total - detalhes.valor_entrega;
                                    document.getElementById('modal-totalPizza').textContent = `R$ ${totalPizza.toFixed(2)}`;

                                    document.getElementById('modal-valor_entrega').textContent = `R$ ${detalhes.valor_entrega.toFixed(2)}`;
                                    document.getElementById('modal-sabores').textContent = detalhes.sabores.split(', ').join(', ');
                                    document.getElementById('modal-tamanho').textContent = detalhes.tamanho;
                                    document.getElementById('modal-borda').textContent = detalhes.borda;

                                    document.getElementById('complemento').textContent = detalhes.complemento || '';
                                    document.getElementById('bairro').textContent = detalhes.bairro;
                                    document.getElementById('numero').textContent = detalhes.numero;
                                    document.getElementById('rua').textContent = detalhes.rua;
                                    document.getElementById('cidade').textContent = detalhes.cidade;

                                    document.getElementById('modal-tempoEspera').textContent = `${detalhes.tempo_espera} minutos`;
                                    document.getElementById('modal-pagamento').textContent = detalhes.forma_pagamento;

                                    const logContainer = document.getElementById('modal-logStatus');
                                    logContainer.innerHTML = ''; 
                                    detalhes.log_status.forEach(log => {
                                        const logItem = document.createElement('p');
                                        logItem.textContent = `${log.data_alteracao}: ${log.status_anterior} → ${log.status_novo}`;
                                        logContainer.appendChild(logItem);
                                    });

                                    modal.style.display = "block";
                                })
                                .catch(error => console.error('Erro ao carregar os detalhes:', error));
                        });


                        containerPedidos.appendChild(divPedido);
                    });
                } else {
                    const mensagemVazia = document.createElement('p');
                    mensagemVazia.textContent = 'Nenhum pedido encontrado.';
                    containerPedidos.appendChild(mensagemVazia);
                }
            })
            .catch(error => console.error('Erro ao carregar os dados:', error));
    }

    spanClose.addEventListener('click', function () {
        modal.style.display = "none";
    });

    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    atualizarPedidos();
});
