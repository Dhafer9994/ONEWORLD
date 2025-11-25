<?php
include __DIR__ . '/../config.php';
class categorieC{
    public function addcategorie($categorie) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('
    INSERT INTO categorie 
    (nom,description) 
    VALUES (:nom, :description)
');


            $req->execute([
                'nom' => $categorie->getnom(),
                'description' => $categorie->getdescription(),
                
            ]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
     public function listecategorie(){
        $db = config::getConnexion();
try{
           
    $liste = $db->query('SELECT * FROM categorie');
    return $liste;
        }catch (exception $e){
            die('error: ' . $e->getMessage());
        }
    }
public function deletecategorie($id){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("DELETE FROM categorie WHERE id=:id");
            $req->execute(['id' => $id]);
        }
        catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }
  public function getCategorieById($id) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare("SELECT * FROM categorie WHERE id = :id");
            $req->execute(['id' => $id]);
            $categorie = $req->fetch(); // récupère une seule ligne
            return $categorie;
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function updateCategorie($id, $nom, $description) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare("UPDATE categorie SET nom=:nom, description=:description WHERE id=:id");
            $req->execute([
                'id' => $id,
                'nom' => $nom,
                'description' => $description
            ]);
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
};