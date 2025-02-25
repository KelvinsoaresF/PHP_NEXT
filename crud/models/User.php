<?php
class User
{
    private $conn;

    public $id;
    public $name;
    public $email;
    public $password;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        try {
            $this->conn->query("USE crud");
    
            $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $this->conn->prepare($query);
    
            // Gera o hash da senha
        
    
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);
    
            if ($stmt->execute()) {
                http_response_code(201);
                return json_encode(["success" => "Usuário criado com sucesso"]);
            } else {
                http_response_code(500);
                return json_encode(["error" => "Erro ao criar usuário"]);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => "Erro no banco de dados", "details" => $e->getMessage()]);
        }
    }
    public function findByEmail()
    {
        try {
            $this->conn->query("USE crud");
    
            $query = "SELECT id, name, email, password FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();
    
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user) {
                return $user; // Retorna os dados do usuário

            } else {
                return null; // Retorna null se o usuário não for encontrado
            }
        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados: " . $e->getMessage());
        }
    }

    public function emailExists($email) {
        $this->conn->query("USE crud");
        $query = "SELECT id FROM users WHERE email = :email";  // Corrigido de :emal para :email
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);  // Corrigido aqui também
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }
}
