<?php
include_once __DIR__ . '/../config.php';
class categorieC
{
    public function addcategorie($categorie)
    {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('
    INSERT INTO categorie 
    (nom,description,image) 
    VALUES (:nom, :description, :image)
');

            $req->execute([
                'nom' => $categorie->getnom(),
                'description' => $categorie->getdescription(),
                'image' => $categorie->getImage()
            ]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    public function listecategorie()
    {
        $db = config::getConnexion();
        try {

            $liste = $db->query('SELECT * FROM categorie');
            return $liste;
        } catch (exception $e) {
            die('error: ' . $e->getMessage());
        }
    }
    public function deletecategorie($id)
    {
        $db = config::getConnexion();
        try {
            $req = $db->prepare("DELETE FROM categorie WHERE id=:id");
            $req->execute(['id' => $id]);
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
    public function getCategorieById($id)
    {
        $db = config::getConnexion();
        $sql = "SELECT * FROM categorie WHERE id = :id";
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id);
        $query->execute();
        return $query->fetch();
    }


    public function updateCategorie($id, $nom, $description, $image = null)
    {
        $db = config::getConnexion();
        try {
            $sql = "UPDATE categorie SET nom=:nom, description=:description";
            $params = [
                'id' => $id,
                'nom' => $nom,
                'description' => $description
            ];

            if ($image) {
                $sql .= ", image=:image";
                $params['image'] = $image;
            }

            $sql .= " WHERE id=:id";

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
            INNER JOIN categorie c ON o.categorie = c.id
            WHERE o.categorie = :id
        ");

            $query->execute([
                'id' => $id_categorie
            ]);

            return $query->fetchAll();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

}
;