<?php
class Conexao
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "bd_pizzaria";
    private $port = "3306";
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname, $this->port);

        if ($this->conn->connect_error) {
            die("Conexão falhou: " . $this->conn->connect_error);
        }
    }

    public function getConn()
    {
        return $this->conn;
    }

    public function fecharConexao()
    {
        $this->conn->close();
    }
}

// Exemplo de uso:
$conexao = new Conexao();
$conn = $conexao->getConn();

?>