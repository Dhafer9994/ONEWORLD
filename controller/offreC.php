<?php
include '../config.php';

class Offrec {
    public function addoffre($offre) {
        $db = config::getConnexion();
        try {
            // Prepare the insert statement for the "offre" table
            $req = $db->prepare('
    INSERT INTO offre 
    (titre,categorie, description, location, status, auteur) 
    VALUES (:titre, :categorie, :description, :location, :status, :auteur)
');


            // Execute with values from the $offre object
            $req->execute([
                'categorie' => $offre->getcategorie(),
                'titre'         => $offre->gettitre(),
                'description' => $offre->getdescription(),
                'location'    => $offre->getlocation(),
                'status'      => $offre->getstatus(),
                'auteur'      => $offre->getauteur()
            ]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    public function listeoffre(){
        $db = config::getConnexion();
try{
           
    $liste = $db->query('SELECT * FROM offre');
    return $liste;
        }catch (exception $e){
            die('error: ' . $e->getMessage());
        }
    }

public function deleteOffre($id){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("DELETE FROM offre WHERE id=:id");
            $req->execute(['id' => $id]);
        }
        catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

   // Récupérer une offre par son ID
public function getOffreById($id){
    $db = config::getConnexion();
    try {
        $req = $db->prepare("SELECT * FROM offre WHERE id=:id");
        $req->execute(['id' => $id]);
        $offre = $req->fetch(); // récupère une seule ligne
        return $offre;
    } catch (Exception $e) {
        die("Error: ".$e->getMessage());
    }
}

// Mettre à jour une offre
public function updateOffre($id, $categorie, $titre, $description, $location, $status, $auteur){
    $db = config::getConnexion();
    try {
        $req = $db->prepare("UPDATE offre 
                             SET categorie=:categorie, titre=:titre, description=:description, location=:location, status=:status, auteur=:auteur
                             WHERE id=:id");
        $req->execute([
            'id' => $id,
            'categorie' => $categorie,
            'titre' => $titre,
            'description' => $description,
            'location' => $location,
            'status' => $status,
            'auteur' => $auteur
        ]);
    } catch (Exception $e) {
        die("Error: ".$e->getMessage());
    }
}

}