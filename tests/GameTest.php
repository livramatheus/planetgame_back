<?php

use PHPUnit\Framework\TestCase;
use Livramatheus\PlanetgameBack\Models\Game as ModelGame;

class GameTest extends TestCase {

    public function setUp() : void {
        if (file_exists('./src/Config/env.local.php')) {
            require './src/Config/env.local.php';
        }
    }
    
    // get() - Existing game
    public function testGetExistingGame() {
        $ModelGame = new ModelGame();
        $ModelGame->setId(3);

        $this->assertInstanceOf('Livramatheus\\PlanetgameBack\\Models\\Game', $ModelGame->get());
        $this->assertIsString($ModelGame->getName());
        $this->assertIsString($ModelGame->getModelPublisher()->getName());
        $this->assertIsString($ModelGame->getModelGenre()->getName());
        $this->assertNotEmpty($ModelGame->getName());
    }

    // get() - Non existing game
    public function testGetNonExistingGame() {
        $ModelGame = new ModelGame();
        $ModelGame->setId(-1);

        $this->expectErrorMessage('Notice: Game not found!');
        $ModelGame->get();
    }

    // setAge() - Calculate an age based on a date
    public function testAgeCalculation() {
        $ModelGame = new ModelGame();
        $ModelGame->setAge('1994-12-08');

        $this->assertIsString($ModelGame->getAge());
        $this->assertNotNull($ModelGame->getAge());
        $this->assertNotEmpty($ModelGame->getAge());
    }

    // delete() - Non existing game
    public function testDeleteNonExistingGame() {
        $ModelGame = new ModelGame();
        $ModelGame->setId(-1);

        $this->expectExceptionMessage('Notice: Game not found.');
        $ModelGame->delete();
    }

}