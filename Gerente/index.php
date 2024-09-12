<?php
include('conexao.php');
$sql = "SELECT 
v.idvendas AS vendas_idvendas,
GROUP_CONCAT(p.nomePizza) AS pizzas,  -- nome da pizza ao invés do ID
GROUP_CONCAT(t.nome) AS tamanhos,  -- nome do tamanho ao invés do ID
GROUP_CONCAT(b.nome) AS bordas,  -- nome da borda ao invés do ID
c.nome,
v.data_venda,
v.total,
s.nome_status
FROM vendas_pizzas vp
INNER JOIN vendas v ON v.idvendas = vp.vendas_idvendas
INNER JOIN clientes c ON v.cliente_id = c.idclientes
INNER JOIN status_venda s ON v.status_id = s.idstatus
INNER JOIN pizzas p ON vp.pizzas_idpizzas = p.idpizzas  -- Join para obter o nome da pizza
INNER JOIN tamanho t ON vp.tamanho_idtamanho = t.idtamanho  -- Join para obter o nome do tamanho
INNER JOIN bordas_pizza b ON vp.borda_idbordas_pizza = b.idbordas_pizza  -- Join para obter o nome da borda
GROUP BY v.idvendas;";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Pedidos - Gerente</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>Painel de Pedidos</h1>
        <input type="text" id="search" placeholder="Buscar por cliente, ID ou status">
    </header>

    <main>
        <div class="filters">
            <label for="filter-status">Filtrar por status:</label>
            <select id="filter-status">
                <option value="todos">Todos</option>
                <option value="pendente">Pendente</option>
                <option value="preparando">Preparando</option>
                <option value="pronto">Pronto</option>
                <option value="entregue">Entregue</option>
            </select>
        </div>

        <table class="order-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pedido</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Valor Total</th>
                </tr>
            </thead>
            <tbody id="order-list">
                <?php foreach ($data as $produto): ?>
                    <tr class="linha-tabela" data-id="<?php echo $produto['vendas_idvendas']; ?>">
                        <td><?php echo $produto['vendas_idvendas']; ?></td>
                        <td>
                            <?php
                            $pizzas = explode(',', $produto['pizzas']);
                            $tamanhos = explode(',', $produto['tamanhos']);
                            $bordas = explode(',', $produto['bordas']);
                            // Percorrendo e concatenando os dados de pizza, tamanho e borda
                            for ($i = 0; $i < count($pizzas); $i++) {
                                echo "Pizza: " . $pizzas[$i] . " - Tamanho: " . $tamanhos[$i] . " - Borda: " . $bordas[$i] . "<br>";
                            }
                            ?>
                        </td>
                        <td><?php echo $produto['nome']; ?></td>
                        <td><?php echo $produto['data_venda']; ?></td>
                        <td><?php echo $produto['nome_status']; ?></td>
                        <td><?php echo $produto['total']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <!-- Modal para os Detalhes do Pedido -->
    <div id="order-details-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Detalhes do Pedido</h2>
            <div class="order-info">
                <p><strong>ID Pedido:</strong> <span id="modal-order-id"></span></p>
                <p><strong>Cliente:</strong> <span id="modal-customer-name"></span></p>
                <p><strong>Data:</strong> <span id="modal-order-date"></span></p>
                <p><strong>Status:</strong> <span id="modal-order-status"></span></p>
                <p><strong>Valor Total:</strong> <span id="modal-order-total"></span></p>
            </div>

            <div class="order-items">
                <h3>Itens do Pedido</h3>
                <ul id="modal-order-items">
                    <!-- Itens do pedido serão carregados dinamicamente -->
                </ul>
            </div>

            <div class="status-update">
                <h3>Atualizar Status</h3>
                <select id="update-status">
                    <option value="pendente">Pendente</option>
                    <option value="preparando">Preparando</option>
                    <option value="pronto">Pronto</option>
                    <option value="entregue">Entregue</option>
                </select>
                <button id="save-status-btn">Salvar</button>
            </div>

            <div class="whatsapp-contact">
                <button id="whatsapp-btn">Conversar pelo WhatsApp</button>
            </div>
        </div>
    </div>
    <div class="grafico">
        <canvas id="statusChart" width="400" height="200"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="script.js"></script>

</body>

</html>