exemple hedha mbdaeyan kamel alih
<?php
require_once __DIR__ . '/../../config/Database.php';

class User {

    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // ✅ CREATE
    public function register($name, $email, $password){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users(name, email, password) VALUES(?,?,?)");
        return $stmt->execute([$name, $email, $hash]);
    }

    // ✅ READ
    public function findByEmail($email){
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id){
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ✅ UPDATE
    public function update($id, $name, $email){
        $stmt = $this->conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        return $stmt->execute([$name, $email, $id]);
    }

    // ✅ DELETE
    public function delete($id){
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}