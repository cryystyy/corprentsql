<html>
<head>
    <title>Etherium Beach</title>
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="style/mystyle.css">
    <link rel="icon" type="image/ico" href="images/icon.ico" sizes="32x32">
    <?php session_start();
    function rootPath()
    {
        $pathInPieces = explode('/', $_SERVER['DOCUMENT_ROOT']);
        return $pathInPieces[0].'/'.$pathInPieces[1].'/'.$pathInPieces[2].'/'.$pathInPieces[3];
    }
    include_once rootPath().'/processors/commands.php';
    $now = time();
    if(isset($_SESSION['loginExpire']) && $now > $_SESSION['loginExpire'])
    {
        session_destroy();
    }
    ?>
</head>
<body background="images/background_nebula.jpg" style=" background-size:cover;">
<nav class="navbar navbar-light justify-content-between" style="background-color: rgba(23, 27, 35, 0.9);">
    <a class="navbar-brand text-white" style="color: white">Home Page</a>
    <?php if (isset($_SESSION['auth_characterid'])) {

        echo '<ul class="nav justify-content-center"><li class="nav-item text-white"><img src="'.$_SESSION['characterPortrait'].'" style="border-radius: 50%;">'.' '.$_SESSION['auth_charactername'].'</li></ul>';
        echo '<a href="logout.php">Logout</a>';
        }
        else
        {
            echo '<a href="login.php"><img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-white-small.png"></a>';
        }
        ?>
</nav>
<img alt = "starfield overlay" src="images/stars_and_lines.png" class="starfield-overlay">
<div class="myMenu">
    <img src="images/ethbc_logo.png" width="320" height="240">
    <button type="button" onclick="window.location.href='http://seat.ethbc.space'" class="btn btn-primary">Member Panel</button>
    <button type="button" class="btn btn-primary" onclick="window.location.href='http://ethbc.space/rentTracker.php'">Rent Tool</button>
    <button type="button" class="btn btn-primary" onclick="window.location.href='http://ridetheclown.com/eveapi/audit.php'">API Check</button>
    <button type="button" class="btn btn-primary" onclick="window.location.href='http://ethbc.space/apply.php'">Apply to Corp</button>
</div>
</body>
</html>
