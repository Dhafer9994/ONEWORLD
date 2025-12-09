<?php
// formation_apprentissage/includes/auth_check.php

/**
 * Vérification d'authentification et autorisations
 */

/**
 * Vérifie si l'utilisateur est connecté et redirige si nécessaire
 */
function requireLogin($redirectTo = '../frontoffice/connexion.php') {
    if (!isLoggedIn()) {
        $currentUrl = $_SERVER['REQUEST_URI'];
        header('Location: ' . $redirectTo . '?redirect=' . urlencode($currentUrl));
        exit();
    }
    return true;
}

/**
 * Vérifie si l'utilisateur est admin et redirige si nécessaire
 */
function requireAdmin($redirectTo = '../../backoffice/admin_login.php') {
    if (!isAdmin()) {
        $_SESSION['flash_messages'][] = [
            'type' => 'error',
            'text' => 'Accès réservé aux administrateurs.'
        ];
        header('Location: ' . $redirectTo);
        exit();
    }
    return true;
}

/**
 * Vérifie si l'utilisateur est réfugié et redirige si nécessaire
 */
function requireRefugie($redirectTo = '../../frontoffice/connexion.php') {
    if (!isRefugie()) {
        $_SESSION['flash_messages'][] = [
            'type' => 'error',
            'text' => 'Accès réservé aux réfugiés.'
        ];
        header('Location: ' . $redirectTo);
        exit();
    }
    return true;
}

/**
 * Vérifie si l'utilisateur a accès à une formation spécifique
 */
function canAccessFormation($formationId) {
    if (isAdmin()) {
        return true;
    }
    
    if (!isRefugie()) {
        return false;
    }
    
    // Vérifier si le réfugié est inscrit à la formation
    require_once __DIR__ . '/../models/InscriptionModel.php';
    $inscriptionModel = new InscriptionModel();
    $refugieId = $_SESSION['refugie_id'];
    
    $inscription = $inscriptionModel->getInscriptionByRefugieAndFormation($refugieId, $formationId);
    
    return !empty($inscription);
}

/**
 * Vérifie si l'utilisateur peut modifier une formation
 */
function canEditFormation($formationId) {
    if (isAdmin()) {
        return true;
    }
    
    // Vérifier si l'utilisateur est le formateur de cette formation
    require_once __DIR__ . '/../models/FormationModel.php';
    $formationModel = new FormationModel();
    $formation = $formationModel->getById($formationId);
    
    if (!$formation) {
        return false;
    }
    
    return (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $formation['formateur_id']);
}

/**
 * Vérifie si l'utilisateur peut voir les détails d'une inscription
 */
function canViewInscription($inscriptionId) {
    if (isAdmin()) {
        return true;
    }
    
    if (!isRefugie()) {
        return false;
    }
    
    // Vérifier si l'inscription appartient au réfugié
    require_once __DIR__ . '/../models/InscriptionModel.php';
    $inscriptionModel = new InscriptionModel();
    $inscription = $inscriptionModel->getById($inscriptionId);
    
    if (!$inscription) {
        return false;
    }
    
    return ($_SESSION['refugie_id'] == $inscription['refugie_id']);
}

/**
 * Vérifie les permissions CSRF
 */
function verifyCsrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!verifyCsrfToken($token)) {
            http_response_code(403);
            die('Token CSRF invalide. Veuillez rafraîchir la page.');
        }
    }
}

/**
 * Limite le taux de requêtes pour prévenir les abus
 */
function rateLimit($key, $limit = 10, $timeout = 60) {
    session_start();
    
    $now = time();
    $requests = $_SESSION['rate_limit'][$key] ?? [];
    
    // Nettoyer les anciennes requêtes
    $requests = array_filter($requests, function($timestamp) use ($now, $timeout) {
        return ($now - $timestamp) < $timeout;
    });
    
    // Vérifier la limite
    if (count($requests) >= $limit) {
        http_response_code(429);
        die('Trop de requêtes. Veuillez patienter quelques instants.');
    }
    
    // Ajouter la requête actuelle
    $requests[] = $now;
    $_SESSION['rate_limit'][$key] = $requests;
    
    return true;
}

/**
 * Vérifie l'origine de la requête (same-origin)
 */
function verifyOrigin() {
    $allowedOrigins = ['http://localhost', 'http://127.0.0.1'];
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    
    if (!empty($origin) && !in_array($origin, $allowedOrigins)) {
        http_response_code(403);
        die('Origine non autorisée.');
    }
    
    return true;
}

/**
 * Vérifie le referer pour les formulaires
 */
function verifyReferer($allowedPages = []) {
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $serverName = $_SERVER['SERVER_NAME'];
    
    if (empty($referer)) {
        return false;
    }
    
    // Vérifier que le referer vient du même domaine
    if (strpos($referer, $serverName) === false) {
        return false;
    }
    
    // Si des pages spécifiques sont fournies, vérifier
    if (!empty($allowedPages)) {
        $valid = false;
        foreach ($allowedPages as $page) {
            if (strpos($referer, $page) !== false) {
                $valid = true;
                break;
            }
        }
        return $valid;
    }
    
    return true;
}

/**
 * Génère et valide un token JWT simple
 */
class SimpleJWT {
    private static $secret = 'your-secret-key-change-this';
    
    public static function encode($payload) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode($payload);
        
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secret, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }
    
    public static function decode($jwt) {
        $parts = explode('.', $jwt);
        if (count($parts) != 3) {
            return false;
        }
        
        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = $parts;
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secret, true);
        $base64UrlSignatureToVerify = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        if ($base64UrlSignature !== $base64UrlSignatureToVerify) {
            return false;
        }
        
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $base64UrlPayload)), true);
        
        // Vérifier l'expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false;
        }
        
        return $payload;
    }
}

/**
 * Middleware pour vérifier l'authentification via JWT (API)
 */
function jwtAuthMiddleware() {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';
    
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $jwt = $matches[1];
        $payload = SimpleJWT::decode($jwt);
        
        if ($payload && isset($payload['user_id'])) {
            $_SESSION['user_id'] = $payload['user_id'];
            $_SESSION['user_role'] = $payload['role'] ?? 'user';
            return true;
        }
    }
    
    http_response_code(401);
    echo json_encode(['error' => 'Non authentifié']);
    exit();
}
?>