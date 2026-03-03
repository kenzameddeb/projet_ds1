<?php
require_once __DIR__ . '/../models/challenge.php';
require_once __DIR__ . '/../config/database.php';

class ChallengeController {
    private $challengeModel;

    public function __construct($pdo) {
        $this->challengeModel = new Challenge($pdo);
    }

    //Lister tous les défis
    public function index() {
        $defis = $this->challengeModel->findAll();
        require __DIR__ . '/../views/challenges/index.php';
    }

    //Afficher un seul défi
    public function show($id) {
        $defi = $this->challengeModel->findById($id);
        if (!$defi) {
            die("Défi introuvable.");
        }
        require __DIR__ . '/../views/challenges/show.php';
    }

    //Afficher le formulaire + traiter l'ajout
    public function create() {
        $erreur = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre       = trim($_POST['titre'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $categorie   = $_POST['categorie'] ?? '';
            $date_limite = $_POST['date_limite'] ?? '';

            if ($titre && $description && $categorie && $date_limite) {
                $this->challengeModel->create($titre, $description, $categorie, $date_limite);
                header("Location: index.php?page=challenges");
                exit;
            } else {
                $erreur = "Tous les champs sont obligatoires.";
            }
        }

        require __DIR__ . '/../views/challenges/create.php';
    }

    //Afficher le formulaire + traiter la modification
    public function edit($id) {
        $defi   = $this->challengeModel->findById($id);
        $erreur = '';

        if (!$defi) {
            die("Défi introuvable.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre       = trim($_POST['titre'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $categorie   = $_POST['categorie'] ?? '';
            $date_limite = $_POST['date_limite'] ?? '';

            if ($titre && $description && $categorie && $date_limite) {
                $this->challengeModel->edit($id, $titre, $description, $categorie, $date_limite);
                header("Location: index.php?page=challenges");
                exit;
            } else {
                $erreur = "Tous les champs sont obligatoires.";
            }
        }

        require __DIR__ . '/../views/challenges/edit.php';
    }

    //Supprimer un défi
    public function delete($id) {
        $this->challengeModel->delete($id);
        header("Location: index.php?page=challenges");
        exit;
    }
}