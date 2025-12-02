<?php
session_start();
include '../controller/UtilisateurController.php';

$uc = new UtilisateurController();

if (!isset($_GET['id'])) {
    exit("Error: No user ID provided.");
}

$id = $_GET['id'];  

$pdo = config::getConnexion();
$stmt = $pdo->prepare("SELECT * FROM user WHERE id = :id");
$stmt->execute(['id' => $id]);
$user = $stmt->fetch();

if (!$user) {
    exit("Error: User not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedUser = new User(
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['email'],
        $user['mdp'],
        $_POST['role'],
        $_POST['telephone'],
        $_POST['adresse']
    );
    $updatedUser->setId($id);

    $result = $uc->updateUser($updatedUser);
    
    if ($result) {
        // REDIRECTION DIRECTE VERS LISTE.PHP
        header('Location: liste.php?edit_success=' . urlencode("Utilisateur modifié avec succès"));
        exit();
    } else {
        $error = "Erreur lors de la modification de l'utilisateur";
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="OneWorld Dashboard - Modifier un utilisateur">
  
    <title>Modifier un utilisateur - OneWorld Dashboard</title>
    
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet" />
    <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />
  
    <!-- PLUGINS CSS STYLE -->
    <link href="../assets/plugins/simplebar/simplebar.css" rel="stylesheet" />
    <link href="../assets/plugins/nprogress/nprogress.css" rel="stylesheet" />
  
    <!-- SLEEK CSS -->
    <link id="sleek-css" rel="stylesheet" href="../assets/css/sleek.css" />
  
    <!-- CUSTOM CSS POUR ONEWORLD -->
    <link rel="stylesheet" href="../assets/css/oneworld.css">
  
    <!-- FAVICON -->
    <link href="../assets/img/favicon.png" rel="shortcut icon" />
  
    <script src="../assets/plugins/nprogress/nprogress.js"></script>

    <style>
      .header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
      }
      .form-group {
        margin-bottom: 1.5rem;
      }
      .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
      }
      .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
      }
      .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
      }
      .form-footer {
        border-top: 1px solid #e5e7eb;
        padding-top: 1.5rem;
        text-align: center;
      }
      .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: none;
      }
      .form-control.error {
        border-color: #dc3545;
      }
    </style>
  </head>

  <body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body">
    <script>
      NProgress.configure({ showSpinner: false });
      NProgress.start();
    </script>

    <div id="toaster"></div>

    <div class="wrapper">
      <!-- Sidebar -->
      <?php include '../partials/sidebar.php'; ?>

      <div class="page-wrapper">
        <!-- Header -->
        <?php include '../partials/header.php'; ?>

        <div class="content-wrapper">
          <div class="content">
            <!-- En-tête de la page -->
            <div class="row">
              <div class="col-12">
                <div class="card card-default">
                  <div class="card-header">
                    <h2>Modifier l'utilisateur</h2>
                    <div class="header-actions">
                      <a href="liste.php" class="btn btn-outline-secondary">
                        <i class="mdi mdi-arrow-left"></i> Retour à la liste
                      </a>
                      <a href="../logout.php" class="btn btn-outline-secondary">
                        <i class="mdi mdi-logout"></i> Déconnexion
                      </a>
                    </div>
                  </div>
                  <div class="card-body">
                    <?php if (isset($error)): ?>
                      <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>
                    <?php endif; ?>

                    <form name="editForm" method="POST" id="editForm">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="nom" class="form-label">Nom *</label>
                            <input type="text" class="form-control" id="nom" name="nom" 
                                   value="<?= htmlspecialchars($user['nom']) ?>">
                            <div class="error-message" id="nomError"></div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="prenom" class="form-label">Prénom *</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" 
                                   value="<?= htmlspecialchars($user['prenom']) ?>">
                            <div class="error-message" id="prenomError"></div>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="email" class="form-label">Email *</label>
                            <input type="text" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($user['email']) ?>">
                            <div class="error-message" id="emailError"></div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="role" class="form-label">Rôle *</label>
                            <select class="form-control" id="role" name="role">
                              <option value="">Sélectionner un rôle</option>
                              <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>Utilisateur</option>
                              <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Administrateur</option>
                            </select>
                            <div class="error-message" id="roleError"></div>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="text" class="form-control" id="telephone" name="telephone" 
                                   value="<?= htmlspecialchars($user['telephone']) ?>">
                            <div class="error-message" id="telephoneError"></div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="adresse" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="adresse" name="adresse" 
                                   value="<?= htmlspecialchars($user['adresse']) ?>">
                          </div>
                        </div>
                      </div>

                      <div class="form-footer mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                          <i class="mdi mdi-content-save"></i> Mettre à jour
                        </button>
                        <a href="liste.php" class="btn btn-secondary btn-lg">Annuler</a>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <?php include '../partials/footer.php'; ?>
      </div>
    </div>

    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/plugins/simplebar/simplebar.min.js"></script>
    <script src="../assets/js/sleek.js"></script>
    <script src="../assets/js/oneworld.js"></script>

    <script>
      function validateForm(event) {
        // Empêcher la soumission par défaut
        event.preventDefault();
        
        // Réinitialiser les erreurs
        $('.error-message').hide();
        $('.form-control').removeClass('error');
        
        let isValid = true;
        
        // Validation du nom
        const nom = $('#nom').val().trim();
        if (nom === '') {
          $('#nomError').text('Le nom est obligatoire').show();
          $('#nom').addClass('error');
          isValid = false;
        }
        
        // Validation du prénom
        const prenom = $('#prenom').val().trim();
        if (prenom === '') {
          $('#prenomError').text('Le prénom est obligatoire').show();
          $('#prenom').addClass('error');
          isValid = false;
        }
        
        // Validation de l'email
        const email = $('#email').val().trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === '') {
          $('#emailError').text('L\'email est obligatoire').show();
          $('#email').addClass('error');
          isValid = false;
        } else if (!emailRegex.test(email)) {
          $('#emailError').text('Format d\'email invalide').show();
          $('#email').addClass('error');
          isValid = false;
        }
        
        // Validation du rôle
        const role = $('#role').val();
        if (role === '') {
          $('#roleError').text('Le rôle est obligatoire').show();
          $('#role').addClass('error');
          isValid = false;
        }
        
        
        if (isValid) {
          document.getElementById('editForm').submit();
        }
        
        return false; 
      }

      $('#email').on('blur', function() {
        const email = $(this).val().trim();
        const emailError = $('#emailError');
        
        if (email) {
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!emailRegex.test(email)) {
            emailError.text("Format d'email invalide").show();
            $(this).addClass('error');
          } else {
            emailError.hide();
            $(this).removeClass('error');
          }
        } else {
          emailError.hide();
          $(this).removeClass('error');
        }
      });

     
      $('#nom, #prenom').on('blur', function() {
        const value = $(this).val().trim();
        const errorId = $(this).attr('id') + 'Error';
        
        if (value === '') {
          $('#' + errorId).text('Ce champ est obligatoire').show();
          $(this).addClass('error');
        } else {
          $('#' + errorId).hide();
          $(this).removeClass('error');
        }
      });

      
      $('#role').on('change', function() {
        const role = $(this).val();
        if (role === '') {
          $('#roleError').text('Le rôle est obligatoire').show();
          $(this).addClass('error');
        } else {
          $('#roleError').hide();
          $(this).removeClass('error');
        }
      });

      document.getElementById('editForm').onsubmit = validateForm;

  
      let formChanged = false;
      const form = document.forms["editForm"];
      
      Array.from(form.elements).forEach(element => {
        element.addEventListener('change', () => {
          formChanged = true;
        });
        element.addEventListener('input', () => {
          formChanged = true;
        });
      });

      window.addEventListener('beforeunload', (e) => {
        if (formChanged) {
          e.preventDefault();
          e.returnValue = '';
        }
      });

      form.addEventListener('submit', () => {
        formChanged = false;
      });
    </script>

  </body>
</html>