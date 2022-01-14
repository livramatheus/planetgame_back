<?php

namespace Livramatheus\PlanetgameBack\Models;

use Exception;
use JsonSerializable;
use Livramatheus\PlanetgameBack\Models\Publisher as ModelPublisher;
use Livramatheus\PlanetgameBack\Models\Genre as ModelGenre;
use Livramatheus\PlanetgameBack\Core\Connection;
use PDO;
use PDOException;
use RelativeTime\RelativeTime;

class Game implements JsonSerializable {

    /** @var ModelPublisher */
    private ModelPublisher $ModelPublisher;

    /** @var ModelGenre */
    private ModelGenre $ModelGenre;

    private $id;
    private $name;
    private $releaseDate;
    private $abstract;
    private $age;

    public function getModelPublisher() {
        return $this->ModelPublisher;
    }

    public function setModelPublisher($ModelPublisher) {
        $this->ModelPublisher = $ModelPublisher;
    }

    public function getModelGenre() {
        return $this->ModelGenre;
    }

    public function setModelGenre($ModelGenre) {
        $this->ModelGenre = $ModelGenre;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getReleaseDate() {
        return $this->releaseDate;
    }

    public function setReleaseDate($releaseDate) {
        $this->releaseDate = $releaseDate;
    }

    public function getAbstract() {
        return $this->abstract;
    }

    public function setAbstract($abstract) {
        $this->abstract = $abstract;
    }

    public function getAge() {
        return $this->age;
    }

    public function setAge($releaseDate) {
        $RelativeTime = new RelativeTime(['truncate' => 1]);
        $this->age = $RelativeTime->timeAgo($releaseDate);
    }

    public function getAll() {
        $sql = 'SELECT tb_game.id,
                       tb_game.name,
                       tb_game.release_date,
                       tb_game.abstract,
                       tb_publisher.name as publisher,
                       tb_genre.name as genre
                  FROM tb_game
                  JOIN tb_publisher ON
                          tb_game.publisher = tb_publisher.id
                  JOIN tb_genre ON
                          tb_game.genre = tb_genre.id;';

        $PdoTransac = Connection::getConn()->query($sql);
        $data = [];

        while($row = $PdoTransac->fetch(PDO::FETCH_ASSOC)) {
            $ModelGame      = new Game();
            $ModelPublisher = new ModelPublisher();
            $ModelGenre     = new ModelGenre();
            
            $ModelGame->setId($row['id']);
            $ModelGame->setName($row['name']);
            $ModelGame->setReleaseDate($row['release_date']);
            $ModelGame->setAge($row['release_date']);
            $ModelGame->setAbstract($row['abstract']);
            $ModelGame->setModelGenre($ModelGenre);
            $ModelGame->setModelPublisher($ModelPublisher);

            $ModelPublisher->setName($row['publisher']);
            $ModelGenre->setName($row['genre']);

            $data[] = $ModelGame;
        }

        return $data;
    }

    public function get() {
        $sql = 'SELECT tb_game.id,
                       tb_game.name,
                       tb_game.release_date,
                       tb_game.abstract,
                       tb_publisher.name as publisher,
                       tb_genre.name as genre
                  FROM tb_game
                  JOIN tb_publisher ON
                          tb_game.publisher = tb_publisher.id
                  JOIN tb_genre ON
                          tb_game.genre = tb_genre.id
                 WHERE tb_game.id = ?;';

        $params = [$this->id];

        $PdoTransac = Connection::getConn()->prepare($sql);
        $PdoTransac->execute($params);

        $res = $PdoTransac->fetch(PDO::FETCH_ASSOC);
        
        if (!$PdoTransac->rowCount()) {
            throw new Exception('Notice: Game not found!');
        }

        $ModelPublisher = new ModelPublisher();
        $ModelGenre     = new ModelGenre();
        
        $this->setId($res['id']);
        $this->setName($res['name']);
        $this->setReleaseDate($res['release_date']);
        $this->setAge($res['release_date']);
        $this->setAbstract($res['abstract']);
        $this->setModelGenre($ModelGenre);
        $this->setModelPublisher($ModelPublisher);

        $ModelPublisher->setName($res['publisher']);
        $ModelGenre->setName($res['genre']);

        return $this;
    }

    public function delete() {
        $sql = 'DELETE
                  FROM tb_game
                 WHERE id = ?;';

        $params = [$this->id];

        $PdoTransac = Connection::getConn()->prepare($sql);
        
        try {
            $PdoTransac->execute($params);
        } catch (Exception $Error) {
            throw new Exception($Error->getMessage());
        }

        if ($PdoTransac->rowCount() == 0) {
            throw new Exception('Notice: Game not found.');
        }

    }

    public function insert() {
        $sql = 'INSERT INTO `tb_game` (`name`, `publisher`, `release_date`, `genre`, `abstract`)
                     VALUES (?, ?, ?, ?, ?);';

        $params = [
            $this->name,
            $this->getModelPublisher()->getId(),
            $this->getReleaseDate(),
            $this->getModelGenre()->getId(), 
            $this->getAbstract()
        ];

        $PdoTransac = Connection::getConn()->prepare($sql);
        
        try {
            $PdoTransac->execute($params);
        } catch (PDOException $Error) {
            throw new Exception($Error->getMessage());
        }
    }

    public function jsonSerialize() {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'release_date' => $this->releaseDate,
            'age'          => $this->age,
            'abstract'     => $this->abstract,
            'publisher'    => $this->getModelPublisher()->getName(),
            'genre'        => $this->getModelGenre()->getName()
        ];
    }

}