import random
import mysql.connector
from datetime import datetime, timedelta

# Configurar conexão com o MySQL
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="bd_pizzaria"
)
cursor = conn.cursor()

# Definir intervalo de tempo (6 meses a partir de 01/01/2025)
data_inicio = datetime(2025, 1, 1)
data_fim = datetime(2025, 6, 1)
dias_totais = (data_fim - data_inicio).days + 1

# IDs fictícios para outros campos obrigatórios na tabela 'vendas'
cliente_id = 1  
forma_entrega_id = 1  
forma_pagamento_id = 1  
status_id = 1  

# Gerar vendas por dia
for i in range(dias_totais):
    data_venda = data_inicio + timedelta(days=i)
    
    # Gerar entre 20 e 50 vendas por dia
    num_vendas = random.randint(10, 30)  # Entre 20 e 50 vendas por dia

    for _ in range(num_vendas):
        total_venda = 0  

        # Inserir a venda do dia na tabela 'vendas' (para cada venda)
        cursor.execute("""
            INSERT INTO vendas (data_venda, total, cliente_id, forma_entrega_id, forma_pagamento_id, status_id)
            VALUES (%s, %s, %s, %s, %s, %s)
        """, (data_venda.strftime('%Y-%m-%d %H:%M:%S'), total_venda, cliente_id, forma_entrega_id, forma_pagamento_id, status_id))

        # Capturar o ID da venda recém-criada
        id_venda = cursor.lastrowid  

        # Gerar uma pizza para essa venda (uma pizza por venda)
        pizza_id = random.randint(1, 59)  # Sorteia um ID de pizza entre 1 e 74
        tamanho_id = random.randint(1, 3)  # Exemplo: tamanhos 1 a 3
        borda_id = random.randint(1, 5)  # Exemplo: bordas 1 a 5

        # 🔹 Verifica se há ingredientes suficientes para essa pizza
        try:
            cursor.execute("""
                SELECT pp.produto_id, p.nomeProduto, pp.quantidade, p.quantidade as estoque
                FROM pizzas_produtos pp
                JOIN produtos p ON pp.produto_id = p.idprodutos
                WHERE pp.pizza_id = %s
            """, (pizza_id,))
            
            ingredientes = cursor.fetchall()
            
            # 🔹 Verifica estoque e lista ingredientes insuficientes
            ingredientes_insuficientes = [
                (nome, qtd, estoque) for _, nome, qtd, estoque in ingredientes
                if int(qtd) > int(estoque)
            ]

            if ingredientes_insuficientes:
                # Se algum ingrediente estiver em falta, para a venda e não insere mais pizzas
                print(f"⚠️ Estoque insuficiente para a pizza {pizza_id}, venda cancelada.")
                for nome, qtd, estoque in ingredientes_insuficientes:
                    print(f"   ❌ Ingrediente: {nome} | Necessário: {qtd} | Em estoque: {estoque}")
                continue  # Ignora essa venda e vai para a próxima

            # Se não houve problemas de estoque, insere a pizza na venda
            cursor.execute("""
                INSERT INTO vendas_pizzas (vendas_idvendas, pizzas_idpizzas, tamanho_idtamanho, borda_idbordas_pizza)
                VALUES (%s, %s, %s, %s)
            """, (id_venda, pizza_id, tamanho_id, borda_id))

            # 🔹 Atualiza o estoque dos ingredientes
            for produto_id, _, qtd_usada, _ in ingredientes:
                cursor.execute("""
                    UPDATE produtos 
                    SET quantidade = quantidade - %s 
                    WHERE idprodutos = %s
                """, (int(qtd_usada), produto_id))

        except mysql.connector.Error as err:
            print(f"Erro ao executar consulta SQL: {err}")
            print(f"Consulta SQL: SELECT pp.produto_id, p.nome, pp.quantidade, p.quantidade as estoque FROM pizzas_produtos pp JOIN produtos p ON pp.produto_id = p.idprodutos WHERE pp.pizza_id = {pizza_id}")

    print(f"Vendas do dia {data_venda.strftime('%Y-%m-%d')} processadas.")

# Confirmar e fechar conexão
conn.commit()
cursor.close()
conn.close()

print("Processamento concluído!")
