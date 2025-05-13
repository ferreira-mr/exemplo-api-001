<?php

class Database
{
    private static ?PDO $pdoInstance = null;
    private const string DB_HOST = 'localhost';
    private const string DB_NAME = 'simple-api';
    private const string DB_USER = 'aluno';
    private const string DB_PASS = 'senai914';
    private const string DB_PORT = '3306';

    public static function getConnection(): PDO
    {
        if (self::$pdoInstance === null) {
            $dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";port=" . self::DB_PORT;

            try {
                self::$pdoInstance = new PDO($dsn, self::DB_USER, self::DB_PASS);
                self::$pdoInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdoInstance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                self::$pdoInstance->exec("SET NAMES 'utf8mb4'");
            } catch (\PDOException $e) {
                throw $e;
            }
        }

        return self::$pdoInstance;
    }

    private function __construct() {}
    private function __clone() {}
    public function __wakeup() {}
}