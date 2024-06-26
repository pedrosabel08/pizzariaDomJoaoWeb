document.getElementById('show-signup').addEventListener('click', function () {
    document.getElementById('login-form').classList.add('hidden');
    document.getElementById('signup-form').classList.remove('hidden');
});

document.getElementById('show-login').addEventListener('click', function () {
    document.getElementById('login-form').classList.remove('hidden');
    document.getElementById('signup-form').classList.add('hidden');
});

document.addEventListener('DOMContentLoaded', function () {

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

});