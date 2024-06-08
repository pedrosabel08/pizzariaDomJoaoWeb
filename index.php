<?php
include ("conexao.php");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/output.css" />
    <link rel="stylesheet" href="./styles/style.css">

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Pizzaria</title>
</head>

<body class="bg-gray-100">


    <header class="w-full h-[420px] bg-orange-300">
        <div class="w-full h-full flex flex-col justify-center items-center">
            <img src="./assets/domJoao.jpg" alt="Logo" class="w-40 h-40 rounded-full shadow-lg hover:scale-110">
            <h1 class="text-3xl mt-4 mb-2 font-bold text-black">Pizzaria Dom João</h1>

            <a href="https://www.google.com/maps/search/?api=1&query=%27Rua%20Jo%C3%A3o%20Pessoa,%201726%20Sl%2004%20-%20Velha%20-%20Blumenau%20/%20SC%27"
                class="text-black font-medium" target="blink">Rua João Pessoa, 1726 Sl 04 - Velha - Blumenau/SC</a>

            <div class="bg-green-500 px-4 py-1 rounded-lg mt-5" id="date-span">
                <span class="text-white font-medium">Seg a Dom - 17:30 as 23:30</span>
            </div>
        </div>
    </header>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-center mb-4">Monte sua Pizza</h1>

        <!-- Step 1: Tamanho da Pizza -->
        <div id="step1" class="mb-8">
            <h2 class="text-2xl mb-4">Escolha o tamanho da pizza:</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <button
                    class="pizza-size flex items-center bg-white p-4 rounded-lg shadow-md hover:border-solid hover:border-2 hover:border-red-500"
                    data-size="Baby" data-sabores="1">
                    <img src="./assets/pizzaBaby.jpg" alt="pizzaBaby" class="w-24 h-24 object-cover rounded-3xl mr-6">
                    <div class="text-left">
                        <p class="font-bold text-2xl mb-3">Baby</p>
                        <p class="text-[16px]">20cm, 4 fatias, 1 sabor</p>
                        <p class="text-[16px] text-red-700 font-medium">A partir de R$ 29,90</p>
                    </div>
                </button>
                <button
                    class="pizza-size flex items-center bg-white p-4 rounded-lg shadow-md hover:border-solid hover:border-2 hover:border-red-500"
                    data-size="Média" data-sabores="2">
                    <img src="./assets/pizzaMedia.jpg" alt="pizzaMedia" class="w-24 h-24 object-cover rounded-3xl mr-6">
                    <div class="text-left">
                        <p class="font-bold text-2xl mb-3">Média</p>
                        <p class="text-[16px]">30cm, 8 fatias, 2 sabores</p>
                        <p class="text-[16px] text-red-700 font-medium">A partir de R$ 58,90</p>
                    </div>
                </button>
                <button
                    class="pizza-size flex items-center bg-white p-4 rounded-lg shadow-md hover:border-solid hover:border-2 hover:border-red-500"
                    data-size="Grande" data-sabores="3">
                    <img src="./assets/pizzaGrande.jpg" alt="pizzaGrande"
                        class="w-24 h-24 object-cover rounded-3xl mr-6">
                    <div class="text-left">
                        <p class="font-bold text-2xl mb-3">Baby</p>
                        <p class="text-[16px]">35cm, 12 fatias, 3 sabores</p>
                        <p class="text-[16px] text-red-700 font-medium">A partir de R$ 76,90</p>
                    </div>
                </button>
                <button
                    class="pizza-size flex items-center bg-white p-4 rounded-lg shadow-md hover:border-solid hover:border-2 hover:border-red-500"
                    data-size="Gigante" data-sabores="4">
                    <img src="./assets/pizzaGigante.jpg" alt="pizzaGigante"
                        class="w-24 h-24 object-cover rounded-3xl mr-6">
                    <div class="text-left">
                        <p class="font-bold text-2xl mb-3">Baby</p>
                        <p class="text-[16px]">45cm, 16 fatias, 4 sabores</p>
                        <p class="text-[16px] text-red-700 font-medium">A partir de R$ 87,90</p>
                    </div>
                </button>
            </div>
        </div>

        <!-- Step 2: Borda da Pizza -->
        <div id="step2" class="mb-8 hidden">
            <h2 class="text-2xl mb-4">Escolha a borda da pizza:</h2>
            <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-8">
                <button
                    class="pizza-border bg-white p-4 rounded-lg shadow-md text-center hover:border-solid hover:border-2 hover:border-red-500"
                    data-border="Sem Borda">
                    Sem Borda
                </button>
                <button
                    class="pizza-border bg-white p-4 rounded-lg shadow-md text-center hover:border-solid hover:border-2 hover:border-red-500"
                    data-border="Tradicional">
                    Catupiry
                </button>
                <button
                    class="pizza-border bg-white p-4 rounded-lg shadow-md text-center hover:border-solid hover:border-2 hover:border-red-500"
                    data-border="Recheada">
                    Cheddar
                </button>
                <button
                    class="pizza-border bg-white p-4 rounded-lg shadow-md text-center hover:border-solid hover:border-2 hover:border-red-500"
                    data-border="Cream Cheese">
                    Cream Cheese
                </button>
                <button
                    class="pizza-border bg-white p-4 rounded-lg shadow-md text-center hover:border-solid hover:border-2 hover:border-red-500"
                    data-border="Chocolate preto">
                    Chocolate preto
                </button>
                <button
                    class="pizza-border bg-white p-4 rounded-lg shadow-md text-center hover:border-solid hover:border-2 hover:border-red-500"
                    data-border="Chocolate branco">
                    Chocolate branco
                </button>
                <button
                    class="pizza-border bg-white p-4 rounded-lg shadow-md text-center hover:border-solid hover:border-2 hover:border-red-500"
                    data-border="Doce de Leite">
                    Doce de Leite
                </button>
            </div>
        </div>

        <!-- Step 3: Sabores da Pizza -->
        <div id="step3" class="mb-8 hidden">

            <!-- Pizzas Salgadas -->
            <h3 class="text-xl mb-2 mt-10">Pizzas Salgadas:</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php
                // Consulta ao banco de dados para recuperar as pizzas salgadas
                $sql = "SELECT p.idpizzas, p.nomePizza, GROUP_CONCAT(pr.nomeProduto SEPARATOR ', ') AS ingredientes
                FROM pizzas p
                JOIN pizzas_produtos pp ON p.idpizzas = pp.pizza_id
                JOIN produtos pr ON pp.produto_id = pr.idprodutos
                WHERE p.tipoPizza = 'salgada'
                GROUP BY p.idpizzas";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Saída dos dados de cada linha
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <button class="pizza-flavor bg-white p-4 rounded-lg shadow-md text-center"
                            data-flavor="<?php echo htmlspecialchars($row['nomePizza']); ?>">
                            <div class="font-bold"><?php echo htmlspecialchars($row['nomePizza']); ?></div>
                            <div class="text-sm text-gray-600"><?php echo htmlspecialchars($row['ingredientes']); ?></div>
                        </button>
                        <?php
                    }
                } else {
                    echo "<p class='col-span-4'>Nenhuma pizza salgada encontrada.</p>";
                }
                ?>
            </div>

            <!-- Pizzas Doces -->
            <h3 class="text-xl mb-2 mt-10">Pizzas Doces:</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php
                // Consulta ao banco de dados para recuperar as pizzas doces
                $sql = "SELECT p.idpizzas, p.nomePizza, GROUP_CONCAT(pr.nomeProduto SEPARATOR ', ') AS ingredientes
                FROM pizzas p
                JOIN pizzas_produtos pp ON p.idpizzas = pp.pizza_id
                JOIN produtos pr ON pp.produto_id = pr.idprodutos
                WHERE p.tipoPizza = 'doce'
                GROUP BY p.idpizzas";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Saída dos dados de cada linha
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <button class="pizza-flavor bg-white p-4 rounded-lg shadow-md text-center"
                            data-flavor="<?php echo htmlspecialchars($row['nomePizza']); ?>">
                            <div class="font-bold"><?php echo htmlspecialchars($row['nomePizza']); ?></div>
                            <div class="text-sm text-gray-600"><?php echo htmlspecialchars($row['ingredientes']); ?></div>
                        </button>
                        <?php
                    }
                } else {
                    echo "<p class='col-span-4'>Nenhuma pizza doce encontrada.</p>";
                }

                // Fechar a conexão
                $conn->close();
                ?>
            </div>

            <button id="addToCart" class="mt-4 bg-green-500 text-white p-4 rounded-lg">Adicionar ao Carrinho</button>
        </div>

        <!-- Carrinho -->
        <form action="finalizar_venda.php" method="post">
            <div id="cart" class="mt-8">
                <h2 class="text-2xl mb-4">Carrinho:</h2>
                <ul id="cartItems" class="list-disc pl-6"></ul>
                <button>Finalizar Venda</button>
            </div>
        </form>
    </div>

    <script src="script.js"></script>
</body>

</html>