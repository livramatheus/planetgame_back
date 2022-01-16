<?php

use Livramatheus\PlanetgameBack\Core\Exceptions\DatabaseException;
use Livramatheus\PlanetgameBack\Core\Exceptions\ItemNotFoundException;
use Livramatheus\PlanetgameBack\Models\Publisher as ModelPublisher;
use PHPUnit\Framework\TestCase;

class PublisherTest extends TestCase {

    public function setUp() : void {
        if (file_exists('./src/Config/env.local.php')) {
            require './src/Config/env.local.php';
        }
    }

    // get() - Existing publisher
    public function testGetExistingPublisher() {
        $ModelPublisher = new ModelPublisher();
        $ModelPublisher->setId(45);

        $this->assertInstanceOf('Livramatheus\\PlanetgameBack\\Models\\Publisher', $ModelPublisher->get());
        $this->assertIsString($ModelPublisher->getName());
        $this->assertNotEmpty($ModelPublisher->getName());
    }

    // get() - Non existing publisher
    public function testGetNonExistingPublisher() {
        $ModelPublisher = new ModelPublisher();
        $ModelPublisher->setId(0);

        $this->expectException(ItemNotFoundException::class);
        $ModelPublisher->get();
    }

    // setAge() - Calculate an age based on a date
    public function testAgeCalculation() {
        $ModelPublisher = new ModelPublisher();
        $ModelPublisher->setAge('1994-12-08');

        $this->assertIsString($ModelPublisher->getAge());
        $this->assertNotNull($ModelPublisher->getAge());
        $this->assertNotEmpty($ModelPublisher->getAge());
    }

    // delete() - Non existing publisher
    public function testDeleteNonExistingPublisher() {
        $ModelPublisher = new ModelPublisher();
        $ModelPublisher->setId(0);

        $this->expectException(ItemNotFoundException::class);
        $ModelPublisher->delete();
    }

    // delete() - Publisher with relations to another table
    public function testDeletePublisherRelatedToGame() {
        $ModelPublisher = new ModelPublisher();
        $ModelPublisher->setId(45);

        $this->expectException(DatabaseException::class);
        $ModelPublisher->delete();
    }

}