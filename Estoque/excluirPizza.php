<?php
if (isset($_POST['idpizzas'])) {
    include 'conexao.php';

    $idPizzaExcluir = $_POST['idpizzas'];

    $conn->begin_transaction();

    try {
        $stmt1 = $conn->prepare("DELETE FROM pizzas_produtos WHERE pizza_id = ?");
        $stmt1->bind_param("i", $idPizzaExcluir);
        $stmt1->execute();

        $stmt2 = $conn->prepare("DELETE FROM pizzas WHERE idpizzas = ?");
        $stmt2->bind_param("i", $idPizzaExcluir);
        $stmt2->execute();

        $conn->commit();

        echo "<script>alert('Pizza excluída com sucesso!');window.location.href='pizzas.php';</script>";
    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        echo "Erro ao excluir pizza: " . $exception->getMessage();
    }

    $stmt1->close();
    $stmt2->close();

    $conn->close();
} else {
    echo "ID da pizza não foi recebido.";
}