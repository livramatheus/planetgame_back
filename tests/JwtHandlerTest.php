<?php

use Livramatheus\PlanetgameBack\Core\JwtHandler;
use PHPUnit\Framework\TestCase;

class JwtHandlerTest extends TestCase {

    public function testCreateToken() {
        $payload = [
            'user_name'  => 'testuser',
            'first_name' => 'Test',
            'last_name'  => 'User'
        ];

        // Assertive token
        $expected = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX25hbWUiOiJ0ZXN0dXNlciIsImZpcnN0X25hbWUiOiJUZXN0IiwibGFzdF9uYW1lIjoiVXNlciJ9.UwjGu030eEWm8PtQsAaBpVGBUjmtGOieAqHaiXj9Aq0';
        $actual   = JwtHandler::createToken($payload);
        $this->assertEquals($expected, $actual);
        
        // Wrong header
        $expected = 'EyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX25hbWUiOiJ0ZXN0dXNlciIsImZpcnN0X25hbWUiOiJUZXN0IiwibGFzdF9uYW1lIjoiVXNlciJ9.UwjGu030eEWm8PtQsAaBpVGBUjmtGOieAqHaiXj9Aq0';
        $this->assertNotEquals($expected, $actual);
        
        // Wrong payload
        $expected = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX25hbWUiOiJ0ZXN0dXNlXiIsImZpcnN0X25hbWUiOiJUZXN0IiwibGFzdF9uYW1lIjoiVXNlciJ9.UwjGu030eEWm8PtQsAaBpVGBUjmtGOieAqHaiXj9Aq0';
        $this->assertNotEquals($expected, $actual);

        // Wrong signature
        $expected = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX25hbWUiOiJ0ZXN0dXNlciIsImZpcnN0X25hbWUiOiJUZXN0IiwibGFzdF9uYW1lIjoiVXNlciJ9.XwjGu030eEWm8PtQsAaBpVGBUjmtGOieAqHaiXj9Aq0';
        $this->assertNotEquals($expected, $actual);
    }

}