<?php
require_once __DIR__ . '/../models/Challenge.php';
require_once __DIR__ . '/../../config/database.php';

class ChallengeController {
    private $challengeModel;

    public function __construct($pdo) {
        $this->challengeModel = new Challenge($pdo);
    }

    // Créer un défi
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title       = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $category    = $_POST['category'] ?? '';
            $deadline    = $_POST['deadline'] ?? '';
            $user_id     = $_SESSION['user']['id'];

            if (empty($title) || empty($description) || empty($category) || empty($deadline)) {
                $_SESSION['error'] = "Tous les champs sont obligatoires.";
                header("Location: /projet_ds1/index.php?page=challenge_create");
                exit;
            }

            // Gestion image
            $image = null;
            if (!empty($_FILES['image']['name'])) {
                $upload_dir = __DIR__ . '/../../public/uploads/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $ext      = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('challenge_') . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename);
                $image = $filename;
            }

            if ($this->challengeModel->create($user_id, $title, $description, $category, $deadline, $image)) {
                $_SESSION['success'] = "Défi créé avec succès !";
                header("Location: /projet_ds1/index.php?page=challenges");
                exit;
            } else {
                $_SESSION['error'] = "Erreur lors de la création du défi.";
                header("Location: /projet_ds1/index.php?page=challenge_create");
                exit;
            }
        }
    }

    // Modifier un défi
    public function edit($id) {
        $defi = $this->challengeModel->findById($id);

        if (!$defi) {
            $_SESSION['error'] = "Défi introuvable.";
            header("Location: /projet_ds1/index.php?page=challenges");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title       = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $category    = $_POST['category'] ?? '';
            $deadline    = $_POST['deadline'] ?? '';

            if (empty($title) || empty($description) || empty($category) || empty($deadline)) {
                $_SESSION['error'] = "Tous les champs sont obligatoires.";
                header("Location: /projet_ds1/index.php?page=challenge_edit&id=" . $id);
                exit;
            }

            // Gestion image
            $image = $defi['image'];
            if (!empty($_FILES['image']['name'])) {
                $upload_dir = __DIR__ . '/../../public/uploads/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $ext      = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('challenge_') . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename);
                $image = $filename;
            }

            if ($this->challengeModel->edit($id, $title, $description, $category, $deadline, $image)) {
                $_SESSION['success'] = "Défi modifié avec succès !";
                header("Location: /projet_ds1/index.php?page=challenge_show&id=" . $id);
                exit;
            } else {
                $_SESSION['error'] = "Erreur lors de la modification.";
                header("Location: /projet_ds1/index.php?page=challenge_edit&id=" . $id);
                exit;
            }
        }
    }

    // Supprimer un défi
    public function delete($id) {
        $this->challengeModel->delete($id);
        $_SESSION['success'] = "Défi supprimé avec succès !";
        header("Location: /projet_ds1/index.php?page=challenges");
        exit;
    }
}