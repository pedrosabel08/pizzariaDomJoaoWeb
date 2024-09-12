document.addEventListener("DOMContentLoaded", function () {
    function atualizarTabela() {
        fetch('pedidos.php') // Caminho para o seu script PHP
            .then(response => response.json())
            .then(data => {
                const tabela = document.getElementById('order-list');
                tabela.innerHTML = ''; // Limpa a tabela atual

                data.forEach(produto => {
                    // Cria uma nova linha
                    const tr = document.createElement('tr');
                    tr.classList.add('linha-tabela');
                    tr.setAttribute('data-id', produto.vendas_idvendas);

                    // Adiciona as células à linha
                    tr.innerHTML = `
                        <td>${produto.vendas_idvendas}</td>
                        <td>
                            ${produto.pizzas.split(',').map((pizza, index) =>
                        `Pizza: ${pizza} - Tamanho: ${produto.tamanhos.split(',')[index]} - Borda: ${produto.bordas.split(',')[index]}`
                    ).join('<br>')}
                        </td>
                        <td>${produto.nome}</td>
                        <td>${produto.data_venda}</td>
                        <td>${produto.nome_status}</td>
                        <td>${produto.total}</td>
                    `;

                    // Adiciona a linha à tabela
                    tabela.appendChild(tr);
                });
            })
            .catch(error => console.error('Erro ao atualizar a tabela:', error));
    }

    // Atualiza a tabela a cada 5 segundos
    setInterval(atualizarTabela, 5000);
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