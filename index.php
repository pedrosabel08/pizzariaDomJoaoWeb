<?php
include("conexao.php");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/output.css" />
    <link rel="stylesheet" href="./styles/style.css">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD75y85lHmcaYraJsfSMtk245adprAqWFw&libraries=places"></script>

    <title>Pizzaria</title>
    <link rel="icon" href="./assets/pizza.png" type="image/png">
</head>

<body id="corpo" class="font-nunito">

    <header class="w-full h-auto bg-orange-300 relative">
        <div class="p-2 flex justify-between items-center">
            <div>
                <button id="login" onclick="window.location.href='login.html'">
                    <i class="fa-solid fa-user"></i>
                    Login
                </button>
                <span id="greeting" style="display: none;">Olá, <span id="cliente-nome"></span></span>
                <button onclick="window.location.href='login.html'" id="button-sair" class="bg-red-500 hover:bg-red-700 text-white py-1 px-4 rounded focus:outline-none focus:shadow-outline ml-4 hidden">Sair</button>
            </div>
            <div class="absolute top-0 right-0 p-3" id="cart-container">
                <button class="flex items-center gap-2 font-bold" id="cart-btn">
                    <i class="fa fa-cart-plus text-3xl"></i>
                    <span class="hidden">Carrinho</span>
                </button>
            </div>
        </div>
        <div class="w-full h-full flex flex-col justify-center items-center">
            <img src="./assets/domJoao.jpg" alt="Logo" class="w-40 h-40 rounded-full shadow-lg hover:scale-110">
            <h1 class="text-3xl mt-4 mb-2 font-bold text-black">Pizzaria Dom João</h1>
            <a href="https://www.google.com/maps/search/?api=1&query=%27Rua%20Jo%C3%A3o%20Pessoa,%201726%20Sl%2004%20-%20Velha%20-%20Blumenau%20/%20SC%27" class="text-black font-medium" target="blink">Rua João Pessoa, 1726 Sl 04 - Velha - Blumenau/SC</a>
            <div class="bg-green-500 px-4 py-1 rounded-lg my-5" id="date-span">
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
                <button class="pizza-size flex items-center bg-white p-4 rounded-lg shadow-md border-2 border-yellow-500" data-size="Baby" data-sabores="1" data-price="29.90" data-id-size="2">
                    <img src="./assets/pizzaBaby.jpg" alt="pizzaBaby" class="w-24 h-24 object-cover rounded-3xl mr-6">
                    <div class="text-left">
                        <p class="font-bold text-2xl mb-3">Baby</p>
                        <p class="text-[16px]">20cm, 4 fatias, 1 sabor</p>
                        <p class="text-[16px] text-red-700 font-medium">A partir de R$ 29,90</p>
                    </div>
                </button>
                <button class="pizza-size flex items-center bg-white p-4 rounded-lg shadow-md border-2 border-yellow-500" data-size="Média" data-sabores="2" data-price="58.90" data-id-size="3">
                    <img src="./assets/pizzaMedia.jpg" alt="pizzaMedia" class="w-24 h-24 object-cover rounded-3xl mr-6">
                    <div class="text-left">
                        <p class="font-bold text-2xl mb-3">Média</p>
                        <p class="text-[16px]">30cm, 8 fatias, 2 sabores</p>
                        <p class="text-[16px] text-red-700 font-medium">A partir de R$ 58,90</p>
                    </div>
                </button>
                <button class="pizza-size flex items-center bg-white p-4 rounded-lg shadow-md border-2 border-yellow-500" data-size="Grande" data-sabores="3" data-price="76.90" data-id-size="4">
                    <img src="./assets/pizzaGrande.jpg" alt="pizzaGrande" class="w-24 h-24 object-cover rounded-3xl mr-6">
                    <div class="text-left">
                        <p class="font-bold text-2xl mb-3">Grande</p>
                        <p class="text-[16px]">35cm, 12 fatias, 3 sabores</p>
                        <p class="text-[16px] text-red-700 font-medium">A partir de R$ 76,90</p>
                    </div>
                </button>
                <button class="pizza-size flex items-center bg-white p-4 rounded-lg shadow-md border-2 border-yellow-500" data-size="Gigante" data-sabores="4" data-price="87.90" data-id-size="5">
                    <img src="./assets/pizzaGigante.jpg" alt="pizzaGigante" class="w-24 h-24 object-cover rounded-3xl mr-6">
                    <div class="text-left">
                        <p class="font-bold text-2xl mb-3">Gigante</p>
                        <p class="text-[16px]">45cm, 16 fatias, 4 sabores</p>
                        <p class="text-[16px] text-red-700 font-medium">A partir de R$ 87,90</p>
                    </div>
                </button>
            </div>
        </div>

        <!-- Step 2: Borda da Pizza -->
        <div id="step2" class="mb-8 hidden">
            <div class="flex items-center mb-4">
                <button id="backToStep1" class="mr-4 bg-zinc-500 text-white p-3 rounded-full hidden border-2 border-yellow-500">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <h2 class="text-2xl">Escolha os tipos de borda:</h2>
            </div>
            <p id="selectedSizeStep2" class="text-lg mb-4"></p>
            <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-8">
                <button class="pizza-border bg-white p-4 rounded-lg shadow-md text-center border-2 border-yellow-500" data-border="Sem Borda" data-border-price="0" data-id-border="7">
                    <div>
                        <p>Sem borda</p>
                    </div>
                </button>
                <button class="pizza-border bg-white p-4 rounded-lg shadow-md text-center border-2 border-yellow-500" data-border="Catupiry" data-border-price="10" data-id-border="1">
                    <div>
                        <p>Catupiry</p>
                        <p class="text-red-500">+ R$10,00</p>
                    </div>
                </button>
                <button class="pizza-border bg-white p-4 rounded-lg shadow-md text-center border-2 border-yellow-500" data-border="Cheddar" data-border-price="10" data-id-border="2">
                    <div>
                        <p>Cheddar</p>
                        <p class="text-red-500">+ R$10,00</p>
                    </div>
                </button>
                <button class="pizza-border bg-white p-4 rounded-lg shadow-md text-center border-2 border-yellow-500" data-border="Cream Cheese" data-border-price="10" data-id-border="3">
                    <div>
                        <p>Cream Cheese</p>
                        <p class="text-red-500">+ R$10,00</p>
                    </div>
                </button>
                <button class="pizza-border bg-white p-4 rounded-lg shadow-md text-center border-2 border-yellow-500" data-border="Chocolate preto" data-border-price="10" data-id-border="4">
                    <div>
                        <p>Chocolate Preto</p>
                        <p class="text-red-500">+ R$10,00</p>
                    </div>
                </button>
                <button class="pizza-border bg-white p-4 rounded-lg shadow-md text-center border-2 border-yellow-500" data-border="Chocolate branco" data-border-price="10" data-id-border="5">
                    <div>
                        <p>Chocolate Branco</p>
                        <p class="text-red-500">+ R$10,00</p>
                    </div>
                </button>
                <button class="pizza-border bg-white p-4 rounded-lg shadow-md text-center border-2 border-yellow-500" data-border="Doce de Leite" data-border-price="10" data-id-border="6">
                    <div>
                        <p>Doce de leite</p>
                        <p class="text-red-500">+ R$10,00</p>
                    </div>
                </button>
            </div>
        </div>

        <!-- Step 3: Sabores da Pizza -->
        <div id="step3" class="mb-8 hidden">
            <div class="flex items-center mb-4">
                <button id="backToStep2" class="mr-4 bg-zinc-500 text-white p-3 rounded-full hidden ">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <h2 class="text-2xl">Escolha os sabores da pizza:</h2>
            </div>
            <p id="selectedSizeStep3" class="text-lg mb-2"></p>
            <p id="selectedBorderStep3" class="text-lg mb-4"></p>
            <h3 class="text-xl mb-2 mt-10">Pizzas Salgadas:</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php
                $sql = "SELECT p.idpizzas, p.nomePizza, GROUP_CONCAT(pr.nomeProduto SEPARATOR ', ') AS ingredientes
                FROM pizzas p
                JOIN pizzas_produtos pp ON p.idpizzas = pp.pizza_id
                JOIN produtos pr ON pp.produto_id = pr.idprodutos
                WHERE p.tipoPizza = 'salgada'
                GROUP BY p.idpizzas";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <button class="pizza-flavor bg-white p-4 rounded-lg shadow-md text-center border-2 border-red-500" data-flavor="<?php echo htmlspecialchars($row['nomePizza']); ?>" data-id-flavor="<?php echo $row['idpizzas'] ?>">
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

            <h3 class="text-xl mb-2 mt-10">Pizzas Doces:</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php
                $sql = "SELECT p.idpizzas, p.nomePizza, GROUP_CONCAT(pr.nomeProduto SEPARATOR ', ') AS ingredientes
                FROM pizzas p
                JOIN pizzas_produtos pp ON p.idpizzas = pp.pizza_id
                JOIN produtos pr ON pp.produto_id = pr.idprodutos
                WHERE p.tipoPizza = 'doce'
                GROUP BY p.idpizzas";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <button class="pizza-flavor bg-white p-4 rounded-lg shadow-md text-center border-2 border-pink-500" data-flavor="<?php echo htmlspecialchars($row['nomePizza']); ?>" data-id-flavor="<?php echo $row['idpizzas'] ?>">
                            <div class="font-bold"><?php echo htmlspecialchars($row['nomePizza']); ?></div>
                            <div class="text-sm text-gray-600"><?php echo htmlspecialchars($row['ingredientes']); ?></div>
                        </button>
                <?php
                    }
                } else {
                    echo "<p class='col-span-4'>Nenhuma pizza doce encontrada.</p>";
                }

                $conn->close();
                ?>
            </div>

            <button id="addToCart" class=" bg-green-500 text-white p-4 rounded-lg mt-6">Adicionar ao Carrinho</button>
        </div>

        <!-- Step 4: Bebidas -->
        <div class="mb-12" id="step4">
            <h2 class="text-2xl mb-4">Escolha a bebida:</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div>
                    <button class="bebida-size flex items-center bg-white p-4 rounded-lg shadow-md w-full border-2 border-yellow-500" id="lata">
                        <img src="./assets/Refrigerante.jpg" alt="lata" class="w-24 h-24 object-cover rounded-3xl mr-6">
                        <div class="text-left">
                            <p>Refrigerante Lata</p>
                        </div>
                    </button>
                    <div id="options-1" class="hidden mt-2">
                        <ul class="border border-gray-300 rounded-lg p-4">
                            <li class="flex flex-col space-y-2">
                                <button class="text-left hover:bg-gray-200 p-2 rounded border-2 border-yellow-500" data-bebida-id="1" data-bebida-name="Coca Cola Lata" data-bebida-price="6">Coca cola - R$6,00</button>
                                <button class="text-left hover:bg-gray-200 p-2 rounded border-2 border-yellow-500" data-bebida-id="2" data-bebida-name="Guaraná Lata" data-bebida-price="6">Guaraná - R$6,00</button>
                                <button class="text-left hover:bg-gray-200 p-2 rounded border-2 border-yellow-500" data-bebida-id="3" data-bebida-name="Sprite Lata" data-bebida-price="6">Sprite - R$6,00</button>
                                <button class="text-left hover:bg-gray-200 p-2 rounded border-2 border-yellow-500" data-bebida-id="4" data-bebida-name="Fanta Lata" data-bebida-price="6">Fanta - R$6,00</button>
                            </li>
                        </ul>
                    </div>
                </div>

                <div>
                    <button class="bebida-size flex items-center bg-white p-4 rounded-lg shadow-md w-full border-2 border-yellow-500" id="600ml">
                        <img src="./assets/Refrigerante600.jfif" alt="600ml" class="w-24 h-24 object-cover rounded-3xl mr-6">
                        <div class="text-left">
                            <p>Refrigerante 600ml</p>
                        </div>
                    </button>
                    <div id="options-2" class="hidden mt-2">
                        <ul class="border border-gray-300 rounded-lg p-4">
                            <li class="flex flex-col space-y-2">
                                <button class="text-left hover:bg-gray-200 p-2 rounded border-2 border-yellow-500" data-bebida-id="5" data-bebida-name="Coca Cola 600ml" data-bebida-price="8">Coca cola - R$8,00</button>
                                <button class="text-left hover:bg-gray-200 p-2 rounded border-2 border-yellow-500" data-bebida-id="6" data-bebida-name="Sprite 600ml" data-bebida-price="8">Sprite - R$8,00</button>
                                <button class="text-left hover:bg-gray-200 p-2 rounded border-2 border-yellow-500" data-bebida-id="7" data-bebida-name="Fanta 600ml" data-bebida-price="8">Fanta - R$8,00</button>
                            </li>
                        </ul>
                    </div>
                </div>

                <div>
                    <button class="bebida-size flex items-center bg-white p-4 rounded-lg shadow-md w-full border-2 border-yellow-500" id="2l">
                        <img src="./assets/Refrigerante2lt.jpg" alt="2l" class="w-24 h-24 object-cover rounded-3xl mr-6">
                        <div class="text-left">
                            <p>Refrigerante 2 Litros</p>
                        </div>
                    </button>
                    <div id="options-3" class="hidden mt-2">
                        <ul class="border border-gray-300 rounded-lg p-4">
                            <li class="flex flex-col space-y-2">
                                <button class="text-left hover:bg-gray-200 p-2 rounded border-2 border-yellow-500" data-bebida-id="8" data-bebida-name="Coca Cola 2L" data-bebida-price="15">Coca cola - R$15,00</button>
                                <button class="text-left hover:bg-gray-200 p-2 rounded border-2 border-yellow-500" data-bebida-id="9" data-bebida-name="Coca Cola zero 2L" data-bebida-price="15">Coca cola zero -
                                    R$15,00</button>
                                <button class="text-left hover:bg-gray-200 p-2 rounded border-2 border-yellow-500" data-bebida-id="10" data-bebida-name="Guaraná 2L" data-bebida-price="12">Guaraná - R$12,00</button>
                                <button class="text-left hover:bg-gray-200 p-2 rounded border-2 border-yellow-500" data-bebida-id="11" data-bebida-name="Sprite 2L" data-bebida-price="12">Sprite - R$12,00</button>
                                <button class="text-left hover:bg-gray-200 p-2 rounded border-2 border-yellow-500" data-bebida-id="12" data-bebida-name="Fanta 2L" data-bebida-price="12">Fanta - R$12,00</button>
                            </li>
                        </ul>
                    </div>
                </div>

                <div>
                    <button class="bebida-size flex items-center bg-white p-4 rounded-lg shadow-md w-full border-2 border-yellow-500" id="agua">
                        <img src="./assets/agua.jpg" alt="agua" class="w-24 h-24 object-cover rounded-3xl mr-6">
                        <div class="text-left">
                            <p>Água</p>
                        </div>
                    </button>
                    <div id="options-4" class="hidden mt-2">
                        <ul class="border border-gray-300 rounded-lg p-4">
                            <li class="flex flex-col space-y-2">
                                <button class="text-left hover:bg-gray-200 p-2 rounded border-2 border-yellow-500" data-bebida-id="13" data-bebida-name="Água com gás" data-bebida-price="3.50">Água com gás -
                                    R$3,50</button>
                                <button class="text-left hover:bg-gray-200 p-2 rounded border-2 border-yellow-500" data-bebida-id="14" data-bebida-name="Água sem gás" data-bebida-price="3.50">Água sem gás -
                                    R$3,50</button>
                            </li>
                        </ul>
                    </div>
                </div>

                <div>
                    <button class="bebida-size flex items-center bg-white p-4 rounded-lg shadow-md w-full border-2 border-yellow-500" id="cerveja">
                        <img src="./assets/longneck.png" alt="cerveja" class="w-24 h-24 object-cover rounded-3xl mr-6">
                        <div class="text-left">
                            <p>Cerveja Longneck</p>
                        </div>
                    </button>
                    <div id="options-5" class="hidden mt-2">
                        <ul class="border border-gray-300 rounded-lg p-4">
                            <li class="flex flex-col space-y-2">
                                <button class="text-left hover:bg-gray-200 p-2 rounded border-2 border-yellow-500" data-bebida-id="15" data-bebida-name="Cerveja Heineken" data-bebida-price="10">Cerveja Heineken -
                                    R$10,00</button>
                                <button class="text-left hover:bg-gray-200 p-2 rounded border-2 border-yellow-500" data-bebida-id="16" data-bebida-name="Cerveja Budweiser" data-bebida-price="10">Cerveja Budweiser -
                                    R$10,00</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal hidden w-full" id="cart-modal">
        <div class="overlay" id="overlay" onclick="fecha()"></div>
        <div class="bg-white p-5 rounded-md min-w-[90%] md:min-w-[600px] sidebar">
            <form id="cartForm" action="finalizar_venda.php" method="post">
                <div id="cart" class="mt-2">
                    <h2 class="text-2xl mb-2">Carrinho:</h2>
                    <ul id="cartItems" class="list-disc pl-6"></ul>
                    <div id="cartInputs"></div>
                    <div id="total-price" class="font-bold text-green-700 mt-4"></div>
                    <input type="hidden" name="cliente_id" id="cliente_id">
                    <input type="hidden" name="cliente_nome" id="cliente_nome">

                    <div class="mt-2">
                        <h3 class="text-xl mb-2">Forma de Entrega:</h3>
                        <div class="flex items-center mb-2">
                            <input type="radio" id="retirada" name="forma_entrega" value="1">
                            <label for="retirada" class="ml-2">Retirada</label>
                        </div>
                        <div class="flex items-center mb-2">
                            <input type="radio" id="entrega" name="forma_entrega" value="2">
                            <label for="entrega" class="ml-2">Entrega</label>
                        </div>

                        <div id="enderecoEntrega" class="mt-2 hidden">
                            <h3 class="text-lg mb-2">CEP:</h3>
                            <input class="border-black border w-20 pl-1" onkeyup="buscaEndereco(this.value);" type="number" id="cep" name="cep" required>
                        </div>
                        <div id="bairroEntrega" class="mt-2 hidden">
                            <h3 class="text-lg mb-2">Bairro</h3>
                            <input class="border-black border w-30 pl-1" type="text" id="bairro" name="bairro">
                        </div>
                        <div id="ruaEntrega" class="mt-2 hidden">
                            <h3 class="text-lg mb-2">Rua</h3>
                            <input class="border-black border w-30 pl-1" type="text" id="rua" name="rua">
                        </div>
                        <div id="numeroEntrega" class="mt-2 hidden">
                            <h3 class="text-lg mb-2">N°</h3>
                            <input class="border-black border w-14 pl-1" type="number" id="numero" name="numero">
                        </div>
                        <div id="complementoEntrega" class="mt-4 hidden">
                            <h3 class="text-lg mb-2">Complemento</h3>
                            <input class="border-black border w-2/3 pl-1" type="text" maxlength="45" id="complemento" name="complemento">
                        </div>
                        <div id="taxaEntrega" class="mt-4 hidden">
                            <h3 class="text-lg mb-2">Taxa de Entrega</h3>
                            <input class="border-black border w-2/3 pl-1" type="text" maxlength="45" id="calcTaxaEntrega" name="calcTaxaEntrega" readonly>
                        </div>
                    </div>
                    <div class="mt-2">
                        <h3 class="text-xl mb-2">Forma de pagamento:</h3>
                        <div class="flex items-center mb-2">
                        <input type="radio" id="pix" name="forma_pagamento" value="1" onchange="gerarQRCode()">
                        <label for="pix" class="ml-2">Pix</label>
                    </div>
                     <!-- Container para o QR Code -->
                    <div id="qrcode" class="mt-4"></div>
                    </div>

