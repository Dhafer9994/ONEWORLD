<?php
session_start();
require_once '../controller/AuthController.php';

if (isset($_GET['code'])) {
    $client = new Google_Client();
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_REDIRECT_URL);

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    if (!isset($token['error'])) {
     
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
      
            $_SESSION['google_data'] = [
                'google_id' => $google_id,
                'email' => $email,
                'nom' => $nom,
                'prenom' => $prenom
            ];
            
         
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("SELECT * FROM user WHERE google_id = :google_id OR email = :email");
            $stmt->execute(['google_id' => $google_id, 'email' => $email]);
            $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existing_user) {
            
                $auth = new AuthController();
                
                if (empty($existing_user['google_id'])) {
                    $stmt = $pdo->prepare("UPDATE user SET google_id = :google_id WHERE id = :id");
                    $stmt->execute(['google_id' => $google_id, 'id' => $existing_user['id']]);
                    $existing_user['google_id'] = $google_id;
                }
                
                $_SESSION['user'] = $existing_user;
                $hist = new HistoriqueC();
                $h = new Historique("Connexion", date('Y-m-d H:i:s'), $existing_user['id']);
                $hist->addHistorique($h);
                
                header("Location: " . ($existing_user['role'] === 'admin' ? 'profile.php' : 'profil.php'));
                exit();
            } else {
              
                header("Location: complete-google-signup.php");
                exit();
            }
        } else {
            echo "Invalid token";
        }
    } else {
        echo "Error fetching token: " . $token['error'];
    }
} else {
    header('Location: login.php');
    exit();
}
?>
