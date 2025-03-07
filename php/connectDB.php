<?php
    $dsn = "mysql:host=localhost;dbname=login_db";
    $user = "root";
    $pass = "";
    $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // echo "Connection Succed";
    }
    catch(PDOException $e) {
        die("Connection Failed: " . $e->getMessage());
    }
?>