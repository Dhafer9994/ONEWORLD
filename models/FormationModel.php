<?php
// formation_apprentissage/models/FormationModel.php
class FormationModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Récupérer toutes les formations (Admin)
    public function getAll() {
        $sql = "SELECT f.*, 
                       CONCAT(u.nom, ' ', u.prenom) as formateur_nom,
                       (SELECT COUNT(*) FROM inscriptions i WHERE i.formation_id = f.id AND i.statut != 'refuse') as inscriptions_count
                FROM formations f
                LEFT JOIN users u ON f.formateur_id = u.id
                ORDER BY f.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Récupérer les formations actives (Front)
    public function getActiveFormations() {
        $sql = "SELECT f.*, CONCAT(u.nom, ' ', u.prenom) as formateur_nom
                FROM formations f
                LEFT JOIN users u ON f.formateur_id = u.id
                WHERE f.statut = 'actif' 
                AND (f.date_fin IS NULL OR f.date_fin >= CURDATE())
                ORDER BY f.date_debut ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Récupérer une formation par ID
    public function getById($id) {
        $sql = "SELECT f.*, CONCAT(u.nom, ' ', u.prenom) as formateur_nom
                FROM formations f
                LEFT JOIN users u ON f.formateur_id = u.id
                WHERE f.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Créer une formation
    public function create($data) {
        $sql = "INSERT INTO formations (titre, description, formateur_id, duree, niveau, 
                prix, date_debut, date_fin, places_max, statut) 
                VALUES (:titre, :description, :formateur_id, :duree, :niveau, 
                :prix, :date_debut, :date_fin, :places_max, :statut)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    // Mettre à jour une formation
    public function update($id, $data) {
        $sql = "UPDATE formations SET 
                titre = :titre,
                description = :description,
                duree = :duree,
                niveau = :niveau,
                prix = :prix,
                date_debut = :date_debut,
                date_fin = :date_fin,
                places_max = :places_max,
                statut = :statut,
                updated_at = NOW()
                WHERE id = :id";
        
        $data[':id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    // Supprimer une formation
    public function delete($id) {
        // Vérifier s'il y a des inscriptions
        $checkSql = "SELECT COUNT(*) as count FROM inscriptions WHERE formation_id = :id";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $checkStmt->execute();
        $result = $checkStmt->fetch();
        
        if ($result['count'] > 0) {
            throw new Exception("Impossible de supprimer : il y a des inscriptions à cette formation");
        }
        
        $sql = "DELETE FROM formations WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    // Vérifier les places disponibles
    public function getAvailablePlaces($formationId) {
        $sql = "SELECT 
                f.places_max - COUNT(i.id) as places_disponibles
                FROM formations f
                LEFT JOIN inscriptions i ON f.id = i.formation_id 
                AND i.statut IN ('confirme', 'en_attente')
                WHERE f.id = :id
                GROUP BY f.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $formationId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result['places_disponibles'] ?? 0;
    }
    
    // Récupérer le nombre d'inscriptions
    public function getInscriptionsCount($formationId) {
        $sql = "SELECT COUNT(*) as count FROM inscriptions WHERE formation_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $formationId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
}
?>