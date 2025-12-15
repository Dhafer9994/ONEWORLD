<?php
session_start();


if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];


$update_success = null;
$update_error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $errors = [];
    
  
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
  
    if (empty($nom)) $errors[] = "Last name is required";
    if (empty($prenom)) $errors[] = "First name is required";
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    
    
    $photo = $user['photo'];
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $fileType = $_FILES['photo']['type'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = "Only JPG, PNG and GIF files are allowed";
        } elseif ($_FILES['photo']['size'] > $maxSize) {
            $errors[] = "File size must be less than 2MB";
        } else {
            $uploadDir = '../../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '_' . $user['id'] . '.' . strtolower($extension);
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                if (!empty($user['photo']) && $user['photo'] !== 'images/default-avatar.png' && file_exists('../../' . $user['photo'])) {
                    unlink('../../' . $user['photo']);
                }
                $photo = 'uploads/' . $fileName;
            } else {
                $errors[] = "Failed to upload image";
            }
        }
    } elseif (isset($_FILES['photo']) && $_FILES['photo']['error'] !== 4) { // Error 4 = No file uploaded
        $errors[] = "Error uploading file. Code: " . $_FILES['photo']['error'];
    }
    
    
    if (empty($errors)) {
        include_once '../../controller/UtilisateurController.php';
        include_once '../../model/user.php';
        $uc = new UtilisateurController();
        
        try {
            
            $updatedUser = new User(
                $nom, 
                $prenom, 
                $email, 
                $user['mdp'],
                $user['role'], 
                $telephone, 
                $adresse, 
                $photo
            );
            $updatedUser->setId($user['id']);

            $result = $uc->updateUser($updatedUser);
            
            if ($result) {
                $_SESSION['user'] = array_merge($user, [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'telephone' => $telephone,
                    'adresse' => $adresse,
                    'photo' => $photo
                ]);
                
                $_SESSION['update_success'] = "Profile updated successfully!";
                $redirectPage = ($user['role'] === 'admin') ? 'profile.php' : 'profil.php';
                header("Location: " . $redirectPage);
                exit();
            } else {
                $update_error = "Error updating profile. Please try again.";
            }
        } catch (Exception $e) {
            $update_error = "Error: " . $e->getMessage();
        }
    } else {
        $update_error = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OneWorld - Edit Profile</title>

    <link href="../../asset/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/font-awesome.min.css" rel="stylesheet">
    <link href="../../css/animate.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
    <link href="../../css/responsive.css" rel="stylesheet">
    <!-- New Modern Green CSS -->
    <link href="../../css/modern-green.css" rel="stylesheet">
    
    <link href='http://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Oswald:300,400,700' rel='stylesheet' type='text/css'>

    <style>
        /* Specific overrides for this page */
        .profile-container {
            margin-top: -30px; /* Overlap header slightly or just spacing */
        }
        
        .avatar-card {
            text-align: center;
            padding: 30px 20px;
        }
        
        .profile-name {
            font-family: 'Oswald', sans-serif;
            font-size: 24px;
            margin-top: 15px;
            color: #333;
        }
        
        .profile-email {
            color: #777;
            margin-bottom: 20px;
        }
        
        .action-btn-group {
            margin-top: 20px;
        }
        
        .action-btn-group .btn {
            margin-bottom: 10px;
            width: 100%;
            border-radius: 30px;
        }
        
        .form-section-title {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 25px;
            font-family: 'Oswald', sans-serif;
            font-size: 20px;
            color: var(--primary-color);
        }
        
        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
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
                            <li><a href="<?php echo ($user['role'] === 'admin') ? 'profile.php' : 'profil.php'; ?>"><i class="fa fa-user-circle"></i> My Profile</a></li>
                            <li><a href="edit-profile.php"><i class="fa fa-edit"></i> Edit Profile</a></li>
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
            <div class="page-title">Edit Profile</div>
            <div class="page-subtitle">Update your personal information settings</div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="profile-section">
        <div class="container profile-container">
            <!-- Notifications -->
            <?php if ($update_success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle"></i> <?php echo $update_success; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            
            <?php if ($update_error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle"></i> <?php echo $update_error; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" enctype="multipart/form-data" id="editProfileForm">
                <div class="row">
                    <!-- Left Column: Avatar & Actions -->
                    <div class="col-md-4">
                        <div class="offer-card avatar-card">
                            <div class="photo-upload" style="position: relative; display: inline-block;">
                                <?php 
                                    $imgSrc = !empty($user['photo']) ? "../../" . $user['photo'] : "../../images/default-avatar.png"; 
                                ?>
                                <img src="<?php echo htmlspecialchars($imgSrc); ?>" 
                                     alt="Profile photo" 
                                     class="profile-avatar"
                                     id="profileImage">
                                <input type="file" 
                                       name="photo" 
                                       id="photoInput" 
                                       accept="image/*"
                                       style="display: none;"
                                       onchange="previewImage(event)">
                                <div class="text-center mt-2">
                                    <label for="photoInput" class="photo-upload-label">
                                        <i class="fa fa-camera"></i> Change Photo
                                    </label>
                                </div>
                            </div>
                            <div class="error-message" id="photoError" style="display:none; text-align: center;"></div>
                            
                            <h3 class="profile-name"><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h3>
                            <p class="profile-email"><i class="fa fa-envelope-o"></i> <?php echo htmlspecialchars($user['email']); ?></p>
                            
                            <hr>
                            
                            <div class="action-btn-group">
                                <button type="submit" class="btn btn-submit-app">Save Changes</button>
                                <a href="change-password.php" class="btn btn-default" style="border: 1px solid #ccc;">Change Password</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column: Edit Form -->
                    <div class="col-md-8">
                        <div class="offer-card">
                            <div class="card-body">
                                <h4 class="form-section-title">Personal Information</h4>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="prenom">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>">
                                            <div class="error-message" id="prenomError"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nom">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>">
                                            <div class="error-message" id="nomError"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email Address <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                                            <div class="error-message" id="emailError"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telephone">Phone Number</label>
                                            <input type="text" class="form-control" id="telephone" name="telephone" value="<?php echo isset($user['telephone']) ? htmlspecialchars($user['telephone']) : ''; ?>">
                                            <div class="error-message" id="telephoneError"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="adresse">Address</label>
                                    <textarea class="form-control" id="adresse" name="adresse" rows="3"><?php echo isset($user['adresse']) ? htmlspecialchars($user['adresse']) : ''; ?></textarea>
                                </div>
                                
                                <br>
                                <h4 class="form-section-title">Account Details</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>User ID</label>
                                            <input type="text" class="form-control" value="#<?php echo $user['id']; ?>" disabled style="background-color: #f9f9f9;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Role</label>
                                            <input type="text" class="form-control" value="<?php echo ucfirst($user['role']); ?>" disabled style="background-color: #f9f9f9;">
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>
                                <div class="text-right">
                                    <a href="<?php echo ($user['role'] === 'admin') ? 'profile.php' : 'profil.php'; ?>" class="btn btn-default" style="margin-right: 10px;">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
    <script src="../../js/jquery-2.1.1.min.js"></script>
    <script src="../../asset/js/bootstrap.min.js"></script>
    <script src="../../js/script.js"></script>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            const image = document.getElementById('profileImage');
            const file = event.target.files[0];
            
            if (file) {
                const maxSize = 2 * 1024 * 1024;
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                
                if (!allowedTypes.includes(file.type)) {
                    document.getElementById('photoError').innerText = 'Only JPG, PNG and GIF files are allowed';
                    document.getElementById('photoError').style.display = 'block';
                    document.getElementById('photoInput').value = '';
                    return;
                }
                
                if (file.size > maxSize) {
                    document.getElementById('photoError').innerText = 'File size must be less than 2MB';
                    document.getElementById('photoError').style.display = 'block';
                    document.getElementById('photoInput').value = '';
                    return;
                }
                
                document.getElementById('photoError').style.display = 'none';
                reader.onload = function() {
                    image.src = reader.result;
                }
                reader.readAsDataURL(file);
            }
        }
        
        // Form Validation Logic
        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            errorElement.textContent = message;
            errorElement.style.display = 'block';
            document.getElementById(elementId.replace('Error', '')).classList.add('is-invalid');
        }
        
        function hideError(elementId) {
            const errorElement = document.getElementById(elementId);
            errorElement.style.display = 'none';
            document.getElementById(elementId.replace('Error', '')).classList.remove('is-invalid');
        }
        
        document.getElementById('editProfileForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate First Name
            const prenom = document.getElementById('prenom');
            if (prenom.value.trim() === '') {
                showError('prenomError', 'First name is required');
                isValid = false;
            } else {
                hideError('prenomError');
            }
            
            // Validate Last Name
            const nom = document.getElementById('nom');
            if (nom.value.trim() === '') {
                showError('nomError', 'Last name is required');
                isValid = false;
            } else {
                hideError('nomError');
            }
            
            // Validate Email
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email.value.trim() === '') {
                showError('emailError', 'Email is required');
                isValid = false;
            } else if (!emailRegex.test(email.value.trim())) {
                showError('emailError', 'Invalid email format');
                isValid = false;
            } else {
                hideError('emailError');
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
        
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
    
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
                    <a class="btn btn-primary btn-submit-app" href="../../controller/AuthController.php?action=logout">Logout</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>