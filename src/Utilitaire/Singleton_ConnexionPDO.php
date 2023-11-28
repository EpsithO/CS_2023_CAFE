<?php

namespace App\Utilitaire;

use PDO;

class Singleton_ConnexionPDO extends PDO
{
    protected static ?PDO $_PDO = null;

    private function __construct()
    {
        parent::__construct('mysql:host=127.0.0.1;dbname=CS_2023_CAFE;charset=UTF8',
            "root",
            "",
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            )
        );

    }

    public static function getInstance(): PDO
    {

        if (is_null(self::$_PDO)) {
            self::$_PDO = new Singleton_ConnexionPDO();
        }
        return self::$_PDO;
    }
}