<script>
                    function gerarQRCode() {
                    // Aqui você deve substituir pelo código do seu pagamento Pix
                    const pixCode = 'assets/Untitled.png'; // Exemplo de código Pix

                    // Limpa o QR Code anterior
                     $('#qrcode').empty();

                    // Gera o QR Code
                    $('#qrcode').qrcode({
                     text: pixCode,
                     width: 200,
                    height: 200
    });
}
</script>
                        <div class="flex items-center mb-2">
                            <input type="radio" id="debito" name="forma_pagamento" value="2">
                            <label for="debito" class="ml-2">Cartão de débito</label>
                        </div>
                        <div class="flex items-center mb-2">
                            <input type="radio" id="credito" name="forma_pagamento" value="3">
                            <label for="credito" class="ml-2">Cartão de crédito</label>
                        </div>
                        <div class="flex items-center mb-2">
                            <input type="radio" id="dinheiro" name="forma_pagamento" value="4" onchange="toggleTrocoOptions()">
                            <label for="dinheiro" class="ml-2">Dinheiro</label>
                        </div>

<!-- Opções para precisar de troco -->
                        <div class="flex items-center mb-2" id="trocoOptions" style="display: none;">
                            <label class="mr-2">Precisa de troco?</label>
                            <input type="radio" id="trocoSim" name="precisaTroco" value="sim" onchange="toggleTrocoInput()">
                            <label for="trocoSim" class="ml-2">Sim</label>
                            <input type="radio" id="trocoNao" name="precisaTroco" value="nao" onchange="toggleTrocoInput()">
                            <label for="trocoNao" class="ml-2">Não</label>
                        </div>

