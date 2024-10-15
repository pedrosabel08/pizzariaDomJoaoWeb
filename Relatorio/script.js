
function fetchSalesData(periodo) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `get_sales_data.php?periodo=${periodo}`, true);
    xhr.onload = function () {
        if (this.status === 200) {
            const data = JSON.parse(this.responseText);
            updateChart(data.labels, data.sales);
        }
    };
    xhr.send();
}

const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [],
        datasets: [{
            label: 'Total de Vendas (R$)',
            data: [],
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

function updateChart(labels, sales) {
    salesChart.data.labels = labels;
    salesChart.data.datasets[0].data = sales;
    salesChart.update();
}

document.getElementById('periodo').addEventListener('change', function () {
    const periodo = this.value;
    fetchSalesData(periodo);
});

fetchSalesData('ano');


function fetchPizzasData(periodo) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `get_pizzas_data.php?periodo=${periodo}`, true);
    xhr.onload = function () {
        if (this.status === 200) {
            const pizzas = JSON.parse(this.responseText);
            populateTable(pizzas);
        }
    };
    xhr.send();
}

function populateTable(pizzas) {
    const tbody = document.querySelector('#pizzasTable tbody');
    tbody.innerHTML = ''; 

    pizzas.forEach(pizza => {
        const row = document.createElement('tr');
        const pizzaNameCell = document.createElement('td');
        const pizzaQtyCell = document.createElement('td');

        pizzaNameCell.textContent = pizza.nome_pizza;
        pizzaQtyCell.textContent = pizza.qtd_vendida;

        row.appendChild(pizzaNameCell);
        row.appendChild(pizzaQtyCell);
        tbody.appendChild(row);
    });
}

document.getElementById('periodo').addEventListener('change', function () {
    const periodo = this.value;
    fetchPizzasData(periodo); 
});

fetchPizzasData('ano');


document.getElementById('generatePDF').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const now = new Date();
    const formattedDate = now.toLocaleDateString();
    const formattedTime = now.toLocaleTimeString();

    const title = 'Relatório de Pizzas Vendidas';
    const subtitle = `Gerado em: ${formattedDate} às ${formattedTime}`;

    doc.setFontSize(18);
    doc.text(title, 14, 20);
    doc.setFontSize(12);
    doc.text(subtitle, 14, 30); 

    const table = document.getElementById('pizzasTable');
    const rows = [];
    const headers = [];

    table.querySelectorAll('thead tr th').forEach(header => {
        headers.push(header.innerText);
    });

    table.querySelectorAll('tbody tr').forEach(row => {
        const rowData = [];
        row.querySelectorAll('td').forEach(cell => {
            rowData.push(cell.innerText);
        });
        rows.push(rowData);
    });

    doc.autoTable({
        head: [headers],
        body: rows,
        startY: 40,
    });

    doc.save('relatorio_pizzas_vendidas.pdf');
});