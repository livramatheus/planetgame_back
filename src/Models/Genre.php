<?php

namespace Livramatheus\PlanetgameBack\Models;

use Exception;
use JsonSerializable;
use Livramatheus\PlanetgameBack\Core\Connection;
use Livramatheus\PlanetgameBack\Core\ErrorLog;
use Livramatheus\PlanetgameBack\Core\Exceptions\DatabaseException;
use Livramatheus\PlanetgameBack\Core\Exceptions\EnvironmentVarsException;
use PDO;
use PDOException;

/**
 * Genre model class
 * 
 * @package Model
 * @author Matheus do Livramento
 */
class Genre implements JsonSerializable {

    private $id;
    private $name;

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

    /**
     * Queries database and returns an array of genres
     * 
     * @return array
     * @throws DatabaseException
     * @throws Exception
     */
    public function getAll() {
        $sql = 'SELECT * FROM tb_genre;';

        try {
            $Connection = Connection::getConn();
            $PdoTransac = $Connection->query($sql);
        } catch (PDOException $Exception) {
            ErrorLog::log($Exception);
            throw new DatabaseException();
        } catch (EnvironmentVarsException $Exception) {
            ErrorLog::log($Exception);
            throw new Exception();
        }

        $data = [];

        while ($row = $PdoTransac->fetch(PDO::FETCH_ASSOC)) {
            $ModelGenre = new Genre();

            $ModelGenre->setId($row['id']);
            $ModelGenre->setName($row['name']);

            $data[] = $ModelGenre;
        }

        return $data;
    }

    /**
     * Returns model's JSON representation
     * 
     * @return mixed
     */
    public function jsonSerialize() : mixed {
        return [
            'id'   => $this->id,
            'name' => $this->name
        ];
    }

}
