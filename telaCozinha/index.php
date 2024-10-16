https://mm.tt/app/map/3426621587?t=0R41jf8xMJ


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status da Cozinha - Pizzaria</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold text-center mb-4">Pizzaria - Tela de Status de Pedidos</h1>

    <div class="bg-white p-4 rounded shadow mb-4">
        <h2 class="text-xl mb-2">Atualização de Status</h2>
        <form action="" method="POST">
            <div class="mb-4">
                <label for="pedidoId" class="block text-gray-700">Selecionar Pedido (ID):</label>
                <input type="text" id="pedidoId" name="pedidoId" placeholder="ID do Pedido" class="mt-1 block w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="mb-4">
                <label for="status" class="block text-gray-700">Atualizar para:</label>
                <select id="status" name="status" class="mt-1 block w-full p-2 border border-gray-300 rounded">
                    <option value="andamento">Andamento</option>
                    <option value="concluida">Concluída</option>
                    <option value="cancelada">Cancelada</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Atualizar Status</button>
        </form>
    </div>

    <h2 class="text-xl mb-2">Status dos Pedidos</h2>
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">ID do Pedido</th>
                <th class="py-2 px-4 border-b">Status</th>
                <th class="py-2 px-4 border-b">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Conexão com o banco de dados
            $mysqli = new mysqli("localhost", "root", "", "bd_pizzaria");

            if ($mysqli->connect_error) {
                die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
            }

            // Define o charset para UTF-8
            $mysqli->set_charset("utf8mb4");

            // Atualiza o status do pedido
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $pedidoId = $_POST['pedidoId'];
                $status = $_POST['status'];

                // Atualiza o status no banco de dados
                $stmt = $mysqli->prepare("UPDATE vendas SET status_id = (SELECT idstatus FROM status_venda WHERE nome_status = ?) WHERE idvendas = ?");
                $stmt->bind_param('si', $status, $pedidoId);
                $stmt->execute();
                $stmt->close();
            }

            // Consulta os pedidos do banco de dados
            $sql = '
            SELECT 
                v.idvendas AS pedido_id,
                s.nome_status AS status
            FROM vendas v
            INNER JOIN status_venda s ON v.status_id = s.idstatus;
            ';
            $result = $mysqli->query($sql);

            // Exibe os pedidos e seus status
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td class='py-2 px-4 border-b'>{$row['pedido_id']}</td>
                            <td class='py-2 px-4 border-b' id='status-{$row['pedido_id']}'>{$row['status']}</td>
                            <td class='py-2 px-4 border-b'>
                                <form action='' method='POST' class='inline'>
                                    <input type='hidden' name='pedidoId' value='{$row['pedido_id']}'>
                                    <input type='hidden' name='status' value='concluida'>
                                    <button type='submit' class='bg-green-500 text-white px-2 py-1 rounded'>Concluir</button>
                                </form>
                                <form action='' method='POST' class='inline'>
                                    <input type='hidden' name='pedidoId' value='{$row['pedido_id']}'>
                                    <input type='hidden' name='status' value='cancelada'>
                                    <button type='submit' class='bg-red-500 text-white px-2 py-1 rounded'>Cancelar</button>
                                </form>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='3' class='text-center py-4'>Nenhum pedido encontrado</td></tr>";
            }

            // Fecha a conexão com o banco
            $mysqli->close();
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
