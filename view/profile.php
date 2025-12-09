<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
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
    <title>OneWorld - Admin Profile</title>

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
        .profile-section {
            padding-bottom: 80px;
            margin-top: 0;
            z-index: 2;
            position: relative;
        }
        
        .navbar-default .navbar-nav .dropdown-menu > li > a {
            color: #333 !important;
        }
        
        .admin-label {
            background: #ff416c;
            color: white;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
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
                            <li><a href="profile.php"><i class="fa fa-user-circle"></i> My Profile <span class="admin-label">ADMIN</span></a></li>
                            <li><a href="edit-profile.php"><i class="fa fa-edit"></i> Edit Profile</a></li>
                            <li><a href="dash.php"><i class="fa fa-tachometer-alt"></i> Admin Dashboard</a></li>
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
            <div class="page-title">Admin Profile</div>
            <div class="page-subtitle">Administration and Account Management</div>
        </div>
    </div>

    <!-- Profile Section -->
    <section class="profile-section">
        <div class="container">
            <div class="row">
                <!-- Left Column: Avatar & Actions -->
                <div class="col-md-4">
                    <div class="offer-card text-center">
                        <div class="card-body">
                            <!-- CHANGE THE LINK BELOW TO YOUR OWN PHOTO URL/PATH -->
                            <img src="../images/eya.png" alt="Admin Profile" class="profile-avatar mb-3" style="margin-bottom: 20px;">
                            
                            <h3 style="margin-top: 10px; margin-bottom: 5px;"><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h3>
                            <p class="text-muted"><?php echo ucfirst($user['role']); ?> Account</p>
                            
                            <hr>
                            
                            <div class="d-grid gap-2">
                                <a href="edit-profile.php" class="btn btn-primary btn-block action-btn">
                                    <i class="fa fa-edit"></i> Edit Profile
                                </a>
                                
                                <a href="dash.php" class="btn btn-warning btn-block action-btn">
                                    <i class="fa fa-tachometer-alt"></i> Admin Dashboard
                                </a>
                                
                                <button class="btn btn-danger btn-block action-btn" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fa fa-sign-out"></i> Logout
                                </button>
                            </div>
                            
                            <!-- Admin Widget -->
                            <?php if ($user['role'] === 'admin'): 
                                include_once '../controller/UtilisateurController.php';
                                $userController = new UtilisateurController();
                                $allUsers = $userController->getAll();
                                $totalUsers = count($allUsers);
                            ?>
                            <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 10px; text-align: center; border: 1px solid #eee;">
                                <div style="font-size: 32px; color: var(--primary-color); font-weight: bold; margin-bottom: 8px;">
                                    <?php echo $totalUsers; ?>
                                </div>
                                <div style="color: #666; font-size: 14px;">Total Users</div>
                                <div style="font-size: 12px; color: #888; margin-top: 8px;">
                                    <i class="fa fa-shield"></i> System Status
                                </div>
                            </div>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                </div>
                
                <!-- Right Column: Personal Info -->
                <div class="col-md-8">
                    <div class="offer-card">
                        <div class="card-body">
                            <div class="offer-title">Personal Information</div>
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
                                            <span class="label label-danger">
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
                    
                    <!-- Recent Activity -->
                    <div class="offer-card" style="margin-top: 30px;">
                        <div class="card-body">
                            <div class="offer-title">Recent Activity</div>
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
                                            <td>Logged in as Administrator</td>
                                        </tr>
                                        <tr>
                                            <td><?php echo date('Y-m-d', strtotime('-1 day')) . ' 10:30:00'; ?></td>
                                            <td>Profile Update</td>
                                            <td><span class="label label-info">Updated</span></td>
                                            <td>Last profile modification</td>
                                        </tr>
                                        <tr>
                                            <td><?php echo date('Y-m-d', strtotime('-3 days')) . ' 14:20:00'; ?></td>
                                            <td>System Check</td>
                                            <td><span class="label label-warning">Completed</span></td>
                                            <td>System maintenance performed</td>
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
                    <a class="btn btn-primary" href="../controller/AuthController.php?action=logout">Logout</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>