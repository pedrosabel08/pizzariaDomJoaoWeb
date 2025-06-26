<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="/pizzariaDomJoaoWeb/assets/stock.png" type="image/x-icon">
    <link rel="stylesheet" href="../../styleSidebar.css">


    <title>Saída de Estoque</title>
</head>

<body>

    <?php

    include "../../sidebar.php";

    ?>
    <div class="container">
        <div class="table-wrapper">
            <!-- Filtros -->
            <div id="filtros">

                <div class="filtro_tipo">
                    <label for="dataInicio">Data Início:</label>
                    <input type="date" id="dataInicio">
                </div>

                <div class="filtro_tipo">
                    <label for="dataFim">Data Fim:</label>
                    <input type="date" id="dataFim">
                </div>

                <div class="filtro_tipo">
                    <label for="produtoFiltro">Produto:</label>
                    <select id="produtoFiltro">
                        <option value="">Todos</option>
                    </select>
                </div>
                <div class="filtro_tipo">
                    <label for="pizzaFiltro">Pizza:</label>
                    <select id="pizzaFiltro">
                        <option value="">Todas</option>
                    </select>
                </div>
            </div>

            <div class="tabela">

                <table id="tabelaSaida">
                    <thead>
                        <tr>
                            <th>Data Saída</th>
                            <th>Produto</th>
                            <th class="pizza">Pizza</th>
                            <th class="venda">Venda</th>
                            <th>Motivo</th>
                            <th>Lote</th>
                            <th>Quantidade</th>
                        </tr>
                    </thead>
                    <tbody id="bodySaida"></tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
    <script src="../../sidebar.js"></script>

</body>

</html>