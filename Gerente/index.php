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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>Painel de Pedidos</h1>
        <p id="data-atual"></p>
    </header>

    <main>

        <table class="order-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sabores</th>
                    <th>Tamanho</th>
                    <th>Borda</th>
                    <th>Bebidas</th>
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

        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div id="form-inserir">
                    <h2>Formulário de Dados</h2>
                    <form id="formPedido">
                        <div>
                            <div id="idpedido">
                                <p>Pedido</p>
                                <span id="idvenda"></span>
                                <p style="background-color: green; padding: 0 10px; color: white">PAGO</p>
                            </div>
                            <div id="pedidos">
                                <input type="text" id="sabores" disabled>
                                <input type="text" id="tamanho" disabled>
                                <input type="text" id="borda" disabled>
                                <input type="text" id="bebidas" disabled>
                            </div>
                            <div>
                                <label>Informações do cliente:</label>
                                <input type="text" id="nome_cliente" disabled>
                                <input type="text" id="telefone" disabled>
                                <input type="text" id="data_venda" disabled>
                            </div>

                            <div>
                                <label for="">Status:</label>
                                <input type="text" id="status" disabled>
                            </div>

                            <div>
                                <label for="">Valor total:</label>
                                <input type="text" id="total" disabled>
                            </div>

                            <div>
                                <label for="">Tempo de espera:</label>
                                <input type="text" id="tempo_espera" disabled>
                            </div>



                            <a id="whatsappLink" href="https://wa.me/" target="_blank">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <div class="grafico">
        <canvas id="statusChart" width="600" height="350"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="script.js"></script>

</body>

</html>