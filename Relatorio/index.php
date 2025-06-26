<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Relatório de Vendas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../styleSidebar.css">


</head>

<body>

    <?php

    include "../sidebar.php";

    ?>

    <div class="container">
        <div class="chart-container">
            <h2>Total de Vendas por Período</h2>
            <select name="periodo" id="periodo">
                <option value="ano">Ano</option>
                <option value="semana">Semana</option>
                <option value="dia">Dia</option>
            </select>
            <canvas id="salesChart"></canvas>
        </div>

        <div class="table-container">
            <h2>Sabores Vendidos por Período</h2>
            <label for="startDate">Data de Início:</label>
            <input type="date" id="startDate" name="startDate">

            <label for="endDate">Data de Fim:</label>
            <input type="date" id="endDate" name="endDate">

            <button id="generatePDF">Gerar PDF</button>
            <table id="pizzasTable">
                <thead>
                    <tr>
                        <th>Nome do Sabor</th>
                        <th>Quantidade Vendida</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <script src="script.js"></script>
    <script src="../sidebar.js"></script>
</body>

</html>