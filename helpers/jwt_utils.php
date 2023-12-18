<?php
// jwt_utils.php
use Firebase\JWT\JWT;

function generate_jwt_token($user_id, $secret_key) {
    $issued_at = time();
    $expiration_time = $issued_at + (60 * 60); // valid for 1 hour

    $payload = array(
        'iat' => $issued_at,
        'exp' => $expiration_time,
        'sub' => $user_id
    );

    return JWT::encode($payload, $secret_key);
}


?>