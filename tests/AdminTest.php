<?php

use Livramatheus\PlanetgameBack\Models\Admin as ModelAdmin;
use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase {

    public function setUp() : void {
        if (file_exists('./src/Config/env.local.php')) {
            require './src/Config/env.local.php';
        }
    }

    public function testLogin() {
        $ModelAdmin = new ModelAdmin();

        // Existing user
        $ModelAdmin->setUsername('testuser');
        $ModelAdmin->setPassword('123456');
        $this->assertTrue($ModelAdmin->login());
        
        // Wrong credentials
        $ModelAdmin->setPassword('654321');
        $this->assertFalse($ModelAdmin->login());

        // Empty credentials
        $ModelAdmin->setUsername('');
        $ModelAdmin->setPassword('');
        $this->assertFalse($ModelAdmin->login());
    }

}