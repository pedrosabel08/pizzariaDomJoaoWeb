<?php
header('Content-Type: application/json');
include '../conexao.php';

// Filtro de tipo
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'ingredientes';

// Arrays auxiliares (opcionalmente incluídos no retorno, se necessário)
$unidades = [];
$marcas = [];
$categorias = [];
$tamanhos = [];
$tipos = [];
$data = [];

// Carregar unidades de medida
$sql_unidades = "SELECT idunidadeMedida, nome FROM unidademedida";
$result = $conn->query($sql_unidades);
while ($row = $result->fetch_assoc()) {
    $unidades[] = $row;
}

// Carregar marcas de bebidas
$sql_marcas = "SELECT idmarcaBebidas, nome FROM marcabebidas";
$result = $conn->query($sql_marcas);
while ($row = $result->fetch_assoc()) {
    $marcas[] = $row;
}

// Carregar categorias de bebidas
$sql_categorias = "SELECT idcategoriaBebidas, nome FROM categoriabebidas";
$result = $conn->query($sql_categorias);
while ($row = $result->fetch_assoc()) {
    $categorias[] = $row;
}

// Carregar tamanhos de bebidas
$sql_tamanhos = "SELECT idtamanhoBebidas, nome, volume FROM tamanhobebidas";
$result = $conn->query($sql_tamanhos);
while ($row = $result->fetch_assoc()) {
    $tamanhos[] = $row;
}

// Carregar tipos de produto
$sql_tipos = "SELECT idtipo_produtos, nome_tipo FROM tipo_produtos";
$result = $conn->query($sql_tipos);
while ($row = $result->fetch_assoc()) {
    $tipos[] = $row;
}

// Dados principais conforme tipo selecionado
if ($tipo == 'ingredientes') {
    $sql = "SELECT 
        p.idprodutos AS id,
        p.nomeProduto AS nome,
        um.nome AS unidadeMedida,
        tp.nome_tipo AS tipo_nome,
        p.tipo_id,
        l.idlote,
        l.quantidade,
        l.data_validade
    FROM produtos p
    INNER JOIN unidademedida um ON p.unidadeMedida = um.idunidadeMedida
    INNER JOIN tipo_produtos tp ON p.tipo_id = tp.idtipo_produtos
    LEFT JOIN estoque_lote l ON p.idprodutos = l.idproduto
    ORDER BY 
        p.nomeProduto ASC,
        l.data_validade ASC;";
} else if ($tipo == 'bebidas') {
    $sql = "SELECT bebidas.idbebidas as id,
                   marcabebidas.nome as marca,
                   categoriabebidas.nome as categoria,
                   tamanhobebidas.nome as tamanho,
                   tamanhobebidas.volume as volume,
                   bebidas.quantidade,
                   bebidas.validade,
                   bebidas.preco,
                   bebidas.marca_id as marca_id,
                   bebidas.categoriabebidas_idcategoriaBebidas as categoria_id,
                   bebidas.tamanhobebidas_idtamanhoBebidas as tamanho_id
            FROM bebidas
            INNER JOIN marcabebidas ON bebidas.marca_id = marcabebidas.idmarcaBebidas
            INNER JOIN categoriabebidas ON bebidas.categoriabebidas_idcategoriaBebidas = categoriabebidas.idcategoriaBebidas
            INNER JOIN tamanhobebidas ON bebidas.tamanhobebidas_idtamanhoBebidas = tamanhobebidas.idtamanhoBebidas
            ORDER BY (bebidas.quantidade <= 7) DESC, marcabebidas.nome, categoriabebidas.nome, tamanhobebidas.nome ASC";
} else {
    http_response_code(400);
    echo json_encode([
        'erro' => 'Tipo inválido informado.',
        'tipo_recebido' => $tipo
    ]);
    exit;
}

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
$conn->close();

// Retorna os dados como JSON
echo json_encode([
    'tipo' => $tipo,
    'dados' => $data,
    'unidades' => $unidades,
    'marcas' => $marcas,
    'categorias' => $categorias,
    'tamanhos' => $tamanhos,
    'tipos' => $tipos
]);
