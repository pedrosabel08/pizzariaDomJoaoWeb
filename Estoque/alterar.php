<?php
// Verifica se o ID do produto a ser alterado foi enviado via POST
if(isset($_POST['idprodutos'])) {
    // Inclui o arquivo de conexão
    include 'conexao.php';

    // Obtém o ID do produto a ser excluído
    $idProdutoAlterar = $_POST['idprodutos'];
    $nome = $_POST['nomeProdutoAlterar'];
    $qtde = $_POST['qtdeProdutoAlterar'];
    $um = $_POST['umProdutoAlterar'];
    $validade = $_POST['validadeProdutoAlterar'];
    // Query para alterar o produto
    $conn->begin_transaction();
    try{
        $stmt1 = $conn->prepare("UPDATE produtos SET nomeProduto = '$nome', quantidade = ?, unidadeMedida = ?, validade = '$validade' WHERE idprodutos = ?");
        $stmt1->bind_param("iii", $qtde, $um, $idProdutoAlterar);
        $stmt1->execute();
        $conn->commit();
        echo "<script>alert('Produto alterado com sucesso!');window.location.href='estoque.php';</script>";
    }catch(mysqli_sql_exception $exception){
        $conn->rollback();
        echo "Erro ao alterar pizza". $exception->getMessage();
    }
    $stmt1->close();
    $conn->close();
    // Executa a query
    // if ($conn->query($sql) === TRUE) {
    //     // Mensagem de sucesso
    //     echo "<script>alert('Produto alterado com sucesso!');window.location.href='estoque.php';</script>";
    // } else {
    //     // Mensagem de erro
    //     echo "Erro ao alterar produto: " . $conn->error;
    // }

    // Fecha a conexão com o banco de dados
    $conn->close();
} else {
    // Se o ID do produto não foi enviado, retorna uma mensagem de erro
    echo "ID do produto não foi recebido.";
}