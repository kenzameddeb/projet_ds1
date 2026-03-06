<?php
class Comment {
    private $db;
    // Le constructeur reçoit la connexion BDD
    public function __construct($db) {
        $this->db = $db;
    }

    // Créer un commentaire
    public function create($submission_id, $user_id, $content) {
        $stmt = $this->db->prepare("
            INSERT INTO comments (submission_id, user_id, content, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        return $stmt->execute([$submission_id, $user_id, $content]);
    }
    // Supprimer un commentaire (seulement le sien !)
    public function delete($comment_id, $user_id) {
        $stmt = $this->db->prepare("
            DELETE FROM comments 
            WHERE id = ? AND user_id = ?
        ");
        return $stmt->execute([$comment_id, $user_id]);
    }
    // Récupérer tous les commentaires d'une participation
    public function findBySubmission($submission_id) {
        $stmt = $this->db->prepare("
            SELECT comments.*, users.username
            FROM comments
            JOIN users ON comments.user_id = users.id
            WHERE comments.submission_id = ?
            ORDER BY comments.created_at DESC
        ");
        $stmt->execute([$submission_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }//jointure entre deux tables comment w user 
}