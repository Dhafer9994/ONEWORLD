<?php

include 'historiqueC.php';

// Gérer la déconnexion
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    $auth = new AuthController();
    $auth->logout();
}

class AuthController {

    public function register($data, $role = 'user') {
        $pdo = config::getConnexion();

        $hashed = password_hash($data['mdp'], PASSWORD_BCRYPT);

        $sql = "INSERT INTO user (nom, prenom, email, mdp, role, telephone, adresse) 
                VALUES (:nom, :prenom, :email, :mdp, :role, :telephone, :adresse)";
       
        $db = $pdo->prepare($sql);
        $db->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':mdp' => $hashed,
            ':role' => $role,
            ':telephone' => $data['telephone'],
            ':adresse' => $data['adresse']
        ]);

     
        $lastId = $pdo->lastInsertId();
        
        if ($lastId) {
            $hist = new HistoriqueC();
            $h = new Historique("Création de compte", date('Y-m-d H:i:s'), $lastId);
            $hist->addHistorique($h);
        }

        return true;  
    }

    public function login($email, $mdp) {
        $pdo = config::getConnexion();
        
        $sql = "SELECT * FROM user WHERE email = :email";
        $bd = $pdo->prepare($sql);
        $bd->execute(['email' => $email]);
    
        $user = $bd->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $Password = $user['mdp'];

            if (password_verify($mdp, $Password) || $mdp === $Password) {
               
                $hist = new HistoriqueC();
                $h = new Historique("Connexion", date('Y-m-d H:i:s'), $user['id']);
                $hist->addHistorique($h);
                
                session_start();
                $_SESSION['user'] = $user;

                if ($user['role'] === 'admin') {
                    header("Location: ../view/dash.php");
                } else {
                    header("Location: ../view/profil.php");
                }
                exit();
            }
        }
        return false;
    }

    public function logout() {
        session_start();

        if (isset($_SESSION['user']['id'])) {
            $hist = new HistoriqueC();
            $h = new Historique("Déconnexion", date('Y-m-d H:i:s'), $_SESSION['user']['id']);
            $hist->addHistorique($h);
        }

        session_destroy();
        header("Location: ../view/login.php");
        exit();
    }
}