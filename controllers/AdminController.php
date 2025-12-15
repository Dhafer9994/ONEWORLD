<?php
require_once __DIR__ . '/../models/FormationManager.php';
require_once __DIR__ . '/../models/InscriptionManager.php';

class AdminController
{
    private $formationManager;
    private $inscriptionManager;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        $this->formationManager = new FormationManager();
        $this->inscriptionManager = new InscriptionManager();
    }

    public function dashboard()
    {
        // Handle Actions
        $action = $_GET['action'] ?? null;
        $error = null;
        $success = null;
        $formationToEdit = null;

        try {
            if ($action === 'delete' && isset($_GET['id'])) {
                $this->formationManager->delete($_GET['id']);
                $success = "Formation supprimée avec succès.";
            }

            if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $f = new Formation();
                $f->hydrate($_POST);
                // Default values if missing
                if (!$f->getFormateurId())
                    $f->setFormateurId(1); // Default admin
                if (!$f->getStatut())
                    $f->setStatut('actif');

                if (!empty($_POST['id'])) {
                    // Update
                    $f->setId($_POST['id']);
                    $this->formationManager->update($f);
                    $success = "Formation mise à jour avec succès.";
                } else {
                    // Create
                    $this->formationManager->create($f);
                    $success = "Formation créée avec succès.";
                }
            }

            if ($action === 'edit' && isset($_GET['id'])) {
                $formationToEdit = $this->formationManager->getById($_GET['id']);
            }

        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        // Fetch Data
        $inscriptions = $this->inscriptionManager->getAllWithDetails();

        // Search Logic
        $formations = $this->formationManager->getAll(); // Simple memory filter for now usually done in SQL
        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $q = strtolower(trim($_GET['q']));
            $formations = array_filter($formations, function ($f) use ($q) {
                return strpos(strtolower($f->getTitre()), $q) !== false;
            });
        }

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }
}
?>