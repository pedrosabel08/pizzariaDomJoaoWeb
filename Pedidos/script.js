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

                        let pizzaImage = '../assets/defaultImage.jpg'; // Imagem padrão

                        // Verifica se há o tamanho da pizza
                        if (pedido.tamanho) {
                            switch (pedido.tamanho) {
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
                                default:
                                    pizzaImage = '../assets/defaultPizza.jpg'; // Imagem padrão caso o tamanho não seja identificado
                                    break;
                            }
                        }
                        
                        // Verifica se há bebidas e usa a primeira apenas se não houver pizza
                        if (pedido.id_bebidas && !pedido.id_pizzas) { // Verifica se há bebidas e se não há pizza
                            const bebidaIds = pedido.id_bebidas.split(", "); // Separar os IDs das bebidas
                            const primeiraBebidaId = parseInt(bebidaIds[0]); // Pega o primeiro ID
                        
                            // Mapeia o ID da bebida para a imagem
                            switch (primeiraBebidaId) {
                                case 1:
                                    pizzaImage = '../assets/Refrigerante.jpg';
                                    break;
                                case 2:
                                    pizzaImage = '../assets/sprite.jpg';
                                    break;
                                case 3:
                                    pizzaImage = '../assets/guarana.jpg';
                                    break;
                                case 5:
                                    pizzaImage = '../assets/Refrigerante.jpg';
                                    break;
                                default:
                                    pizzaImage = '../assets/defaultDrink.jpg'; // Imagem padrão caso a bebida não seja identificada
                                    break;
                            }
                        }

                        // Se não houver pizza nem bebida, usa a imagem padrão
                        if (!pizzaImage) {
                            pizzaImage = '../assets/defaultImage.jpg'; // Imagem padrão
                        }

                        divPedido.innerHTML = `
                            <div class="imagem">
                                <img src="${pizzaImage}" alt="Imagem do Pedido">
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
                                    <input type="text" name="tipo" id="tipo" value="${pedido.forma_entrega}" readonly>
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
                                    console.log(detalhes);
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
                                        case 'Em andamento':
                                            statusElement.classList.add('status-laranja');
                                            break;
                                        case 'Finalizado':
                                            statusElement.classList.add('status-verde');
                                            break;
                                        case 'Cancelado':
                                            statusElement.classList.add('status-cancelado');
                                            break;
                                    }

                                    // Atualiza os valores no modal
                                    document.getElementById('modal-totalPedido').textContent = `R$ ${detalhes.total}`;
                                    const totalPizza = detalhes.total - detalhes.valor_entrega;
                                    document.getElementById('modal-totalPizza').textContent = `R$ ${totalPizza.toFixed(2)}`;
                                    document.getElementById('modal-valor_entrega').textContent = `R$ ${detalhes.valor_entrega.toFixed(2)}`;

                                    // Verifica se existem sabores antes de usar o split
                                    if (detalhes.sabores && detalhes.sabores.trim() !== '') {
                                        document.getElementById('modal-sabores').textContent = detalhes.sabores.split(', ').join(', ');
                                    } else {
                                        document.getElementById('modal-sabores').textContent = 'Nenhum sabor selecionado'; // Mensagem padrão ou ocultar
                                    }

                                    // Atualiza tamanho e borda
                                    document.getElementById('modal-tamanho').textContent = detalhes.tamanho || 'N/A';
                                    document.getElementById('modal-borda').textContent = detalhes.borda || 'N/A';

                                    // Atualizando as informações das bebidas
                                    document.getElementById('modal-bebidas').textContent = detalhes.bebidas || '-';
                                    document.getElementById('modal-quantidadeBebidas').textContent = detalhes.quantidade_bebidas || 0;

                                    // Atualiza informações do endereço
                                    document.getElementById('complemento').textContent = detalhes.complemento || '';
                                    document.getElementById('bairro').textContent = detalhes.bairro || '';
                                    document.getElementById('numero').textContent = detalhes.numero || '';
                                    document.getElementById('rua').textContent = detalhes.rua || '';
                                    document.getElementById('cidade').textContent = detalhes.cidade || '';

                                    // Verifica se os campos do endereço estão vazios
                                    const enderecoDiv = document.querySelector('.address');
                                    if (!detalhes.rua && !detalhes.numero && !detalhes.bairro && !detalhes.cidade && !detalhes.complemento) {
                                        enderecoDiv.style.display = 'none';
                                    } else {
                                        enderecoDiv.style.display = 'block';
                                    }

                                    document.getElementById('modal-tempoEspera').textContent = `${detalhes.tempo_espera} minutos`;
                                    document.getElementById('modal-pagamento').textContent = detalhes.forma_pagamento || 'N/A';

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
