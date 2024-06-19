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
        console.log(totalCartPrice)
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

        cartItems.forEach((item, index) => {
            // Adicionar inputs para cada atributo do item
            for (const key in item) {
                if (item.hasOwnProperty(key)) {
                    // Incluir o ID da bebida se o atributo for 'bebidaId'
                    if (key === "bebidaId") {
                        const inputBebidaId = document.createElement('input');
                        inputBebidaId.type = 'hidden';
                        inputBebidaId.name = `cartItems[${index}][${key}]`;
                        inputBebidaId.value = item[key];
                        cartInputs.appendChild(inputBebidaId);
                    } else if (key === "flavors") {
                        item[key].forEach((flavor, flavorIndex) => {
                            // Criar inputs para os sabores (caso existam)
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = `cartItems[${index}][${key}][${flavorIndex}]`;
                            input.value = flavor;
                            cartInputs.appendChild(input);
                        });
                    } else {
                        // Criar inputs para outros atributos do item
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `cartItems[${index}][${key}]`;
                        input.value = item[key];
                        cartInputs.appendChild(input);
                    }
                }
            }
        });

        // Adicionar input para o preço total usando totalCartPrice
        const totalPriceInput = document.createElement('input');
        totalPriceInput.type = 'hidden';
        totalPriceInput.name = 'total_price';
        totalPriceInput.value = totalCartPrice.toFixed(2); // Usando totalCartPrice
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

    const retiradaRadio = document.getElementById('retirada');
    const entregaRadio = document.getElementById('entrega');
    const enderecoEntregaDiv = document.getElementById('enderecoEntrega');

    retiradaRadio.addEventListener('change', () => {
        if (retiradaRadio.checked) {
            enderecoEntregaDiv.classList.add('hidden');
        }
    });

    entregaRadio.addEventListener('change', () => {
        if (entregaRadio.checked) {
            enderecoEntregaDiv.classList.remove('hidden');
        }
    });

    const cerveja = document.getElementById('cerveja');
    const options5 = document.getElementById('options-5');
    cerveja.addEventListener('click', function () {
        options5.classList.remove('hidden');
    })
    const agua = document.getElementById('agua');
    const options4 = document.getElementById('options-4');
    agua.addEventListener('click', function () {
        options4.classList.remove('hidden');
    })
    const doisL = document.getElementById('2l');
    const options3 = document.getElementById('options-3');
    doisL.addEventListener('click', function () {
        options3.classList.remove('hidden');
    })
    const seiscentosMl = document.getElementById('600ml');
    const options2 = document.getElementById('options-2');
    seiscentosMl.addEventListener('click', function () {
        options2.classList.remove('hidden');
    })
    const lata = document.getElementById('lata');
    const options1 = document.getElementById('options-1');
    lata.addEventListener('click', function () {
        options1.classList.remove('hidden');
    })

    document.querySelectorAll('.bebida-size button').forEach(button => {
        button.addEventListener('click', function () {
            // Obtenha o id do botão pai (lata, 600ml, 2l, agua, cerveja)
            var parentId = this.parentNode.id;
            // Encontre o div de opções correspondente com base no id do botão pai
            var optionsDiv = document.getElementById('options-' + parentId.substr(-1));
            // Exiba as opções relacionadas a esse botão
            optionsDiv.classList.remove('hidden');
        });
    });

    // Função para adicionar um item ao carrinho
    function addToCart(bebidaId, bebidaName, bebidaPrice) {
        // Verificar se a bebida já está no carrinho
        const existingItem = cartItems.find(item => item.id === bebidaId);

        if (existingItem) {
            // Se a bebida já está no carrinho, incrementar a quantidade
            existingItem.quantity++;
            // Atualizar o texto do li existente no carrinho
            const existingLi = document.querySelector(`#cartItems li[data-id="${bebidaId}"]`);
            existingLi.textContent = `${existingItem.nome} - R$${existingItem.preco.toFixed(2)} - ${existingItem.quantity}`;
        } else {
            // Se a bebida não está no carrinho, adicionar como um novo item
            const item = {
                id: bebidaId,
                nome: bebidaName,
                preco: parseFloat(bebidaPrice),
                quantity: 1
            };
            // Adicione o item ao carrinho
            cartItems.push(item);
            console.log(cartItems);
            // Exiba o item no carrinho
            const li = document.createElement('li');
            li.textContent = `${item.nome} - R$${item.preco.toFixed(2)} - ${item.quantity}`;
            li.setAttribute('data-id', bebidaId); // Adicionar um atributo para identificação do item
            document.getElementById('cartItems').appendChild(li);
        }

        // Atualizar o preço total do carrinho
        totalCartPrice += parseFloat(bebidaPrice);
        totalPriceElement.textContent = `Total: R$ ${totalCartPrice.toFixed(2)}`;

        cartCounter.innerHTML = cartItems.length;
    }

    // Adicione um evento de clique a todos os botões dentro dos elementos de opções
    document.querySelectorAll('[id^=options] button').forEach(button => {
        button.addEventListener('click', function () {
            // Obtenha o ID, nome e preço da bebida deste botão usando os atributos de dados (data)
            var bebidaId = this.getAttribute('data-bebida-id');
            var bebidaName = this.getAttribute('data-bebida-name');
            var bebidaPrice = this.getAttribute('data-bebida-price');
            // Adicione a bebida ao carrinho
            addToCart(bebidaId, bebidaName, bebidaPrice);
        });
    });
});


