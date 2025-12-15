<?php
// Main Router
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/AdminController.php';

// Define Constants
define('ASSETS_URL', 'assets/front');
define('ASSETS_ADMIN_URL', 'assets/admin');

// Simple Routing
$route = $_GET['route'] ?? 'home';

switch ($route) {
    case 'admin':
        $controller = new AdminController();
        $controller->dashboard();
        break;

    case 'home':
    default:
        $controller = new HomeController();
        $controller->index();
        break;
}
?>