<?php
require_once __DIR__ . '/entities/Formation.php';

class FormationManager
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll()
    {
        $sql = "SELECT f.* FROM formations f ORDER BY f.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $formations = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $formation = new Formation();
            $formation->hydrate($row);
            $formations[] = $formation;
        }
        return $formations;
    }

    public function getActiveFormations()
    {
        $sql = "SELECT f.* FROM formations f 
                WHERE f.statut = 'actif' 
                AND (f.date_fin IS NULL OR f.date_fin >= CURDATE())
                ORDER BY f.date_debut ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $formations = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $formation = new Formation();
            $formation->hydrate($row);
            $formations[] = $formation;
        }
        return $formations;
    }

    public function getById($id)
    {
        $sql = "SELECT f.* FROM formations f WHERE f.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $formation = new Formation();
            $formation->hydrate($row);
            return $formation;
        }
        return null;
    }

    public function create(Formation $formation)
    {
        $sql = "INSERT INTO formations (titre, description, formateur_id, duree, niveau, 
                prix, date_debut, date_fin, places_max, statut) 
                VALUES (:titre, :description, :formateur_id, :duree, :niveau, 
                :prix, :date_debut, :date_fin, :places_max, :statut)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titre' => $formation->getTitre(),
            ':description' => $formation->getDescription(),
            ':formateur_id' => $formation->getFormateurId(),
            ':duree' => $formation->getDuree(),
            ':niveau' => $formation->getNiveau(),
            ':prix' => $formation->getPrix(),
            ':date_debut' => $formation->getDateDebut(),
            ':date_fin' => $formation->getDateFin(),
            ':places_max' => $formation->getPlacesMax(),
            ':statut' => $formation->getStatut()
        ]);
    }

    public function update(Formation $formation)
    {
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

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $formation->getId(),
            ':titre' => $formation->getTitre(),
            ':description' => $formation->getDescription(),
            ':duree' => $formation->getDuree(),
            ':niveau' => $formation->getNiveau(),
            ':prix' => $formation->getPrix(),
            ':date_debut' => $formation->getDateDebut(),
            ':date_fin' => $formation->getDateFin(),
            ':places_max' => $formation->getPlacesMax(),
            ':statut' => $formation->getStatut()
        ]);
    }

    public function delete($id)
    {
        $checkSql = "SELECT COUNT(*) as count FROM inscriptions WHERE formation_id = :id";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->execute([':id' => $id]);
        if ($checkStmt->fetch()['count'] > 0) {
            throw new Exception("Impossible de supprimer : il y a des inscriptions active.");
        }

        $sql = "DELETE FROM formations WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Places disponibles count logic remains usually raw or value object, keeping simple int return here
    public function getAvailablePlaces($formationId)
    {
        $sql = "SELECT f.places_max - COUNT(i.id) as places_disponibles
                FROM formations f
                LEFT JOIN inscriptions i ON f.id = i.formation_id AND i.statut IN ('confirme', 'en_attente')
                WHERE f.id = :id GROUP BY f.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $formationId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['places_disponibles'] : 0;
    }
}
?>