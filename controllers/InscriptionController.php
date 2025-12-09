<?php
// formation_apprentissage/controllers/FormationController.php
class FormationController {
    private $formationModel;
    
    public function __construct() {
        $this->formationModel = new FormationModel();
    }
    
    // Liste des formations (Front)
    public function listFormationsFront() {
        $formations = $this->formationModel->getActiveFormations();
        $isRefugie = isset($_SESSION['refugie_id']);
        
        // Chemin ABSOLU depuis la racine du projet
        require_once __DIR__ . '/../views/front/formations_list.php';
    }
    
    // Détails d'une formation (Front)
    public function showFormation($id) {
        $formation = $this->formationModel->getById($id);
        
        if (!$formation) {
            header('Location: index.php?error=notfound');
            exit();
        }
        
        $placesDisponibles = $this->formationModel->getAvailablePlaces($id);
        $isRefugie = isset($_SESSION['refugie_id']);
        
        require_once __DIR__ . '/../views/front/formation_details.php';
    }
    
    // Liste des formations (Admin)
    public function listFormationsAdmin() {
        $formations = $this->formationModel->getAll();
        require_once __DIR__ . '/../views/admin/formations_list.php';
    }
    
    // Créer une formation (Admin)
    public function createFormation() {
        $errors = [];
        $formation = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->validateForm($_POST, $errors);
            
            if (empty($errors)) {
                // Ajouter l'ID du formateur (admin connecté)
                $data[':formateur_id'] = $_SESSION['admin_id'] ?? 1;
                
                if ($this->formationModel->create($data)) {
                    header('Location: index.php?controller=formation&action=admin_list&success=created');
                    exit();
                } else {
                    $errors[] = "Erreur lors de la création de la formation";
                }
            }
        }
        
        require_once __DIR__ . '/../views/admin/formation_form.php';
    }
    
    // Modifier une formation (Admin)
    public function editFormation($id) {
        $formation = $this->formationModel->getById($id);
        
        if (!$formation) {
            header('Location: index.php?controller=formation&action=admin_list&error=notfound');
            exit();
        }
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->validateForm($_POST, $errors);
            
            if (empty($errors)) {
                if ($this->formationModel->update($id, $data)) {
                    header('Location: index.php?controller=formation&action=admin_list&success=updated');
                    exit();
                } else {
                    $errors[] = "Erreur lors de la mise à jour";
                }
            }
        }
        
        require_once __DIR__ . '/../views/admin/formation_form.php';
    }
    
    // Supprimer une formation (Admin)
    public function deleteFormation($id) {
        try {
            if ($this->formationModel->delete($id)) {
                header('Location: index.php?controller=formation&action=admin_list&success=deleted');
            } else {
                header('Location: index.php?controller=formation&action=admin_list&error=delete_failed');
            }
            exit();
        } catch (Exception $e) {
            header('Location: index.php?controller=formation&action=admin_list&error=' . urlencode($e->getMessage()));
            exit();
        }
    }
    
    // Validation du formulaire
    private function validateForm($postData, &$errors) {
        $data = [];
        
        // Titre
        if (empty(trim($postData['titre']))) {
            $errors[] = "Le titre est obligatoire";
        } else {
            $data[':titre'] = trim($postData['titre']);
            if (strlen($data[':titre']) < 5) {
                $errors[] = "Le titre doit contenir au moins 5 caractères";
            }
        }
        
        // Description
        if (empty(trim($postData['description']))) {
            $errors[] = "La description est obligatoire";
        } else {
            $data[':description'] = trim($postData['description']);
            if (strlen($data[':description']) < 20) {
                $errors[] = "La description doit contenir au moins 20 caractères";
            }
        }
        
        // Durée
        $data[':duree'] = trim($postData['duree'] ?? '40 heures');
        
        // Niveau
        $niveaux = ['débutant', 'intermédiaire', 'avancé'];
        $data[':niveau'] = in_array($postData['niveau'] ?? '', $niveaux) 
            ? $postData['niveau'] 
            : 'débutant';
        
        // Prix
        $prix = $postData['prix'] ?? 0;
        if (!is_numeric($prix) || $prix < 0) {
            $errors[] = "Le prix doit être un nombre positif";
        } else {
            $data[':prix'] = floatval($prix);
        }
        
        // Date début
        if (empty($postData['date_debut'])) {
            $errors[] = "La date de début est obligatoire";
        } else {
            $data[':date_debut'] = $postData['date_debut'];
        }
        
        // Date fin
        $data[':date_fin'] = !empty($postData['date_fin']) ? $postData['date_fin'] : null;
        
        // Vérifier que date_fin > date_debut
        if (!empty($data[':date_fin']) && $data[':date_fin'] < $data[':date_debut']) {
            $errors[] = "La date de fin doit être postérieure à la date de début";
        }
        
        // Places max
        $places = $postData['places_max'] ?? 20;
        if (!is_numeric($places) || $places <= 0) {
            $errors[] = "Le nombre de places doit être un nombre positif";
        } else {
            $data[':places_max'] = intval($places);
        }
        
        // Statut
        $statuts = ['actif', 'inactif', 'complet'];
        $data[':statut'] = in_array($postData['statut'] ?? '', $statuts) 
            ? $postData['statut'] 
            : 'actif';
        
        return $data;
    }
}
?>