<?php
session_start();
include '../controller/AuthController.php';

$message = '';

if ($_POST && isset($_POST['email']) && isset($_POST['mdp'])) {
    $auth = new AuthController();
    $user = $auth->login($_POST['email'], $_POST['mdp']);

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OneWorld - Login</title>

    <!-- Chemins corrigés avec ../ -->
    <link href="../asset/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/animate.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/color/green.css">
    <link href='http://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
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

    <!-- Login Section -->
    <section class="login-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="login-form">
                        <div class="section-title text-center">
                            <h3>Login to Your Account</h3>
                            <p>Enter your credentials to access your account</p>
                        </div>
                        
                        <?php if ($message) echo "<div class='alert alert-danger'>$message</div>"; ?>
                        
                        <form name="loginForm" method="POST">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email">
                                </div>
                                <span class="form-error" id="emailError"></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="mdp">Password</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Enter your password">
                                </div>
                                <span class="form-error" id="passwordError"></span>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                <i class="fa fa-sign-in"></i> Sign In
                            </button>
                            
                            <div class="text-center" style="margin-top: 20px;">
                                <p>Don't have an account? <a href="register.php" class="text-primary">Create account</a></p>
                            </div>
                        </form>
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

    <!-- Scripts avec chemins corrigés -->
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