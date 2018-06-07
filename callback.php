<?php
function rootPath()
{
    $pathInPieces = explode('/', $_SERVER['DOCUMENT_ROOT']);
    return $pathInPieces[0].'/'.$pathInPieces[1].'/'.$pathInPieces[2].'/'.$pathInPieces[3];
}
include_once rootPath().'/processors/commands.php';
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

function auth_error($error_message)
{
    print "There's been an error";
    error_log($error_message);
    exit();
}

global $loginClient_id;
global $loginSecret_key;
session_start();
$useragent="ETHBC Auth Module";
// Make sure that the secret matches the one set before the redirect.
if (isset($_SESSION['auth_state']) and isset($_GET['state']) and $_SESSION['auth_state']==$_GET['state']) {
    $code=$_GET['code'];
    $state=$_GET['state'];
    //Do the initial check.
    $url='https://login.eveonline.com/oauth/token';
    $verify_url='https://login.eveonline.com/oauth/verify';
    $header='Authorization: Basic '.base64_encode($loginClient_id.':'.$loginSecret_key);
    $fields_string='';
    $fields=array(
        'grant_type' => 'authorization_code',
        'code' => $code
    );
    foreach ($fields as $key => $value) {
        $fields_string .= $key.'='.$value.'&';
    }
    rtrim($fields_string, '&');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array($header));
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    $result = curl_exec($ch);
    if ($result===false) {
        auth_error(curl_error($ch));
    }
    curl_close($ch);
    $response=json_decode($result);
    $auth_token=$response->access_token;
    $ch = curl_init();
// Get the Character details from SSO
    $header='Authorization: Bearer '.$auth_token;
    curl_setopt($ch, CURLOPT_URL, $verify_url);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array($header));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    $result = curl_exec($ch);
    if ($result===false) {
        auth_error(curl_error($ch));
    }
    curl_close($ch);
    $response=json_decode($result);
    if (!isset($response->CharacterID)) {
        auth_error('No character ID returned');
    }

    $auth= new EsiAuthentication([
        'client_id'     => $client_id,
        'secret'        => $secret_key,
        'refresh_token' => $refresh_token]);
    $esi2 = new Eseye($auth);
    $characterPortrait = $esi2->invoke('get', '/characters/{character_id}/portrait', ['character_id' => $response->CharacterID]);

    $_SESSION['characterPortrait'] =$characterPortrait->px64x64;;
    $_SESSION['auth_characterid']=$response->CharacterID;
    $_SESSION['auth_charactername']=$response->CharacterName;
    $_SESSION['auth_characterhash']=$response->CharacterOwnerHash;
    $_SESSION['loginTime'] = time();
    $_SESSION['loginExpire'] = $_SESSION['loginTime'] + (60 * 60);
    session_write_close();
    header('Location:'. $_SESSION['auth_redirect']);

    exit;
} else {
    echo "State is wrong. Did you make sure to actually hit the login url first?";
    error_log($_SESSION['auth_state']);
    error_log($_GET['state']);
}