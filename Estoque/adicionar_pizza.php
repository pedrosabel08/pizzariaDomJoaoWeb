<?php

include("conexao.php");

$pizza_name = $_POST['pizza_name'];
$ingredients = $_POST['ingredients'];
$quantities = $_POST['quantities'];
$tipoPizza = $_POST['tipoPizza'];

$sql_pizza = "INSERT INTO pizzas (nomePizza, tipoPizza) VALUES (?,?)";
$stmt_pizza = $conn->prepare($sql_pizza);
$stmt_pizza->bind_param("ss", $pizza_name, $tipoPizza);
$stmt_pizza->execute();
$pizza_id = $stmt_pizza->insert_id;
$stmt_pizza->close();

$sql_ingredient = "INSERT INTO pizzas_produtos (pizza_id, produto_id, quantidade) VALUES (?, ?, ?)";
$stmt_ingredient = $conn->prepare($sql_ingredient);

for ($i = 0; $i < count($ingredients); $i++) {
    $ingredient_id = $ingredients[$i];
    $quantity = $quantities[$i];
    $stmt_ingredient->bind_param("iii", $pizza_id, $ingredient_id, $quantity);
    $stmt_ingredient->execute();
}

$stmt_ingredient->close();
$conn->close();

echo "<script>alert('Pizza cadastrada com sucesso!');window.location.href='pizzas.php';</script>";
exit();