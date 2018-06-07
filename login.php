<?php
/**
 * Created by PhpStorm.
 * User: arsen
 * Date: 2/25/2018
 * Time: 2:16 PM
 */
session_start();
function rootPath()
{
    $pathInPieces = explode('/', $_SERVER['DOCUMENT_ROOT']);
    return $pathInPieces[0].'/'.$pathInPieces[1].'/'.$pathInPieces[2].'/'.$pathInPieces[3];
}
include_once rootPath().'/processors/commands.php';

if (isset($_SESSION['auth_characterid'])) {
    echo "Logged in. " . $_SESSION['auth_characterid'];
    exit;
}
else
{
    $authsite='https://login.eveonline.com';
    $authurl='/oauth/authorize';
    $redirect_uri="http://ethbc.space/callback.php";
    $state=uniqid();
    $redirecturl=$_SERVER['HTTP_REFERER'];

    if (!preg_match("#^http://ethbc.space/(.*)$#", $redirecturl, $matches)) {
        $redirecturl='/';
    } else {
        $redirecturl=$matches[1];
    }
    $redirect_to="http://ethbc.space/".$redirecturl;
    $_SESSION['auth_state']=$state;
    $_SESSION['auth_redirect']=$redirect_to;
    session_write_close();
    header(
        'Location:'.$authsite.$authurl
        .'?response_type=code&redirect_uri='.$redirect_uri
        .'&client_id='.$loginClient_id.'&scope=&state='.$state
    );
    exit;
}