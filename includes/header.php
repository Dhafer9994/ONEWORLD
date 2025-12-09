<?php
// formation_apprentissage/includes/header.php
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php 
        if (isset($page_title)) {
            echo htmlspecialchars($page_title) . ' - ';
        }
        ?>Formation & Apprentissage - OneWorld
    </title>
    
    <!-- CSS Principal -->
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    
    <!-- Meta Tags SEO -->
    <meta name="description" content="Plateforme de formation et d'apprentissage pour réfugiés - OneWorld">
    <meta name="keywords" content="formation, apprentissage, réfugiés, intégration, compétences">
    <meta name="author" content="OneWorld Team">
    
    <!-- Open Graph -->
    <meta property="og:title" content="Formation & Apprentissage - OneWorld">
    <meta property="og:description" content="Plateforme de formation pour l'intégration des réfugiés">
    <meta property="og:image" content="../assets/images/og-image.jpg">
    <meta property="og:url" content="<?php echo isset($_SERVER['HTTPS']) ? 'https://' : 'http://'; echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Styles supplémentaires selon la page -->
    <style>
        /* Styles globaux additionnels */
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        a:hover {
            color: var(--secondary-color);
        }
        
        .hidden {
            display: none !important;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-left {
            text-align: left;
        }
        
        .mt-1 { margin-top: 0.25rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-3 { margin-top: 1rem; }
        .mt-4 { margin-top: 1.5rem; }
        .mt-5 { margin-top: 3rem; }
        
        .mb-1 { margin-bottom: 0.25rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-3 { margin-bottom: 1rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        .mb-5 { margin-bottom: 3rem; }
        
        .p-1 { padding: 0.25rem; }
        .p-2 { padding: 0.5rem; }
        .p-3 { padding: 1rem; }
        .p-4 { padding: 1.5rem; }
        .p-5 { padding: 3rem; }
        
        /* Loading spinner */
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 4px solid var(--primary-color);
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                font-size: 12pt;
                color: #000;
            }
            
            a {
                color: #000;
                text-decoration: underline;
            }
        }
    </style>
    
    <!-- Scripts head -->
    <script>
        // Variables globales JavaScript
        const BASE_URL = '<?php echo isset($_SERVER['HTTPS']) ? 'https://' : 'http://'; echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']); ?>';
        const CSRF_TOKEN = '<?php echo bin2hex(random_bytes(32)); ?>';
        
        // Fonction pour afficher les messages
        function showMessage(type, message, duration = 5000) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `alert alert-${type} floating-message`;
            messageDiv.innerHTML = message;
            messageDiv.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                max-width: 500px;
                animation: slideInRight 0.3s ease;
            `;
            
            document.body.appendChild(messageDiv);
            
            setTimeout(() => {
                messageDiv.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => messageDiv.remove(), 300);
            }, duration);
            
            // Ajouter les animations CSS si elles n'existent pas
            if (!document.querySelector('#message-animations')) {
                const style = document.createElement('style');
                style.id = 'message-animations';
                style.textContent = `
                    @keyframes slideInRight {
                        from { transform: translateX(100%); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                    @keyframes slideOutRight {
                        from { transform: translateX(0); opacity: 1; }
                        to { transform: translateX(100%); opacity: 0; }
                    }
                `;
                document.head.appendChild(style);
            }
        }
        
        // Vérifier si l'utilisateur est connecté
        function isLoggedIn() {
            return <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
        }
        
        // Redirection si non connecté
        function requireLogin(redirectUrl = '../frontoffice/connexion.php') {
            if (!isLoggedIn()) {
                window.location.href = redirectUrl + '?redirect=' + encodeURIComponent(window.location.href);
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <!-- Skip to main content for accessibility -->
    <a href="#main-content" class="skip-link">Aller au contenu principal</a>
    
    <!-- Main container -->
    <div class="page-wrapper">
        <?php
        // Inclure la navigation
        require_once __DIR__ . '/nav.php';
        ?>
        
        <!-- Main content -->
        <main id="main-content" class="main-content">
            <div class="container">
                <?php
                // Afficher les messages flash
                if (isset($_SESSION['flash_messages'])) {
                    require_once __DIR__ . '/alerts.php';
                }
                ?>