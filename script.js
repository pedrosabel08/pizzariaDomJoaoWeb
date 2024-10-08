document.addEventListener('DOMContentLoaded', function () {
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    const step4 = document.getElementById('step4');
    const cartBtn = document.getElementById("cart-btn");
    const cartModal = document.getElementById("cart-modal")
    const closeModalBtn = document.getElementById("close-modal-btn")
    const totalPriceElement = document.getElementById('total-price');

    let pizzaSize = '';
    let pizzaSizePrice = 0;
    let pizzaBorder = '';
    let pizzaBorderPrice = 0;
    let pizzaFlavors = [];
    let maxFlavors = 0;
    let totalCartPrice = 0;


    cartBtn.addEventListener("click", function () {
        cartModal.style.display = "flex"
        cartModal.style.justifyContent = "center"
        document.getElementById('corpo').style.position = "fixed"
        document.getElementById('corpo').style.width = "100%"
    })

    cartModal.addEventListener("click", function (event) {
        if (event.target === cartModal) {
            cartModal.style.display = "none"
            document.getElementById('corpo').style.position = "absolute"
        }
    })

    closeModalBtn.addEventListener("click", function () {
        cartModal.style.display = "none"
        document.getElementById('corpo').style.position = "absolute"
    })

    document.querySelectorAll('.pizza-size').forEach(button => {
        button.addEventListener('click', function () {
            pizzaSize = this.dataset.size;
            pizzaSizePrice = parseFloat(this.dataset.price);
            maxFlavors = parseInt(this.dataset.sabores);
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            step4.classList.add('hidden')
            document.getElementById('selectedSizeStep2').textContent = `Tamanho escolhido: ${pizzaSize}`;
            document.getElementById('backToStep1').classList.remove('hidden');
            console.log('Selected size:', pizzaSize);
            console.log('Max flavors:', maxFlavors);
        });
    });

    document.querySelectorAll('.pizza-border').forEach(button => {
        button.addEventListener('click', function () {
            pizzaBorder = this.dataset.border;
            pizzaBorderPrice = parseFloat(this.dataset.borderPrice);
            step2.classList.add('hidden');
            step3.classList.remove('hidden');
            step4.classList.add('hidden')
            document.getElementById('selectedSizeStep3').textContent = `Tamanho escolhido: ${pizzaSize}`;
            document.getElementById('selectedBorderStep3').textContent = `Borda escolhida: ${pizzaBorder}`;
            document.getElementById('backToStep2').classList.remove('hidden');
        });
    });

    document.querySelectorAll('.pizza-flavor').forEach(button => {
        button.addEventListener('click', function () {
            const flavor = this.dataset.flavor;
            if (pizzaFlavors.includes(flavor)) {
                pizzaFlavors = pizzaFlavors.filter(flavorItem => flavorItem !== flavor);
                this.classList.remove('bg-green-300');
            } else {
                if (pizzaFlavors.length < maxFlavors) {
                    pizzaFlavors.push(flavor);
                    this.classList.add('bg-green-300');
                } else {
                    alert(`Você só pode selecionar até ${maxFlavors} sabores.`);
                }
            }
        });
    });
    let cartItems = [];

    document.getElementById('addToCart').addEventListener('click', function () {
        if (pizzaFlavors.length > 0) {
            const totalPrice = pizzaSizePrice + pizzaBorderPrice;

            if(pizzaFlavors.length < maxFlavors){
                let diferenca = maxFlavors - pizzaFlavors.length;
                for(let i = diferenca; i < maxFlavors; i++){
                    pizzaFlavors.push(pizzaFlavors);
                }
            }

            const cartItem = {
                id: Date.now(),
                size: pizzaSize,
                border: pizzaBorder,
                flavors: pizzaFlavors,
                price: totalPrice,
                quantity: 1
            };

            cartItems.push(cartItem);

            const li = document.createElement('li');
            li.textContent = `Pizza ${pizzaSize} com borda ${pizzaBorder}, sabores: ${pizzaFlavors.join(', ')}, Preço: R$ ${totalPrice.toFixed(2)}`;

            const botaoMenos = document.createElement('button');
            botaoMenos.innerHTML = '<img class="ml-4" src="./assets/menos.png">';
            const inputQtde = document.createElement('input');
            inputQtde.className = 'ml-2 w-5';
            inputQtde.type = 'text';
            inputQtde.size = 2;
            inputQtde.maxLength = 2;
            inputQtde.value = 1;
            const botaoMais = document.createElement('button');
            botaoMais.innerHTML = '<img class="ml-2" src="./assets/mais.png">';

            botaoMenos.addEventListener('click', function () {
                if (cartItem.quantity > 1) {
                    cartItem.quantity -= 1;
                    inputQtde.value = cartItem.quantity;
                    totalCartPrice -= cartItem.price;
                    totalPriceElement.textContent = `Total: R$ ${totalCartPrice.toFixed(2)}`;
                } else {
                    totalCartPrice -= cartItem.price;
                    totalPriceElement.textContent = `Total: R$ ${totalCartPrice.toFixed(2)}`;
                    cartItems = cartItems.filter(item => item.id !== cartItem.id);
                    li.remove();
                    cartCounter.innerHTML = cartItems.length;
                }
            });

            botaoMais.addEventListener('click', function () {
                cartItem.quantity += 1;
                inputQtde.value = cartItem.quantity;
                totalCartPrice += cartItem.price;
                totalPriceElement.textContent = `Total: R$ ${totalCartPrice.toFixed(2)}`;
            });

            inputQtde.addEventListener('input', function () {
                const newQuantity = parseInt(inputQtde.value);
                if (!isNaN(newQuantity) && newQuantity > 0) {
                    const difference = newQuantity - cartItem.quantity;
                    cartItem.quantity = newQuantity;
                    totalCartPrice += difference * cartItem.price;
                    totalPriceElement.textContent = `Total: R$ ${totalCartPrice.toFixed(2)}`;
                } else if (newQuantity === 0) {
                    totalCartPrice -= cartItem.quantity * cartItem.price;
                    totalPriceElement.textContent = `Total: R$ ${totalCartPrice.toFixed(2)}`;
                    cartItems = cartItems.filter(item => item.id !== cartItem.id);
                    li.remove();
                    cartCounter.innerHTML = cartItems.length;
                } else {
                    inputQtde.value = cartItem.quantity;
                }
            });

            totalCartPrice += totalPrice;

            totalPriceElement.textContent = `Total: R$ ${totalCartPrice.toFixed(2)}`;

            li.appendChild(botaoMenos);
            li.appendChild(inputQtde);
            li.appendChild(botaoMais);
            document.getElementById('cartItems').appendChild(li);

            resetSelection();
            console.log("Itens do carrinho", cartItems);
            alertAdd();
        } else {
            alert('Por favor, selecione pelo menos um sabor.');
        }
    });

    function alertAdd() {
        Toastify({
            text: `Pizza adicionada ao carrinho!`,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "left",
            backgroundColor: "linear-gradient(to right, #3256a8, #3e9cab)",
            className: "info",
            stopOnFocus: true
        }).showToast();
    }

    function resetSelection() {
        step3.classList.add('hidden');
        step1.classList.remove('hidden');
        step4.classList.remove('hidden');
        pizzaSize = '';
        pizzaSizePrice = 0;
        pizzaBorder = '';
        pizzaBorderPrice = 0;
        pizzaFlavors = [];
        maxFlavors = 0;
        document.querySelectorAll('.pizza-flavor').forEach(button => button.classList.remove('bg-green-300'));
        document.getElementById('backToStep2').classList.add('hidden');
    }

    document.getElementById('backToStep1').addEventListener('click', function () {
        step2.classList.add('hidden');
        step1.classList.remove('hidden');
        step4.classList.remove('hidden');
        this.classList.add('hidden');
    });

    document.getElementById('backToStep2').addEventListener('click', function () {
        step3.classList.add('hidden');
        step2.classList.remove('hidden');
        this.classList.add('hidden');
    });

    document.getElementById('finalizeSale').addEventListener('click', function () {
        const cartItemsJSON = JSON.stringify(cartItems);

        document.getElementById('cartItems').value = cartItemsJSON;
    });

    document.getElementById('finalizeSale').addEventListener('click', function (event) {
        event.preventDefault();

        const cartInputs = document.getElementById('cartInputs');
        cartInputs.innerHTML = '';
        cartItems.forEach((item, index) => {
            for (const key in item) {
                if (item.hasOwnProperty(key)) {
                    if (key === "flavors") {
                        item[key].forEach((flavor, flavorIndex) => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = `cartItems[${index}][${key}][${flavorIndex}]`;
                            input.value = flavor;
                            cartInputs.appendChild(input);
                        });
                    } else {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `cartItems[${index}][${key}]`;
                        input.value = item[key];
                        cartInputs.appendChild(input);
                    }
                }
            }
        });

        const deliveryFee = parseFloat(document.getElementById('calcTaxaEntrega').value) || 0;

        const totalWithDelivery = totalCartPrice + deliveryFee;

        const totalPriceInput = document.createElement('input');
        totalPriceInput.type = 'hidden';
        totalPriceInput.name = 'total_price';
        totalPriceInput.value = totalWithDelivery.toFixed(2);
        cartInputs.appendChild(totalPriceInput);



        console.log('Dados do formulário:', cartInputs);

        const isOpen = checkRestaurantOpen();
        if (!isOpen) {
            Toastify({
                text: "Ops, o restaurante está fechado",
                duration: 3000,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                    background: "#ef4444",
                },
            }).showToast();
            return;
        }
        if (clienteId === null) {
            Toastify({
                text: "Faça login ou cadastro para realizar o pedido",
                duration: 3000,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                    background: "#ef4444",
                },
            }).showToast();
            return;
        }

        document.getElementById('cartForm').submit();
    });


    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const message = urlParams.get('message');

    if (status && message) {
        let backgroundColor = "#10B981";
        if (status === 'error') {
            backgroundColor = "#EF4444";
        }

        Toastify({
            text: decodeURIComponent(message.replace(/\+/g, ' ')),
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            stopOnFocus: true,
            style: {
                background: backgroundColor,
            },
        }).showToast();
    }

    const params = new URLSearchParams(window.location.search);
    const nomeCliente = params.get('nome');
    const clienteId = params.get('idCliente');

    const clientIdInput = document.getElementById('cliente_id');

    if (clientIdInput) {
        clientIdInput.value = clienteId;
        console.log(clienteId);
    }


    if (nomeCliente) {
        const loginButton = document.getElementById('login');
        const menuButton = document.getElementById('menuButton');
        const clienteNomeSpan = document.getElementById('cliente-nome');
        const clienteNomeInput = document.getElementById('cliente_nome');
        const buttonSair = document.getElementById('button-sair')

        loginButton.style.display = 'none';
        clienteNomeSpan.textContent = nomeCliente;
        clienteNomeInput.value = nomeCliente
        menuButton.style.display = 'inline';
        buttonSair.classList.remove('hidden');
    }

    const retiradaRadio = document.getElementById('retirada');
    const entregaRadio = document.getElementById('entrega');
    const enderecoEntregaDiv = document.getElementById('enderecoEntrega');
    const bairroEntregaDiv = document.getElementById('bairroEntrega');
    const ruaEntregaDiv = document.getElementById('ruaEntrega');
    const numeroEntregaDiv = document.getElementById('numeroEntrega');
    const complementoEntregaDiv = document.getElementById('complementoEntrega');
    const taxaEntregaDiv = document.getElementById('taxaEntrega');
    const calcDuracao = document.getElementById('calcDuracao');

    retiradaRadio.addEventListener('change', () => {
        if (retiradaRadio.checked) {
            enderecoEntregaDiv.classList.add('hidden');
            bairroEntregaDiv.classList.add('hidden');
            ruaEntregaDiv.classList.add('hidden');
            numeroEntregaDiv.classList.add('hidden');
            complementoEntregaDiv.classList.add('hidden');
            taxaEntregaDiv.classList.add('hidden');
            calcDuracao.classList.add('hidden');

        }
    });

    entregaRadio.addEventListener('change', () => {
        if (entregaRadio.checked) {
            enderecoEntregaDiv.classList.remove('hidden');
            bairroEntregaDiv.classList.remove('hidden');
            ruaEntregaDiv.classList.remove('hidden');
            numeroEntregaDiv.classList.remove('hidden');
            complementoEntregaDiv.classList.remove('hidden');
            taxaEntregaDiv.classList.remove('hidden');
            calcDuracao.classList.remove('hidden');
        }
    });

    const cerveja = document.getElementById('cerveja');
    const options5 = document.getElementById('options-5');
    cerveja.addEventListener('click', function () {
        options5.classList.toggle('hidden');
    })
    const agua = document.getElementById('agua');
    const options4 = document.getElementById('options-4');
    agua.addEventListener('click', function () {
        options4.classList.toggle('hidden');
    })
    const doisL = document.getElementById('2l');
    const options3 = document.getElementById('options-3');
    doisL.addEventListener('click', function () {
        options3.classList.toggle('hidden');
    })
    const seiscentosMl = document.getElementById('600ml');
    const options2 = document.getElementById('options-2');
    seiscentosMl.addEventListener('click', function () {
        options2.classList.toggle('hidden');
    })
    const lata = document.getElementById('lata');
    const options1 = document.getElementById('options-1');
    lata.addEventListener('click', function () {
        options1.classList.toggle('hidden');
    })

    document.querySelectorAll('.bebida-size button').forEach(button => {
        button.addEventListener('click', function () {
            var parentId = this.parentNode.id;
            var optionsDiv = document.getElementById('options-' + parentId.substr(-1));
            optionsDiv.classList.remove('hidden');
        });
    });

    function addToCart(bebidaId, bebidaName, bebidaPrice) {
        const existingItem = cartItems.find(item => item.id === bebidaId);

        if (existingItem) {
            existingItem.quantity++;
            const existingLi = document.querySelector(`#cartItems li[data-id="${bebidaId}"]`);
            existingLi.querySelector('.item-text').textContent = `${existingItem.nome} - R$${existingItem.preco.toFixed(2)}`;
        } else {
            const item = {
                id: bebidaId,
                nome: bebidaName,
                preco: parseFloat(bebidaPrice),
                quantity: 1
            };
            cartItems.push(item);
            console.log(cartItems);
            const li = document.createElement('li');
            li.setAttribute('data-id', bebidaId);

            const itemText = document.createElement('span');
            itemText.classList.add('item-text');
            itemText.textContent = `${item.nome} - R$${item.preco.toFixed(2)}`;

            const botaoMenos = document.createElement('button');
            botaoMenos.innerHTML = '<img class="ml-4" src="./assets/menos.png">';
            const inputQtde = document.createElement('input');
            inputQtde.className = 'ml-2 w-5 text-center';
            inputQtde.type = 'text';
            inputQtde.size = 2;
            inputQtde.maxLength = 2;
            inputQtde.value = item.quantity;
            const botaoMais = document.createElement('button');
            botaoMais.innerHTML = '<img class="ml-2" src="./assets/mais.png">';

            botaoMenos.addEventListener('click', function () {
                if (item.quantity > 1) {
                    item.quantity--;
                    inputQtde.value = item.quantity;
                    totalCartPrice -= item.preco;
                    totalPriceElement.textContent = `Total: R$ ${totalCartPrice.toFixed(2)}`;
                    itemText.textContent = `${item.nome} - R$${item.preco.toFixed(2)}`;
                } else {
                    totalCartPrice -= item.preco;
                    totalPriceElement.textContent = `Total: R$ ${totalCartPrice.toFixed(2)}`;
                    cartItems = cartItems.filter(cartItem => cartItem.id !== item.id);
                    li.remove();
                }
            });

            botaoMais.addEventListener('click', function () {
                item.quantity++;
                inputQtde.value = item.quantity;
                totalCartPrice += item.preco;
                totalPriceElement.textContent = `Total: R$ ${totalCartPrice.toFixed(2)}`;
                itemText.textContent = `${item.nome} - R$${item.preco.toFixed(2)}`;
            });

            inputQtde.addEventListener('input', function () {
                const newQuantity = parseInt(inputQtde.value);
                if (!isNaN(newQuantity) && newQuantity > 0) {
                    const difference = newQuantity - item.quantity;
                    item.quantity = newQuantity;
                    totalCartPrice += difference * item.preco;
                    totalPriceElement.textContent = `Total: R$ ${totalCartPrice.toFixed(2)}`;
                    itemText.textContent = `${item.nome} - R$${item.preco.toFixed(2)}`;
                } else if (newQuantity === 0) {
                    totalCartPrice -= item.quantity * item.preco;
                    totalPriceElement.textContent = `Total: R$ ${totalCartPrice.toFixed(2)}`;
                    cartItems = cartItems.filter(cartItem => cartItem.id !== item.id);
                    li.remove();
                } else {
                    inputQtde.value = item.quantity;
                }
            });

            li.appendChild(itemText);
            li.appendChild(botaoMenos);
            li.appendChild(inputQtde);
            li.appendChild(botaoMais);
            document.getElementById('cartItems').appendChild(li);
        }

        totalCartPrice += parseFloat(bebidaPrice);
        totalPriceElement.textContent = `Total: R$ ${totalCartPrice.toFixed(2)}`;

        alertAddBebida();

    }

    document.querySelectorAll('[id^=options] button').forEach(button => {
        button.addEventListener('click', function () {
            var bebidaId = this.getAttribute('data-bebida-id');
            var bebidaName = this.getAttribute('data-bebida-name');
            var bebidaPrice = this.getAttribute('data-bebida-price');
            addToCart(bebidaId, bebidaName, bebidaPrice);
        });
    })

    function alertAddBebida() {
        Toastify({
            text: `Bebida adicionada ao carrinho!`,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "left",
            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
            className: "info",
            stopOnFocus: true
        }).showToast();
    }


    document.getElementById('cartForm').addEventListener('submit', function (event) {
        const entregaOptions = document.getElementsByName('forma_entrega');
        let entregaSelected = false;

        for (let i = 0; i < entregaOptions.length; i++) {
            if (entregaOptions[i].checked) {
                entregaSelected = true;
                break;
            }
        }

        if (!entregaSelected) {
            event.preventDefault();
            alert('Por favor, selecione uma forma de entrega.');
        }
    });

    document.getElementById('cartForm').addEventListener('submit', function (event) {

        const pagamentoOptions = document.getElementsByName('forma_pagamento');
        let pagamentoSelected = false;

        for (let i = 0; i < pagamentoOptions.length; i++) {
            if (pagamentoOptions[i].checked) {
                pagamentoSelected = true;
                break;
            }
        }

        if (!pagamentoSelected) {
            event.preventDefault();
            alert('Por favor, selecione uma forma de pagamento.');
        }
    });
});

