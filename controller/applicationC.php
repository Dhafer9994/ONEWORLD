<?php
include_once __DIR__ . '/../config.php';

class ApplicationC
{

    public function addApplication($application)
    {
        $db = config::getConnexion();

        try {
            $req = $db->prepare("
            INSERT INTO application (id_offre, cv, date_postulation, status, full_name, email, phone) 
            VALUES (:id_offre, :cv, :date_postulation, :status, :full_name, :email, :phone)
        ");

            $req->execute([
                'id_offre' => $application->getIdOffre(),
                'cv' => $application->getCv(),
                'date_postulation' => $application->getDatePostulation(),
                'status' => $application->getStatus(),
                'full_name' => $application->getFullName(),
                'email' => $application->getEmail(),
                'phone' => $application->getPhone()
            ]);

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function listApplications()
    {
        $db = config::getConnexion();

        try {
            $sql = "
                SELECT a.*, o.titre AS offre_titre, c.nom AS categorie_nom 
                FROM application a
                LEFT JOIN offre o ON a.id_offre = o.id
                LEFT JOIN categorie c ON o.id_categorie = c.id
                ORDER BY a.date_postulation DESC
            ";
            $query = $db->query($sql);
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function deleteApplication($id)
    {
        $db = config::getConnexion();

        try {
            $req = $db->prepare("DELETE FROM application WHERE id = :id");
            $req->execute(['id' => $id]);
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getApplicationById($id)
    {
        $db = config::getConnexion();

        try {
            $req = $db->prepare("SELECT * FROM application WHERE id = :id");
            $req->execute(['id' => $id]);
            return $req->fetch();
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }


    public function updateApplication($application)
    {
        $db = config::getConnexion();
        try {
            $req = $db->prepare("
            UPDATE application 
            SET id_offre = :id_offre,
                cv = :cv,
                status = :status,
                full_name = :full_name,
                email = :email,
                phone = :phone
            WHERE id = :id
        ");

            $req->execute([
                'id_offre' => $application->getIdOffre(),
                'cv' => $application->getCv(),
                'status' => $application->getStatus(),
                'full_name' => $application->getFullName(),
                'email' => $application->getEmail(),
                'phone' => $application->getPhone(),
                'id' => $application->getId()
            ]);
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

}
