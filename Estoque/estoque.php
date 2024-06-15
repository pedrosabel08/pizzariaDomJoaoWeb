<?php
include 'conexao.php';

$sql = "SELECT produtos.idprodutos, produtos.nomeProduto, produtos.quantidade, unidademedida.nome as unidadeMedida, produtos.validade 
        FROM produtos 
        INNER JOIN unidademedida ON produtos.unidadeMedida = unidademedida.idunidadeMedida 
        ORDER BY produtos.nomeProduto ASC;";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$sql_unidades = "SELECT idunidadeMedida, nome FROM unidademedida";
$result_unidades = $conn->query($sql_unidades);

$unidades = array();
if ($result_unidades->num_rows > 0) {
    while ($row = $result_unidades->fetch_assoc()) {
        $unidades[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeProduto = $_POST['nomeProduto'];
    $quantidade = $_POST['quantidade'];
    $unidadeMedida = $_POST['unidadeMedida'];
    $validadeFormatada = date('Y-m-d', strtotime($_POST['validade']));

    $sql = "INSERT INTO produtos (nomeProduto, quantidade, unidadeMedida, validade) VALUES ('$nomeProduto', '$quantidade', '$unidadeMedida', '$validadeFormatada')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Novo produto inserido com sucesso!');window.location.href='estoque.php';</script>";
    } else {
        echo "Erro ao inserir produto: " . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Estoque</title>
</head>

<body>
    <main>
        <div class="tabela">
            <button class="btn" onclick="window.location.href='produtos.html'">Voltar</button>
            <h2>Estoque</h2>
            <input type="text" id="filterInput" onkeyup="filterTable()" placeholder="Filtrar por:">
            <div class="table-wrapper">
                <table id="tabelaEstoque">
                    <tr>
                        <th>Nome do Produto</th>
                        <th>Quantidade</th>
                        <th class="unidadeMedida">Unidade Medida</th>
                        <th>Validade</th>
                    </tr>
                    <?php foreach ($data as $produto): ?>
                        <tr class="linha-tabela" data-id="<?php echo $produto['idprodutos']; ?>">
                            <td><?php echo $produto['nomeProduto']; ?></td>
                            <td><?php echo $produto['quantidade']; ?></td>
                            <td><?php echo $produto['unidadeMedida']; ?></td>
                            <td><?php echo $produto['validade']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        <div class="inserir">
            <h2>Inserir produto: </h2>
            <form action="estoque.php" method="POST">
                <ul>
                    <li>
                        <label for="nomeProduto">Nome Produto: </label>
                        <input type="text" name="nomeProduto" id="nomeProduto">
                    </li>
                    <li>
                        <label for="quantidade">Quantidade: </label>
                        <input type="number" name="quantidade" id="quantidade">
                    </li>
                    <li>
                        <label for="unidadeMedida">Unidade de Medida: </label>
                        <select name="unidadeMedida" id="unidadeMedida" required>
                            <option value="">Selecione</option>
                            <?php foreach ($unidades as $unidade): ?>
                                <option value="<?php echo $unidade['idunidadeMedida']; ?>">
                                    <?php echo $unidade['nome']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </li>
                    <li>
                        <label for="validade">Validade: </label>
                        <input type="date" name="validade" id="validade">
                    </li>
                </ul>
                <div class="buttons">
                    <button type="submit">Inserir Produto</button>
                </div>
            </form>
            <div id="botoes">
                <form id="formExcluirProduto" action="excluir.php" method="POST">
                    <input type="hidden" name="idprodutos" id="idProdutoExcluir">
                    <button type="button" id="botaoExcluir">Excluir Item</button>
                </form>
                <form id="formAlterarProduto" action="alterar.php" method="POST">
                    <input type="hidden" name="idprodutos" id="idProdutoAlterar">
                    <button type="button" id="botaoAlterar">Alterar Item</button>

                    <input type="hidden" name="nomeProdutoAlterar" id="nomeProdutoAlterar">
                    <input type="hidden" name="qtdeProdutoAlterar" id="qtdeProdutoAlterar">
                    <input type="hidden" name="umProdutoAlterar" id="umProdutoAlterar">
                    <input type="hidden" name="validadeProdutoAlterar" id="validadeProdutoAlterar">
                </form>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; Arthur, Pedro e Vitor</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="script.js"></script>
</body>

</html>