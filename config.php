<?php
// Configuration de l'application
define('APP_NAME', 'Événements Communautaires');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // 'development' ou 'production'

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'projetweb');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuration de l'admin
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123');

// Chemins
define('BASE_URL', 'http://localhost/projetweb/');
define('ADMIN_URL', BASE_URL . 'admin/');
define('FRONT_URL', BASE_URL . 'front/');

// Sécurité
define('CSRF_TOKEN_LIFE', 3600); // 1 heure en secondes
define('SESSION_LIFETIME', 86400); // 24 heures

// En mode production, désactiver l'affichage des erreurs
if (APP_ENV === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
?>