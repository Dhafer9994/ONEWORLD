<?php
include '../config.php';
include '../model/user.php';

class UtilisateurController {

   
    public function addUser($user) {
        $pdo = config::getConnexion();

        try {
            $sql = "INSERT INTO user 
            (nom, prenom, email, mdp, role, telephone, adresse)
            VALUES (:nom, :prenom, :email, :mdp, :role, :telephone, :adresse)";
                   
            
            $db = $pdo->prepare($sql);
            $db->execute([
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'email' => $user->getEmail(),
                'mdp' => password_hash($user->getMdp(), PASSWORD_BCRYPT),
                'role' => $user->getRole(),
                'telephone' => $user->getTelephone(),
                'adresse' => $user->getAdresse()
            ]);

            return true;

        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    
    public function getAll() {
        $pdo = config::getConnexion();

        try {
            $db= $pdo->query("SELECT * FROM user");
            return $db->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

   
    public function delete($id) {
        $pdo = config::getConnexion();

        try {
            $db = $pdo->prepare("DELETE FROM user WHERE id = :id");
            $db->execute(['id' => $id]);
            return true;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

  
    public function updateUser($user) {
        $pdo = config::getConnexion();

        try {
            $sql = "UPDATE user 
                    SET nom=:nom, prenom=:prenom, email=:email, role=:role, telephone=:telephone, adresse=:adresse
                    WHERE id=:id";

            $db = $pdo->prepare($sql);
            $db->execute([
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'telephone' => $user->getTelephone(),
                'adresse' => $user->getAdresse(),
                'id' => $user->getId()
            ]);

            return true;

        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
    
