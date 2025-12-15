<?php
require_once __DIR__ . '/../models/FormationManager.php';
require_once __DIR__ . '/../models/InscriptionManager.php';

class HomeController
{
    private $formationManager;
    private $inscriptionManager;

    public function __construct()
    {
        $this->formationManager = new FormationManager();
        $this->inscriptionManager = new InscriptionManager();
    }

    public function index()
    {
        // Fetch active formations for the homepage
        $formations = $this->formationManager->getActiveFormations();

        // Pass data to view
        require_once __DIR__ . '/../views/front/home.php';
    }
}
?>