<?php
// db.php
$host = 'localhost';
$db = 'food_blog';
$user = 'root';
$pass = '';

// $host = 'sql206.infinityfree.com';
// $db = 'if0_37760837_food_blog';
// $user = 'if0_37760837';
// $pass = 'Ronald05241996';


// $host = 'sql207.infinityfree.com';
// $db = 'if0_37769020_foodblog_db';
// $user = 'if0_37769020';
// $pass = 'F00dBungalow123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>
