document.getElementById('showSignup').addEventListener('click', function () {
    document.getElementById('login').classList.add('hidden');
    document.getElementById('signup').classList.remove('hidden');
});

document.getElementById('showLogin').addEventListener('click', function () {
    document.getElementById('signup').classList.add('hidden');
    document.getElementById('login').classList.remove('hidden');
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