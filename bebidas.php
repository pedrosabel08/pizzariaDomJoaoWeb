<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $sql = "SELECT m.nomeMarca, t.tamanho, t.preco
            FROM tamanhosBebidas t
            JOIN marcas m ON t.marca_id = m.idmarca
            WHERE t.tamanho = ?
            ORDER BY m.nomeMarca";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='card'>
                    <h3>{$row['nomeMarca']}</h3>
                    <p>Tamanho: {$row['tamanho']}</p>
                    <p>Preço: R$ {$row['preco']}</p>
                  </div>";
        }
    } else {
        echo "<p>Nenhuma bebida encontrada</p>";
    }

    $stmt->close();
    $conn->close();
}