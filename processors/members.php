<?php


include_once '/var/www/eve/vendor/autoload.php';
include_once '/var/www/eve/processors/commands.php';
include_once '/var/www/eve/secret/esiConn.php';
include_once '/var/www/eve/secret/dbconn.php';


use function commands\deleteAlt;
use function commands\deleteCharacter;
use function commands\deleteMain;
use function commands\deleteWallet;
use function commands\selectCharacter;
use function commands\insertCharacter;
use function commands\selectCharacters;
use function commands\selectMain;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;


$newConn = createConnection();
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
$log = new Logger('members');
$log->pushHandler(new StreamHandler("/var/www/eve/processors/logs/members.log"));

$authentication = new EsiAuthentication([
    'client_id'     => $client_id,
    'secret'        => $secret_key,
    'refresh_token' => $refresh_token,
]);

$esi = new Eseye($authentication);

$corporationInfo = $esi->invoke('get', '/corporations/{corporation_ID}/members/',
    ['corporation_ID' => $corporationID]);


foreach ($corporationInfo as $entry) {

        $characterInfo = $esi->invoke('get','/characters/{character_id}/',['character_id' => $entry]);
        if(selectCharacter($newConn,$entry)->rowCount() == 0)
        {
            insertCharacter($newConn,$entry,$characterInfo->name,$characterInfo->birthday);
            $log->warning('Character '.$characterInfo->name.' inserted');
        }
}

$members = selectCharacters($newConn);
foreach ($members as $member)
{
    if (in_array($member['character_id'], (array)$corporationInfo)) {
    }
    else
    {
        $isMain = selectMain($newConn,$member['character_id']);
        if($isMain->rowCount() > 0) {
            $log->warning($member['name']." is no longer a member, removing traces, he was a MAIN.");
            deleteWallet($newConn,$member['character_id']);
            deleteMain($newConn,$member['character_id']);
            deleteCharacter($newConn,$member['character_id']);
        }
        else {
            $log->warning($member['name']." is no longer a member, removing traces, he was an ALT.");
            deleteWallet($newConn,$member['character_id']);
            deleteAlt($newConn,$member['character_id']);
            deleteCharacter($newConn,$member['character_id']);
        }
    }
}
closeConnection();