<!-- Caixa de entrada para o valor do troco -->
                        <div id="trocoContainer" class="mt-2" style="display: none;">
                            <label for="valorTroco" class="mr-2">Troco para?</label>
                            <input type="number" id="valorTroco" placeholder="Informe o valor do troco">
                        </div>

<script>
    function toggleTrocoOptions() {
        const trocoOptions = document.getElementById('trocoOptions');
        trocoOptions.style.display = 'block'; // Exibe as opções de troco
        document.getElementById('trocoSim').checked = false; // Limpa a seleção
        document.getElementById('trocoNao').checked = false; // Limpa a seleção
        toggleTrocoInput(); // Limpa a entrada de troco
}

function toggleTrocoInput() {
        const trocoContainer = document.getElementById('trocoContainer');
        const precisaTrocoSim = document.getElementById('trocoSim').checked;

        if (precisaTrocoSim) {
         trocoContainer.style.display = 'block'; // Mostra a caixa de entrada
        } else {
        trocoContainer.style.display = 'none'; // Esconde a caixa de entrada
        document.getElementById('valorTroco').value = ''; // Limpa o valor do troco
    }
}
</script>

                    </div>
                    <div class="flex items-center justify-between mt-5 w-full mr-14">
                        <button id="close-modal-btn" type="button" class="bg-red-500 text-white px-4 py-1 rounded">Fechar</button>
                        <button id="finalizeSale" type="submit" class="bg-green-500 text-white px-4 py-1 rounded">Finalizar Pedido</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <footer class="bg-gray-300 flex items-center flex-col py-2">
        <p class="font-medium text-sm">Desenvolvido por Arthur, Pedro e Vitor</p>
        <p class="font-medium text-sm">SENAI-SC </p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="script.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>

</html>     