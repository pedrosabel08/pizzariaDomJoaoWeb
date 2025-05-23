<?php
include '../conexao.php';

// Filtro de tipo
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'ingredientes';

// Busca unidades para ingredientes
$sql_unidades = "SELECT idunidadeMedida, nome FROM unidademedida";
$result_unidades = $conn->query($sql_unidades);
$unidades = array();
if ($result_unidades->num_rows > 0) {
    while ($row = $result_unidades->fetch_assoc()) {
        $unidades[] = $row;
    }
}

$sql_marcas = "SELECT idmarcaBebidas, nome FROM marcaBebidas";
$result_marcas = $conn->query($sql_marcas);
$marcas = [];
if ($result_marcas && $result_marcas->num_rows > 0) {
    while ($row = $result_marcas->fetch_assoc()) {
        $marcas[] = $row;
    }
}

$sql_categorias = "SELECT idcategoriaBebidas, nome FROM categoriaBebidas";
$result_categorias = $conn->query($sql_categorias);
$categorias = [];
if ($result_categorias && $result_categorias->num_rows > 0) {
    while ($row = $result_categorias->fetch_assoc()) {
        $categorias[] = $row;
    }
}

// Busca dados conforme tipo
$data = array();
if ($tipo == 'ingredientes') {
    $sql = "SELECT produtos.idprodutos as id, produtos.nomeProduto as nome,
                   produtos.quantidade, unidademedida.nome as unidadeMedida, produtos.validade 
            FROM produtos 
            INNER JOIN unidademedida ON produtos.unidadeMedida = unidademedida.idunidadeMedida 
            ORDER BY produtos.nomeProduto ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
} else if ($tipo == 'bebidas') {
    $sql = "SELECT bebidas.idbebidas as id, 
               marcabebidas.nome as marca, 
               categoriabebidas.nome as categoria, 
               bebidas.quantidade, 
               bebidas.validade, 
               bebidas.preco, 
               bebidas.marca_id, 
               bebidas.categoria_id
        FROM bebidas
        INNER JOIN marcabebidas ON bebidas.marca_id = marcabebidas.idmarcaBebidas
        INNER JOIN categoriabebidas ON bebidas.categoria_id = categoriabebidas.idcategoriaBebidas
        ORDER BY marcabebidas.nome, categoriabebidas.nome ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Estoque</title>
    <link rel="icon" href="./assets/caixa.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex align-items-center mb-3">
            <label for="tipoFiltro" class="me-2 fw-bold">Tipo:</label>
            <select id="tipoFiltro" class="form-select w-auto" onchange="trocarTipo()">
                <option value="ingredientes" <?= $tipo == 'ingredientes' ? 'selected' : '' ?>>Ingredientes</option>
                <option value="bebidas" <?= $tipo == 'bebidas' ? 'selected' : '' ?>>Bebidas</option>
            </select>
            <button class="btn btn-success ms-3" onclick="abrirModalNovo()">Novo <?= $tipo == 'ingredientes' ? 'Ingrediente' : 'Bebida' ?></button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="tabelaEstoque">
                <thead>
                <tr>
                    <?php if ($tipo == 'ingredientes'): ?>
                        <th>Nome</th>
                        <th>Quantidade</th>
                        <th>Unidade Medida</th>
                        <th>Validade</th>
                        <th>Ações</th>
                    <?php else: ?>
                        <th>Marca</th>
                        <th>Categoria</th>
                        <th>Quantidade</th>
                        <th>Validade</th>
                        <th>Preço</th>
                        <th>Ações</th>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $item): ?>
                    <tr data-id="<?= $item['id'] ?>" data-marca-id="<?= $item['marca_id'] ?>" data-categoria-id="<?= $item['categoria_id'] ?>">
                        <?php if ($tipo == 'ingredientes'): ?>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td><?= htmlspecialchars($item['quantidade']) ?></td>
                            <td><?= htmlspecialchars($item['unidadeMedida']) ?></td>
                            <td>
                                <?php
                                    $dataValidade = $item['validade'];
                                    echo $dataValidade ? date('d/m/Y', strtotime($dataValidade)) : '';
                                ?>
                            </td>
                        <?php else: ?>
                            <td><?= htmlspecialchars($item['marca']) ?></td>
                            <td><?= htmlspecialchars($item['categoria']) ?></td>
                            <td><?= htmlspecialchars($item['quantidade']) ?></td>
                            <td>
                                <?php
                                    $dataValidade = $item['validade'];
                                    echo $dataValidade ? date('d/m/Y', strtotime($dataValidade)) : '';
                                ?>
                            </td>
                            <td><?= htmlspecialchars($item['preco']) ?></td>
                        <?php endif; ?>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="abrirModalEditar(this)">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="excluirLinha(this)">Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Novo/Editar Ingrediente -->
    <div class="modal fade" id="modalIngrediente" tabindex="-1">
      <div class="modal-dialog">
        <form class="modal-content" id="formIngrediente" method="POST" action="salvar_ingrediente.php">
          <div class="modal-header">
            <h5 class="modal-title" id="tituloModalIngrediente">Novo Ingrediente</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" id="idIngrediente">
            <div class="mb-3">
              <label for="nomeIngrediente" class="form-label">Nome</label>
              <input type="text" class="form-control" name="nome" id="nomeIngrediente" required>
            </div>
            <div class="mb-3">
              <label for="quantidadeIngrediente" class="form-label">Quantidade</label>
              <input type="number" class="form-control" name="quantidade" id="quantidadeIngrediente" required>
            </div>
            <div class="mb-3">
              <label for="unidadeIngrediente" class="form-label">Unidade de Medida</label>
              <select class="form-select" name="unidadeMedida" id="unidadeIngrediente" required>
                <option value="">Selecione</option>
                <?php foreach ($unidades as $unidade): ?>
                  <option value="<?= $unidade['idunidadeMedida'] ?>"><?= $unidade['nome'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="validadeIngrediente" class="form-label">Validade</label>
              <input type="date" class="form-control" name="validade" id="validadeIngrediente" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Salvar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Novo/Editar Bebida -->
    <div class="modal fade" id="modalBebida" tabindex="-1">
      <div class="modal-dialog">
        <form class="modal-content" id="formBebida" method="POST" action="salvar_bebida.php">
          <div class="modal-header">
            <h5 class="modal-title" id="tituloModalBebida">Nova Bebida</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" id="idBebida">
            <div class="mb-3">
              <label for="quantidadeBebida" class="form-label">Quantidade</label>
              <input type="number" class="form-control" name="quantidade" id="quantidadeBebida" required>
            </div>
            <div class="mb-3">
              <label for="marcaBebida" class="form-label">Marca</label>
              <select class="form-select" name="marca_id" id="marcaBebida" required>
                <option value="">Selecione</option>
                <?php foreach ($marcas as $marca): ?>
                  <option value="<?= $marca['idmarcaBebidas'] ?>"><?= htmlspecialchars($marca['nome']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="categoriaBebida" class="form-label">Categoria</label>
              <select class="form-select" name="categoria_id" id="categoriaBebida" required>
                <option value="">Selecione</option>
                <?php foreach ($categorias as $cat): ?>
                  <option value="<?= $cat['idcategoriaBebidas'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="validadeBebida" class="form-label">Validade</label>
              <input type="date" class="form-control" name="validade" id="validadeBebida" required>
            </div>
            <div class="mb-3">
              <label for="precoBebida" class="form-label">Preço</label>
              <input type="number" step="0.01" class="form-control" name="preco" id="precoBebida" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Salvar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function trocarTipo() {
        const tipo = $('#tipoFiltro').val();
        window.location.href = 'estoque.php?tipo=' + tipo;
    }

    function abrirModalNovo() {
        const tipo = $('#tipoFiltro').val();
        if (tipo === 'ingredientes') {
            $('#formIngrediente')[0].reset();
            $('#idIngrediente').val('');
            $('#nomeIngrediente').prop('readonly', false);
            $('#tituloModalIngrediente').text('Novo Ingrediente');
            new bootstrap.Modal(document.getElementById('modalIngrediente')).show();
        } else {
            $('#formBebida')[0].reset();
            $('#idBebida').val('');
            $('#tituloModalBebida').text('Nova Bebida');
            new bootstrap.Modal(document.getElementById('modalBebida')).show();
        }
    }

    function abrirModalEditar(btn) {
        const tipo = $('#tipoFiltro').val();
        const tr = $(btn).closest('tr');
        const tds = tr.find('td');
        if (tipo === 'ingredientes') {
            $('#idIngrediente').val(tr.data('id'));
            $('#nomeIngrediente').val(tds.eq(0).text()).prop('readonly', true);
            $('#quantidadeIngrediente').val(tds.eq(1).text());
            $('#unidadeIngrediente option').filter(function() {
                return $(this).text() === tds.eq(2).text();
            }).prop('selected', true);
            $('#validadeIngrediente').val(tds.eq(3).text().split('/').reverse().join('-'));
            $('#tituloModalIngrediente').text('Editar Ingrediente');
            new bootstrap.Modal(document.getElementById('modalIngrediente')).show();
        } else {
            $('#idBebida').val(tr.data('id'));
            $('#marcaBebida').val(tr.data('marca-id'));
            $('#categoriaBebida').val(tr.data('categoria-id'));
            $('#quantidadeBebida').val(tds.eq(2).text());
            let validade = tds.eq(3).text().trim();
            if (validade) {
                let partes = validade.split('/');
                if (partes.length === 3) {
                    validade = [partes[2], partes[1], partes[0]].join('-');
                } else {
                    validade = '';
                }
            }
            $('#validadeBebida').val(validade);
            $('#precoBebida').val(tds.eq(4).text());
            $('#tituloModalBebida').text('Editar Bebida');
            new bootstrap.Modal(document.getElementById('modalBebida')).show();
        }
    }

    function excluirLinha(btn) {
        if (!confirm('Deseja realmente excluir este item?')) return;
        const tipo = $('#tipoFiltro').val();
        const tr = $(btn).closest('tr');
        const id = tr.data('id');
        $.post('excluir.php', { tipo, id }, function(resp) {
            if (resp.trim() === 'ok') {
                tr.remove();
            } else {
                alert('Erro ao excluir!');
            }
        });
    }
    </script>
</body>
</html>