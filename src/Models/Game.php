<?php

namespace Livramatheus\PlanetgameBack\Models;

use Exception;
use Livramatheus\PlanetgameBack\Core\Exceptions\ItemNotFoundException;
use JsonSerializable;
use Livramatheus\PlanetgameBack\Models\Publisher as ModelPublisher;
use Livramatheus\PlanetgameBack\Models\Genre as ModelGenre;
use Livramatheus\PlanetgameBack\Core\Connection;
use Livramatheus\PlanetgameBack\Core\ErrorLog;
use Livramatheus\PlanetgameBack\Core\Exceptions\DatabaseException;
use Livramatheus\PlanetgameBack\Core\Exceptions\EnvironmentVarsException;
use PDO;
use PDOException;
use RelativeTime\RelativeTime;

/**
 * Game model class
 * 
 * @package Model
 * @author Matheus do Livramento
 */
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
    private $contributor;
    private $approved;

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

    /**
     * Takes a date as parameter to create the following sentence: "53 years ago"
     * 
     * @param string $releaseDate Game's release date
     */
    public function setAge($releaseDate) {
        $RelativeTime = new RelativeTime(['truncate' => 1]);
        $this->age = $RelativeTime->timeAgo($releaseDate);
    }

    /**
     * @return string Returns contributor name or "Anonymous" if it is empty
     */
    public function getContributor() {
        if (!empty($this->contributor)) {
            return $this->contributor;
        }

        return 'Anonymous';
    }

    public function setContributor($contributor) {
        $this->contributor = $contributor;
    }
    
    public function getApproved() {
        return $this->approved;
    }

    public function setApproved($approved) {
        $this->approved = $approved;
    }

    /**
     * Queries database and returns an array of publishers
     * 
     * @param bool $showUnapprovedGames Whether or not unapproved games should be included in the result
     * @return array
     * @throws DatabaseException
     * @throws Exception
     */
    public function getAll($showUnapprovedGames = false) {
        $sql = 'SELECT tb_game.id,
                       tb_game.name,
                       tb_game.release_date,
                       tb_game.abstract,
                       tb_game.contributor,
                       tb_game.approved,
                       tb_publisher.name as publisher,
                       tb_genre.name as genre
                  FROM tb_game
                  JOIN tb_publisher ON
                          tb_game.publisher = tb_publisher.id
                  JOIN tb_genre ON
                          tb_game.genre = tb_genre.id
                 ' . ($showUnapprovedGames ? '' : 'WHERE tb_game.approved = 1') . '
                 ORDER BY tb_game.id;';

        try {
            $Connection = Connection::getConn();
            $PdoTransac = $Connection->query($sql);
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
                $ModelGame->setContributor($row['contributor']);
                $ModelGame->setApproved($row['approved']);
                $ModelGame->setModelGenre($ModelGenre);
                $ModelGame->setModelPublisher($ModelPublisher);
    
                $ModelPublisher->setName($row['publisher']);
                $ModelGenre->setName($row['genre']);
    
                $data[] = $ModelGame;
            }
        } catch (EnvironmentVarsException $Exception) {
            ErrorLog::log($Exception);
            throw new DatabaseException();
        } catch (PDOException $Exception) {
            ErrorLog::log($Exception);
            throw new Exception();
        }

        return $data;
    }

    /**
     * Queries database and returns the desired game
     * 
     * @param bool $showUnapprovedGames Whether or not unapproved games should be selectable
     * @return Game
     * @throws ItemNotFoundException
     * @throws DatabaseException
     * @throws Exception
     */
    public function get($showUnapprovedGames = false) {
        $sql = 'SELECT tb_game.id,
                       tb_game.name,
                       tb_game.release_date,
                       tb_game.abstract,
                       tb_game.contributor,
                       tb_game.approved,
                       tb_publisher.name as publisher,
                       tb_genre.name as genre
                  FROM tb_game
                  JOIN tb_publisher ON
                          tb_game.publisher = tb_publisher.id
                  JOIN tb_genre ON
                          tb_game.genre = tb_genre.id
                 WHERE tb_game.id = ?
                   ' . ($showUnapprovedGames ? '' : 'AND tb_game.approved = 1') . ';';

        $params = [$this->id];

        try {
            $Connection = Connection::getConn();
            $PdoTransac = $Connection->prepare($sql);
            $PdoTransac->execute($params);
            $res = $PdoTransac->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $Exception) {
            ErrorLog::log($Exception);
            throw new DatabaseException();
        } catch (EnvironmentVarsException $Exception) {
            ErrorLog::log($Exception);
            throw new Exception();
        }
        
        if (!$PdoTransac->rowCount()) {
            throw new ItemNotFoundException();
        }

        $ModelPublisher = new ModelPublisher();
        $ModelGenre     = new ModelGenre();
        
        $this->setId($res['id']);
        $this->setName($res['name']);
        $this->setReleaseDate($res['release_date']);
        $this->setAge($res['release_date']);
        $this->setAbstract($res['abstract']);
        $this->setContributor($res['contributor']);
        $this->setApproved($res['approved']);
        $this->setModelGenre($ModelGenre);
        $this->setModelPublisher($ModelPublisher);

        $ModelPublisher->setName($res['publisher']);
        $ModelGenre->setName($res['genre']);

        return $this;
    }

    /**
     * Deletes a game by ID
     * 
     * @throws ItemNotFoundException
     * @throws DatabaseException
     * @throws Exception
     */
    public function delete() {
        $sql = 'DELETE
                  FROM tb_game
                 WHERE id = ?;';

        $params = [$this->id];

        try {
            $Connection = Connection::getConn();
            $PdoTransac = $Connection->prepare($sql);
            $PdoTransac->execute($params);
        } catch (EnvironmentVarsException $Exception) {
            ErrorLog::log($Exception);
            throw new Exception();
        } catch (PDOException $Exception) {
            ErrorLog::log($Exception);
            throw new DatabaseException();
        }

        if ($PdoTransac->rowCount() == 0) {
            throw new ItemNotFoundException();
        }

    }

    /**
     * Inserts a new Game into the database
     * 
     * @throws DatabaseException
     * @throws Exception
     */
    public function insert() {
        $sql = 'INSERT INTO `tb_game` (`name`, `publisher`, `release_date`, `genre`, `abstract`, `contributor`, `approved`)
                     VALUES (?, ?, ?, ?, ?, ?, 0);';

        $params = [
            $this->name,
            $this->getModelPublisher()->getId(),
            $this->getReleaseDate(),
            $this->getModelGenre()->getId(), 
            $this->getAbstract(),
            $this->getContributor()
        ];

        try {
            $Connection = Connection::getConn();
            $PdoTransac = $Connection->prepare($sql);
            $PdoTransac->execute($params);
        } catch (EnvironmentVarsException $Exception) {
            ErrorLog::log($Exception);
            throw new Exception();
        } catch (PDOException $Exception) {
            ErrorLog::log($Exception);
            throw new DatabaseException();
        }
    }

    /**
     * Approves a game
     * 
     * @return bool
     * @throws DatabaseException
     * @throws Exception
     */
    public function approve() {
        $sql = 'UPDATE tb_game
                   SET approved = 1
                 WHERE id = ?;';
        
        $params = [$this->getId()];

        try {
            $Connection = Connection::getConn();
            $PdoTransac = $Connection->prepare($sql);
            $PdoTransac->execute($params);
        } catch (PDOException $Error) {
            ErrorLog::log($Error);
            throw new DatabaseException();
        } catch (EnvironmentVarsException $Exception) {
            ErrorLog::log($Exception);
            throw new Exception();
        }

        return true;
    }

    /**
     * Returns model's JSON representation
     * 
     * @return mixed
     */
    public function jsonSerialize() : mixed {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'release_date' => $this->releaseDate,
            'age'          => $this->age,
            'abstract'     => $this->abstract,
            'contributor'  => $this->getContributor(),
            'approved'     => $this->approved,
            'publisher'    => $this->getModelPublisher()->getName(),
            'genre'        => $this->getModelGenre()->getName()
        ];
    }
    
}
