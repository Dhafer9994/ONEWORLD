<?php
session_start();

// Check if this is an AJAX request to verify current password
if (isset($_POST['verify_current_password'])) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['valid' => false, 'message' => 'Not logged in']);
        exit();
    }
    
    $user_id = $_SESSION['user']['id'];
    $current_password = $_POST['current_password'] ?? '';
    
    include '../../controller/UtilisateurController.php';
    $uc = new UtilisateurController();
    $user_data = $uc->getUserById($user_id);
    
    if ($user_data && password_verify($current_password, $user_data['mdp'])) {
        echo json_encode(['valid' => true]);
    } else {
        echo json_encode(['valid' => false, 'message' => 'Current password is incorrect']);
    }
    exit();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: ../front-office/login.php");
    exit();
}

$user = $_SESSION['user'];

// Check if user is a social login user (no password set yet)
include '../../controller/UtilisateurController.php';
$uc = new UtilisateurController();
$user_data = $uc->getUserById($user['id']);
$isSocialUser = ($user_data && (
    strpos($user_data['mdp'], 'GOOGLE_LOGIN_NO_PASSWORD') !== false ||
    strpos($user_data['mdp'], 'FACEBOOK_LOGIN_NO_PASSWORD') !== false
));

$message = null;
$error = null;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['verify_current_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    $errors = [];
    
    // Validate current password only for non-social users
    if (!$isSocialUser && empty($current_password)) {
        $errors[] = "Current password is required";
    }
    
    if (empty($new_password)) {
        $errors[] = "New password is required";
    } elseif (strlen($new_password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    }
    
    if (empty($confirm_password)) {
        $errors[] = "Confirm password is required";
    } elseif ($new_password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    if (empty($errors)) {
        // Verify current password for non-social users
        if (!$isSocialUser) {
            if (!password_verify($current_password, $user_data['mdp'])) {
                $errors[] = "Current password is incorrect";
            }
        }
        
        if (empty($errors)) {
            try {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $result = $uc->updatePassword($user['id'], $hashed_password);
                
                if ($result) {
                    // Update session
                    $_SESSION['user']['mdp'] = $hashed_password;
                    
                    // Add to history
                    include_once '../../controller/HistoriqueC.php';
                    include '../model/historique.php';
                    $hc = new HistoriqueC();
                    $historique = new Historique();
                    $historique->setAction($isSocialUser ? 'Password Set (First Time)' : 'Password Changed');
                    $historique->setDateAction(date('Y-m-d H:i:s'));
                    $historique->setIdUser($user['id']);
                    $hc->addHistorique($historique);
                    
                    // Store success message in session and redirect
                    $_SESSION['password_success'] = $isSocialUser 
                        ? "Password set successfully! You can now login with email and password." 
                        : "Password changed successfully!";
                    
                    header("Location: profil.php");
                    exit();
                } else {
                    $error = "Error updating password. Please try again.";
                }
            } catch (Exception $e) {
                $error = "Error: " . $e->getMessage();
            }
        } else {
            $error = implode("<br>", $errors);
        }
    } else {
        $error = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OneWorld - Change Password</title>

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
            z-index: 2;
            position: relative;
        }
        
        .navbar-default .navbar-nav .dropdown-menu > li > a {
            color: #333 !important;
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
            z-index: 10;
        }
        
        .password-feedback {
            font-size: 12px;
            margin-top: 5px;
        }
        
        .password-feedback.error {
            color: #dc3545;
        }
        
        .password-feedback.success {
            color: #28a745;
        }
    </style>
</head>

<body class="index">
    
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="../../index.php">
                    <img src="../../images/logo.png" alt="OneWorld Logo" style="height: 45px; display: inline-block; vertical-align: middle; margin-right: 10px;">
                    OneWorld
                </a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="../../index.php">HOME</a></li>
                    <li><a href="../../index.php#about">About</a></li>
                    <li><a href="../../index.php#contact">Contact</a></li>
                    <li><a href="../../index.php#services">Services</a></li>
                    <li><a href="../../index.php#news">News</a></li>
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user"></i> <?php echo htmlspecialchars($user['prenom']); ?> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="profil.php"><i class="fa fa-user-circle"></i> My Profile</a></li>
                            <li><a href="edit-profile.php"><i class="fa fa-edit"></i> Edit Profile</a></li>
                            <li><a href="change-password.php"><i class="fa fa-key"></i> Change Password</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#" data-toggle="modal" data-target="#logoutModal"><i class="fa fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-title-section">
        <div class="container">
            <div class="page-title"><?php echo $isSocialUser ? 'Set Your Password' : 'Change Password'; ?></div>
            <div class="page-subtitle">
                <?php echo $isSocialUser 
                    ? 'Set a password to enable email login' 
                    : 'Update your account password for security'; ?>
            </div>
        </div>
    </div>

    <!-- Change Password Section -->
    <section class="profile-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <?php if ($message): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa fa-check-circle"></i> <?php echo $message; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="offer-card">
                        <div class="card-body">
                            <div class="offer-title">
                                <i class="fa fa-key"></i> 
                                <?php echo $isSocialUser ? 'Set Your Password' : 'Change Your Password'; ?>
                            </div>
                            
                            <?php if ($isSocialUser): ?>
                                <div class="alert alert-info" style="margin-bottom: 25px;">
                                    <i class="fa fa-info-circle"></i> 
                                    You signed up with social login. Set a password to enable email/password login.
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="" id="changePasswordForm">
                                <?php if (!$isSocialUser): ?>
                                    <div class="form-group">
                                        <label for="current_password">Current Password <span class="text-danger">*</span></label>
                                        <div class="password-container">
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="current_password" 
                                                   name="current_password"
                                                   placeholder="Enter your current password">
                                            <button type="button" class="password-toggle" onclick="togglePassword('current_password', this)">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                        <div id="currentPasswordFeedback" class="password-feedback"></div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="new_password">New Password <span class="text-danger">*</span></label>
                                            <small class="form-text text-muted">Minimum 8 characters</small>
                                            <div class="password-container">
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="new_password" 
                                                       name="new_password"
                                                       placeholder="Enter new password">
                                                <button type="button" class="password-toggle" onclick="togglePassword('new_password', this)">
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
                                                       placeholder="Confirm new password">
                                                <button type="button" class="password-toggle" onclick="togglePassword('confirm_password', this)">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </div>
                                            <div id="passwordMatchMessage" class="password-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center" style="margin-top: 30px;">
                                    <button type="submit" class="btn btn-submit-app btn-lg" id="submitBtn">
                                        <i class="fa fa-check"></i> 
                                        <?php echo $isSocialUser ? 'Set Password' : 'Update Password'; ?>
                                    </button>
                                    <a href="<?php echo ($user['role'] === 'admin') ? 'profile.php' : 'profil.php'; ?>" class="btn btn-default btn-lg" style="margin-left: 10px;">
                                        <i class="fa fa-times"></i> Cancel
                                    </a>
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

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="logoutModalLabel">Ready to Leave?</h4>
                </div>
                <div class="modal-body">
                    Select "Logout" below if you are ready to end your current session.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="../../controller/AuthController.php?action=logout">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="../../js/jquery-2.1.1.min.js"></script>
    <script src="../../asset/js/bootstrap.min.js"></script>
    <script src="../../js/script.js"></script>
    
    <script>
        let currentPasswordValid = <?php echo $isSocialUser ? 'true' : 'false'; ?>;
        
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
        
        // Real-time current password verification
        <?php if (!$isSocialUser): ?>
        $('#current_password').on('input', function() {
            const password = $(this).val();
            const feedback = $('#currentPasswordFeedback');
            
            if (password.length === 0) {
                feedback.html('').removeClass('error success');
                currentPasswordValid = false;
                return;
            }
            
            // Show checking message
            feedback.html('<i class="fa fa-spinner fa-spin"></i> Checking...').removeClass('error success');
            
            // AJAX call to verify password
            $.ajax({
                url: 'change-password.php',
                method: 'POST',
                data: {
                    verify_current_password: true,
                    current_password: password
                },
                dataType: 'json',
                success: function(response) {
                    if (response.valid) {
                        feedback.html('<i class="fa fa-check-circle"></i> Current password is correct')
                               .removeClass('error').addClass('success');
                        currentPasswordValid = true;
                    } else {
                        feedback.html('<i class="fa fa-times-circle"></i> ' + (response.message || 'Incorrect password'))
                               .removeClass('success').addClass('error');
                        currentPasswordValid = false;
                    }
                },
                error: function() {
                    feedback.html('<i class="fa fa-exclamation-circle"></i> Error checking password')
                           .removeClass('success').addClass('error');
                    currentPasswordValid = false;
                }
            });
        });
        <?php endif; ?>
        
        // Password match validation
        $('#confirm_password').on('input', function() {
            const newPassword = $('#new_password').val();
            const confirmPassword = $(this).val();
            const message = $('#passwordMatchMessage');
            
            if (confirmPassword === '') {
                message.html('').removeClass('error success');
            } else if (newPassword === confirmPassword) {
                message.html('<i class="fa fa-check-circle"></i> Passwords match')
                       .removeClass('error').addClass('success');
            } else {
                message.html('<i class="fa fa-times-circle"></i> Passwords do not match')
                       .removeClass('success').addClass('error');
            }
        });
        
        // Form submission validation
        $('#changePasswordForm').on('submit', function(e) {
            <?php if (!$isSocialUser): ?>
            if (!currentPasswordValid) {
                e.preventDefault();
                $('#currentPasswordFeedback').html('<i class="fa fa-times-circle"></i> Please enter the correct current password')
                       .removeClass('success').addClass('error');
                $('#current_password').focus();
                return false;
            }
            <?php endif; ?>
            
            const newPassword = $('#new_password').val();
            const confirmPassword = $('#confirm_password').val();
            
            if (newPassword.length < 8) {
                e.preventDefault();
                return false;
            }
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                $('#passwordMatchMessage').html('<i class="fa fa-times-circle"></i> Passwords do not match')
                       .removeClass('success').addClass('error');
                return false;
            }
        });
    </script>
</body>
</html>