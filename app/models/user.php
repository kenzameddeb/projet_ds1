<?php
require_once(__DIR__ . '/../../config/database.php');

class User {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    // Inscription
    public function register($name, $email, $password) {
        // Vérifier si l'email existe déjà
        $check = $this->db->prepare("SELECT id FROM users WHERE email = :email");
        $check->execute([':email' => $email]);
        if ($check->fetch()) {
            return false; // Email déjà utilisé
        }

        // Hachage sécurisé du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insertion dans la BD
        $sql = "INSERT INTO users (name, email, password) 
                VALUES (:name, :email, :password)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name'     => $name,
            ':email'    => $email,
            ':password' => $hashedPassword,
        ]);
    }

    // Connexion
    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifie si user existe et si le mot de passe est correct
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // Profil : données d'un utilisateur spécifique
    public function getById($id) {
        $sql = "SELECT id, name, email, created_at FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}