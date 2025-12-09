<?php
include_once __DIR__ . '/../config.php';

class Offrec
{
    public function addoffre($offre)
    {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('
            INSERT INTO offre 
            (titre, id_categorie, description, location, status, auteur, image) 
            VALUES (:titre, :id_categorie, :description, :location, :status, :auteur, :image)
        ');

            $req->execute([
                'id_categorie' => $offre->getcategorie(),
                'titre' => $offre->gettitre(),
                'description' => $offre->getdescription(),
                'location' => $offre->getlocation(),
                'status' => $offre->getstatus(),
                'auteur' => $offre->getauteur(),
                'image' => $offre->getImage()
            ]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function listeoffre()
    {
        $db = config::getConnexion();
        try {
            $sql = "
            SELECT o.id, o.titre, o.id_categorie, c.nom AS categorie, o.description, o.location, o.status, o.auteur
            FROM offre o
            JOIN categorie c ON o.id_categorie = c.id
        ";
            $liste = $db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function deleteOffre($id)
    {
        $db = config::getConnexion();
        try {
            $req = $db->prepare("DELETE FROM offre WHERE id=:id");
            $req->execute(['id' => $id]);
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
    public function getOffreById($id)
    {
        $db = config::getConnexion();
        try {
            $req = $db->prepare("SELECT * FROM offre WHERE id=:id");
            $req->execute(['id' => $id]);
            $offre = $req->fetch(); // fetch a single row
            return $offre;
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function updateOffre($id, $id_categorie, $titre, $description, $location, $status, $auteur, $image)
    {
        $db = config::getConnexion();
        try {
            $sql = "UPDATE offre SET id_categorie = :id_categorie, titre = :titre, description = :description, location = :location, status = :status, auteur = :auteur";

            $params = [
                'id' => $id,
                'id_categorie' => $id_categorie,
                'titre' => $titre,
                'description' => $description,
                'location' => $location,
                'status' => $status,
                'auteur' => $auteur
            ];

            if ($image) {
                $sql .= ", image = :image";
                $params['image'] = $image;
            }

            $sql .= " WHERE id = :id";

            $req = $db->prepare($sql);
            $req->execute($params);
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
    public function getOffresByCategorie($id_categorie)
    {
        $db = config::getConnexion();

        try {
            $query = $db->prepare("
            SELECT o.*, c.nom AS categorie_nom
            FROM offre o
            INNER JOIN categorie c ON o.id_categorie = c.id
            WHERE o.id_categorie = :id
        ");

            $query->execute([
                'id' => $id_categorie
            ]);

            return $query->fetchAll();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    public function getOffreByName($name)
    {
        $db = config::getConnexion();
        $sql = "SELECT * FROM offre WHERE titre = :name LIMIT 1";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute(['name' => $name]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error fetching offer by name: " . $e->getMessage();
            return false;
        }
    }
}
