<?php
session_start();


if (!isset($_SESSION['user'])) {
    header("Location: ../front-office/login.php");
    exit();
}

$user = $_SESSION['user'];


include '../../controller/UtilisateurController.php';
$uc = new UtilisateurController();
$user_data = $uc->getUserById($user['id']);
$needsPassword = ($user_data && (
    strpos($user_data['mdp'], 'GOOGLE_LOGIN_NO_PASSWORD') !== false ||
    strpos($user_data['mdp'], 'FACEBOOK_LOGIN_NO_PASSWORD') !== false
));

if (!$needsPassword) {
    
    header("Location: " . ($user['role'] === 'admin' ? 'profile.php' : 'profil.php'));
    exit();
}


$password_error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    $errors = [];
    
    if (empty($new_password)) {
        $errors[] = "Password is required";
    } elseif (strlen($new_password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    }
    
    if (empty($confirm_password)) {
        $errors[] = "Confirm password is required";
    } elseif ($new_password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    if (empty($errors)) {
        try {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $result = $uc->updatePassword($user['id'], $hashed_password);
            
            if ($result) {
               
                $_SESSION['user']['mdp'] = $hashed_password;
                
           
                include_once '../../controller/HistoriqueC.php';
                include '../model/historique.php';
                $hc = new HistoriqueC();
                $historique = new Historique();
                $historique->setAction('Password Set (First Time)');
                $historique->setDateAction(date('Y-m-d H:i:s'));
                $historique->setIdUser($user['id']);
                $hc->addHistorique($historique);
                
           
                header("Location: " . ($user['role'] === 'admin' ? 'profile.php' : 'profil.php'));
                exit();
            } else {
                $password_error = "Error setting password. Please try again.";
            }
        } catch (Exception $e) {
            $password_error = "Error: " . $e->getMessage();
        }
    } else {
        $password_error = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OneWorld - Set Password</title>

    <link href="../../asset/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/font-awesome.min.css" rel="stylesheet">
    <link href="../../css/animate.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
    <link href="../../css/responsive.css" rel="stylesheet">
    <link href="../../css/modern-green.css" rel="stylesheet">
    
    <style>
        .set-password-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        
        .set-password-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
            padding: 40px;
        }
        
        .welcome-message {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .welcome-message h2 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .welcome-message p {
            color: #666;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ddd;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-set-password {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }
        
        .btn-set-password:hover {
            opacity: 0.9;
        }
        
        .password-container {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
        }
        
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        
        .info-box i {
            color: #2196f3;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="set-password-container">
        <div class="set-password-card">
            <div class="welcome-message">
                <h2>Welcome, <?php echo htmlspecialchars($user['prenom']); ?>! 👋</h2>
                <p>You're almost done!</p>
            </div>
            
            <div class="info-box">
                <i class="fa fa-info-circle"></i>
                <strong>Set a password</strong> to secure your account and enable email login.
            </div>
            
            <?php if ($password_error): ?>
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-circle"></i> <?php echo $password_error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="setPasswordForm">
                <div class="form-group">
                    <label for="new_password">Password <span class="text-danger">*</span></label>
                    <div class="password-container">
                        <input type="password" 
                               class="form-control" 
                               id="new_password" 
                               name="new_password"
                               placeholder="Enter your password (min 8 characters)"
                               required>
                        <button type="button" class="password-toggle" onclick="togglePassword('new_password', this)">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                    <div class="password-container">
                        <input type="password" 
                               class="form-control" 
                               id="confirm_password" 
                               name="confirm_password"
                               placeholder="Confirm your password"
                               required>
                        <button type="button" class="password-toggle" onclick="togglePassword('confirm_password', this)">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <div id="passwordMatchMessage" style="font-size: 12px; margin-top: 5px;"></div>
                </div>
                
                <button type="submit" class="btn btn-set-password">
                    <i class="fa fa-check"></i> Set Password & Continue
                </button>
            </form>
        </div>
    </div>

    <script src="../../js/jquery-2.1.1.min.js"></script>
    <script src="../../asset/js/bootstrap.min.js"></script>
    
    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fa fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fa fa-eye';
            }
        }
        
        document.getElementById('confirm_password').addEventListener('input', function() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            const message = document.getElementById('passwordMatchMessage');
            
            if (confirmPassword === '') {
                message.textContent = '';
            } else if (newPassword === confirmPassword) {
                message.textContent = '✓ Passwords match';
                message.style.color = '#28a745';
            } else {
                message.textContent = '✗ Passwords do not match';
                message.style.color = '#dc3545';
            }
        });
    </script>
</body>
</html>
