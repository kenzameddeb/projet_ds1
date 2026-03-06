<?php
// /app/controllers/CommentController.php

class CommentController {
    private $commentModel;

    public function __construct($db) {
        $this->commentModel = new Comment($db);
    }

    // Appelé quand le formulaire de commentaire est soumis
    public function create() {
        // 1. Récupérer les données du formulaire
        $submission_id = $_POST['submission_id'];
        $user_id       = $_SESSION['user_id']; // l'utilisateur connecté
        $content       = trim($_POST['content']);//trim tnahi l'espace el zeyed

        // 2. nthabtou ken el cmnt vide ou non
        if (empty($content)) {
            header("Location: /challenge/show/" . $submission_id . "?error=empty");
            exit;
        }

        // 3. Sauvegarder dans comment.php
        $this->commentModel->create($submission_id, $user_id, $content);

        // 4. Rediriger
        header("Location: /challenge/show/" . $submission_id);
        exit;//nabaathou l'utilisateur lil page defi bch ychouf cmnt mteou
    }

    // Appelé quand on clique "Supprimer" sur un commentaire
    public function delete() {
        $comment_id = $_POST['comment_id'];
        $user_id    = $_SESSION['user_id'];
       //kthina lid taa cmnt w chkoun yheb yfaskhou
        $this->commentModel->delete($comment_id, $user_id);
        //yhtabet eli el cmnt id howa taa il user bidou
        header("Location: " . $_SERVER['HTTP_REFERER']); // retour à la page précédente
        exit;
    }
}