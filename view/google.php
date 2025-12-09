<?php
session_start();
require_once '../controller/AuthController.php';

if (empty(GOOGLE_CLIENT_ID) || GOOGLE_CLIENT_ID === 'YOUR_GOOGLE_CLIENT_ID') {
    die('Please configure Google Client ID in social_config.php');
}

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URL);
$client->addScope("email");
$client->addScope("profile");
$client->setPrompt('select_account'); 

$loginUrl = $client->createAuthUrl();
header('Location: ' . $loginUrl);
exit();
?>
