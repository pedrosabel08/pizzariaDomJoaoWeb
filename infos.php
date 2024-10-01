<?php
session_start();

include 'conexao.php';

if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.html");
    exit();
}


$cliente_id = $_SESSION['cliente_id'];

$query = "
    SELECT 
        c.nome,
        c.sobrenome,
        c.telefone,
        c.email,
        c.senha
    FROM 
        clientes c
    WHERE 
    c.idclientes = ?
";

// Prepara a consulta
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();


$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <title>Informações do Usuário</title>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <button id="voltar" onclick="window.location.href='index.php'">Voltar</button>

    <div class="w-full max-w-[1000px] p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-4xl mb-6 text-center">Informações:</h1>

        <form id="userForm" action="updateInfos.php" method="POST">
            <!-- Seção: Informações Básicas -->
            <fieldset class="mb-6">
                <legend class="text-2xl font-bold mb-4">Informações Básicas</legend>
                <div class="mb-4">
                    <h3 class="text-lg mb-2">Nome:</h3>
                    <input class="border border-black w-full p-2 rounded" type="text" name="nome" id="nome"
                        value="<?php echo htmlspecialchars($userData['nome']); ?>" required>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg mb-2">Sobrenome:</h3>
                    <input class="border border-black w-full p-2 rounded" type="text" name="sobrenome" id="sobrenome"
                        value="<?php echo htmlspecialchars($userData['sobrenome']); ?>" required>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg mb-2">Email:</h3>
                    <input class="border border-black w-full p-2 rounded" type="email" name="email" id="email"
                        value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg mb-2">Senha:</h3>
                    <input class="border border-black w-full p-2 rounded" type="text" name="senha" id="senha"
                        value="<?php echo htmlspecialchars($userData['senha']); ?>" required>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg mb-2">Telefone:</h3>
                    <input class="border border-black w-full p-2 rounded" type="text" name="telefone" id="telefone"
                        value="<?php echo htmlspecialchars($userData['telefone']); ?>" required>
                </div>
            </fieldset>

            <div class="mt-6">
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded w-full">Atualizar
                    informações</button>
            </div>
        </form>
    </div>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="./script/scriptUsuario.js"></script>
</body>

</html>