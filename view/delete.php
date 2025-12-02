<?php
session_start();
include '../controller/UtilisateurController.php';

if (isset($_GET['id'])) {
    $uc = new UtilisateurController();
    $result = $uc->delete($_GET['id']);
    
    if ($result) {
        $_SESSION['delete_notification'] = [
            'message' => "Utilisateur supprimé avec succès",
            'type' => "success"
        ];
    } else {
        $_SESSION['delete_notification'] = [
            'message' => "Erreur lors de la suppression de l'utilisateur",
            'type' => "error"
        ];
    }
} else {
    $_SESSION['delete_notification'] = [
        'message' => "Aucun ID utilisateur fourni",
        'type' => "error"
    ];
}

// Redirection immédiate vers liste.php
header('Location: liste.php');
exit();
?>