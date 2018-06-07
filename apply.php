<?php

use function commands\insertCorpApplication;

function rootPath()
{
    $pathInPieces = explode('/', $_SERVER['DOCUMENT_ROOT']);
    return $pathInPieces[0].'/'.$pathInPieces[1].'/'.$pathInPieces[2].'/'.$pathInPieces[3];
}
include_once rootPath().'/processors/commands.php';
$successful = 0;
$conn = createConnection();

if((isset($_POST['keyID']) && !empty($_POST['keyID']))
&& (isset($_POST['verificationCode']) && !empty($_POST['verificationCode']))
&& (isset($_POST['description']) && !empty($_POST['description']))) {
    print_r($_POST);
    $keyID = $_POST['keyID'];
    $verificationCode = $_POST['verificationCode'];
    $description = $_POST['description'];

    insertCorpApplication($conn,$keyID,$verificationCode,$description);
    $successful = 1;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Etherium Beach</title>
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="style/mystyle.css">
    <link rel="icon" type="image/ico" href="images/icon.ico" sizes="32x32">
</head>
<body background="images/background_nebula.jpg">
<img alt = "starfield overlay" src="images/stars_and_lines.png" class="starfield-overlay">
<div class="registerMenu">
    <div class="center-block" id="alert_placeholder"></div>
    <?php
    if($successful == 1)
    {
       echo '<div id="alertdiv" class="alert alert-success"> <strong>Success!</strong> Your application has been submitted, a recruiter will contact you back once the application is reviewed.</div>';
       echo '<script>document.getElementById("applicationForm").reset();</script>';
    }
    ?>

<form id="applicationForm" method="POST">
    <div class="form-group">
        <label class="text-primary" >Key ID</label>
        <input type="text" class="form-control" name="keyID" id="keyID" aria-describedby="key" placeholder="Enter Key ID">
    </div>
    <div class="form-group">
        <label class="text-primary" >Verification Code</label>
        <input type="text" class="form-control" name="verificationCode" id="verificationCode" aria-describedby="verification" placeholder="Enter Verification Code">
    </div>
    <div class="form-group">
        <label class="text-primary" >Describe yourself</label>
        <textarea type="text" rows="5" class="form-control" name="description" id="description" aria-describedby="description" placeholder="Please write a small description about yourself."></textarea>
    </div>

    <div class="form-group-sm">
        <small id="help" class="form-text text-muted">We'll never share your API with anyone else.</small>
        <br><button type="submit" class="btn btn-primary">Apply to Corp</button>
            <button type="button" class="btn btn-primary" onclick="window.location.href='http://ethbc.space'">Main Menu</button>
    </div>
</form>
</div>
</body>
</html>