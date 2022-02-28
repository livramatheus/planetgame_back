<?php

namespace Livramatheus\PlanetgameBack\Core;
use PDO, PDOException;

class Connection {

    private static $conn;

    public static function getConn() {

        if (empty(self::$conn)) {
            $x = explode('@', getenv('CLEARDB_DATABASE_URL'));
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
                ErrorLog::log($Error);
                $Response = new Response();
                $Response->setResponseCode(400);
                $Response->setData('Database Error. Try again later.');
                $Response->send();
            }
        }

        return self::$conn;
    }

}