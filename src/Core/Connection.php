<?php

namespace Livramatheus\PlanetgameBack\Core;

use Livramatheus\PlanetgameBack\Core\Exceptions\EnvironmentVarsException;
use PDO, PDOException;

/**
 * Database connection manager class
 * 
 * @package Core
 * @author Matheus do Livramento
 */
class Connection {

    private static $conn;
    private static $envData;

    /**
     * Returns a connection with the database using singleton pattern
     * @throws EnvironmentVarsException
     * @throws PDOException
     * @return PDO
     */
    public static function getConn() {

        if (empty(self::$conn)) {
            self::$envData = getenv('CLEARDB_DATABASE_URL');
            
            if (empty(self::$envData)) {
                throw new EnvironmentVarsException();
            }

            $x = explode('@', self::$envData);
            $y = explode(':', $x[0]);
            $z = explode('/', $x[1]);
            $a = explode('/', $z[1]);
            $b = explode('?', $a[0]);

            $host     = $z[0];
            $dbname   = $b[0];
            $user     = str_replace('//', '', $y[1]);
            $password = $y[2];

            try {
                self::$conn = new PDO('mysql:host=' .$host. ';dbname=' . $dbname, $user, $password);
            } catch (PDOException $Error) {
                throw new PDOException($Error);
            }
        }

        return self::$conn;
    }

}