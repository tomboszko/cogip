<?php

require_once 'jwt_utils.php';
require_once '../vendor/autoload.php';
require_once './db.php';

use \Firebase\JWT\JWT;

// Create a new PDO instance
$pdo = new PDO($dsn, $user, $pass,);

// Test loginUser function
$email = 'jc.ranu@cogip.com';
$password = 'paella123';
$token = loginUser($email, $password, $pdo);
echo "Generated token: $token\n";

// Test validate_jwt_token function
try {
    $decoded = validate_jwt_token($token, $pdo);
    echo "Token is valid\n";
} catch (Exception $e) {
    echo "Token validation failed: " . $e->getMessage() . "\n";
}

// Test verifyToken function
try {
    verifyToken($token, $pdo);
    echo "Token verification passed\n";
} catch (Exception $e) {
    echo "Token verification failed: " . $e->getMessage() . "\n";
}