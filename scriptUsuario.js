const urlParams = new URLSearchParams(window.location.search);
const status = urlParams.get('status');
const message = urlParams.get('message');

if (status && message) {
    let backgroundColor = "#10B981"; // Cor verde padrão para sucesso
    if (status === 'error') {
        backgroundColor = "#EF4444"; // Cor vermelha para erro
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