<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Estoque</title>
  <link rel="icon" href="../assets/caixa.png" type="image/png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <!-- Inclua o SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="container mt-4">
    <div class="d-flex align-items-center mb-3">
      <label for="tipoFiltro" class="me-2 fw-bold">Tipo:</label>
      <select id="tipoFiltro" class="form-select w-auto" onchange="trocarTipo()">
        <option value="ingredientes">Ingredientes</option>
        <option value="bebidas">Bebidas</option>
      </select>
      <!-- <button class="btn btn-success ms-3" onclick="abrirModalNovo()"></button> -->
    </div>

    <div class="table-responsive" style="max-height: 70vh;">
      <table id="tabelaEstoque">
        <thead>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Bebida -->
  <div class="modal fade" id="modalBebida" tabindex="-1">
    <div class="modal-dialog">
      <form class="modal-content" id="formBebida">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModalBebida">Nova Bebida</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="idBebida">
          <div class="mb-3">
            <label for="marcaBebida" class="form-label">Marca</label>
            <select class="form-select" id="marcaBebida" required></select>
          </div>
          <div class="mb-3">
            <label for="categoriaBebida" class="form-label">Categoria</label>
            <select class="form-select" id="categoriaBebida" required></select>
          </div>
          <div class="mb-3">
            <label for="tamanhoBebida" class="form-label">Tamanho</label>
            <select class="form-select" id="tamanhoBebida" required></select>
          </div>
          <div class="mb-3">
            <label for="quantidadeBebida" class="form-label">Quantidade</label>
            <input type="number" class="form-control" id="quantidadeBebida" required>
          </div>
          <div class="mb-3">
            <label for="validadeBebida" class="form-label">Validade</label>
            <input type="date" class="form-control" id="validadeBebida" required>
          </div>
          <div class="mb-3">
            <label for="precoBebida" class="form-label">Preço</label>
            <input type="number" step="0.01" class="form-control" id="precoBebida" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="salvarBebida()">Salvar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Ingrediente -->
  <div class="modal fade" id="modalIngrediente" tabindex="-1">
    <div class="modal-dialog">
      <form class="modal-content" id="formIngrediente">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModalIngrediente">Novo Ingrediente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="idIngrediente">
          <div class="mb-3">
            <label for="nomeIngrediente" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nomeIngrediente" required>
          </div>
          <div class="mb-3">
            <label for="quantidadeIngrediente" class="form-label">Quantidade</label>
            <input type="number" class="form-control" id="quantidadeIngrediente" required>
          </div>
          <div class="mb-3">
            <label for="unidadeIngrediente" class="form-label">Unidade Medida</label>
            <select class="form-select" id="unidadeIngrediente" required></select>
          </div>
          <div class="mb-3">
            <label for="tipoIngrediente" class="form-label">Tipo</label>
            <select class="form-select" id="tipoIngrediente" required></select>
          </div>
          <div class="mb-3">
            <label for="validadeIngrediente" class="form-label">Validade</label>
            <input type="date" class="form-control" id="validadeIngrediente" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="salvarIngrediente()">Salvar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>


  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="script.js"></script>
</body>

</html>