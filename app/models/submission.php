<?php

class Submission {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    //Créer une nouvelle soumission
    public function create($challenge_id, $user_id, $contenu, $date_soumission) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO submissions (challenge_id, user_id, contenu, date_soumission) 
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$challenge_id, $user_id, $contenu, $date_soumission]);
    }

    //Modifier une soumission
    public function edit($id, $contenu, $date_soumission) {
        $stmt = $this->pdo->prepare(
            "UPDATE submissions 
             SET contenu = ?, date_soumission = ? 
             WHERE id = ?"
        );
        return $stmt->execute([$contenu, $date_soumission, $id]);
    }

    //Supprimer une soumission
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM submissions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    //Récupérer toutes les soumissions d'un défi
    public function findByChallenge($challenge_id) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM submissions WHERE challenge_id = ? ORDER BY date_soumission DESC"
        );
        $stmt->execute([$challenge_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}