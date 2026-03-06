<?php
require_once(__DIR__ . '/../../config/database.php');
require_once(__DIR__ . '/../models/user.php');

class UserController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }

    // Inscription
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name     = htmlspecialchars(trim($_POST['name']));
            $email    = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
                $_SESSION['error'] = "Veuillez remplir tous les champs correctement (Mot de passe: 8 car. min).";
                header('Location: /projet_ds1/index.php?page=register');
                exit();
            }

            if ($this->userModel->register($name, $email, $password)) {
                $_SESSION['success'] = "Inscription réussie, connectez-vous !";
                header('Location: /projet_ds1/index.php?page=login');
                exit();
            } else {
                $_SESSION['error'] = "Erreur lors de l'inscription ! L'email est peut-être déjà utilisé.";
                header('Location: /projet_ds1/index.php?page=register');
                exit();
            }
        }
    }

    // Connexion
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            $user = $this->userModel->login($email, $password);

            if ($user) {
                $_SESSION['user'] = [
                    'id'    => $user['id'],
                    'name'  => $user['name'],
                    'email' => $user['email']
                ];
                header('Location: /projet_ds1/index.php?page=dashboard');
                exit();
            } else {
                $_SESSION['error'] = "Email ou mot de passe invalide.";
                header('Location: /projet_ds1/index.php?page=login');
                exit();
            }
        }
    }

    // Déconnexion
    public function logout() {
        session_destroy();
        header('Location: /projet_ds1/index.php?page=login');
        exit();
    }
}