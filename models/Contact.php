<?php
require_once __DIR__ . "/Database.php";

class Contact {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

   
    public function create($user_id, $name, $phone, $email, $notes) {
        $sql = "INSERT INTO contacts (user_id, name, phone, email, notes)
                VALUES (:user_id, :name, :phone, :email, :notes)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":user_id" => $user_id,
            ":name" => $name,
            ":phone" => $phone,
            ":email" => $email,
            ":notes" => $notes
        ]);
    }

  
    public function getAll($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM contacts WHERE user_id = :user_id ORDER BY id DESC");
        $stmt->execute([":user_id" => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function get($id) {
        $stmt = $this->conn->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function update($id, $name, $phone, $email, $notes) {
        $sql = "UPDATE contacts SET name = :name, phone = :phone, email = :email, notes = :notes WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":name" => $name,
            ":phone" => $phone,
            ":email" => $email,
            ":notes" => $notes,
            ":id" => $id
        ]);
    }

    // DELETE
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM contacts WHERE id = :id");
        return $stmt->execute([":id" => $id]);
    }
}
