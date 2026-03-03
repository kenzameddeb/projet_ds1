<?php
class Challenge {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    //Récupérer tous les défis
    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM challenges ORDER BY deadline ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Récupérer un défi par son ID
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM challenges WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Créer un nouveau défi
    public function create($titre, $description, $categorie, $date_limite) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO challenges (title, description, category, deadline) 
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$titre, $description, $categorie, $date_limite]);
    }

    //Modifier un défi existant
    public function edit($id, $titre, $description, $categorie, $date_limite) {
        $stmt = $this->pdo->prepare(
            "UPDATE challenges 
             SET title = ?, description = ?, category = ?, deadline = ? 
             WHERE id = ?"
        );
        return $stmt->execute([$titre, $description, $categorie, $date_limite, $id]);
    }

    //Supprimer un défi 
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM challenges WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
