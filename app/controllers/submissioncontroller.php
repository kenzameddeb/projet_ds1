<?php
class SubmissionController {
    private $submissionModel;

    public function __construct($db) {
        $this->submissionModel = new Submission($db);
    }

    public function create() {
        $challenge_id = $_POST['challenge_id'];
        $user_id      = $_SESSION['user']['id'];
        $description  = trim($_POST['description']);
        $image        = null;

        if (!empty($_FILES['image']['name'])) {
            $allowed   = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (!in_array($extension, $allowed)) {
                $_SESSION['error'] = "Format d'image invalide.";
                header("Location: /projet_ds1/index.php?page=challenge_show&id=" . $challenge_id);
                exit;
            }

            if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                $_SESSION['error'] = "Image trop lourde (max 2 Mo).";
                header("Location: /projet_ds1/index.php?page=challenge_show&id=" . $challenge_id);
                exit;
            }

            $upload_dir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

            $image = uniqid() . '.' . $extension;
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image);
        }

        if (empty($description)) {
            $_SESSION['error'] = "La description est obligatoire.";
            header("Location: /projet_ds1/index.php?page=challenge_show&id=" . $challenge_id);
            exit;
        }

        $this->submissionModel->create($challenge_id, $user_id, $description, $image);
        header("Location: /projet_ds1/index.php?page=challenge_show&id=" . $challenge_id);
        exit;
    }

    public function edit() {
        $submission_id = $_POST['submission_id'];
        $challenge_id  = $_POST['challenge_id'];
        $user_id       = $_SESSION['user']['id']; 
        $description   = trim($_POST['description']);
        $image         = $_POST['current_image'];

        if (!empty($_FILES['image']['name'])) {
            $allowed   = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (!in_array($extension, $allowed)) {
                $_SESSION['error'] = "Format d'image invalide.";
                header("Location: /projet_ds1/index.php?page=challenge_show&id=" . $challenge_id);
                exit;
            }

            if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                $_SESSION['error'] = "Image trop lourde (max 2 Mo).";
                header("Location: /projet_ds1/index.php?page=challenge_show&id=" . $challenge_id);
                exit;
            }

            // Supprimer l'ancienne image
            if (!empty($_POST['current_image'])) {
                $old_path = __DIR__ . '/../../public/uploads/' . $_POST['current_image'];
                if (file_exists($old_path)) unlink($old_path);
            }

            $upload_dir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

            $image = uniqid() . '.' . $extension;
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image);
        } 

        if (empty($description)) {
            $_SESSION['error'] = "La description est obligatoire.";
            header("Location: /projet_ds1/index.php?page=challenge_show&id=" . $challenge_id);
            exit;
        }

        $this->submissionModel->edit($submission_id, $description, $image);
        header("Location: /projet_ds1/index.php?page=challenge_show&id=" . $challenge_id);
        exit;
    }

    public function delete() {
        $submission_id = $_POST['submission_id'];
        $challenge_id  = $_POST['challenge_id'];
        $user_id       = $_SESSION['user']['id']; 
        $image         = $_POST['image'];

        if (!empty($image)) {
            $path = __DIR__ . '/../../public/uploads/' . $image;
            if (file_exists($path)) unlink($path);
        }

        $this->submissionModel->delete($submission_id, $user_id);
        header("Location: /projet_ds1/index.php?page=challenge_show&id=" . $challenge_id);
        exit;
    }
}