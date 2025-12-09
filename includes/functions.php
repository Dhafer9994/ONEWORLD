<?php
// formation_apprentissage/includes/functions.php

/**
 * Fonctions utilitaires pour l'application
 */

/**
 * Redirection avec message flash
 */
function redirectWithMessage($url, $message, $type = 'info') {
    if (!isset($_SESSION['flash_messages'])) {
        $_SESSION['flash_messages'] = [];
    }
    $_SESSION['flash_messages'][] = [
        'type' => $type,
        'text' => $message
    ];
    header('Location: ' . $url);
    exit();
}

/**
 * Vérifie si l'utilisateur est connecté
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) || isset($_SESSION['refugie_id']) || isset($_SESSION['admin_id']);
}

/**
 * Vérifie si l'utilisateur est administrateur
 */
function isAdmin() {
    return isset($_SESSION['admin_id']);
}

/**
 * Vérifie si l'utilisateur est réfugié
 */
function isRefugie() {
    return isset($_SESSION['refugie_id']);
}

/**
 * Formate une date en français
 */
function formatDateFrench($date, $withTime = false) {
    if (empty($date)) return 'Non définie';
    
    $timestamp = strtotime($date);
    $formats = [
        'd/m/Y' . ($withTime ? ' H:i' : ''),
        'Y-m-d' . ($withTime ? ' H:i:s' : '')
    ];
    
    foreach ($formats as $format) {
        $dateObj = DateTime::createFromFormat($format, $date);
        if ($dateObj !== false) {
            $timestamp = $dateObj->getTimestamp();
            break;
        }
    }
    
    $months = [
        'January' => 'janvier', 'February' => 'février', 'March' => 'mars',
        'April' => 'avril', 'May' => 'mai', 'June' => 'juin',
        'July' => 'juillet', 'August' => 'août', 'September' => 'septembre',
        'October' => 'octobre', 'November' => 'novembre', 'December' => 'décembre'
    ];
    
    $days = [
        'Monday' => 'lundi', 'Tuesday' => 'mardi', 'Wednesday' => 'mercredi',
        'Thursday' => 'jeudi', 'Friday' => 'vendredi', 'Saturday' => 'samedi',
        'Sunday' => 'dimanche'
    ];
    
    $dateStr = date('l j F Y', $timestamp);
    if ($withTime) {
        $dateStr .= ' à ' . date('H:i', $timestamp);
    }
    
    foreach ($days as $en => $fr) {
        $dateStr = str_replace($en, $fr, $dateStr);
    }
    
    foreach ($months as $en => $fr) {
        $dateStr = str_replace($en, $fr, $dateStr);
    }
    
    return $dateStr;
}

/**
 * Valide un email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Génère un mot de passe sécurisé
 */
function generatePassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $password;
}

/**
 * Hash un mot de passe
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Vérifie un mot de passe
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Échappe les données pour l'affichage HTML
 */
function escape($data) {
    if (is_array($data)) {
        return array_map('escape', $data);
    }
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Tronque un texte avec des points de suspension
 */
function truncateText($text, $length = 100, $ellipsis = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    $text = substr($text, 0, $length);
    $text = substr($text, 0, strrpos($text, ' '));
    return $text . $ellipsis;
}

/**
 * Formate un nombre avec séparateurs de milliers
 */
function formatNumber($number, $decimals = 0) {
    return number_format($number, $decimals, ',', ' ');
}

/**
 * Calcule l'âge à partir d'une date de naissance
 */
function calculateAge($birthdate) {
    if (empty($birthdate)) return null;
    
    $birth = new DateTime($birthdate);
    $now = new DateTime();
    $age = $now->diff($birth);
    return $age->y;
}

/**
 * Vérifie si une date est valide
 */
function isValidDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Génère un token CSRF
 */
function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie un token CSRF
 */
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Upload un fichier
 */
function uploadFile($file, $allowedTypes = [], $maxSize = 2097152, $uploadDir = '../uploads/') {
    $errors = [];
    
    // Vérifier les erreurs d'upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Erreur lors du téléchargement du fichier.';
        return ['success' => false, 'errors' => $errors];
    }
    
    // Vérifier la taille
    if ($file['size'] > $maxSize) {
        $errors[] = 'Le fichier est trop volumineux. Taille maximum: ' . formatFileSize($maxSize);
    }
    
    // Vérifier le type
    $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!empty($allowedTypes) && !in_array($fileType, $allowedTypes)) {
        $errors[] = 'Type de fichier non autorisé. Types autorisés: ' . implode(', ', $allowedTypes);
    }
    
    // Vérifier si le fichier est une image (pour les images seulement)
    if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $imageInfo = getimagesize($file['tmp_name']);
        if (!$imageInfo) {
            $errors[] = 'Le fichier n\'est pas une image valide.';
        }
    }
    
    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }
    
    // Générer un nom de fichier unique
    $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9\.\-]/', '_', $file['name']);
    $destination = $uploadDir . $filename;
    
    // Créer le répertoire s'il n'existe pas
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Déplacer le fichier
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return [
            'success' => true,
            'filename' => $filename,
            'path' => $destination,
            'original_name' => $file['name'],
            'size' => $file['size'],
            'type' => $fileType
        ];
    } else {
        $errors[] = 'Erreur lors du déplacement du fichier.';
        return ['success' => false, 'errors' => $errors];
    }
}

/**
 * Formate la taille d'un fichier
 */
function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, 2) . ' ' . $units[$pow];
}

/**
 * Envoie un email
 */
function sendEmail($to, $subject, $body, $isHTML = true, $from = 'no-reply@oneworld.tn') {
    $headers = "From: $from\r\n";
    $headers .= "Reply-To: $from\r\n";
    
    if ($isHTML) {
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    } else {
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    }
    
    return mail($to, $subject, $body, $headers);
}

/**
 * Génère un code de vérification
 */
function generateVerificationCode($length = 6) {
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= random_int(0, 9);
    }
    return $code;
}

/**
 * Calcule la progression en pourcentage
 */
function calculateProgress($current, $total) {
    if ($total == 0) return 0;
    $progress = ($current / $total) * 100;
    return min(100, max(0, round($progress)));
}

/**
 * Génère un slug URL-friendly
 */
function generateSlug($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    
    if (empty($text)) {
        return 'n-a';
    }
    
    return $text;
}

/**
 * Vérifie si une chaîne contient du HTML
 */
function containsHTML($string) {
    return $string !== strip_tags($string);
}

/**
 * Nettoie une chaîne de caractères
 */
function sanitizeString($string) {
    $string = strip_tags($string);
    $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    $string = trim($string);
    $string = stripslashes($string);
    return $string;
}

/**
 * Convertit un tableau en objet JSON sécurisé
 */
function jsonSafeEncode($data) {
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
}

/**
 * Journalise une action
 */
function logAction($userId, $action, $details = []) {
    $logFile = '../logs/actions.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Inconnu';
    
    $logEntry = json_encode([
        'timestamp' => $timestamp,
        'user_id' => $userId,
        'action' => $action,
        'details' => $details,
        'ip' => $ip,
        'user_agent' => $userAgent
    ], JSON_PRETTY_PRINT);
    
    // Créer le dossier logs s'il n'existe pas
    if (!is_dir('../logs')) {
        mkdir('../logs', 0777, true);
    }
    
    file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND | LOCK_EX);
}

/**
 * Débogue une variable
 */
function debug($var, $die = false) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    if ($die) die();
}
?>