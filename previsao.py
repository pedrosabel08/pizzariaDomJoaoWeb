import mysql.connector

# Configurar conexão com o MySQL
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="bd_pizzaria"
)
cursor = conn.cursor()

# Pegar o ID do produto pelo console
produto_id = input("Digite o ID do produto: ")

# Buscar o consumo total desse produto
query_consumo = """
    SELECT 
        SUM(pp.quantidade) AS total_consumo
    FROM pizzas_produtos pp
    JOIN vendas_pizzas vp ON pp.pizza_id = vp.pizzas_idpizzas
    WHERE pp.produto_id = %s;
"""
cursor.execute(query_consumo, (produto_id,))
consumo_total = float(cursor.fetchone()[0] or 0)  # Converte para float

# Buscar a quantidade de dias de vendas registradas
query_dias = "SELECT COUNT(DISTINCT DATE(v.data_venda)) FROM vendas v;"
cursor.execute(query_dias)
dias_totais = cursor.fetchone()[0] or 1  # Evitar divisão por zero

# Calcular consumo médio diário
consumo_diario = consumo_total / dias_totais

# Pegar o estoque atual desse produto
query_estoque = "SELECT quantidade FROM produtos WHERE idprodutos = %s;"
cursor.execute(query_estoque, (produto_id,))
estoque_atual = float(cursor.fetchone()[0] or 0)  # Converte para float

# Calcular quantos dias o estoque durará
if consumo_diario > 0:
    dias_restantes = estoque_atual / consumo_diario
    print(f"📊 O estoque do produto {produto_id} durará aproximadamente {dias_restantes:.1f} dias.")
else:
    print("⚠️ Não há consumo registrado para esse produto.")

# Fechar conexão
cursor.close()
conn.close()
