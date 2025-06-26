<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela Cozinha</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../assets/pizza.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../styleSidebar.css">

</head>

<body>

    <?php

    include "../sidebar.php";

    ?>

    <div class="container">


        <header>
            <h2>Tela Cozinha</h2>
        </header>
        <main>

            <table id="tablePedidos">
                <thead>
                    <tr>
                        <th>Tamanho</th>
                        <th>Borda</th>
                        <th>Sabores</th>
                        <th>Data Venda</th>
                        <th>Status</th>
                        <th>Tempo espera</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </main>
    </div>

    <script src="script.js"></script>
    <script src="../sidebar.js"></script>

</body>

</html>