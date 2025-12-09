<?php
session_start();
require_once '../controller/AuthController.php';

if (empty(FACEBOOK_APP_ID) || FACEBOOK_APP_ID === 'YOUR_FACEBOOK_APP_ID') {
    die('Please configure Facebook App ID in social_config.php');
}

$provider = new \League\OAuth2\Client\Provider\Facebook([
    'clientId'          => FACEBOOK_APP_ID,
    'clientSecret'      => FACEBOOK_APP_SECRET,
    'redirectUri'       => FACEBOOK_REDIRECT_URL,
    'graphApiVersion'   => FACEBOOK_GRAPH_VERSION,
]);


$authUrl = $provider->getAuthorizationUrl([
    'scope' => ['public_profile'],
]);
$_SESSION['oauth2state'] = $provider->getState();

header('Location: ' . $authUrl);
exit;
?>
