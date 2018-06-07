<?php


include_once '/var/www/eve/processors/commands.php';

use function commands\getConfig;
use function commands\number_shorten;
use function commands\selectMains;
use function commands\selectWallet;
use function commands\selectAlts;

function populateAltsDropdown($list)
{
    echo "<div class='dropdown'>
    <button class='btn btn-secondary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
    Alts
    </button>
    <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>";

    foreach ($list as $row)
    {
            echo "<a class='dropdown-item disabled' href='#'>" . $row['name'] . "</a>";
    }
    echo "</div></div>";
}
function populateTable()
{
    $newconn = createConnection();
    $mains = selectMains($newconn);
    $monthlyRent = getConfig($newconn,'MonthlyRent');
    foreach ($mains as $row) {
        $characterFunds = selectWallet($newconn,$row['main_id'])['funds'];

        //start row
        if($characterFunds == 0)
        {
            echo "<tr class='table-warning'>";
        }
        else if($characterFunds < 0)
        {
            echo "<tr class='table-danger'>";
        }
        else {
            echo "<tr class='table-success'>";
        }

        echo "<td>";
        echo $row['name'];
        echo "</td>";

        echo "<td>";
        echo  number_shorten($characterFunds);
        echo "</td>";

        $totalALTS = selectAlts($newconn,$row['main_id'])->rowCount();

        echo "<td>";
        echo number_shorten((($totalALTS+1) * $monthlyRent));
        echo "</td>";

        echo "<td>";
        if($totalALTS != 0) {
            populateAltsDropdown(selectAlts($newconn, $row['main_id']));
        }
        else
        {
            echo "No Alts";
        }
        echo "</td>";


        echo "</tr>";
        //end row
    }
}
function tableStart()
{
    echo "<table class='table'>
          <thead class='text-white'>
          <tr>
            <th>Character Name</th>
            <th>Wallet Amount</th>
            <th>Monthly Rent</th>
            <th>Alts</th>
          </tr>
          </thead>
          <tbody>";
}
function tableEnd()
{
    echo "</tbody></table>";
}

?>
<html>
<head>
    <title>Etherium Beach</title>
    <link rel="icon" type="image/ico" href="images/icon.ico" sizes="32x32">
    <link rel="stylesheet" href="style/mystyle.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <div class="container text-center" style="height: 300px; width:1000px;">
        <img class="text-center" src="images/ethbc_logo.png" width="320" height="240"><p>
        <button type="button" class="btn btn-primary float-right" onclick="window.location.href='http://ethbc.space'">Main Page</button>
    </div>
</head>
<body background="images/background_nebula.jpg" style=" background-size:cover;">
<img alt = "starfield overlay" src="images/stars_and_lines.png" class="starfield-overlay">

<div  class="container text-center" style="width: 1000px; background-color: rgba(23, 27, 35, 0.9);">
    <?php
    tableStart();
    populateTable();
    tableEnd();
    closeConnection();
   ?>
</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

</body>
</html>