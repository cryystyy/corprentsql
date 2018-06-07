<?php

use function commands\createWalletsForMain;
use function commands\creditWallet;
use function commands\debitWallet;
use function commands\getConfig;
use function commands\selectAlts;
use function commands\selectMains;
use function commands\updateConfig;
use function commands\selectUncreditedTransactions;
use function commands\selectWallet;
use function commands\updateTransaction;


include_once '/var/www/eve/vendor/autoload.php';
include_once '/var/www/eve/processors/commands.php';
include_once '/var/www/eve/secret/esiConn.php';
include_once '/var/www/eve/secret/dbconn.php';

$newConn = createConnection();
$mains = selectMains($newConn);
$uncreditedTransactions = selectUncreditedTransactions($newConn);

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
$log = new Logger('wallet');
$log->pushHandler(new StreamHandler("/var/www/eve/processors/logs/wallet.log"));


if($uncreditedTransactions->rowCount() != 0) {
    foreach ($uncreditedTransactions as $row) {
        if(selectWallet($newConn,$row['character_id']) != null) {

            creditWallet($newConn, $row['character_id'],$row['amount']);
            updateTransaction($newConn,$row['transactionID']);
            $log->warning($row['character_id']." credited with ".$row['amount']);
        }
        else
        {
            $log->warning("User has no wallet");
        }
    }
}
else
{
    $log->warning("All transactions credited");
}

if(getConfig($newConn,'ProcessedMonth') == date("m") )
{
    $monthlyRent = getConfig($newConn,'MonthlyRent');
    foreach ($mains as $row)
    {
        $totalALTS = selectAlts($newConn,$row['main_id'])->rowCount();
        if(selectWallet($newConn,$row['main_id']) != null) {
            debitWallet($newConn, $row['main_id'], ($totalALTS + 1) * $monthlyRent);
            $log->warning($row['main_id']." has been debited ".(($totalALTS + 1) * $monthlyRent).' isk.');
        }
        else
        {
            $log->warning("User could not be debited.");
        }
    }
    updateConfig($newConn,'ProcessedMonth',date('m', strtotime('+1 month')));
}

foreach ($mains as $row) {
    if(selectWallet($newConn,$row['main_id']) == null)
    {
        createWalletsForMain($newConn, $row['main_id']);
        $log->warning('Wallet has been created for '.$row['main_id']);
    }
}
closeConnection();
