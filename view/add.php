<?php
session_start();
include '../controller/UtilisateurController.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (emailExists($_POST['email'])) {
        $error = "Cet email est déjà utilisé par un autre utilisateur";
    } else if ($_POST['password'] !== $_POST['confirm_password']) {
        $error = "Les mots de passe ne correspondent pas";
    } else {
        $user = new User(
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['email'],
            $_POST['password'],
            $_POST['role'],
            $_POST['telephone'],
            $_POST['adresse']
        );
        
        $uc = new UtilisateurController();
        $result = $uc->addUser($user);
        
        if ($result) {
            // REDIRECTION AVEC NOTIFICATION
            header('Location: liste.php?add_success=' . urlencode("Utilisateur ajouté avec succès"));
            exit();
        } else {
            $error = "Erreur lors de l'ajout de l'utilisateur";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="OneWorld Dashboard - Ajouter un utilisateur">
  
    <title>Ajouter un utilisateur - OneWorld Dashboard</title>
    
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
      .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: none;
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
            <div class="row">
              <div class="col-12">
                <div class="card card-default">
                  <div class="card-header">
                    <h2>Ajouter un utilisateur</h2>
                    <div class="header-actions">
                      <a href="liste.php" class="btn btn-outline-secondary">
                        <i class="mdi mdi-arrow-left"></i> Retour à la liste
                      </a>
                    </div>
                  </div>
                  <div class="card-body">
                    <?php if (isset($error)): ?>
                      <div class="alert alert-danger alert-dismissible fade show">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>
                    <?php endif; ?>

                    <form method="POST" action="" id="addForm">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="nom">Nom *</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>">
                            <div class="error-message" id="nomError"></div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="prenom">Prénom *</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>">
                            <div class="error-message" id="prenomError"></div>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="text" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            <div class="error-message" id="emailError"></div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="role">Rôle *</label>
                            <select class="form-control" id="role" name="role">
                              <option value="">Sélectionner un rôle</option>
                              <option value="user" <?php echo (isset($_POST['role']) && $_POST['role'] == 'user') ? 'selected' : ''; ?>>Utilisateur</option>
                              <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] == 'admin') ? 'selected' : ''; ?>>Administrateur</option>
                            </select>
                            <div class="error-message" id="roleError"></div>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="telephone">Téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" value="<?php echo isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : ''; ?>">
                            <div class="error-message" id="telephoneError"></div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="adresse">Adresse</label>
                            <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo isset($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : ''; ?>">
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="password">Mot de passe *</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="error-message" id="passwordError"></div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="confirm_password">Confirmer le mot de passe *</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            <div class="error-message" id="confirmPasswordError"></div>
                          </div>
                        </div>
                      </div>

                      <div class="form-footer mt-4">
                        <button type="submit" class="btn btn-primary">
                          <i class="mdi mdi-content-save"></i> Enregistrer
                        </button>
                        <a href="liste.php" class="btn btn-secondary">Annuler</a>
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
        event.preventDefault();
        
        // Réinitialiser les erreurs
        $('.error-message').hide();
        
        let isValid = true;
        
        // Validation du nom
        const nom = $('#nom').val().trim();
        if (nom === '') {
          $('#nomError').text('Le nom est obligatoire').show();
          isValid = false;
        }
        
        // Validation du prénom
        const prenom = $('#prenom').val().trim();
        if (prenom === '') {
          $('#prenomError').text('Le prénom est obligatoire').show();
          isValid = false;
        }
        
        // Validation de l'email
        const email = $('#email').val().trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === '') {
          $('#emailError').text('L\'email est obligatoire').show();
          isValid = false;
        } else if (!emailRegex.test(email)) {
          $('#emailError').text('Format d\'email invalide').show();
          isValid = false;
        }
        
        // Validation du rôle
        const role = $('#role').val();
        if (role === '') {
          $('#roleError').text('Le rôle est obligatoire').show();
          isValid = false;
        }
        
        // Validation du mot de passe
        const password = $('#password').val();
        if (password === '') {
          $('#passwordError').text('Le mot de passe est obligatoire').show();
          isValid = false;
        } else if (password.length < 6) {
          $('#passwordError').text('Le mot de passe doit contenir au moins 6 caractères').show();
          isValid = false;
        }
        
        // Validation de la confirmation du mot de passe
        const confirmPassword = $('#confirm_password').val();
        if (confirmPassword === '') {
          $('#confirmPasswordError').text('La confirmation du mot de passe est obligatoire').show();
          isValid = false;
        } else if (password !== confirmPassword) {
          $('#confirmPasswordError').text('Les mots de passe ne correspondent pas').show();
          isValid = false;
        }
       
        if (isValid) {
          // Vérification finale de l'email via AJAX
          $.get('add.php', { check_email: email })
            .done(function(data) {
              if (data.exists) {
                $('#emailError').text('Cet email est déjà utilisé').show();
              } else {
                document.getElementById('addForm').submit();
              }
            })
            .fail(function() {
              document.getElementById('addForm').submit();
            });
        }
      }

      // Vérification email en temps réel
      $('#email').on('blur', function() {
        const email = $(this).val().trim();
        const emailError = $('#emailError');
        
        if (email) {
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!emailRegex.test(email)) {
            emailError.text("Format d'email invalide").show();
          } else {
            $.get('add.php', { check_email: email })
              .done(function(data) {
                if (data.exists) {
                  emailError.text("Cet email est déjà utilisé").show();
                } else {
                  emailError.hide();
                }
              });
          }
        } else {
          emailError.hide();
        }
      });

      // Vérification des mots de passe en temps réel
      $('#confirm_password').on('input', function() {
        const password = $('#password').val();
        const confirmPassword = $(this).val();
        const passwordError = $('#confirmPasswordError');
        
        if (confirmPassword && password !== confirmPassword) {
          passwordError.text("Les mots de passe ne correspondent pas").show();
        } else {
          passwordError.hide();
        }
      });

      // Validation de la longueur du mot de passe en temps réel
      $('#password').on('input', function() {
        const password = $(this).val();
        const passwordError = $('#passwordError');
        
        if (password && password.length < 6) {
          passwordError.text("Le mot de passe doit contenir au moins 6 caractères").show();
        } else {
          passwordError.hide();
        }
      });

      // Attacher la validation au formulaire
      document.getElementById('addForm').onsubmit = validateForm;
    </script>
  </body>
</html>