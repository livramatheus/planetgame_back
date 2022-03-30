<?php

namespace Livramatheus\PlanetgameBack\Models;

use Livramatheus\PlanetgameBack\Core\Connection;
use Livramatheus\PlanetgameBack\Core\ErrorLog;
use Livramatheus\PlanetgameBack\Core\Exceptions\DatabaseException;
use Livramatheus\PlanetgameBack\Core\Exceptions\EvironmentVarsException;
use PDO, Exception;
use PDOException;

class Admin {

    private $username;
    private $password;
    private $firstName;
    private $lastName;

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    private function getPasswordHashed() {
        return hash('sha512', getenv('ADMIN_SALT') . $this->password);
    }

    public function login() {
        $sql = 'SELECT user_name,
                       first_name,
                       last_name
                  FROM tb_admin
                 WHERE user_name = ?
                   AND password = ?;';
        
        $params = [$this->username, $this->getPasswordHashed()];

        try {
            $Connection = Connection::getConn();
            $PdoTransac = $Connection->prepare($sql);
            $PdoTransac->execute($params);
            $res = $PdoTransac->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $Exception) {
            ErrorLog::log($Exception);
            throw new DatabaseException($Exception);
        } catch (EvironmentVarsException|Exception $Exception) {
            ErrorLog::log($Exception);
            throw new Exception($Exception);
        }
        
        if (!$PdoTransac->rowCount()) {
            return false;
        }

        $this->setUsername($res['user_name']);
        $this->setFirstName($res['first_name']);
        $this->setLastName($res['last_name']);

        return true;
    }
}
