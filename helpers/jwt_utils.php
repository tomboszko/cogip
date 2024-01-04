<?php

require_once '../vendor/autoload.php';
require_once './db.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;
use \Firebase\JWT\SignatureInvalidException;
use \Firebase\JWT\BeforeValidException;


class utils {
    protected $db;

    public function __construct($database) {
        $this->db = $database;
    }

function loginUser($email, $password, $pdo) {
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        return $this->generate_jwt_token($user['id'], $pdo); // Generate and return the JWT token
    } else {
        throw new Exception("Invalid login credentials");
    }
}

function generate_jwt_token($user_id, $pdo, $alg = 'HS256') {
    $secret_key = 'your_secret_key'; // Use a secure, constant key not related to user credentials

    $issued_at = time();
    $expiration_time = $issued_at + (60 * 60); // Token valid for 1 hour

    $payload = [
        'iat' => $issued_at,
        'exp' => $expiration_time,
        'sub' => $user_id
    ];

    return JWT::encode($payload, $secret_key, $alg); // Generate and return JWT
}

function validate_jwt_token($jwt_token, $pdo, $alg = 'HS256') {
    // Decode the token without verifying the signature
    $decoded = JWT::decode($jwt_token, null, false);

    // Get the user ID from the token
    $user_id = $decoded->sub;

    // Query the database for the user's secret key
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception('User not found');
    }

    $secret_key = $user['secret_key'];

    // Verify the token with the user's secret key
    try {
        $decoded = JWT::decode($jwt_token, $secret_key, array($alg));
        return $decoded;
    } catch (ExpiredException $e) {
        throw new Exception('Token expired');
    } catch (SignatureInvalidException $e) {
        throw new Exception('Invalid token signature');
    } catch (BeforeValidException $e) {
        throw new Exception('Token not valid yet');
    } catch (Exception $e) {
        throw new Exception('Invalid token');
    }
}

function verifyToken($jwt, $pdo) {
    try {
        $decoded = validate_jwt_token($jwt, $pdo);
        // Token is valid, proceed with the request
    } catch (Exception $e) {
        // Handle invalid token (e.g., send a 401 Unauthorized response)
    }
}
}