<?php
session_start();

if (!isset($_SESSION['facebook_data'])) {
    header("Location: login.php");
    exit();
}

$facebook_data = $_SESSION['facebook_data'];
$error = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $adresse = $_POST['adresse'] ?? '';
    
  
    $photo = "";
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        
        if (in_array(strtolower($extension), $allowed)) {
            $newFilename = uniqid() . "." . $extension;
            $uploadDir = "../uploads/";
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $newFilename)) {
                $photo = "uploads/" . $newFilename;
            }
        }
    }
    
    if (empty($email)) {
        $error = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif (empty($password)) {
        $error = "Password is required";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
      
        require_once '../../controller/AuthController.php';
        $auth = new AuthController();
        
        $userData = [
            'nom' => $facebook_data['nom'],
            'prenom' => $facebook_data['prenom'],
            'email' => $email,
            'mdp' => $password,
            'photo' => $photo,
            'telephone' => $telephone,
            'adresse' => $adresse,
            'facebook_id' => $facebook_data['facebook_id']
        ];
        
        $auth->register($userData);
        
        $pdo = config::getConnexion();
        $stmt = $pdo->prepare("SELECT * FROM user WHERE facebook_id = :facebook_id");
        $stmt->execute(['facebook_id' => $facebook_data['facebook_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $_SESSION['user'] = $user;
            unset($_SESSION['facebook_data']);
            
            $hist = new HistoriqueC();
            $h = new Historique("Connexion", date('Y-m-d H:i:s'), $user['id']);
            $hist->addHistorique($h);
            
            header("Location: " . ($user['role'] === 'admin' ? '../dashboard/profile.php' : '../dashboard/profil.php'));
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Complete Your Signup - OneWorld</title>

    <link href="../../asset/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/font-awesome.min.css" rel="stylesheet">
    <link href="../../css/animate.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
    <link href="../../css/responsive.css" rel="stylesheet">
    <link href="../../css/modern-green.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Oswald:300,400,700' rel='stylesheet' type='text/css'>
    
    <style>
        .profile-section {
            padding-bottom: 80px;
            margin-top: 0;
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
        
        .facebook-badge {
            background: #3b5998;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .facebook-badge i {
            margin-right: 8px;
        }
    </style>
</head>

<body class="index">
    
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header page-scroll">
                <a class="navbar-brand page-scroll" href="../../index.php">
                    <img src="../../images/logo.png" alt="OneWorld Logo" style="height: 45px; display: inline-block; vertical-align: middle; margin-right: 10px;">
                    OneWorld
                </a>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-title-section">
        <div class="container">
            <div class="page-title">Complete Your Signup</div>
            <div class="page-subtitle">
                <span class="facebook-badge">
                    <i class="fa fa-facebook"></i> Signing up with Facebook
                </span>
            </div>
        </div>
    </div>

    <!-- Signup Section -->
    <section class="profile-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="offer-card">
                        <div class="card-body">
                            <div class="offer-title">Welcome, <?php echo htmlspecialchars($facebook_data['prenom']); ?>!</div>
                            <p class="text-muted" style="margin-bottom: 30px;">Complete your account setup by providing the information below</p>
                            
                            <div class="row" style="margin-bottom: 20px;">
                                <div class="col-md-12">
                                    <div class="info-item">
                                        <div class="info-label">Name</div>
                                        <div class="info-value"><?php echo htmlspecialchars($facebook_data['prenom'] . ' ' . $facebook_data['nom']); ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="form-group text-center" style="margin-bottom: 30px;">
                                    <label>Profile Photo</label>
                                    <div style="margin: 15px 0;">
                                        <img id="photoPreview" src="../../images/default-avatar.png" alt="Preview" class="profile-avatar" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover;">
                                    </div>
                                    <input type="file" id="photo" name="photo" accept="image/*" style="display: none;" onchange="previewPhoto(event)">
                                    <label for="photo" class="btn btn-default">
                                        <i class="fa fa-camera"></i> Choose Photo
                                    </label>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="email">Email Address <span class="text-danger">*</span></label>
                                            <?php if (empty($facebook_data['email'])): ?>
                                                <small class="form-text text-muted">Facebook didn't share your email. Please enter your email address.</small>
                                            <?php endif; ?>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="email" 
                                                   name="email"
                                                   value="<?php echo htmlspecialchars($facebook_data['email']); ?>"
                                                   placeholder="Enter your email address">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Create a Password <span class="text-danger">*</span></label>
                                            <small class="form-text text-muted">You'll use this to login with your email</small>
                                            <div class="password-container">
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="password" 
                                                       name="password"
                                                       placeholder="Enter password (min 8 characters)">
                                                <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                                            <small class="form-text text-muted">&nbsp;</small>
                                            <div class="password-container">
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="confirm_password" 
                                                       name="confirm_password"
                                                       placeholder="Confirm your password">
                                                <button type="button" class="password-toggle" onclick="togglePassword('confirm_password', this)">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </div>
                                            <div id="passwordMatchMessage" style="font-size: 12px; margin-top: 5px;"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telephone">Phone Number</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="telephone" 
                                                   name="telephone"
                                                   placeholder="Enter your phone number">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="adresse">Address</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="adresse" 
                                                   name="adresse"
                                                   placeholder="Enter your address">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center" style="margin-top: 20px;">
                                    <button type="submit" class="btn btn-submit-app btn-lg">
                                        <i class="fa fa-check"></i> Complete Signup
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="style-1">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <span class="copyright">Copyright &copy; OneWorld 2025</span>
                </div>
            </div>
        </div>
    </footer>

    <script src="../../js/jquery-2.1.1.min.js"></script>
    <script src="../../asset/js/bootstrap.min.js"></script>
    <script src="../../js/script.js"></script>
    
    <script>
        function previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photoPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
        
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
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const message = document.getElementById('passwordMatchMessage');
            
            if (confirmPassword === '') {
                message.textContent = '';
            } else if (password === confirmPassword) {
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
