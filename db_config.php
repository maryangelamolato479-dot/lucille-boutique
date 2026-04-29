<?php
// db_config.php
$host = 'localhost';
$db   = 'lucille_boutique';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksyon failed: " . $e->getMessage());
}
?>