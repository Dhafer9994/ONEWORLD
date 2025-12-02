<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OneWorld - My Profile</title>

    <!-- Chemins corrigés avec ../ -->
    <link href="../asset/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/animate.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/color/green.css">
    <link href='http://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <style>
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid white;
            object-fit: cover;
        }
        
        .profile-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        
        .profile-card:hover {
            transform: translateY(-5px);
        }
        
        .info-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #667eea;
        }
        
        .info-value {
            color: #666;
        }
        
        .action-btn {
            margin: 5px;
            min-width: 150px;
        }
        
        .section-title {
            position: relative;
            margin-bottom: 30px;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50px;
            height: 3px;
            background: #667eea;
        }
        
        .profile-section {
            padding-top: 100px;
            padding-bottom: 50px;
        }
        
        .dropdown-menu li a {
            padding: 8px 20px;
        }
        
        .dropdown-menu li a i {
            margin-right: 10px;
            width: 20px;
        }


/* Visibilité du texte dans le dropdown */
.navbar-default .navbar-nav .dropdown-menu > li > a {
    color: #333 !important;
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
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user"></i> <?php echo htmlspecialchars($user['prenom']); ?> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="profil.php"><i class="fa fa-user-circle"></i> My Profile</a></li>
                            <li><a href="edit-profile.php"><i class="fa fa-edit"></i> Edit Profile</a></li>
                           
                            <li role="separator" class="divider"></li>
                            <li><a href="../controller/AuthController.php?action=logout"><i class="fa fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Profile Section -->
    <section class="profile-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="profile-header text-center">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="../images/default-avatar.png" alt="Profile photo" class="profile-avatar">
                            </div>
                            <div class="col-md-8 text-left" style="padding-top: 30px;">
                                <h2><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h2>
                                <p class="lead"><?php echo ucfirst($user['role']); ?> Account</p>
                                <p><i class="fa fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?></p>
                                <p><i class="fa fa-calendar"></i> Member since: <?php echo date('F Y'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default profile-card">
                        <div class="panel-body">
                            <h3 class="section-title">Personal Information</h3>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Full Name</div>
                                        <div class="info-value"><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Email Address</div>
                                        <div class="info-value"><?php echo htmlspecialchars($user['email']); ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Phone Number</div>
                                        <div class="info-value"><?php echo isset($user['telephone']) ? htmlspecialchars($user['telephone']) : 'Not provided'; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Account Type</div>
                                        <div class="info-value">
                                            <span class="label label-<?php echo $user['role'] == 'admin' ? 'danger' : 'success'; ?>">
                                                <?php echo ucfirst($user['role']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="info-item">
                                        <div class="info-label">Address</div>
                                        <div class="info-value"><?php echo isset($user['adresse']) ? htmlspecialchars($user['adresse']) : 'Not provided'; ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">User ID</div>
                                        <div class="info-value">#<?php echo $user['id']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Status</div>
                                        <div class="info-value">
                                            <span class="label label-success">
                                                <i class="fa fa-check-circle"></i> Active
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="panel panel-default profile-card">
                        <div class="panel-body">
                            <h3 class="section-title"> Actions</h3>
                            
                            <div class="text-center" style="margin: 20px 0;">
                                <a href="edit-profile.php" class="btn btn-primary btn-lg action-btn">
                                    <i class="fa fa-edit"></i> Edit Profile
                                </a>
                                
                              
                                <a href="../controller/AuthController.php?action=logout" class="btn btn-danger btn-lg action-btn" 
                                   onclick="return confirm('Are you sure you want to logout?')">
                                    <i class="fa fa-sign-out"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                    
                  
            <!-- Recent Activity Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default profile-card">
                        <div class="panel-body">
                            <h3 class="section-title">Recent Activity</h3>
                            
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Activity</th>
                                            <th>Status</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo date('Y-m-d H:i:s'); ?></td>
                                            <td>Account Login</td>
                                            <td><span class="label label-success">Successful</span></td>
                                            <td>Logged in from this device</td>
                                        </tr>
                                        <tr>
                                            <td><?php echo date('Y-m-d', strtotime('-1 day')) . ' 10:30:00'; ?></td>
                                            <td>Profile Update</td>
                                            <td><span class="label label-info">Updated</span></td>
                                            <td>Last profile modification</td>
                                        </tr>
                                        <tr>
                                            <td><?php echo date('Y-m-d', strtotime('-3 days')) . ' 14:20:00'; ?></td>
                                            <td>Password Change</td>
                                            <td><span class="label label-warning">Changed</span></td>
                                            <td>Password was updated</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
        $(document).ready(function() {
            // Animation des compteurs
            function animateCounter(element, target) {
                $({ count: 0 }).animate({ count: target }, {
                    duration: 2000,
                    easing: 'swing',
                    step: function() {
                        element.text(Math.floor(this.count));
                    },
                    complete: function() {
                        element.text(this.count);
                    }
                });
            }
            
            animateCounter($('.order-count'), 0);
            animateCounter($('.completed-count'), 0);
        });
    </script>

</body>
</html>