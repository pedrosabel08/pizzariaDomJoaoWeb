const sidebar = document.querySelector('.sidebar');
const body = document.querySelector('body');
sidebar.addEventListener('mouseenter', function () {

    if (sidebar) {
        // Verifica se a sidebar tem a classe "mini"
        if (sidebar.classList.contains('mini')) {
            // Remove a classe "mini" e adiciona a classe "complete"
            sidebar.classList.remove('mini');

            sidebar.classList.add('complete');
        }
    }
});

sidebar.addEventListener('mouseleave', function () {

    if (sidebar) {
        // Verifica se a sidebar tem a classe "complete"
        if (sidebar.classList.contains('complete')) {
            // Remove a classe "complete" e adiciona a classe "mini"
            sidebar.classList.remove('complete');
            sidebar.classList.add('mini');
        }
    }
});