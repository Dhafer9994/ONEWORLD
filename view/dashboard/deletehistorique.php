<?php
session_start();
include '../../controller/HistoriqueC.php';

if (isset($_GET['id'])) {
    $historiqueId = $_GET['id'];
    $hc = new HistoriqueC();
    
  
    $result = $hc->delete($historiqueId);
    
    if ($result) {
        $_SESSION['delete_notification'] = [
            'type' => 'success',
            'message' => 'Historique supprimé avec succès!'
        ];
    } else {
        $_SESSION['delete_notification'] = [
            'type' => 'error',
            'message' => 'Erreur lors de la suppression!'
        ];
    }
}

header('Location: hist.php');
exit();
?>