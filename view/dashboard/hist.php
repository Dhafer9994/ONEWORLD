<?php
session_start();
date_default_timezone_set('Africa/Tunis');
include '../../controller/HistoriqueC.php';


$delete_notification = null;
if (isset($_SESSION['delete_notification'])) {
    $delete_notification = $_SESSION['delete_notification'];
    unset($_SESSION['delete_notification']);
}

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
    
    <style>
/* Popup de confirmation style moderne */
.confirmation-popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.confirmation-box {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    width: 400px;
    max-width: 90%;
    animation: popup-appear 0.2s ease-out;
}

@keyframes popup-appear {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.confirmation-header {
    padding: 20px 24px 0;
    text-align: center;
}

.confirmation-header h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    color: #1f2937;
}

.confirmation-body {
    padding: 20px 24px;
    text-align: center;
    color: #6b7280;
    font-size: 15px;
    line-height: 1.5;
}

.confirmation-footer {
    padding: 16px 24px 24px;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.confirmation-btn {
    padding: 10px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    min-width: 80px;
}

.confirmation-btn.cancel {
    background: #f3f4f6;
    color: #374151;
}

.confirmation-btn.cancel:hover {
    background: #e5e7eb;
}

.confirmation-btn.delete {
    background: #dc2626;
    color: white;
}

.confirmation-btn.delete:hover {
    background: #b91c1c;
}

/* STYLES NOTIFICATION DE SUPPRESSION */
.success-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #10b981;
    color: white;
    padding: 16px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 10000;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: slideIn 0.3s ease-out;
    max-width: 400px;
}

.success-notification.error {
    background: #ef4444;
}

.success-notification.hide {
    animation: slideOut 0.3s ease-in;
    opacity: 0;
}

.success-notification .close-btn {
    background: transparent;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 18px;
    margin-left: auto;
    padding: 0 5px;
}

