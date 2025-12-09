<?php
session_start();
include '../controller/AuthController.php';

$message = '';


$facebook_login_url = 'fb.php'; 
$google_login_url = 'google.php';     

function emailExists($email) {
    $pdo = config::getConnexion();
    $stmt = $pdo->prepare("SELECT id FROM user WHERE email = :email");
    $stmt->execute(['email' => trim($email)]);
    return $stmt->fetch() !== false;
}

if (isset($_GET['check_email'])) {
    header('Content-Type: application/json');
    echo json_encode(['exists' => emailExists($_GET['check_email'])]);
    exit();
}

if ($_POST) {
    $data = array_map('trim', $_POST);

    if (!empty($data['nom']) && !empty($data['prenom']) && !empty($data['email']) &&
        !empty($data['mdp']) && !empty($data['telephone']) && !empty($data['adresse'])) {

        if (emailExists($data['email'])) {
            $message = "This email is already registered. Please use a different email.";
        } else {
            $auth = new AuthController();
            $success = $auth->register($data);

            if ($success) {
                $message = "Registration successful! You can now log in";
                $_SESSION['user'] = $user;
                header("Location: login.php");
                exit();
            } else {
                $message = "Error during registration. Please try again";
            }
        }
    } else {
        $message = "Please fill all required fields";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>OneWorld - Registration</title>

    <link href="../asset/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/animate.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <!-- Modern Green CSS -->
    <link href="../css/modern-green.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Oswald:300,400,700' rel='stylesheet' type='text/css'>

    <style>
        .register-card {
            padding: 40px;
            margin-top: -50px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            margin-bottom: 50px;
        }

        .social-login-section {
            margin-bottom: 30px;
        }
        
        .social-divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
            color: #666;
        }
        
        .social-divider::before,
        .social-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #ddd;
        }
        
        .social-divider span {
            padding: 0 15px;
            font-size: 14px;
        }
        
        .social-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .social-btn {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 3px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 500;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .social-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-decoration: none;
        }
        
        .social-btn.facebook {
            background: #3b5998;
            color: white;
            border: none;
        }
        
        .social-btn.facebook:hover {
            background: #344e86;
            color: white;
        }
        
        .social-btn.google {
            background: #db4437;
            color: white;
            border: none;
        }
        
        .social-btn.google:hover {
            background: #c23325;
            color: white;
        }
        
        .form-error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
        
        .page-title-section {
            padding: 120px 0 100px;
        }
        
        @media (max-width: 768px) {
            .social-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>

<body class="index">
    
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="../index.php">
                    <img src="../images/logo.png" alt="OneWorld Logo" style="height: 45px; margin-right: 10px;">
                    OneWorld
                </a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="../index.php">HOME</a></li>
                    <li><a href="../index.php#about">About</a></li>
                    <li><a href="../index.php#contact">Contact</a></li>
                    <li><a href="../index.php#services">Services</a></li>
                    <li><a href="../index.php#news">News</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Page Header -->
    <div class="page-title-section no-img">
        <div class="container">
            <div class="page-title">Create Account</div>
            <div class="page-subtitle">Join OneWorld today and start your journey</div>
        </div>
    </div>

    <section class="register-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="offer-card register-card">
                        <div class="card-body">
                            <div class="text-center mb-4" style="margin-bottom: 30px;">
                                <h3 class="offer-title">Sign Up</h3>
                            </div>
                            
                            <?php if ($message): ?>
                                <div class='alert <?php echo strpos($message, 'successful') !== false ? 'alert-success' : 'alert-danger'; ?>'>
                                    <?php echo $message; ?>
                                </div>
                            <?php endif; ?>
                            
                         
                            <div class="social-login-section">
                                <div class="social-buttons">
                                    <a href="<?php echo $facebook_login_url; ?>" class="social-btn facebook">
                                        <i class="fa fa-facebook"></i>
                                        <span>Continue with Facebook</span>
                                    </a>
                                    
                                    <a href="<?php echo $google_login_url; ?>" class="social-btn google">
                                        <i class="fa fa-google"></i>
                                        <span>Continue with Google</span>
                                    </a>
                                </div>
                                
                                <div class="social-divider">
                                    <span>Or sign up with email</span>
                                </div>
                            </div>
                            
                            <!-- FORMULAIRE EMAIL -->
                            <form name="registerForm" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nom">Name</label>
                                            <input type="text" class="form-control" id="nom" name="nom" placeholder="Enter your name" value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>">
                                            <span class="form-error" id="nomError"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="prenom">User Name</label>
                                            <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Enter your username" value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>">
                                            <span class="form-error" id="prenomError"></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                    <span class="form-error" id="emailError"></span>
                                </div>

                                <div class="form-group">
                                    <label for="image">Profile Picture</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                </div>
                                
                                <div class="form-group">
                                    <label for="mdp">Password</label>
                                    <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Enter your password">
                                    <span class="form-error" id="mdpError"></span>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telephone">Phone</label>
                                            <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Enter your phone number" value="<?php echo isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : ''; ?>">
                                            <span class="form-error" id="telephoneError"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="adresse">Address</label>
                                            <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Enter your address" value="<?php echo isset($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : ''; ?>">
                                            <span class="form-error" id="adresseError"></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group" style="margin-top: 20px;">
                                    <button type="submit" class="btn btn-submit-app btn-block btn-lg">
                                        <i class="fa fa-user-plus"></i> Create Account
                                    </button>
                                </div>
                                
                                <div class="text-center" style="margin-top: 20px;">
                                    <p>Already have an account? <a href="login.php" style="color: var(--primary-color);">Login here</a></p>
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

    <script src="../js/jquery-2.1.1.min.js"></script>
    <script src="../asset/js/bootstrap.min.js"></script>

    <script>
        function validateForm(event) {
            event.preventDefault(); 
            const nom = document.forms["registerForm"]["nom"].value.trim();
            const prenom = document.forms["registerForm"]["prenom"].value.trim();
            const email = document.forms["registerForm"]["email"].value.trim();
            const mdp = document.forms["registerForm"]["mdp"].value;
            const telephone = document.forms["registerForm"]["telephone"].value.trim();
            const adresse = document.forms["registerForm"]["adresse"].value.trim();

            document.getElementById("nomError").innerText = "";
            document.getElementById("prenomError").innerText = "";
            document.getElementById("emailError").innerText = "";
            document.getElementById("mdpError").innerText = "";
            document.getElementById("telephoneError").innerText = "";
            document.getElementById("adresseError").innerText = "";

            let valid = true;

            if (nom === "") {
                document.getElementById("nomError").innerText = "Name is required";
                valid = false;
            }
            if (prenom === "") {
                document.getElementById("prenomError").innerText = "User Name is required";
                valid = false;
            }
            if (email === "") {
                document.getElementById("emailError").innerText = "Email is required";
                valid = false;
            }
            if (mdp === "") {
                document.getElementById("mdpError").innerText = "Password is required";
                valid = false;
            }
            if (telephone === "") {
                document.getElementById("telephoneError").innerText = "Phone is required";
                valid = false;
            }
            if (adresse === "") {
                document.getElementById("adresseError").innerText = "Address is required";
                valid = false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email !== "" && !emailRegex.test(email)) {
                document.getElementById("emailError").innerText = "Invalid email address";
                valid = false;
            }

            if (mdp !== "" && mdp.length < 6) {
                document.getElementById("mdpError").innerText = "Password must be at least 6 characters";
                valid = false;
            }

            if (telephone !== "" && !/^\d+$/.test(telephone)) {
                document.getElementById("telephoneError").innerText = "Phone number must contain only digits";
                valid = false;
            }

            if (valid) {
                $.get('register.php', { check_email: email })
                    .done(function(data) {
                        if (data.exists) {
                            document.getElementById("emailError").innerText = "This email is already registered";
                        } else {
                            document.forms["registerForm"].submit();
                        }
                    })
                    .fail(function() {
                        document.forms["registerForm"].submit();
                    });
            }
        }

        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value.trim();
            const emailError = document.getElementById('emailError');
            
            if (email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    emailError.innerText = "Invalid email address";
                } else {
                    $.get('register.php', { check_email: email })
                        .done(function(data) {
                            if (data.exists) {
                                emailError.innerText = "This email is already registered";
                            } else {
                                emailError.innerText = "";
                            }
                        });
                }
            } else {
                emailError.innerText = "";
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.querySelector('form[name="registerForm"]');
            if (registerForm) {
                registerForm.onsubmit = validateForm;
            }
        });
    </script>

</body>
</html>