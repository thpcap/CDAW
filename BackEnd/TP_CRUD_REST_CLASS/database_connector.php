<?php

    class DatabaseConnector {

        protected static $pdo = NULL;

        public static function current(){
        if(is_null(static::$pdo))
            static::createPDO();

            return static::$pdo;
        }

        protected static function createPDO() {
            // $db = new PDO("sqlite::memory");

            $connectionString = "mysql:host=". _MYSQL_DB_HOST;

            if(defined('_MYSQL_PORT'))
                $connectionString .= ";port=". _MYSQL_DB_PORT;

            $connectionString .= ";dbname=" . _MYSQL_DB_DATABASE;

            static::$pdo = new PDO($connectionString,_MYSQL_DB_USERNAME,_MYSQL_DB_PASSWORD);
            static::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }