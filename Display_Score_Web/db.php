<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'scoring_manager';

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    exit('La connexion a échoué : '.$conn->connect_error);
}

// $dsn = 'mysql:dbname=scoring_web;host=localhost;charset=utf8mb4';

// try {
//     // Read settings from INI file, set UTF8
//     $pdo = new PDO($dsn, $username, $password, [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4']);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     // Disable emulation of prepared statements, use REAL prepared statements instead.
//     $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

//     // Connection succeeded, set the boolean to true.
//     $bConnected = true;
// } catch (PDOException $e) {
//     // Write into log
//     echo $Exception($e->getMessage());

//     exit;
// }
