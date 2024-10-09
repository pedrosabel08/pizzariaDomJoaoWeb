const express = require('express');
const nodemailer = require('nodemailer');
const sendgridTransport = require('nodemailer-sendgrid');
const cors = require('cors');
const bodyParser = require('body-parser');

const app = express();
app.use(cors());
app.use(bodyParser.json());

// Rota para testar se o servidor está funcionando
app.get('/', (req, res) => {
    res.send('Servidor está funcionando! Use o endpoint POST /send-email para enviar e-mails.');
});

// Rota para enviar e-mail usando SendGrid
app.post('/send-email', (req, res) => {
    const { to, subject, text } = req.body;

    // Configurando o Nodemailer para usar SendGrid
    let transporter = nodemailer.createTransport(
        sendgridTransport({
            apiKey: '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'  // Substitua com a sua API Key do SendGrid
        })
    );

    // Opções do e-mail
    let mailOptions = {
        from: 'euarthurhp@gmail.com',  // Substitua com um remetente válido (verificado no SendGrid)
        to: to,  // Destinatário do e-mail
        subject: subject,  // Assunto do e-mail
        text: text  // Corpo do e-mail
    };

    transporter.sendMail(mailOptions, (error, info) => {
        if (error) {
            return res.status(500).send('Erro ao enviar e-mail: ' + error.toString());
        }
        res.status(200).send('E-mail enviado: ' + info.response);
    });
});

// Iniciando o servidor na porta 3000
app.listen(3000, () => {
    console.log('Servidor rodando na porta 3000');
});
