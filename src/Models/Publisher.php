<?php

namespace Livramatheus\PlanetgameBack\Models;

use Exception;
use JsonSerializable;
use Livramatheus\PlanetgameBack\Core\Connection;
use PDO;
use PDOException;
use RelativeTime\RelativeTime;

class Publisher implements JsonSerializable {

    private $id;
    private $name;
    private $founded;
    private $logo;
    private $website;
    private $age;

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

    public function getFounded() {
        return $this->founded;
    }

    public function setFounded($founded) {
        $this->founded = $founded;
    }

    public function getLogo() {
        return $this->logo;
    }

    public function setLogo($logo) {
        $this->logo = $logo;
    }

    public function getWebsite() {
        return $this->website;
    }

    public function setWebsite($website) {
        $this->website = $website;
    }

    public function getAge() {
        return $this->age;
    }

    public function setAge($age) {
        $RelativeTime = new RelativeTime(['truncate' => 1]);
        $this->age = $RelativeTime->timeAgo($age);
    }

    public function getAll() {
        $sql = 'SELECT `id`,
                       `name`,
                       `website`
                  FROM `tb_publisher`;';

        $PdoTransac = Connection::getConn()->query($sql);

        return $PdoTransac->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get() {
        $sql = 'SELECT id,
                       name,
                       founded,
                       logo,
                       website
                  FROM tb_publisher
                 WHERE id = ?;';

        $params = [$this->id];

        $PdoTransac = Connection::getConn()->prepare($sql);
        $PdoTransac->execute($params);

        $res = $PdoTransac->fetch(PDO::FETCH_ASSOC);

        if (!$PdoTransac->rowCount()) {
            throw new Exception('Notice: Publisher not found.');
        }

        $this->setName($res['name']);
        $this->setFounded($res['founded']);
        $this->setLogo($res['logo']);
        $this->setWebsite($res['website']);
        $this->setAge($res['founded']);

        return $this;
    }

    public function delete() {
        $sql = 'DELETE
                  FROM tb_publisher
                 WHERE id = ?;';

        $params = [$this->id];

        $PdoTransac = Connection::getConn()->prepare($sql);

        try {
            $PdoTransac->execute($params);
        } catch (PDOException $Error) {
            throw new Exception('Something went wrong with the database.');
        }

        if ($PdoTransac->rowCount() == 0) {
            throw new Exception('Notice: Publisher not found.');
        }
    }

    public function insert() {
        $sql = 'INSERT INTO tb_publisher (id, name, founded, logo, website)
                                  VALUES (?, ?, ?, ?, ?);';

        try {
            $PdoTransac = Connection::getConn()->prepare($sql);
            $PdoTransac->execute([
                $this->id,
                $this->name,
                $this->founded,
                $this->logo,
                $this->website
            ]);
        } catch (PDOException $Error) {
            throw new Exception($Error->getMessage());
        }
    }

    public function jsonSerialize() {
        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'founded' => $this->founded,
            'logo'    => $this->logo,
            'website' => $this->website,
            'age'     => $this->age
        ];
    }
}
