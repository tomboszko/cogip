<?php

require 'vendor/autoload.php';

//LOCAL CONNECTION//

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();

// $host = $_ENV['DB_HOST'];
// $db   = $_ENV['DB_DATABASE'];
// $user = $_ENV['DB_USERNAME'];
// $pass = $_ENV['DB_PASSWORD'];


//HEROKU CONNECTION ///

$host = getenv('DB_HOST');
$db   = getenv('DB_DATABASE');
$user = getenv('DB_USERNAME');
$pass = getenv('DB_PASSWORD');
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

?>

