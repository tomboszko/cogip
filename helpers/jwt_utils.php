<?php
// jwt_utils.php

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

function generate_jwt_token($user_id, $secret_key, $alg = 'HS256') {
    $issued_at = time();
    $expiration_time = $issued_at + (60 * 60); // valid for 1 hour

    $payload = array(
        'iat' => $issued_at,
        'exp' => $expiration_time,
        'sub' => $user_id
    );

    $jwt = JWT::encode($payload, $secret_key, $alg);
    var_dump($jwt);
    return $jwt;
}

function validate_jwt_token($jwt_token, $secret_key) {
    try {
        return JWT::decode($jwt_token, $secret_key, array('HS256'));
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