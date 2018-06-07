<?php

include_once '/var/www/eve/vendor/autoload.php';
include_once '/var/www/eve/processors/commands.php';
include_once '/var/www/eve/secret/esiConn.php';
include_once '/var/www/eve/secret/dbconn.php';

use function commands\selectTransaction;
use function commands\insertTransaction;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;


$newConn = createConnection();
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
$log = new Logger('transactions');
$log->pushHandler(new StreamHandler("/var/www/eve/processors/logs/transactions.log"));

$authentication = new EsiAuthentication([
    'client_id'     => $client_id,
    'secret'        => $secret_key,
    'refresh_token' => $refresh_token,
]);

$esi = new Eseye($authentication);

$corporationRent = $esi->invoke('get', '/corporations/{corporation_id}/wallets/{division}/journal/',
    ['corporation_id' => $corporationID,'division' => 6]);

foreach ($corporationRent as $entry) {
    if($entry->ref_type == 'player_donation') {

        if(selectTransaction($newConn, $entry->ref_id)->rowCount() == 0)
        {
            insertTransaction($newConn,$entry->ref_id,$entry->first_party_id,$entry->amount,$entry->date);
            $log->warning('Transaction '.$entry->ref_id.' from user '.$entry->first_party_id.' with amount '.$entry->amount.' inserted.');
        }
    }
}
closeConnection();



