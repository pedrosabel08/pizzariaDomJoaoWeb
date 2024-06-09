<?php
class Conexao
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "bd_pizzaria";
    private $conn;

    // Construtor
    public function __construct()
    {
        // Cria uma conexão
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        // Verifica a conexão
        if ($this->conn->connect_error) {
            die("Conexão falhou: " . $this->conn->connect_error);
        }
    }

    // Retorna a conexão
    public function getConn()
    {
        return $this->conn;
    }

    // Fecha a conexão
    public function fecharConexao()
    {
        $this->conn->close();
    }
}

// Exemplo de uso:
$conexao = new Conexao();
$conn = $conexao->getConn();

// Agora você pode usar $conn para executar consultas SQL
?>