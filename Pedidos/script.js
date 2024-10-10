document.addEventListener("DOMContentLoaded", function () {
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
                                    <input type="text" name="rua" id="rua" value="${pedido.rua}" readonly>
                                </div>
                                <div>
                                    <input type="text" name="nome_status" id="nome_status" value="${pedido.nome_status}" readonly class="${statusClass}">
                                </div>
                            </div>
                        `;

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

    atualizarPedidos();
});
