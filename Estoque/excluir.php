<?php
// Verifica se o ID do produto a ser excluído foi enviado via POST
if(isset($_POST['idprodutos'])) {
    // Inclui o arquivo de conexão
    include 'conexao.php';

    // Obtém o ID do produto a ser excluído
    $idProdutoExcluir = $_POST['idprodutos'];

    // Query para excluir o produto
    $sql = "DELETE FROM produtos WHERE idprodutos = $idProdutoExcluir";

    // Executa a query
    if ($conn->query($sql) === TRUE) {
        // Mensagem de sucesso
        echo "<script>alert('Produto excluído com sucesso!');window.location.href='estoque.php';</script>";
    } else {
        // Mensagem de erro
        echo "Erro ao excluir produto: " . $conn->error;
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
} else {
    // Se o ID do produto não foi enviado, retorna uma mensagem de erro
    echo "ID do produto não foi recebido.";
}