<?php
class Challenge {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupérer tous les défis
    public function findAll() {
        $stmt = $this->pdo->query(
            "SELECT c.*, u.name as author_name 
             FROM challenges c 
             LEFT JOIN users u ON c.user_id = u.id 
             ORDER BY c.deadline ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un défi par son ID
    public function findById($id) {
        $stmt = $this->pdo->prepare(
            "SELECT c.*, u.name as author_name 
             FROM challenges c 
             LEFT JOIN users u ON c.user_id = u.id 
             WHERE c.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Créer un nouveau défi
    public function create($user_id, $title, $description, $category, $deadline, $image = null) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO challenges (user_id, title, description, category, deadline, image) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$user_id, $title, $description, $category, $deadline, $image]);
    }

    // Modifier un défi
    public function edit($id, $title, $description, $category, $deadline, $image = null) {
        $stmt = $this->pdo->prepare(
            "UPDATE challenges 
             SET title = ?, description = ?, category = ?, deadline = ?, image = ?
             WHERE id = ?"
        );
        return $stmt->execute([$title, $description, $category, $deadline, $image, $id]);
    }

    // Supprimer un défi
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM challenges WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Recherche
    public function search($terme) {
        $stmt = $this->pdo->prepare(
            "SELECT c.*, u.name as author_name 
             FROM challenges c 
             LEFT JOIN users u ON c.user_id = u.id
             WHERE c.title LIKE :terme 
             OR c.description LIKE :terme 
             OR c.category LIKE :terme 
             ORDER BY c.deadline ASC"
        );
        $stmt->execute([':terme' => '%' . $terme . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les défis d'un utilisateur
    public function findByUser($user_id) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM challenges WHERE user_id = ? ORDER BY created_at DESC"
        );
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>