<?php

namespace Livramatheus\PlanetgameBack\Models;

use JsonSerializable,
    Livramatheus\PlanetgameBack\Core\Connection,
    PDO;

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
        $PdoTransac = Connection::getConn()->query($sql);

        return $PdoTransac->fetchAll(PDO::FETCH_ASSOC); 
    }

    public function jsonSerialize() {
        return [
            'id'   => $this->id,
            'name' => $this->name
        ];
    }

}
