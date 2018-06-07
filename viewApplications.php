<?php
include_once rootPath().'/processors/commands.php';

use function commands\selectApplications;
use function HtmlCreator\createFooter;
use function HtmlCreator\createHeader;



$conn = createConnection();
function rootPath()
{
    $pathInPieces = explode('/', $_SERVER['DOCUMENT_ROOT']);
    return $pathInPieces[0].'/'.$pathInPieces[1].'/'.$pathInPieces[2].'/'.$pathInPieces[3];
}
function tableStart()
{
    echo "<table class='table'>
          <thead class='text-white'>
          <tr>
            <th>keyID</th>
            <th>vCode</th>
            <th>Description</th>
            <th>Direct Link</th>
            <th>Vote Status</th>
            <th>Vote</th>
          </tr>
          </thead>
          <tbody>";
}
function tableEnd()
{
    echo "</tbody></table>";
}

function populateTable()
{
    global $conn;
    $applications = selectApplications($conn);

    foreach ($applications as $application)
    {
        echo "<tr class='table-info'>";
        echo "<td>".$application['keyID']."</td><td>".$application['vCode']."</td><td>".$application['userDescription']."</td><td><a href='http://ridetheclown.com/eveapi/audit.php?usid=".$application['keyID']."&apik=".$application['vCode']."'>Link</a></td><td>".$application['approved']."</td><td><button type=\"button\" class=\"btn\">Basic</button></td>";
        echo "</tr>";
    }
}


createHeader();
?>
    <div  class="container text-center" style="width: 1000px; background-color: rgba(23, 27, 35, 0.9);">
        <?php
        tableStart();
        populateTable();
        tableEnd();
        ?>
    </div>

<?php
createFooter();
