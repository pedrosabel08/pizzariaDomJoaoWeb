document.addEventListener("DOMContentLoaded", function () {
    function atualizarTabela() {
        fetch('pedidos.php')
            .then(response => response.json())
            .then(data => {
                const tabela = document.getElementById('order-list');
                tabela.innerHTML = '';

                data.forEach(produto => {
                    const tr = document.createElement('tr');
                    tr.classList.add('linha-tabela');
                    tr.setAttribute('data-id', produto.vendas_idvendas);

                    tr.innerHTML = `
                        <td>${produto.vendas_idvendas}</td>
                        <td>${produto.pizzas || 'Nenhuma Pizza'}</td>
                        <td>${produto.tamanho || 'Sem Tamanho'}</td>
                        <td>${produto.borda || 'Sem Borda'}</td>
                        <td>${produto.bebidas || 'Nenhuma Bebida'}</td>
                        <td>${produto.nome}</td>
                        <td>${produto.data_venda}</td>
                        <td>${produto.nome_status}</td>
                        <td>${produto.total}</td>
                    `;

                    tabela.appendChild(tr);
                });

                const linhasTabela = document.querySelectorAll('.linha-tabela');
                linhasTabela.forEach(linha => {
                    linha.addEventListener('click', function () {
                        modal.style.display = "flex";
                        var idPedidoSelecionado = this.getAttribute('data-id');

                        window.addEventListener('click', function (event) {
                            if (event.target === modal) {
                                modal.style.display = "none";
                            }
                        });
                        
                        const spanClose = document.querySelector(".close");


                        spanClose.addEventListener('click', function () {
                            modal.style.display = "none";
                        });

                        
                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: "http://localhost:8066/pizzariaDomJoaoWeb/gerente/buscaAJAX.php",
                            data: { ajid: idPedidoSelecionado },
                            success: function (response) {
                                if (response.length > 0) {
                                    var clienteTelefone = response[0].telefone;
                                    document.getElementById('idvenda').innerText = response[0].vendas_idvendas;
                                    document.getElementById('sabores').value = response[0].pizzas;
                                    document.getElementById('bebidas').value = response[0].bebidas; // Adicione esta linha
                                    document.getElementById('tamanho').value = response[0].tamanho;
                                    document.getElementById('borda').value = response[0].borda;
                                    document.getElementById('nome_cliente').value = response[0].nome_cliente;
                                    document.getElementById('telefone').value = response[0].telefone;
                                    document.getElementById('data_venda').value = response[0].data_venda;
                                    document.getElementById('total').value = response[0].total;
                                    document.getElementById('tempo_espera').value = response[0].tempo_espera;
                                    document.getElementById('status').value = response[0].nome_status;
                            
                                    clienteTelefone = clienteTelefone.replace(/\D/g, '');
                            
                                    var whatsappLink = document.getElementById('whatsappLink');
                                    whatsappLink.href = `https://wa.me/${clienteTelefone}`;
                                } else {
                                    console.log("Nenhuma venda encontrada.");
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.error("Erro na requisição AJAX: " + textStatus, errorThrown);
                            }
                        });
                    });
                });
            })
            .catch(error => console.error('Erro ao atualizar a tabela:', error));
    }

    atualizarTabela();
});

var ctx = document.getElementById('statusChart').getContext('2d');
var statusChart = new Chart(ctx, {
    type: 'pie', // Pode ser 'bar', 'pie', 'doughnut', etc.
    data: {
        labels: [], // Será preenchido dinamicamente
        datasets: [{
            label: 'Status dos Pedidos',
            data: [], // Será preenchido dinamicamente
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Função para buscar os dados via AJAX e atualizar o gráfico
function updateChart() {
    fetch('graficoAJAX.php') // Substitua pelo caminho correto do PHP
        .then(response => response.json())
        .then(data => {
            var labels = data.map(item => item.nome_status);
            var values = data.map(item => item.total);

            statusChart.data.labels = labels;
            statusChart.data.datasets[0].data = values;
            statusChart.update();
        });
}

// Atualiza o gráfico a cada 5 segundos (5000 milissegundos)
setInterval(updateChart, 5000);