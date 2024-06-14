document.addEventListener('DOMContentLoaded', function () {
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    const step4 = document.getElementById('step4');
    const cart = document.getElementById('cartItems');
    const cartBtn = document.getElementById("cart-btn");
    const cartModal = document.getElementById("cart-modal")
    const closeModalBtn = document.getElementById("close-modal-btn")
    const cartCounter = document.getElementById("cart-count")
    const totalPriceElement = document.getElementById('total-price');
    const modalBebidas = document.getElementById('stepBebidas');

    let pizzaSize = '';
    let pizzaSizePrice = 0;
    let pizzaBorder = '';
    let pizzaBorderPrice = 0;
    let pizzaFlavors = [];
    let maxFlavors = 0;
    let totalCartPrice = 0;


    // Abrir o modal do carrinho
    cartBtn.addEventListener("click", function () {
        cartModal.style.display = "flex"
    })

    // Fechar o modal quando clicar fora

    cartModal.addEventListener("click", function (event) {
        if (event.target === cartModal) {
            cartModal.style.display = "none"
        }
    })

    closeModalBtn.addEventListener("click", function () {
        cartModal.style.display = "none"
    })

    // Tamanho da Pizza
    document.querySelectorAll('.pizza-size').forEach(button => {
        button.addEventListener('click', function () {
            pizzaSize = this.dataset.size;
            pizzaSizePrice = parseFloat(this.dataset.price);
            maxFlavors = parseInt(this.dataset.sabores);
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            document.getElementById('selectedSizeStep2').textContent = `Tamanho escolhido: ${pizzaSize}`;
            document.getElementById('backToStep1').classList.remove('hidden');
            console.log('Selected size:', pizzaSize);
            console.log('Max flavors:', maxFlavors);
        });
    });

    // Borda da Pizza
    document.querySelectorAll('.pizza-border').forEach(button => {
        button.addEventListener('click', function () {
            pizzaBorder = this.dataset.border;
            pizzaBorderPrice = parseFloat(this.dataset.borderPrice);
            step2.classList.add('hidden');
            step3.classList.remove('hidden');
            document.getElementById('selectedSizeStep3').textContent = `Tamanho escolhido: ${pizzaSize}`;
            document.getElementById('selectedBorderStep3').textContent = `Borda escolhida: ${pizzaBorder}`;
            document.getElementById('backToStep2').classList.remove('hidden');
        });
    });

    // Sabores da Pizza
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
    // Variável global para armazenar os itens do carrinho
    let cartItems = [];

    //Bebidas 
    modalBebidas.addEventListener('click', function () {
        step4.classList.remove('hidden');
        step3.classList.add('hidden');
    });

    function selectBebidas() {
        var addBebida = confirm('Deseja adicionar bebida?');
        if (addBebida) {
            step4.classList.remove('hidden');
            step1.classList.add('hidden');
        }
    }

    document.getElementById('addToCart').addEventListener('click', function () {
        if (pizzaFlavors.length > 0) {
            const totalPrice = pizzaSizePrice + pizzaBorderPrice;

            // Criar um objeto para representar o item do carrinho
            const cartItem = {
                id: Date.now(), // Usar timestamp como ID único
                size: pizzaSize,
                border: pizzaBorder,
                flavors: pizzaFlavors, // Enviar como array
                price: totalPrice
            };

            // Adicionar o item ao carrinho
            cartItems.push(cartItem);

            // Criar o elemento li para exibir no carrinho
            const li = document.createElement('li');
            li.textContent = `Pizza ${pizzaSize} com borda ${pizzaBorder}, sabores: ${pizzaFlavors.join(', ')}, Preço: R$ ${totalPrice.toFixed(2)}`;

            // Atualizar o total acumulado
            totalCartPrice += totalPrice;

            // Exibir o total acumulado
            totalPriceElement.textContent = `Total: R$ ${totalCartPrice.toFixed(2)}`;

            // Adicionar o item ao carrinho na interface do usuário
            document.getElementById('cartItems').appendChild(li);

            cartCounter.innerHTML = cartItems.length;

            // Resetar seleção
            resetSelection();
            console.log("Itens do carrinho", cartItems);

            selectBebidas();

        } else {
            alert('Por favor, selecione pelo menos um sabor.');
        }
    });

    function resetSelection() {
        step3.classList.add('hidden');
        step1.classList.remove('hidden');
        pizzaSize = '';
        pizzaSizePrice = 0;
        pizzaBorder = '';
        pizzaBorderPrice = 0;
        pizzaFlavors = [];
        maxFlavors = 0;
        document.querySelectorAll('.pizza-flavor').forEach(button => button.classList.remove('bg-green-300'));
        document.getElementById('backToStep2').classList.add('hidden');
    }

    document.getElementById('stepBebidas').addEventListener('click', function () {
        // Ao clicar no botão "Bebidas", perguntar se o usuário deseja adicionar bebidas
        selectBebidas();
    });

    // Botões para voltar
    document.getElementById('backToStep1').addEventListener('click', function () {
        step2.classList.add('hidden');
        step1.classList.remove('hidden');
        this.classList.add('hidden');
    });

    document.getElementById('backToStep2').addEventListener('click', function () {
        step3.classList.add('hidden');
        step2.classList.remove('hidden');
        this.classList.add('hidden');
    });

    document.getElementById('finalizeSale').addEventListener('click', function () {
        // Converter os detalhes do carrinho para uma string JSON
        const cartItemsJSON = JSON.stringify(cartItems);

        // Adicionar os detalhes do carrinho ao campo hidden do formulário
        document.getElementById('cartItems').value = cartItemsJSON;
    });

    document.getElementById('finalizeSale').addEventListener('click', function (event) {
        event.preventDefault(); // Evitar envio automático do formulário

        // Adicionar inputs para cada item do cartItems
        const cartInputs = document.getElementById('cartInputs');
        cartInputs.innerHTML = ''; // Limpar inputs existentes

        let totalPrice = 0;

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
            totalPrice += item.price;
        });

        // Adicionar input para o preço total
        const totalPriceInput = document.createElement('input');
        totalPriceInput.type = 'hidden';
        totalPriceInput.name = 'total_price';
        totalPriceInput.value = totalPrice.toFixed(2);
        cartInputs.appendChild(totalPriceInput);

        console.log('Dados do formulário:', cartInputs);

        // Submeter o formulário
        document.querySelector('form').submit();
    });

    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const message = urlParams.get('message');

    if (status && message) {
        let backgroundColor = "#10B981"; // Verde para sucesso
        if (status === 'error') {
            backgroundColor = "#EF4444"; // Vermelho para erro
        }

        Toastify({
            text: decodeURIComponent(message.replace(/\+/g, ' ')),
            duration: 3000,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "right", // `left`, `center` or `right`
            stopOnFocus: true, // Prevents dismissing of toast on hover
            style: {
                background: backgroundColor,
            },
        }).showToast();
    }

    const params = new URLSearchParams(window.location.search);
    const nomeCliente = params.get('nome');

    if (nomeCliente) {
        const loginButton = document.getElementById('login');
        const greeting = document.getElementById('greeting');
        const clienteNomeSpan = document.getElementById('cliente-nome');
        const buttonSair = document.getElementById('button-sair')

        loginButton.style.display = 'none';
        clienteNomeSpan.textContent = nomeCliente;
        greeting.style.display = 'inline';
        buttonSair.classList.remove('hidden');
    }

});

