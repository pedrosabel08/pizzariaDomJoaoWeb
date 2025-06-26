<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Sidebar</title>
</head>

<body>
    <div class="sidebar mini">
        <button id="menuButton">
            <i class="fa-solid fa-bars"></i>
        </button>
        <ul>
            <ul class="division">
                <label for="">Insights</label>
                <li><a title="Página Principal" href="http://localhost:8066/PizzariaDomJoaoWeb/Estoque/produtos.html"><i class="fas fa-home"></i><span> Página Principal</span></a></li>
            </ul>

            <ul class="division">
                <label for="">Estoque</label>
                <li><a title="Sabores" href="http://localhost:8066/PizzariaDomJoaoWeb/Estoque/Pizza/"><i class="fa-solid fa-user"></i><span>Sabores</span></a></li>
                <li><a title="Estoque Geral" href="http://localhost:8066/PizzariaDomJoaoWeb/Estoque/IngredientesBebidas"><i class="fas fa-list"></i><span>Estoque Geral</span></a></li>
                <li><a title="Saída de estoque" href="http://localhost:8066/PizzariaDomJoaoWeb/Estoque/Saida"><i class="fas fa-check"></i><span>Saída Estoque</span></a></li>
                <li><a title="Fornecedor" href="http://localhost:8066/PizzariaDomJoaoWeb/Fornecedor"><i class="fas fa-check"></i><span>Fornecedor</span></a></li>
            </ul>

            <ul class="division">
                <label for="">Pedidos</label>
                <li><a title="Pedidos" href="http://localhost:8066/PizzariaDomJoaoWeb/Gerente/"><i class="fas fa-shopping-cart"></i><span>Pedidos</span></a></li>
                <li><a title="Relatório de Vendas" href="http://localhost:8066/PizzariaDomJoaoWeb/Relatorio/"><i class="fas fa-file-alt"></i><span>Relatório de Pedidos</span></a></li>
                <li><a title="Tela Cozinha" href="http://localhost:8066/PizzariaDomJoaoWeb/telaCozinha/"><i class="fas fa-chart-line"></i><span>Tela Cozinha</span></a></li>
            </ul>

            <ul class="division">
                <label for="">Configurações</label>
                <li><a title="Configurações" href="http://localhost:8066/PizzariaDomJoaoWeb/Configuracoes/"><i class="fas fa-cog"></i><span>Configurações</span></a></li>
                <li><a title="Sair" href="http://localhost:8066/PizzariaDomJoaoWeb/login.html"><i class="fas fa-sign-out-alt"></i><span>Sair</span></a></li>
            </ul>



    </div>
</body>

</html>