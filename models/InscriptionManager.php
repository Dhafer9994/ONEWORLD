<?php
require_once __DIR__ . '/entities/Inscription.php';

class InscriptionManager
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function subscribe($refugieId, $formationId)
    {
        $checkSql = "SELECT id FROM inscriptions WHERE refugie_id = :rid AND formation_id = :fid";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->execute([':rid' => $refugieId, ':fid' => $formationId]);

        if ($checkStmt->fetch()) {
            return false;
        }

        $sql = "INSERT INTO inscriptions (refugie_id, formation_id) VALUES (:rid, :fid)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':rid' => $refugieId, ':fid' => $formationId]);
    }

    public function getByRefugie($refugieId)
    {
        $sql = "SELECT * FROM inscriptions WHERE refugie_id = :rid ORDER BY date_inscription DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':rid' => $refugieId]);

        $inscriptions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $i = new Inscription();
            $i->hydrate($row);
            $inscriptions[] = $i;
        }
        return $inscriptions;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM inscriptions ORDER BY date_inscription DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $inscriptions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $i = new Inscription();
            $i->hydrate($row);
            $inscriptions[] = $i;
        }
        return $inscriptions;
    }

    // Additional methods (withJoins) for displaying related data efficiently
    public function getAllWithDetails()
    {
        $sql = "SELECT i.*, 
                       f.titre as formation_titre,
                       CONCAT(ur.nom, ' ', ur.prenom) as refugie_nom,
                       'N/A' as refugie_pays,
                       ur.email as refugie_contact,
                       'N/A' as refugie_id_code
                FROM inscriptions i
                JOIN formations f ON i.formation_id = f.id
                JOIN users ur ON i.refugie_id = ur.id
                ORDER BY i.date_inscription DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Returns array for easier display in tables
    }

    public function cancel($id)
    {
        $sql = "DELETE FROM inscriptions WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>