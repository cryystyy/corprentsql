<?php

    $servername = "localhost";
    $username = "seat";
    $password = "extreme1";
    $dbname = "ethbcadmin";

    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    $log = new Logger('connection');
    $log->pushHandler(new StreamHandler('/var/www/eve/processors/logs/eseye.log'));

    function createConnection()
    {
       global $servername, $username, $password, $dbname, $log;
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $log->warning('DB Connection successful');
        } catch (PDOException $e) {
            $log->error('DB Connection failed');
        }

        return $conn;
    }

    function closeConnection()
    {
        $conn = null;
    }



?>