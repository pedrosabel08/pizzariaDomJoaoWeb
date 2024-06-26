document.addEventListener('DOMContentLoaded', function () {
    const quantidade = {
        'Temperos': {
            'Molho de tomate': 18000,
            'Alho': 300,
            'Alho Frito': 1500,
            'Orégano': 600,
            'Manjericão': 300,
            'Molho de pimenta': 1500,
            'Pimenta biquinho': 600,
            'Canela': 300,
            'Geleia de pimenta': 900
        },
        'Queijos': {
            'Queijo Mussarela': 45000,
            'Parmesão': 6000,
            'Catupiry': 4500,
            'Provolone': 3000,
            'Cream Cheese': 1500,
            'Cheddar': 1500,
            'Queijo brie': 1500,
            'Gorgonzola': 1500
        },
        'Carnes': {
            'Bacon': 6000,
            'Carne Moída': 9000,
            'Calabresa': 15000,
            'Chester': 3000,
            'Frango': 15000,
            'Lombo': 6000,
            'Peito de Peru': 4500,
            'Presunto': 9000,
            'Salame Italiano': 3000,
            'Pepperoni': 6000,
            'Coração': 3000,
            'Tiras de Carne': 6000,
            'Linguiça Blumenau': 3000,
            'Strogonoff de carne': 3000,
            'Strogonoff de frango': 3000,
            'Camarão': 1500,
            'Mignon': 1500,
            'Filé': 1500,
            'Carne seca desfiada': 1500,
            'Costelinha suína desfiada': 1500
        },
        'Enlatados': {
            'Atum': 4500,
            'Milho': 300,
            'Palmito': 3000,
            'Azeitona': 1500,
            'Azeitonas pretas': 1500,
            'Champignon': 1500
        },
        'Vegetais': {
            'Cebola': 6000,
            'Brócolis': 3000,
            'Tomate': 15000,
            'Tomate seco': 3000,
            'Rúcula': 1500,
            'Morango': 1500,
            'Banana': 1500,
            'Cereja': 1500
        },
        'Outros': {
            'Óleo': 3000,
            'Barbecue': 3000,
            'Creme de Leite': 6000,
            'Doritos': 1500,
            'Granulado': 600,
            'Confetti': 600,
            'Creme de coco': 1500,
            'Goiabada': 1500,
            'Sonho de valsa': 1500,
            'Capuccino': 1500,
            'Doce de leite': 1500,
            'Amendoim': 1500,
            'Coco ralado': 1500,
            'Marshmallow': 1500,
            'Leite Condensado': 6000,
            'Chocolate Preto': 3000,
            'Chocolate Branco': 3000
        }
    };

    // Obtendo a lista de produtos
    const produtosLista = document.getElementById('produtos-lista');

    // Iterando sobre cada categoria e seus produtos
    Object.keys(quantidade).forEach(categoria => {
        Object.keys(quantidade[categoria]).forEach(produto => {
            const quantidadeTotal = quantidade[categoria][produto];
            const vintePorcento = quantidadeTotal * 0.2; // 20% da quantidade total

            // Criando elemento para o produto
            const produtoElement = document.createElement('div');
            produtoElement.textContent = `${produto}: ${quantidadeTotal}`;

            // Verificando se a quantidade restante está abaixo de 20%
            if (quantidadeTotal <= vintePorcento) {
                produtoElement.style.backgroundColor = 'yellow'; // Mudança de cor de fundo
            }

            // Adicionando o produto à lista de produtos
            produtosLista.appendChild(produtoElement);
        });
    });
});