<?php

include __DIR__ . '/historiqueC.php';
require_once dirname(__DIR__) . '/social_config.php';
if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
}
date_default_timezone_set('Africa/Tunis');

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    $auth = new AuthController();
    $auth->logout();
}

class AuthController {

    public function register($data, $role = 'user') {
        $pdo = config::getConnexion();

        $hashed = password_hash($data['mdp'], PASSWORD_BCRYPT);

      
        $photo = isset($data['photo']) ? $data['photo'] : "";
        
       
        if (empty($photo) && isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $filetype = $_FILES['image']['type'];
            $filesize = $_FILES['image']['size'];
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (in_array(strtolower($extension), $allowed)) {
                $newFilename = uniqid() . "." . $extension;
                $uploadDir = "../assets/uploads/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newFilename)) {
                    $photo = "assets/uploads/" . $newFilename;
                }
            }
        }

        $sql = "INSERT INTO user (nom, prenom, email, mdp, role, telephone, adresse, photo, google_id, facebook_id) 
                VALUES (:nom, :prenom, :email, :mdp, :role, :telephone, :adresse, :photo, :google_id, :facebook_id)";
       
        $db = $pdo->prepare($sql);
        $db->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':mdp' => $hashed,
            ':role' => $role,
            ':telephone' => $data['telephone'],
            ':adresse' => $data['adresse'],
            ':photo' => $photo,
            ':google_id' => isset($data['google_id']) ? $data['google_id'] : null,
            ':facebook_id' => isset($data['facebook_id']) ? $data['facebook_id'] : null
        ]);

     
        $lastId = $pdo->lastInsertId();
        
        if ($lastId) {
            $hist = new HistoriqueC();
            $h = new Historique("Création de compte", date('Y-m-d H:i:s'), $lastId);
            $hist->addHistorique($h);
        }

        return true;  
    }

    public function loginWithGoogle($token) {
        $client = new Google_Client(['client_id' => GOOGLE_CLIENT_ID]);
        
       
        $id_token = $token;
        if (is_array($token) && isset($token['id_token'])) {
            $id_token = $token['id_token'];
        }
        
        $payload = $client->verifyIdToken($id_token);
        
        if ($payload) {
            $google_id = $payload['sub'];
            $email = $payload['email'];
            $nom = $payload['given_name'];
            $prenom = $payload['family_name'];

            $pdo = config::getConnexion();
            
            $stmt = $pdo->prepare("SELECT * FROM user WHERE google_id = :google_id");
            $stmt->execute(['google_id' => $google_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                
                $this->loginUser($user);
            } else {
                // Check if email exists
                $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                   
                    $stmt = $pdo->prepare("UPDATE user SET google_id = :google_id WHERE id = :id");
                    $stmt->execute(['google_id' => $google_id, 'id' => $user['id']]);
                    $user['google_id'] = $google_id; // Update local array
                    $this->loginUser($user);
                } else {
                   
                    $userData = [
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'email' => $email,
                        'mdp' => 'GOOGLE_LOGIN_NO_PASSWORD',
                        'telephone' => '',
                        'adresse' => '',
                        'google_id' => $google_id
                    ];
                    $this->register($userData);
                    
                 
                    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
                    $stmt->execute(['email' => $email]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    $this->loginUser($user);
                }
            }
        } else {
            return false; 
        }
    }

    public function loginWithFacebook($fbUser) {
     

        $facebook_id = $fbUser['id'];
        $email = isset($fbUser['email']) ? $fbUser['email'] : '';
        $name = isset($fbUser['name']) ? $fbUser['name'] : '';
        
        $parts = explode(' ', $name, 2);
        $nom = $parts[0];
        $prenom = isset($parts[1]) ? $parts[1] : '';

        $pdo = config::getConnexion();

 
        $stmt = $pdo->prepare("SELECT * FROM user WHERE facebook_id = :facebook_id");
        $stmt->execute(['facebook_id' => $facebook_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $this->loginUser($user);
        } else {
          
            $userFound = false;
            if ($email) {
                $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user) {
                  
                    $stmt = $pdo->prepare("UPDATE user SET facebook_id = :facebook_id WHERE id = :id");
                    $stmt->execute(['facebook_id' => $facebook_id, 'id' => $user['id']]);
                    $userFound = true;
                    $user['facebook_id'] = $facebook_id;
                    $this->loginUser($user);
                }
            }

            if (!$userFound) {
               
                 $userData = [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email ? $email : $facebook_id . '@facebook.com', // Fallback email
                    'mdp' => 'FACEBOOK_LOGIN_NO_PASSWORD',
                    'telephone' => '',
                    'adresse' => '',
                    'facebook_id' => $facebook_id
                ];
                $this->register($userData);
                
              
                $stmt = $pdo->prepare("SELECT * FROM user WHERE facebook_id = :facebook_id");
                $stmt->execute(['facebook_id' => $facebook_id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->loginUser($user);
            }
        }
    }

    private function loginUser($user) {
   
        $hist = new HistoriqueC();
        $h = new Historique("Connexion", date('Y-m-d H:i:s'), $user['id']);
        $hist->addHistorique($h);
        
        session_start();
        $_SESSION['user'] = $user;
        
    
        error_log("Password hash for user " . $user['id'] . ": " . substr($user['mdp'], 0, 30));
        
        $needsPassword = (
            strpos($user['mdp'], 'GOOGLE_LOGIN_NO_PASSWORD') !== false ||
            strpos($user['mdp'], 'FACEBOOK_LOGIN_NO_PASSWORD') !== false
        );
        
        error_log("Needs password: " . ($needsPassword ? 'YES' : 'NO'));
        
        if ($needsPassword) {
  
            error_log("Redirecting to set-password.php");
            header("Location: /dash/theme/view/front-office/set-password.php");
        } elseif ($user['role'] === 'admin') {
            header("Location: /dash/theme/view/front-office/profile.php");
        } else {
            header("Location: /dash/theme/view/front-office/profil.php");
        }
        exit();
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
                    header("Location: /dash/theme/view/front-office/profile.php");
                } else {
                    header("Location: /dash/theme/view/front-office/profil.php");
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
        header("Location: /dash/theme/view/front-office/login.php");
        exit();
    }
}