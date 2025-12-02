<?php

session_start();
include '../controller/HistoriqueC.php';



$hc = new HistoriqueC();
$liste = $hc->ListeHistorique();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="OneWorld Dashboard - Historique des connexions">
  
    <title>Historique des connexions - OneWorld Dashboard</title>
    
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet" />
    <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />
  
    <!-- PLUGINS CSS STYLE -->
    <link href="../assets/plugins/simplebar/simplebar.css" rel="stylesheet" />
    <link href="../assets/plugins/nprogress/nprogress.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
  
    <!-- SLEEK CSS -->
    <link id="sleek-css" rel="stylesheet" href="../assets/css/sleek.css" />
  
    <!-- CUSTOM CSS POUR ONEWORLD -->
    <link rel="stylesheet" href="../assets/css/users.css">
  
    <!-- FAVICON -->
    <link href="../assets/img/favicon.png" rel="shortcut icon" />
  
    <script src="../assets/plugins/nprogress/nprogress.js"></script>

    <style>
      .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(45deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 14px;
        margin-right: 10px;
      }
      .badge-connexion {
        background: linear-gradient(45deg, #10b981, #059669);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
      }
      .badge-deconnexion {
        background: linear-gradient(45deg, #ef4444, #dc2626);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
      }
      .badge-creation {
        background: linear-gradient(45deg, #3b82f6, #1d4ed8);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
      }
      .badge-admin {
        background: linear-gradient(45deg, #FF416C, #FF4B2B);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
      }
      .badge-user {
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
      }
      .table th {
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #e9ecef;
      }
      .user-info {
        display: flex;
        align-items: center;
      }
      .user-id { 
        background: #f8f9fa; 
        padding: 2px 6px; 
        border-radius: 4px; 
        font-size: 12px; 
        color: #666; 
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
                    <h2>Historique des connexions</h2>
                    <div class="header-actions">
                      <a href="login.php" class="btn btn-outline-secondary">
                        <i class="mdi mdi-logout"></i> Déconnexion
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tableau de l'historique -->
            <div class="row">
              <div class="col-12">
                <div class="card card-default">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover" id="historyTable">
                        <thead>
                          <tr>
                            <th>Utilisateur</th>
                            <th>ID User</th>
                            <th>Rôle</th>
                            <th>Action</th>
                            <th>Date/Heure</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if(!empty($liste)): ?>
                            <?php foreach ($liste as $h): ?>
                              <tr>
                                <td>
                                  <div class="user-info">
                                    <div class="user-avatar">
                                      <?php echo strtoupper(substr($h['prenom'], 0, 1) . substr($h['nom'], 0, 1)); ?>
                                    </div>
                                    <div>
                                      <strong><?= htmlspecialchars($h['prenom'] . ' ' . $h['nom']) ?></strong>
                                      <br>
                                      <small class="text-muted"><?= htmlspecialchars($h['email']) ?></small>
                                    </div>
                                  </div>
                                </td>
                                <td>
                                  <span class="user-id">#<?= $h['id_user'] ?></span>
                                </td>
                                <td>
                                  <span class="badge-<?= htmlspecialchars($h['role']) ?>">
                                    <?= ucfirst(htmlspecialchars($h['role'])) ?>
                                  </span>
                                </td>
                                <td>
                                  <span class="badge-<?= strtolower($h['action']) ?>">
                                    <?= htmlspecialchars($h['action']) ?>
                                  </span>
                                </td>
                                <td>
                                  <?php 
                                  if (!empty($h['date']) && $h['date'] != '0000-00-00 00:00:00') {
                                    $date = new DateTime($h['date']);
                                    echo $date->format('d/m/Y à H:i:s');
                                  } else {
                                    echo 'Date non disponible';
                                  }
                                  ?>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                          <?php else: ?>
                            <tr>
                              <td colspan="5" class="text-center">Aucun historique trouvé</td>
                            </tr>
                          <?php endif; ?>
                        </tbody>
                      </table>
                    </div>
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

   
    <script>
      $(document).ready(function() {
        $('#historyTable').DataTable({
          "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
          },
          "responsive": true,
          "order": [[4, "desc"]],
          "pageLength": 10
        });
      });
    </script>

  </body>
</html>