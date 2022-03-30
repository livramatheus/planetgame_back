<?php

namespace Livramatheus\PlanetgameBack\Models;

use Exception;
use JsonSerializable;
use Livramatheus\PlanetgameBack\Core\Connection;
use Livramatheus\PlanetgameBack\Core\ErrorLog;
use Livramatheus\PlanetgameBack\Core\Exceptions\DatabaseException;
use Livramatheus\PlanetgameBack\Core\Exceptions\EnvironmentVarsException;
use Livramatheus\PlanetgameBack\Core\Exceptions\ItemNotFoundException;
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
        $sql = 'SELECT *
                  FROM `tb_publisher`;';

        try {
            $Connection = Connection::getConn();
            $PdoTransac = $Connection->query($sql);
        } catch (PDOException $Exception) {
            ErrorLog::log($Exception);
            throw new DatabaseException();
        } catch (EnvironmentVarsException $Exception) {
            ErrorLog::log($Exception);
            throw new Exception($Exception->getMessage());
        }

        $data = [];

        while ($row = $PdoTransac->fetch(PDO::FETCH_ASSOC)) {
            $ModelPublisher = new Publisher();

            $ModelPublisher->setId($row['id']);
            $ModelPublisher->setName($row['name']);
            $ModelPublisher->setWebsite($row['website']);
            $ModelPublisher->setLogo($row['logo']);
            $ModelPublisher->setFounded($row['founded']);
            $ModelPublisher->setAge($row['founded']);

            $data[] = $ModelPublisher;
        }

        return $data;
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

        try {
            $Connection = Connection::getConn();
            $PdoTransac = $Connection->prepare($sql);
            $PdoTransac->execute($params);
            $res = $PdoTransac->fetch(PDO::FETCH_ASSOC);
        } catch (EnvironmentVarsException $Exception) {
            ErrorLog::log($Exception);
            throw new Exception();
        } catch (PDOException $Exception) {
            ErrorLog::log($Exception);
            throw new DatabaseException();
        }

        if (!$PdoTransac->rowCount()) {
            throw new ItemNotFoundException();
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

    public function insert() {
        $sql = 'INSERT INTO tb_publisher (id, name, founded, logo, website)
                                  VALUES (?, ?, ?, ?, ?);';

        try {
            $Connection = Connection::getConn();
            $PdoTransac = $Connection->prepare($sql);
            $PdoTransac->execute([
                $this->id,
                $this->name,
                $this->founded,
                $this->logo,
                $this->website
            ]);
        } catch (PDOException $Exception) {
            ErrorLog::log($Exception);
            throw new DatabaseException();
        } catch (EnvironmentVarsException $Exception) {
            ErrorLog::log($Exception);
            throw new Exception();
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
