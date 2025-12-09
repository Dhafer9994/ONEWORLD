<?php
session_start();
require_once '../controller/AuthController.php';

if (isset($_GET['code'])) {
    $provider = new \League\OAuth2\Client\Provider\Facebook([
        'clientId'          => FACEBOOK_APP_ID,
        'clientSecret'      => FACEBOOK_APP_SECRET,
        'redirectUri'       => FACEBOOK_REDIRECT_URL,
        'graphApiVersion'   => FACEBOOK_GRAPH_VERSION,
    ]);

    if (!isset($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
        exit('Invalid state');
    }

    try {
       
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

       
        $fbUser = $provider->getResourceOwner($accessToken);
        $fbUserData = $fbUser->toArray();
        
        $facebook_id = $fbUserData['id'];
        $email = isset($fbUserData['email']) ? $fbUserData['email'] : '';
        $name = isset($fbUserData['name']) ? $fbUserData['name'] : '';
        $parts = explode(' ', $name, 2);
        $nom = $parts[0];
        $prenom = isset($parts[1]) ? $parts[1] : '';
        
       
        $_SESSION['facebook_data'] = [
            'facebook_id' => $facebook_id,
            'email' => $email, 
            'nom' => $nom,
            'prenom' => $prenom
        ];
        
       
        $pdo = config::getConnexion();
        $stmt = $pdo->prepare("SELECT * FROM user WHERE facebook_id = :facebook_id" . ($email ? " OR email = :email" : ""));
        $params = ['facebook_id' => $facebook_id];
        if ($email) {
            $params['email'] = $email;
        }
        $stmt->execute($params);
        $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing_user) {
          
            if (empty($existing_user['facebook_id'])) {
                $stmt = $pdo->prepare("UPDATE user SET facebook_id = :facebook_id WHERE id = :id");
                $stmt->execute(['facebook_id' => $facebook_id, 'id' => $existing_user['id']]);
                $existing_user['facebook_id'] = $facebook_id;
            }
            
            $_SESSION['user'] = $existing_user;
            $hist = new HistoriqueC();
            $h = new Historique("Connexion", date('Y-m-d H:i:s'), $existing_user['id']);
            $hist->addHistorique($h);
            
            header("Location: " . ($existing_user['role'] === 'admin' ? 'profile.php' : 'profil.php'));
            exit();
        } else {
        
            header("Location: complete-facebook-signup.php");
            exit();
        }
        
    } catch (Exception $e) {
        exit('Error: ' . $e->getMessage());
    }
} else {
    header('Location: login.php');
    exit();
}
?>
