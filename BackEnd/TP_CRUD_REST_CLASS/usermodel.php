<?php

	class UserModel
	{
    
        /**
        * Sanitizes input to protect against SQL injection.
        * @param string $input The input to be sanitized.
        * @return string The sanitized input.
        */
        public static function sanitizeInput($input) {
            return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    
        }
        private static $pdo;
    
        public function __construct($pdo) {
            self::$pdo = $pdo;
        }
    
        public static function db() {
            return self::$pdo;
        }
    
        private static function query($sql, $params = []) {
            $stmt = self::db()->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt;
        }
    
        public static function getAllUsers() {
            $stmt = self::query("SELECT * FROM users");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        public static function getUserById($id) {
            $stmt = self::query("SELECT * FROM users WHERE id = :id", [':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    
        public static function getUserByName($name) {
            $name = self::sanitizeInput($name);
            $stmt = self::query("SELECT * FROM users WHERE name = :name", [':name' => $name]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    
        public static function getUserByEmail($email) {
            $email = self::sanitizeInput($email);
            $stmt = self::query("SELECT * FROM users WHERE email = :email", [':email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    
        public static function createUser($name, $email) {
            $name = self::sanitizeInput($name);
            $email = self::sanitizeInput($email);
            return self::query("INSERT INTO users (name, email) VALUES (:name, :email)", [':name' => $name, ':email' => $email])->rowCount() > 0;
        }
    
        public static function updateUser($id, $name, $email) {
            $name = self::sanitizeInput($name);
            $email = self::sanitizeInput($email);
            return self::query("UPDATE users SET name = :name, email = :email WHERE id = :id", [':id' => $id, ':name' => $name, ':email' => $email])->rowCount() > 0;
        }
    
        public static function deleteUser($id) {
            return self::query("DELETE FROM users WHERE id = :id", [':id' => $id])->rowCount() > 0;
        }
        
    }