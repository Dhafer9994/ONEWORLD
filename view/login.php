<?php
session_start();
include '../controller/AuthController.php';

$message = '';

if ($_POST && isset($_POST['email']) && isset($_POST['mdp'])) {
    $auth = new AuthController();
    if (!$auth->login($_POST['email'], $_POST['mdp'])) {
        $message = "Incorrect Email or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OneWorld - Login</title>

    <link href="../asset/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/animate.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">
    <!-- Modern Green CSS -->
    <link href="../css/modern-green.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Oswald:300,400,700' rel='stylesheet' type='text/css'>
    
    <style>
        .login-card {
            padding: 40px;
            margin-top: -50px; /* Overlap header */
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
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
    </style>
</head>

<body>
    
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
                <a class="navbar-brand page-scroll" href="../index.php">
                    <img src="../images/logo.png" alt="OneWorld Logo" style="height: 45px; display: inline-block; vertical-align: middle; margin-right: 10px;">
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
                    <li class="active"><a href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-title-section no-img">
        <div class="container">
            <div class="page-title">Welcome Back</div>
            <div class="page-subtitle">Access your account to manage your profile and settings</div>
        </div>
    </div>

    <!-- Login Section -->
    <section class="login-section" style="padding-bottom: 80px;">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="offer-card login-card">
                        <div class="card-body">
                            <div class="text-center mb-4" style="margin-bottom: 30px;">
                                <h3 class="offer-title" style="font-size: 24px;">Login to Your Account</h3>
                                <p class="text-muted">Enter your credentials below</p>
                            </div>
                            
                            <?php if ($message) echo "<div class='alert alert-danger'><i class='fa fa-exclamation-circle'></i> $message</div>"; ?>
                            
                            <form name="loginForm" method="POST">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-addon" style="background:transparent; border-right:0;"><i class="fa fa-envelope text-muted"></i></span>
                                        <input type="text" class="form-control" style="border-left:0;" id="email" name="email" placeholder="Enter your email">
                                    </div>
                                    <span class="form-error" id="emailError"></span>
                                </div>
                                
                                <div class="form-group">
                                    <label for="mdp">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-addon" style="background:transparent; border-right:0;"><i class="fa fa-lock text-muted"></i></span>
                                        <input type="password" class="form-control" style="border-left:0;" id="mdp" name="mdp" placeholder="Enter your password">
                                    </div>
                                    <span class="form-error" id="passwordError"></span>
                                </div>
                                
                                <div class="form-group" style="margin-top: 30px;">
                                    <button type="submit" class="btn btn-submit-app btn-block btn-lg">
                                        <i class="fa fa-sign-in"></i> Sign In
                                    </button>
                                </div>
                                
                                <div class="text-center" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                                    <p>Don't have an account? <a href="register.php" style="color: var(--primary-color); font-weight: 600;">Create account</a></p>
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

    <!-- Scripts -->
    <script src="../js/jquery-2.1.1.min.js"></script>
    <script src="../asset/js/bootstrap.min.js"></script>
    <script src="../js/script.js"></script>

    <script>
        function validateLogin(event) {
            event.preventDefault();
            
            const email = document.forms["loginForm"]["email"].value.trim();
            const password = document.forms["loginForm"]["mdp"].value.trim();

            document.getElementById("emailError").innerText = "";
            document.getElementById("passwordError").innerText = "";

            let valid = true;

            // Validation email
            if (email === "") {
                document.getElementById("emailError").innerText = "Email is required";
                valid = false;
            } else {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    document.getElementById("emailError").innerText = "Invalid email address";
                    valid = false;
                }
            }

            // Validation password
            if (password === "") {
                document.getElementById("passwordError").innerText = "Password is required";
                valid = false;
            } else if (password.length < 6) {
                document.getElementById("passwordError").innerText = "Password must be at least 6 characters";
                valid = false;
            }

            if (valid) {
                document.forms["loginForm"].submit();
            }
        }

        // Attacher le validateur
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('form[name="loginForm"]');
            if (loginForm) {
                loginForm.onsubmit = validateLogin;
            }
        });
    </script>

</body>
</html>