<?php
class Vote {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    // Enregistrer un vote
    public function create($submission_id, $user_id) {
        $stmt = $this->db->prepare("
            INSERT INTO votes (submission_id, user_id, created_at)
            VALUES (?, ?, NOW())
        ");
        return $stmt->execute([$submission_id, $user_id]);
    }

    // Vérifier si l'utilisateur a déjà voté
    public function checkVoteExists($submission_id, $user_id) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM votes 
            WHERE submission_id = ? AND user_id = ?
        ");
        $stmt->execute([$submission_id, $user_id]);
        return $stmt->fetchColumn() > 0; // retourne true ou false
    }

    // Compter les votes d'une participation
    public function countVotes($submission_id) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM votes
            WHERE submission_id = ?
        ");
        $stmt->execute([$submission_id]);
        return $stmt->fetchColumn(); // retourne un nombre
    }
}