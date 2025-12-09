<?php
// formation_apprentissage/models/InscriptionModel.php
class InscriptionModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // S'inscrire à une formation
    public function subscribe($refugieId, $formationId) {
        // Vérifier si déjà inscrit
        $checkSql = "SELECT id FROM inscriptions 
                     WHERE refugie_id = :refugie_id AND formation_id = :formation_id";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->bindParam(':refugie_id', $refugieId, PDO::PARAM_INT);
        $checkStmt->bindParam(':formation_id', $formationId, PDO::PARAM_INT);
        $checkStmt->execute();
        
        if ($checkStmt->fetch()) {
            return false; // Déjà inscrit
        }
        
        $sql = "INSERT INTO inscriptions (refugie_id, formation_id) 
                VALUES (:refugie_id, :formation_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':refugie_id' => $refugieId,
            ':formation_id' => $formationId
        ]);
    }
    
    // Récupérer les inscriptions d'un réfugié
    public function getByRefugie($refugieId) {
        $sql = "SELECT i.*, f.titre, f.description, f.date_debut, f.date_fin,
                       CONCAT(u.nom, ' ', u.prenom) as formateur_nom,
                       f.statut as formation_statut
                FROM inscriptions i
                JOIN formations f ON i.formation_id = f.id
                JOIN users u ON f.formateur_id = u.id
                WHERE i.refugie_id = :refugie_id
                ORDER BY i.date_inscription DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':refugie_id', $refugieId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Récupérer toutes les inscriptions (Admin)
    public function getAll() {
        $sql = "SELECT i.*, 
                       f.titre as formation_titre,
                       CONCAT(ur.nom, ' ', ur.prenom) as refugie_nom,
                       CONCAT(uf.nom, ' ', uf.prenom) as formateur_nom
                FROM inscriptions i
                JOIN formations f ON i.formation_id = f.id
                JOIN users ur ON i.refugie_id = ur.id
                JOIN users uf ON f.formateur_id = uf.id
                ORDER BY i.date_inscription DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Mettre à jour le statut d'une inscription
    public function updateStatus($id, $status) {
        $sql = "UPDATE inscriptions SET statut = :statut WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':statut' => $status
        ]);
    }
    
    // Mettre à jour la progression
    public function updateProgression($id, $progression) {
        $sql = "UPDATE inscriptions SET progression = :progression WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':progression' => $progression
        ]);
    }
    
    // Récupérer une inscription spécifique
    public function getById($id) {
        $sql = "SELECT i.* FROM inscriptions i WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Annuler une inscription
    public function cancel($id, $refugieId) {
        $sql = "DELETE FROM inscriptions WHERE id = :id AND refugie_id = :refugie_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':refugie_id' => $refugieId
        ]);
    }
}
?>