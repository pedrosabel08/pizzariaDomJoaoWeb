document.getElementById('showSignup').addEventListener('click', function () {
    document.getElementById('login').classList.add('hidden');
    document.getElementById('signup').classList.remove('hidden');
});

document.getElementById('showLogin').addEventListener('click', function () {
    document.getElementById('signup').classList.add('hidden');
    document.getElementById('login').classList.remove('hidden');
});

function exibeVerificacao() {
    let nome = document.getElementById('nome').value;
    let sobrenome = document.getElementById('sobrenome').value;
    let telefone = document.getElementById('telefone').value;
    let email = document.getElementById('emailCad').value;
    let senha = document.getElementById('senhaCad').value;
    let erros = '';

    if(nome == ''){
        erros += '\nNome deve ser informado!';
    }
    if(sobrenome == ''){
        erros += '\nSobrenome deve ser informado!';
    }
    if(telefone == ''){
        erros += '\nTelefone deve ser informado!';
    }
    if(email == ''){
        erros += '\nE-mail deve ser informado!';
    }
    if(senha == ''){
        erros += '\nSenha deve ser informada!';
    }
    if(erros != ''){
        alert(erros);
        return;
    }
    
    generateVerificationCode();

    const emailData = {
        to: email,
        subject: 'Código de verificação Pizza Control',
        text: ''+generatedCode
    };

    // Enviar e-mail através de uma requisição POST ao backend
    fetch('http://localhost:3000/send-email', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(emailData)
    })
    .then(response => response.text())
    .then(result => {
        console.log('Sucesso:', result);
        document.getElementById('signup').classList.add('hidden');
        document.getElementById('verify').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Erro:', error);
    });
    document.getElementById('signup').classList.add('hidden');
    document.getElementById('verify').classList.remove('hidden');
}

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

let generatedCode = null;
let expirationTime = null;

// Função para gerar um código de verificação aleatório de 6 dígitos
function generateVerificationCode() {
    generatedCode = Math.floor(100000 + Math.random() * 900000); // Gera um número de 6 dígitos
    expirationTime = new Date().getTime() + (10 * 60 * 1000); // Expira em 10 minutos
    console.log(`Código gerado: ${generatedCode}`);
}

function validateVerificationCode() {
    const userCode = document.getElementById('token').value;
    const currentTime = new Date().getTime();

    if (!generatedCode) {
        document.getElementById('statusToken').innerText = "Nenhum código gerado ainda!";
        return;
    }

    if (currentTime > expirationTime) {
        document.getElementById('statusToken').innerText = "O código expirou!";
        return;
    }

    if (userCode == generatedCode) {
        document.getElementById('statusToken').innerText = "Código validado com sucesso!";
        document.getElementById('formCadastro').submit();
    } else {
        document.getElementById('statusToken').innerText = "Código inválido!";
    }
}