document.addEventListener('DOMContentLoaded', function () {
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    const cart = document.getElementById('cartItems');

    let pizzaSize = '';
    let pizzaSizePrice = 0;
    let pizzaBorder = '';
    let pizzaBorderPrice = 0;
    let pizzaFlavors = [];
    let maxFlavors = 0;

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

            document.getElementById('total-price').textContent = `Total: R$ ${totalPrice.toFixed(2)}`;

            // Adicionar o item ao carrinho na interface do usuário
            document.getElementById('cartItems').appendChild(li);
            console.log(pizzaSize, pizzaBorder, pizzaFlavors, totalPrice);

            // Resetar seleção
            resetSelection();
            console.log("Itens do carrinho", cartItems);
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
});


