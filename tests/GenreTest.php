<?php

use PHPUnit\Framework\TestCase;
use Livramatheus\PlanetgameBack\Models\Genre as ModelGenre;

class GenreTest extends TestCase {

    public function setUp() : void {
        if (file_exists('./src/Config/env.local.php')) {
            require './src/Config/env.local.php';
        }
    }

    public function testGetAll() {
        $ModelGenre = new ModelGenre();
        $data = $ModelGenre->getAll();

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }

}