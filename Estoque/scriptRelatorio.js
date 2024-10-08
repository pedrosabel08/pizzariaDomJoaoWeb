document.getElementById('report-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const date = document.getElementById('date').value;

    fetch(`get_sales.php?date=${date}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('sales-table').querySelector('tbody');
            tbody.innerHTML = '';

            data.forEach(sale => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${sale.vendas_idvendas}</td>
                    <td>${sale['Nome Pizza']}</td>
                    <td>${sale.Tamanho}</td>
                    <td>${sale.Borda}</td>
                    <td>${sale.total}</td>
                    <td>${sale.Data}</td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching sales:', error));
});

document.getElementById('download-pdf').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.setFontSize(18);
    doc.text('Relatório de Vendas', 14, 22);

    const table = document.getElementById('sales-table');
    const rows = table.querySelectorAll('tr');

    let startY = 30;
    doc.setFontSize(12);

    rows.forEach((row, index) => {
        const cells = row.querySelectorAll('th, td');
        cells.forEach((cell, cellIndex) => {
            let cellText = cell.innerText || '';
            doc.rect(14 + (40 * cellIndex), startY + (index * 10), 40, 10);
            doc.text(cellText, 18 + (40 * cellIndex), startY + 8 + (index * 10));
        });''
    });

    doc.save('relatorio_vendas.pdf');
});