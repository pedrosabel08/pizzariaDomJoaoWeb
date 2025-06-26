<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <title>Registrar Compra</title>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="../styleSidebar.css">

</head>

<body>

    <?php

    include "../sidebar.php";

    ?>

    <form id="formCompra">
        <label for="idfornecedor">Fornecedor:</label>
        <select name="idfornecedor" id="idfornecedor" required>
            <option value="">Selecione...</option>
        </select>
        <div id="produtos-container"></div>
        <label for="observacoes">Observações:</label>
        <textarea name="observacoes" id="observacoes"></textarea>
        <button type="submit">Registrar Compra</button>
    </form>

    <script src="../sidebar.js"></script>

</body>

</html>