<?php
require_once __DIR__ . "/Database.php";

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Registrar nuevo usuario
    public function register($name, $lastname, $username, $email, $password)
    {
        // Encriptar contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, lastname, username, email, password) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->connect()->prepare($sql);
        return $stmt->execute([$name, $lastname, $username, $email, $passwordHash]);
    }

    // Verificar si usuario ya existe
    public function userExists($email)
    {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->execute([$email]);

        return $stmt->rowCount() > 0;
    }

    // Login
    public function login($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->execute([$email]);

        if ($stmt->rowCount() == 0) {
            return false;
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $user["password"])) {
            return $user;
        }

        return false;
    }
}
