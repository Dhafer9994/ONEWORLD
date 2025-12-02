<?php
include '../config.php';

header('Content-Type: application/json');

if (isset($_GET['email'])) {
    $email = trim($_GET['email']);
    
    $pdo = config::getConnexion();
    $stmt = $pdo->prepare("SELECT id FROM user WHERE email = :email");
    $stmt->execute(['email' => $email]);
    
    echo json_encode(['exists' => $stmt->fetch() !== false]);
} else {
    echo json_encode(['exists' => false]);
}
?>