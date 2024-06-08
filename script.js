document.addEventListener('DOMContentLoaded', function() {
  const step1 = document.getElementById('step1');
  const step2 = document.getElementById('step2');
  const step3 = document.getElementById('step3');
  const cart = document.getElementById('cartItems');

  let pizzaSize = '';
  let pizzaBorder = '';
  let pizzaFlavors = [];
  let maxFlavors = 0;

  // Verifique se os botões estão sendo selecionados corretamente
  const pizzaSizeButtons = document.querySelectorAll('.pizza-size');
  console.log('Pizza size buttons:', pizzaSizeButtons);

  // Tamanho da Pizza
  pizzaSizeButtons.forEach(button => {
      button.addEventListener('click', function() {
          pizzaSize = this.dataset.size;
          maxFlavors = parseInt(this.dataset.sabores);
          step1.classList.add('hidden');
          step2.classList.remove('hidden');
          console.log('Selected size:', pizzaSize);
          console.log('Max flavors:', maxFlavors);
      });
  });

  // Borda da Pizza
  document.querySelectorAll('.pizza-border').forEach(button => {
      button.addEventListener('click', function() {
          pizzaBorder = this.dataset.border;
          step2.classList.add('hidden');
          step3.classList.remove('hidden');
      });
  });

  // Sabores da Pizza
  document.querySelectorAll('.pizza-flavor').forEach(button => {
      button.addEventListener('click', function() {
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

  // Adicionar ao Carrinho
  document.getElementById('addToCart').addEventListener('click', function() {
      if (pizzaFlavors.length > 0) {
          const li = document.createElement('li');
          li.textContent = `Pizza ${pizzaSize} com borda ${pizzaBorder} e sabores: ${pizzaFlavors.join(', ')}`;
          cart.appendChild(li);

          // Resetar seleção
          step3.classList.add('hidden');
          step1.classList.remove('hidden');
          pizzaSize = '';
          pizzaBorder = '';
          pizzaFlavors = [];
          maxFlavors = 0;
          document.querySelectorAll('.pizza-flavor').forEach(button => button.classList.remove('bg-green-300'));
      } else {
          alert('Por favor, selecione pelo menos um sabor.');
      }
  });
});