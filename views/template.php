<?php
// formation_apprentissage/views/template.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formation & Apprentissage - OneWorld</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <a href="index.php" class="logo">
                <span class="logo-icon">📚</span>
                <span>OneWorld Formation</span>
            </a>
            
            <div class="nav-container">
                <div class="nav-links">
                    <?php if (isset($_SESSION['admin_id'])): ?>
                        <!-- Liens Admin -->
                        <a href="index.php?controller=formation&action=admin_list" 
                           class="nav-link <?php echo ($_GET['action'] ?? '') == 'admin_list' ? 'active' : ''; ?>">
                            👨‍🏫 Formations
                        </a>
                        <a href="index.php?controller=inscription&action=admin_list" 
                           class="nav-link <?php echo ($_GET['action'] ?? '') == 'admin_list' ? 'active' : ''; ?>">
                            📋 Inscriptions
                        </a>
                        <a href="../../backoffice/admin_dashboard.php" class="nav-link">
                            🏠 Dashboard
                        </a>
                    <?php elseif (isset($_SESSION['refugie_id'])): ?>
                        <!-- Liens Réfugié -->
                        <a href="index.php?controller=formation&action=front_list" 
                           class="nav-link <?php echo ($_GET['action'] ?? '') == 'front_list' ? 'active' : ''; ?>">
                            📚 Formations
                        </a>
                        <a href="index.php?controller=inscription&action=mes_inscriptions" 
                           class="nav-link <?php echo ($_GET['action'] ?? '') == 'mes_inscriptions' ? 'active' : ''; ?>">
                            📋 Mes inscriptions
                        </a>
                        <a href="../../frontoffice/dashboard.php" class="nav-link">
                            👤 Mon compte
                        </a>
                    <?php else: ?>
                        <!-- Liens Visiteur -->
                        <a href="index.php" 
                           class="nav-link <?php echo (!isset($_GET['action']) || $_GET['action'] == 'front_list') ? 'active' : ''; ?>">
                            📚 Formations
                        </a>
                        <a href="../../frontoffice/connexion.php" class="nav-link">
                            🔐 Connexion
                        </a>
                        <a href="../../frontoffice/inscription_form.php" class="nav-link">
                            📝 Inscription
                        </a>
                    <?php endif; ?>
                    <a href="../../index.php" class="nav-link">
                        🏠 Accueil
                    </a>
                </div>
                
                <?php if (isset($_SESSION['refugie_id']) || isset($_SESSION['admin_id'])): ?>
                    <div class="user-info">
                        <div class="user-avatar">
                            <?php 
                            $initials = '';
                            if (isset($_SESSION['refugie_nom'])) {
                                $initials = substr($_SESSION['refugie_nom'], 0, 1);
                            } elseif (isset($_SESSION['admin_nom'])) {
                                $initials = substr($_SESSION['admin_nom'], 0, 1);
                            }
                            echo strtoupper($initials);
                            ?>
                        </div>
                        <div class="user-details">
                            <div class="user-name">
                                <?php 
                                echo isset($_SESSION['refugie_nom']) 
                                    ? htmlspecialchars($_SESSION['refugie_nom']) 
                                    : (isset($_SESSION['admin_nom']) 
                                        ? htmlspecialchars($_SESSION['admin_nom']) 
                                        : 'Utilisateur');
                                ?>
                            </div>
                            <div class="user-role">
                                <?php echo isset($_SESSION['admin_id']) ? 'Administrateur' : 'Réfugié'; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
        
        <div class="content">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <?php 
                    $messages = [
                        'created' => '✅ Formation créée avec succès !',
                        'updated' => '✅ Formation mise à jour avec succès !',
                        'deleted' => '✅ Formation supprimée avec succès !',
                        'subscribed' => '✅ Inscription effectuée avec succès !',
                        'status_updated' => '✅ Statut mis à jour avec succès !',
                        'cancelled' => '✅ Inscription annulée avec succès !'
                    ];
                    echo $messages[$_GET['success']] ?? '✅ Opération réussie !';
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <?php 
                    $errors = [
                        'no_places' => '⛔ Plus de places disponibles pour cette formation.',
                        'already_subscribed' => '⛔ Vous êtes déjà inscrit à cette formation.',
                        'formation_not_found' => '⛔ Formation non trouvée.',
                        'missing_data' => '⛔ Données manquantes.',
                        'invalid_status' => '⛔ Statut invalide.',
                        'update_failed' => '⛔ Échec de la mise à jour.',
                        'cancel_failed' => '⛔ Échec de l\'annulation.',
                        'delete_failed' => '⛔ Échec de la suppression.',
                        'notfound' => '⛔ Ressource non trouvée.'
                    ];
                    $errorKey = $_GET['error'];
                    echo isset($errors[$errorKey]) 
                        ? $errors[$errorKey] 
                        : (strpos($errorKey, ' ') !== false 
                            ? '⛔ ' . htmlspecialchars($errorKey) 
                            : '⛔ Une erreur est survenue.');
                    ?>
                </div>
            <?php endif; ?>