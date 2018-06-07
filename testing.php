<?php
function rootPath()
{
    $pathInPieces = explode('/', $_SERVER['DOCUMENT_ROOT']);
    return $pathInPieces[0].'/'.$pathInPieces[1].'/'.$pathInPieces[2].'/'.$pathInPieces[3];
}
include_once rootPath().'/processors/commands.php';
$newconn = createConnection();


use function commands\selectAlt;
use function commands\selectAlts;
use function commands\selectCharacters;
use function commands\selectMain;
use function commands\selectMains;
use function HtmlCreator\createFooter;
use function HtmlCreator\createHeader;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;
$authentication2 = new EsiAuthentication([
    'client_id'     => $client_id,
    'secret'        => $secret_key,
    'refresh_token' => $refresh_token,
]);
$esi2 = new Eseye($authentication2);
$characterPortrait = $esi2->invoke('get', '/characters/{character_id}/', ['character_id' => 92360452]);


function populateTable()
{
    global $newconn;
    $mains = selectMains($newconn);
    foreach ($mains as $row) {
        //start row
        echo "<tr class='table-info'>";

        echo "<td>";
        echo $row['name'];
        echo "</td>";

        $alts = selectAlts($newconn,$row['main_id']);
        $alts_string = "";
        echo "<td>";
        foreach ($alts as $alt)
        {
           echo $alt['name'].',';
        }
        echo "</td>";


        echo "</tr>";
        //end row
    }
}
function tableStart()
{
    echo "    <h2><p class=\"text-info\">Assigned Accounts</p></h2>";
    echo "<table class='table'>
          <thead class='text-white'>
          <tr>
            <th>Main</th>
            <th>Alts</th>
          </tr>
          </thead>
          <tbody>";
}
function tableEnd()
{
    echo "</tbody></table>";
}




createHeader();
?>
<div  class="container text-center" style="width: 1000px; background-color: rgba(23, 27, 35, 0.9);">
    <?php
    tableStart();
    populateTable();
    tableEnd();
    ?>
    <h2><p class="text-danger">Unassigned Accounts</p></h2>
    <table class="table">
        <tbody>
        <tr class='table-danger'>
    <?php
    $members = selectCharacters($newconn);
    $counter = 0;
    foreach ($members as $member)
    {
        $isMain = selectMain($newconn,$member['character_id']);
        if($isMain->rowCount() == 0) {
            $isAlt = selectAlt($newconn, $member['character_id']);
            if ($isAlt->rowCount() == 0) {
                echo '<td>'.$member['name'] . '</td>';
                $counter++;
                if($counter > 7) {
                    $counter = 0;
                    echo '</tr><tr class="table-danger">';
                }
            }
        }
    }
    ?>
        </tr>
        </tbody>
    </table>
</div>
<?php


createFooter();
