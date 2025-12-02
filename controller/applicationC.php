<?php
include_once __DIR__ . '/../config.php';

class ApplicationC {

    // Ajouter une candidature
public function addApplication($application) {
    $db = config::getConnexion();

    try {
        $req = $db->prepare("
            INSERT INTO application (id_offre, cv, date_postulation, status) 
            VALUES (:id_offre, :cv, :date_postulation, :status)
        ");

        $req->execute([
            'id_offre'         => $application->getIdOffre(),
            'cv'               => $application->getCv(),
            'date_postulation' => $application->getDatePostulation(),
            'status'           => $application->getStatus()
        ]);

    } catch (Exception $e) {
        die('Error: ' . $e->getMessage());
    }
}
   
    // Lister toutes les candidatures
    public function listApplications() {
        $db = config::getConnexion();

        try {
            $query = $db->query("SELECT * FROM application ORDER BY date_postulation DESC");
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Supprimer une candidature
    public function deleteApplication($id) {
        $db = config::getConnexion();

        try {
            $req = $db->prepare("DELETE FROM application WHERE id = :id");
            $req->execute(['id' => $id]);
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Récupérer une candidature par ID
    public function getApplicationById($id) {
        $db = config::getConnexion();

        try {
            $req = $db->prepare("SELECT * FROM application WHERE id = :id");
            $req->execute(['id' => $id]);
            return $req->fetch();
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Mettre à jour le statut (Pending / Accepted / Rejected)
   // Mettre à jour une candidature
public function updateApplication($application) {
    $db = config::getConnexion();
    try {
        $req = $db->prepare("
            UPDATE application 
            SET id_offre = :id_offre,
                cv = :cv,
                status = :status
            WHERE id = :id
        ");

        $req->execute([
            'id_offre' => $application->getIdOffre(),
            'cv'       => $application->getCv(),
            'status'   => $application->getStatus(),
            'id'       => $application->getId()
        ]);
    } catch (Exception $e) {
        die("Error: ".$e->getMessage());
    }
}

}
