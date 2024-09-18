<?php
    $driver = 'pgsql';
    $host = 'localhost';
    $port = '5432';
    $db_name = 'e-library';
    $db_user = 'postgres';
    $db_pass = '1234';
    $charset = 'utf8';
    $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

    try {
        $pdo = new PDO(
            "$driver:host=$host;port=$port;dbname=$db_name", $db_user, $db_pass, $options
        );
        // echo "<script>console.log('ура подключились!!');</script>";
    } catch (PDOException $e) {
        // echo "<script>console.log('не подключились ;((');</script>";
        die($e->getMessage());
    }
?>