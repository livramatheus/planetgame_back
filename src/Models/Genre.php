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

    public function jsonSerialize() {
        return [
            'id'   => $this->id,
            'name' => $this->name
        ];
    }

}
