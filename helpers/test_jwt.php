<?php
// test jwt_utils.php
use PHPUnit\Framework\TestCase;

require 'vendor/autoload.php';
require 'jwt_utils.php'; // path to your jwt_utils.php file

class JwtUtilsTest extends TestCase {
    private $secret_key = 'your_secret_key'; // replace with your secret key
    private $alg = 'HS256'; // replace with your algorithm

    public function testGenerateAndValidateJwtToken() {
        $user_id = 1; // replace with a test user_id

        // Generate a new JWT token
        $jwt_token = generate_jwt_token($user_id, $this->secret_key, $this->alg);

        // Assert the token is a string
        $this->assertIsString($jwt_token);

        // Validate the generated JWT token
        $decoded_token = validate_jwt_token($jwt_token, $this->secret_key);

        // Assert the decoded token is an object
        $this->assertIsObject($decoded_token);

        // Assert the 'sub' property of the decoded token matches the user_id
        $this->assertEquals($user_id, $decoded_token->sub);
    }
}