const optionsData = {
    lata: [
        { name: 'Coca Cola', price: 'R$ 6,00' },
        { name: 'Guaraná', price: 'R$ 6,00' },
        { name: 'Sprite', price: 'R$ 6,00' },
        { name: 'Fanta', price: 'R$ 6,00' },
    ],
    '600ml': [
        { name: 'Coca Cola', price: 'R$ 8,00' },
        { name: 'Sprite', price: 'R$ 8,00' },
        { name: 'Fanta', price: 'R$ 8,00' },
    ],
    '2l': [
        { name: 'Coca Cola', price: 'R$ 15,00' },
        { name: 'Coca Cola Zero', price: 'R$ 15,00' },
        { name: 'Guaraná', price: 'R$ 12,00' },
        { name: 'Sprite', price: 'R$ 12,00' },
        { name: 'Fanta', price: 'R$ 12,00' },
    ],
    agua: [
        { name: 'Com gás', price: 'R$ 3,50' },
        { name: 'Sem gás', price: 'R$ 3,50' },
    ],
    cerveja: [
        { name: 'Budweiser', price: 'R$ 10,00' },
        { name: 'Heineken', price: 'R$ 10,00' },
    ]
};
function showOptions(category) {
    const optionsContainer = document.getElementById('options');
    optionsContainer.innerHTML = '';

    optionsData[category].forEach(option => {
        const optionDiv = document.createElement('div');
        optionDiv.classList.add('button', 'text-lg', 'p-2', 'border', 'border-gray-300', 'rounded-lg', 'bg-white', 'shadow-sm', 'hover:bg-green-500');
        optionDiv.textContent = `${option.name} - ${option.price}`;
        optionsContainer.appendChild(optionDiv);
    });
}

