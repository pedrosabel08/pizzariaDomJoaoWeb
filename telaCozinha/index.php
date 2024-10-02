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
            // Simulação de pedidos (pode ser substituído por uma consulta ao banco de dados)
            $pedidos = [
                ['id' => '001', 'status' => 'Andamento'],
                ['id' => '002', 'status' => 'Concluída'],
                ['id' => '003', 'status' => 'Cancelada'],
            ];

            // Atualiza o status do pedido
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $pedidoId = $_POST['pedidoId'];
                $status = $_POST['status'];

                foreach ($pedidos as &$pedido) {
                    if ($pedido['id'] === $pedidoId) {
                        $pedido['status'] = ucfirst($status);
                    }
                }
            }

            foreach ($pedidos as $pedido) {
                echo "<tr>
                        <td class='py-2 px-4 border-b'>{$pedido['id']}</td>
                        <td class='py-2 px-4 border-b' id='status-{$pedido['id']}'>{$pedido['status']}</td>
                        <td class='py-2 px-4 border-b'>
                            <form action='' method='POST' class='inline'>
                                <input type='hidden' name='pedidoId' value='{$pedido['id']}'>
                                <input type='hidden' name='status' value='concluida'>
                                <button type='submit' class='bg-green-500 text-white px-2 py-1 rounded'>Concluir</button>
                            </form>
                            <form action='' method='POST' class='inline'>
                                <input type='hidden' name='pedidoId' value='{$pedido['id']}'>
                                <input type='hidden' name='status' value='cancelada'>
                                <button type='submit' class='bg-red-500 text-white px-2 py-1 rounded'>Cancelar</button>
                            </form>
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