function checkRestaurantOpen() {
    const data = new Date();
    const hora = data.getHours();
    const minutos = data.getMinutes();

    const totalMinutos = hora * 60 + minutos;

    const horaAbertura = 17 * 60 + 30;
    const horaFechamento = 23 * 60 + 30;

    return totalMinutos >= horaAbertura && totalMinutos < horaFechamento;
}

const spanItem = document.getElementById("date-span");
const isOpen = checkRestaurantOpen();

if (isOpen) {
    spanItem.classList.remove("bg-red-500");
    spanItem.classList.add("bg-green-500")
} else {
    spanItem.classList.remove("bg-green-500");
    spanItem.classList.add("bg-red-500")
}


const enderecoPizzaria = 'R. Francisco Vahldieck, 236 - Fortaleza, Blumenau - SC, 89056-000'; // Endereço fixo da pizzaria

function buscaEndereco(cep) {
    if (cep.length == 8) {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "https://viacep.com.br/ws/" + cep + "/json/",
            success: function (data) {
                if (!data.erro) {

                    document.getElementById('bairro').value = data.bairro;
                    document.getElementById('rua').value = data.logradouro;

                    const enderecoCliente = `${data.logradouro}, ${data.bairro}, ${data.localidade}, ${data.uf}`;

                    calcularTaxaEntrega(enderecoCliente);
                }
            }

        });
    }
}
function calcularTaxaEntrega(enderecoCliente) {
    $.ajax({
        url: 'http://localhost:8066/pizzariaDomJoaoWeb/taxaEntrega.php',
        method: 'GET',
        data: {
            enderecoCliente: enderecoCliente,
            enderecoPizzaria: enderecoPizzaria
        },
        success: function (response) {
            if (response.status === 'success') {
                console.log('Distância: ' + response.distanciaMetros + ' metros');
                console.log('Taxa de entrega: R$ ' + response.taxaEntrega);
                console.log('Duração estimada: ' + response.duracaoMinutos + ' minutos');

                document.getElementById('calcTaxaEntrega').value = `${response.taxaEntrega}`;

                document.getElementById('calcTempoDuracao').value = `${response.duracaoMinutos} minutos`;
            } else {
                console.error(response.message);
            }
        },
        error: function (err) {
            console.error('Erro na requisição: ', err);
        }
    });
}



function fecha() {
    document.getElementById("cart-modal").style.display = "none"
    document.getElementById('corpo').style.position = "absolute"
}


document.addEventListener("DOMContentLoaded", function () {

    document.getElementById('menuButton').addEventListener('click', function () {
        const menu = document.getElementById('menu');
        menu.classList.toggle('hidden');
    });

    window.addEventListener('click', function (event) {
        const menu = document.getElementById('menu');
        const button = document.getElementById('menuButton');

        if (!button.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });

});

function gerarQRCode() {

    const pixCode = 'assets/Untitled.png';

    $('#qrcode').empty();

    $('#qrcode').qrcode({
        text: pixCode,
        width: 200,
        height: 200
    });
}


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