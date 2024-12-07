<?php
$host = '127.0.0.1';
$dbname = 'aerionix';
$username = 'root';
$password = '';
$base_url = 'http://127.0.0.1/aviation_management_system/';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