.success-notification .close-btn:hover {
    opacity: 0.8;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.success-notification i {
    font-size: 20px;
}
/* FIN STYLES NOTIFICATION */

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

.action-buttons .btn {
    margin: 2px;
    border-radius: 6px;
    padding: 4px 8px;
    font-size: 12px;
}

.timestamp-cell {
    white-space: nowrap;
    font-family: 'Courier New', monospace;
    font-size: 13px;
}
    </style>

    <!-- Popup de confirmation -->
    <div class="confirmation-popup" id="confirmationPopup">
        <div class="confirmation-box">
            <div class="confirmation-header">
                <h3>Confirmer la suppression</h3>
            </div>
            <div class="confirmation-body">
                <p id="confirmationMessage">Êtes-vous sûr de vouloir supprimer cet historique ?</p>
                <p style="font-size: 13px; color: #9ca3af; margin-top: 8px;">
                    Cette action est irréversible.
                </p>
            </div>
            <div class="confirmation-footer">
                <button type="button" class="confirmation-btn cancel" onclick="closeConfirmation()">
                    Annuler
                </button>
                <button type="button" class="confirmation-btn delete" id="confirmDeleteBtn">
                    Supprimer
                </button>
            </div>
        </div>
    </div>

    <script>
    let historiqueToDelete = null;

    function showDeleteConfirmation(historiqueId, userName, action, date) {
        historiqueToDelete = historiqueId;
        const popup = document.getElementById('confirmationPopup');
        const message = document.getElementById('confirmationMessage');
        
        message.innerHTML = `Êtes-vous sûr de vouloir supprimer l'historique de <strong>"${userName}"</strong> ?<br>
                            <small>Action: ${action} - Date: ${date}</small>`;
        
        popup.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeConfirmation() {
        const popup = document.getElementById('confirmationPopup');
        popup.style.display = 'none';
        historiqueToDelete = null;
        document.body.style.overflow = 'auto';
    }

    function confirmDelete() {
        if (historiqueToDelete) {
            window.location.href = `deletehistorique.php?id=${historiqueToDelete}`;
        }
    }

    // Fermer la popup en cliquant à l'extérieur
    document.getElementById('confirmationPopup').addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmation();
        }
    });

    // Lier le bouton de confirmation
    document.getElementById('confirmDeleteBtn').addEventListener('click', confirmDelete);

    // Fermer avec la touche Échap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeConfirmation();
        }
    });
    </script>
    
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet" />
    <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />
  
    <!-- PLUGINS CSS STYLE -->
    <link href="../../assets/plugins/simplebar/simplebar.css" rel="stylesheet" />
    <link href="../../assets/plugins/nprogress/nprogress.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
  
    <!-- SLEEK CSS -->
    <link id="sleek-css" rel="stylesheet" href="../../assets/css/sleek.css" />
  
    <!-- CUSTOM CSS POUR ONEWORLD -->
    <link rel="stylesheet" href="../../assets/css/users.css">
  
    <!-- FAVICON -->
    <link href="../../assets/img/favicon.png" rel="shortcut icon" />
  
    <script src="../../assets/plugins/nprogress/nprogress.js"></script>
  </head>

  <body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body">
    <script>
      NProgress.configure({ showSpinner: false });
      NProgress.start();
    </script>

    <div id="toaster"></div>

    <!-- NOTIFICATION DE SUPPRESSION -->
    <?php if ($delete_notification): ?>
    <div class="success-notification <?php echo $delete_notification['type'] === 'error' ? 'error' : ''; ?>" id="deleteNotification">
        <i class="mdi <?php echo $delete_notification['type'] === 'success' ? 'mdi-check-circle' : 'mdi-alert-circle'; ?>"></i>
        <span><?php echo htmlspecialchars($delete_notification['message']); ?></span>
        <button class="close-btn" onclick="this.parentElement.remove()">×</button>
    </div>
    <?php endif; ?>

    <div class="wrapper">
      <!-- Sidebar -->
      <?php include 'partials/sidebar.php'; ?>

      <div class="page-wrapper">
        <!-- Header -->
        <?php include 'partials/header.php'; ?>

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
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if(!empty($liste)): ?>
                            <?php foreach ($liste as $h): ?>
                              <?php
                              // Formatage de la date et heure
                              $dateFormatted = '';
                              $timestamp = '';
                              
                              if (!empty($h['date']) && $h['date'] != '0000-00-00 00:00:00') {
                                  try {
                                      $date = new DateTime($h['date']);
                                      
                                      // Vérifier si l'heure est 00:00:00
                                      if ($date->format('H:i:s') === '00:00:00') {
                                          $currentTime = new DateTime();
                                          $date->setTime(
                                              $currentTime->format('H'),
                                              $currentTime->format('i'),
                                              $currentTime->format('s')
                                          );
                                      }
                                      
                                      $dateFormatted = $date->format('Y-m-d H:i:s');
                                      $timestamp = $date->format('Y-m-d H:i:s');
                                  } catch (Exception $e) {
                                      $currentDate = new DateTime();
                                      $dateFormatted = $currentDate->format('Y-m-d H:i:s');
                                      $timestamp = $currentDate->format('Y-m-d H:i:s');
                                  }
                              } else {
                                  $currentDate = new DateTime();
                                  $dateFormatted = $currentDate->format('Y-m-d H:i:s');
                                  $timestamp = $currentDate->format('Y-m-d H:i:s');
                              }
                              
                              // ID de l'historique (ajustez selon votre structure de base de données)
                              // Si votre table historique a un champ 'id', utilisez $h['id']
                              // Sinon, créez un identifiant unique
                              $historiqueId = isset($h['id']) ? $h['id'] : $h['id_user'] . '_' . strtotime($timestamp);
                              ?>
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
                                <td class="timestamp-cell" data-order="<?= htmlspecialchars($timestamp) ?>">
                                  <?= htmlspecialchars($dateFormatted) ?>
                                </td>
                                <td>
                                  <div class="action-buttons">
                                    <button type="button" class="btn btn-danger btn-sm" title="Supprimer"
                                            onclick="showDeleteConfirmation(
                                                '<?= $historiqueId ?>',
                                                '<?= htmlspecialchars($h['prenom'] . ' ' . $h['nom']) ?>',
                                                '<?= htmlspecialchars($h['action']) ?>',
                                                '<?= htmlspecialchars($dateFormatted) ?>'
                                            )">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                  </div>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                          <?php else: ?>
                            <tr>
                              <td colspan="6" class="text-center">Aucun historique trouvé</td>
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
        <?php include 'partials/footer.php'; ?>
      </div>
    </div>

   
    <script src="../../assets/plugins/jquery/jquery.min.js"></script>
    <script src="../../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
      $(document).ready(function() {
        $('#historyTable').DataTable({
          "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
          },
          "responsive": true,
          "order": [[4, "desc"]], // Tri descendant sur la colonne Date/Heure
          "pageLength": 10,
          "columnDefs": [
            {
              "targets": [4], // Colonne Date/Heure
              "type": "date",
              "render": function(data, type, row) {
                return data;
              }
            }
          ]
        });

        // FERMER AUTOMATIQUEMENT LES NOTIFICATIONS APRÈS 5 SECONDEs
        setTimeout(function() {
          $('.success-notification').each(function() {
            $(this).addClass('hide');
            
            setTimeout(() => {
              $(this).remove();
            }, 300);
          });
        }, 1000);

        // Nettoyer l'URL
        if (window.location.search.includes('delete_notification')) {
          setTimeout(function() {
            window.history.replaceState({}, document.title, window.location.pathname);
          }, 50);
        }
      });

      // Fonction pour fermer manuellement les notifications
      function closeAllNotifications() {
        $('.success-notification').each(function() {
          $(this).addClass('hide');
          setTimeout(() => {
            $(this).remove();
          }, 300);
        });
      }

      // Fermer au clic en dehors
      document.addEventListener('click', function(e) {
        if (!e.target.closest('.success-notification') && !e.target.classList.contains('close-btn')) {
          closeAllNotifications();
        }
      });
    </script>

  </body>
</html>