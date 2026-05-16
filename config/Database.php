<?php

class Database {
    private static ?mysqli $instance = null;

    public static function getConnection(): mysqli {
        if (self::$instance === null) {
            $host   = "localhost";
            $user   = "root";
            $pass   = "";
            $dbname = "artclub";

            self::$instance = new mysqli($host, $user, $pass, $dbname);

            if (self::$instance->connect_error) {
                die("Connection failed: " . self::$instance->connect_error);
            }
        }
        return self::$instance;
    }
}