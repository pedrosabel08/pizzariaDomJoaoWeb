<?php
// Conexão com o banco de dados
include('../conexao.php');

// Captura os dados enviados via POST
$dados = json_decode(file_get_contents("php://input"), true);

// Verifica se o ID do pedido foi passado
if (isset($dados['vendas_idvendas'])) {
    $pedidoId = $dados['vendas_idvendas'];

    // Query para buscar os detalhes do pedido
    $sql = "SELECT 
    vp.vendas_idvendas, 
    t.nome AS tamanho, 
    b.nome AS borda, 
    GROUP_CONCAT(DISTINCT p.nomePizza SEPARATOR ', ') AS sabores, 
    v.total, 
    v.data_venda, 
    f.tipo AS forma_entrega, 
    s.nome_status, 
    e.numero, 
    MAX(e.complemento) AS complemento, -- Pega o valor máximo para resolver a duplicação
    e.rua, 
    e.bairro, 
    e.cidade,
    v.tempo_espera,
    v.valor_entrega,
    fp.tipo AS forma_pagamento
FROM vendas_pizzas vp
JOIN pizzas p ON vp.pizzas_idpizzas = p.idpizzas
JOIN tamanho t ON vp.tamanho_idtamanho = t.idtamanho
JOIN bordas_pizza b ON vp.borda_idbordas_pizza = b.idbordas_pizza
JOIN vendas v ON v.idvendas = vp.vendas_idvendas
JOIN forma_pagamento fp ON v.forma_pagamento_id = fp.idforma_pagamento
JOIN status_venda s ON v.status_id = s.idstatus
JOIN forma_entrega f ON v.forma_entrega_id = f.idforma_entrega
JOIN clientes c ON v.cliente_id = c.idclientes
JOIN endereco e ON c.idclientes = e.cliente_id
WHERE vp.vendas_idvendas = ?
GROUP BY 
    vp.vendas_idvendas, 
    t.nome, 
    b.nome, 
    v.total, 
    v.data_venda, 
    f.tipo, 
    s.nome_status, 
    e.numero, 
    e.rua, 
    e.bairro, 
    e.cidade;";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $pedidoId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Dados principais do pedido, incluindo os novos campos
            $pedidoDetalhado = [
                'vendas_idvendas' => $row['vendas_idvendas'],
                'total' => $row['total'],
                'data_venda' => $row['data_venda'],
                'tipo' => $row['forma_entrega'], // Atualizado para a forma de entrega
                'nome_status' => $row['nome_status'],
                'sabores' => $row['sabores'],
                'tamanho' => $row['tamanho'],
                'borda' => $row['borda'],
                'numero' => $row['numero'],
                'complemento' => $row['complemento'],
                'rua' => $row['rua'],
                'bairro' => $row['bairro'],
                'cidade' => $row['cidade'],
                'tempo_espera' => $row['tempo_espera'],
                'forma_pagamento' => $row['forma_pagamento'],
                'valor_entrega' => $row['valor_entrega'],

            ];

            // Query para buscar o log de alterações de status
            $sqlLog = "SELECT 
            sa.nome_status AS status_anterior,
            sn.nome_status AS status_novo,
            ls.data_alteracao
            FROM 
            log_status ls
            JOIN 
            status_venda sa ON ls.status_anterior = sa.idstatus
            JOIN 
            status_venda sn ON ls.status_novo = sn.idstatus
            JOIN 
            vendas v ON ls.venda_id = v.idvendas
            WHERE 
            ls.venda_id = ?
            ORDER BY 
            ls.data_alteracao ASC;";

            if ($stmtLog = mysqli_prepare($conn, $sqlLog)) {
                mysqli_stmt_bind_param($stmtLog, "i", $pedidoId);
                mysqli_stmt_execute($stmtLog);
                $resultLog = mysqli_stmt_get_result($stmtLog);

                // Inicializa o array de logs
                $logs = [];

                // Percorre o resultado da consulta de logs
                while ($rowLog = mysqli_fetch_assoc($resultLog)) {
                    $logs[] = [
                        'status_anterior' => $rowLog['status_anterior'],
                        'status_novo' => $rowLog['status_novo'],
                        'data_alteracao' => $rowLog['data_alteracao']
                    ];
                }

                // Adiciona o log de status ao resultado final
                $pedidoDetalhado['log_status'] = $logs;

                mysqli_stmt_close($stmtLog);
            }

            // Retorna o pedido com os detalhes e o log de status
            echo json_encode($pedidoDetalhado);
        } else {
            echo json_encode(['success' => false, 'message' => 'Pedido não encontrado.']);
        }

        mysqli_stmt_close($stmt);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID do pedido não foi fornecido.']);
}