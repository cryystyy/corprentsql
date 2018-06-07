<?php

namespace commands {

    include_once "/var/www/eve/vendor/autoload.php";
    include_once "/var/www/eve/secret/dbconn.php";
    include_once "/var/www/eve/secret/esiConn.php";
    include_once "/var/www/eve/processors/htmlCreator.php";

    function selectCharacter($conn, $characterID)
    {
        $stmt = $conn->prepare('SELECT * FROM characters WHERE character_id= ?');
        $stmt->execute([$characterID]);

        return $stmt;
    }

    function selectCharacters($conn)
    {
        $stmt = $conn->prepare('SELECT * FROM characters');
        $stmt->execute();

        return $stmt;
    }

    function deleteCharacter($conn,$characterID)
    {
        $stmt = $conn->prepare('DELETE FROM characters WHERE character_id = ? ');
        $stmt->execute([$characterID]);
    }
    function deleteMain($conn,$characterID)
    {
        $stmt = $conn->prepare('DELETE FROM charactermapping WHERE main_id = ? ');
        $stmt->execute([$characterID]);
    }

    function deleteAlt($conn,$alt_id)
    {
        $stmt = $conn->prepare('DELETE FROM charactermapping WHERE alt_id = ? ');
        $stmt->execute([$alt_id]);
    }

    function selectMains($conn)
    {
        $stmt = $conn->prepare('SELECT cm.main_id,c.name FROM charactermapping cm
                                LEFT JOIN characters c
                                          ON (cm.main_id = c.character_id)
                                WHERE c.active = TRUE 
                                GROUP BY cm.main_id
                                ORDER BY c.name');
        $stmt->execute();

        return $stmt;
    }

    function selectMain($conn,$characterID)
    {
        $stmt = $conn->prepare('SELECT cm.main_id,c.name FROM charactermapping cm
                                LEFT JOIN characters c
                                          ON (cm.main_id = c.character_id)
                                WHERE c.active = TRUE and cm.main_id = ?
                                GROUP BY cm.main_id');
        $stmt->execute([$characterID]);

        return $stmt;
    }

    function selectAlts($conn,$character_id)
    {
        $stmt = $conn->prepare('SELECT cm.alt_id,c.name FROM charactermapping cm
                                INNER JOIN characters c
                                          ON (cm.alt_id = c.character_id)
                                 WHERE c.active = TRUE and cm.main_id = ?');
        $stmt->execute([$character_id]);

        return $stmt;
    }

    function selectAlt($conn,$characterID)
    {
        $stmt = $conn->prepare('SELECT cm.alt_id,c.name FROM charactermapping cm
                                LEFT JOIN characters c
                                          ON (cm.alt_id = c.character_id)
                                WHERE c.active = TRUE and cm.alt_id = ?
                                GROUP BY cm.main_id');
        $stmt->execute([$characterID]);

        return $stmt;
    }

    function createWalletsForMain($conn,$characterID)
    {
        $stmt = $conn->prepare('INSERT INTO wallet VALUES(?,?)');
        $stmt->execute([$characterID,0]);
    }

    function insertCharacter($conn,$characterID,$name,$birthday)
    {
        $stmt = $conn->prepare('INSERT INTO characters VALUES(?,?,?,?)');
        $stmt->execute([$characterID,$name,$birthday,1]);
    }

    function updateCharacter()
    {
        //TO DO: Create this
    }

    function selectTransaction($conn, $transactionID)
    {
        $stmt = $conn->prepare('SELECT * FROM transactions WHERE transactionID= ?');
        $stmt->execute([$transactionID]);

        return $stmt;
    }

    function insertTransaction($conn,$transactionID,$characterID,$amount,$dateAdded)
    {
        $stmt = $conn->prepare('INSERT INTO transactions VALUES(?,?,?,?,?)');
        $stmt->execute([$transactionID,$characterID,$amount,$dateAdded,0]);
    }

    function selectUncreditedTransactions($conn)
    {
        $stmt = $conn->prepare('SELECT * FROM transactions WHERE WalletUpdated = 0');
        $stmt->execute();

        return $stmt;
    }

    function updateTransaction($conn,$transactionID)
    {
        $stmt = $conn->prepare('UPDATE transactions
                                SET WalletUpdated = 1
                                WHERE transactionID = ?');
        $stmt->execute([$transactionID]);

        echo  "Transaction updated <p>";
    }

    function deleteTransaction()
    {
        //TO DO: Create this
    }
    function selectWallet($conn,$character_id)
    {
        $stmt = $conn->prepare('SELECT c.character_id,w.funds FROM wallet w
                                LEFT JOIN characters c ON c.character_id = w.character_id
                                WHERE c.character_id = ?');
        $stmt->execute([$character_id]);

        return $stmt->fetch();
    }

    function deleteWallet($conn,$character_id)
    {
        $stmt = $conn->prepare('DELETE FROM wallet WHERE character_id = ?');
        $stmt->execute([$character_id]);

    }

    function insertCorpApplication($conn,$keyID,$vCode,$desc)
    {

        $stmt = $conn->prepare('INSERT INTO corpapplications VALUES(?,?,?,?)');
        $stmt->execute([$keyID,$vCode,$desc,0]);
    }

    function selectApplications($conn)
    {
        $stmt = $conn->prepare('SELECT * from corpapplications');
        $stmt->execute();

        return $stmt;
    }

    function selectApplication($conn,$vkey)
    {

    }

    function creditWallet($conn,$characterID,$amount)
    {
        $stmt = $conn->prepare('UPDATE wallet
                                SET funds = funds + ?
                                WHERE character_id = ?');
        $stmt->execute([$amount,$characterID]);
    }

    function debitWallet($conn,$characterID,$amount)
    {
        $stmt = $conn->prepare('UPDATE wallet
                                SET funds = funds - ?
                                WHERE character_id = ?');
        $stmt->execute([$amount,$characterID]);
    }

    function getConfig($conn,$configKey)
    {
        $stmt = $conn->prepare('SELECT configValue FROM configvar
                                WHERE configKey = ?');
        $stmt->execute([$configKey]);

        return $stmt->fetch()['configValue'];
    }

    function updateConfig($conn,$configKey,$newValue)
    {
        $stmt = $conn->prepare('UPDATE configvar
                                SET configValue = ? 
                                WHERE configKey = ?');
        $stmt->execute([$newValue,$configKey]);

        echo 'Key updated';
    }

    function number_shorten($number, $precision = 0, $divisors = null) {

        // Setup default $divisors if not provided
        if (!isset($divisors)) {
            $divisors = array(
                pow(1000, 0) => ' Mil', // 1000^0 == 1
                pow(1000, 2) => ' Mil', // Million
                pow(1000, 3) => ' Bil', // Billion
                pow(1000, 4) => ' Tril', // Trillion
                pow(1000, 5) => ' Qa', // Quadrillion
                pow(1000, 6) => ' Qi', // Quintillion
            );
        }

        // Loop through each $divisor and find the
        // lowest amount that matches
        foreach ($divisors as $divisor => $shorthand) {
            if (abs($number) < ($divisor * 1000)) {
                // We found a match!
                break;
            }
        }

        // We found our match, or there were no matches.
        // Either way, use the last defined value for $divisor.
        return number_format($number / $divisor, $precision) . $shorthand;
    }

}
?>