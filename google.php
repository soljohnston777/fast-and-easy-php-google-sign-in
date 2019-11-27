<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
$google_redirect_url = 'http://localhost/sign_in/google.php';
//start session
session_start();
//include google api files
include_once 'vendor/autoload.php';
// New Google client
$gClient = new Google_Client();
$gClient->setApplicationName('ApplicationName');
$gClient->setAuthConfigFile('client_secrets.json');
$gClient->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
$gClient->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
// New Google Service
$google_oauthV2 = new Google_Service_Oauth2($gClient);
// LOGOUT?
if (isset($_REQUEST['logout'])) 
{
	unset($_SESSION["auto"]);
	unset($_SESSION['token']);
	$gClient->revokeToken();
	
	//Forced Hard Log off from google - then redirect to your page
	$google_redirect_url1 ='https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://localhost/sign_in/google.php';
	header('Location: ' . filter_var($google_redirect_url1, FILTER_SANITIZE_URL)); //redirect user back to page
	
	//Soft Log off - only on your server - not on google
	// header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL)); //redirect user back to page
}
// GOOGLE CALLBACK?
if (isset($_GET['code'])) 
{
	$gClient->authenticate($_GET['code']);
	$_SESSION['token'] = $gClient->getAccessToken();
    header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
    return;
}
// PAGE RELOAD?
if (isset($_SESSION['token'])) 
{
    $gClient->setAccessToken($_SESSION['token']);
}
// Autologin?
if(isset($_GET["auto"]))
{
	$_SESSION['auto'] = $_GET["auto"];
}
// LOGGED IN?
if ($gClient->getAccessToken()) // Sign in
{
	//For logged in user, get details from google using access token
	try {
		$user = $google_oauthV2->userinfo->get();
		$user_id              = $user['id'];
		$user_name            = filter_var($user['givenName'], FILTER_SANITIZE_SPECIAL_CHARS);
		$email                = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
		$gender               = filter_var($user['gender'], FILTER_SANITIZE_SPECIAL_CHARS);
		$profile_url          = filter_var($user['link'], FILTER_VALIDATE_URL);
		$profile_image_url    = filter_var($user['picture'], FILTER_VALIDATE_URL);
		$personMarkup         = "$email<div><img src='$profile_image_url?sz=50'></div>";
		$_SESSION['token']    = $gClient->getAccessToken();
		
		// Show user
		echo '<br /><a href="'.$profile_url.'" target="_blank"><img src="'.$profile_image_url.'?sz=100" /></a>';
		echo '<br /><a class="logout" href="?logout=1">Logout</a>';
		
		$boolarray = Array(false => 'false', true => 'true');
		echo '<p>Was automatically login? '.$boolarray[isset($_SESSION["auto"])].'</p>';

		//list all user details
		echo '<pre>'; 
		print_r($user);
		echo '</pre>';  
	} catch (Exception $e) {
		// The user revoke the permission for this App! Therefore reset session token	
		unset($_SESSION["auto"]);
		unset($_SESSION['token']);
		header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
	}
}
else // Sign up
{
    //For Guest user, get google login url
    $authUrl = $gClient->createAuthUrl();
	$auth_url = $gClient->createAuthUrl();
	// Fast access or manual login button?
	if(isset($_GET["auto"]))
	{

		header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
	}
	else
	{
		echo '<p>Login?</p>';
		// echo '<a class="login" href="'.$authUrl.'"><img src="images/google-login-button.png" /></a>';
		echo "<a href='$auth_url'>Login Through Google </a>";
	}
}
