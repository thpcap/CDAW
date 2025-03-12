<?php
require_once 'initPDO.php';
header("Access-Control-Allow-Origin: GET, POST, PUT, DELETE");

class User {
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

    public function getAllUsers() {
        $stmt = self::query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = self::query("SELECT * FROM users WHERE id = :id", [':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByName($name) {
        $name = self::sanitizeInput($name);
        $stmt = self::query("SELECT * FROM users WHERE name = :name", [':name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email) {
        $email = self::sanitizeInput($email);
        $stmt = self::query("SELECT * FROM users WHERE email = :email", [':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($name, $email) {
        $name = self::sanitizeInput($name);
        $email = self::sanitizeInput($email);
        return self::query("INSERT INTO users (name, email) VALUES (:name, :email)", [':name' => $name, ':email' => $email])->rowCount() > 0;
    }

    public function updateUser($id, $name, $email) {
        $name = self::sanitizeInput($name);
        $email = self::sanitizeInput($email);
        return self::query("UPDATE users SET name = :name, email = :email WHERE id = :id", [':id' => $id, ':name' => $name, ':email' => $email])->rowCount() > 0;
    }

    public function deleteUser($id) {
        return self::query("DELETE FROM users WHERE id = :id", [':id' => $id])->rowCount() > 0;
    }
}

header(header: 'Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
$user = new User($pdo);
$response = [];
$status_code = 200;

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $response = $user->getUserById($_GET['id']);
            if (!$response) {
                $response = ['error' => 'User not found'];
                $status_code = 404;
            }
        } elseif (isset($_GET['name'])) {
            $response = $user->getUserByName($_GET['name']);
            if (!$response) {
                $response = ['error' => 'User not found'];
                $status_code = 404;
            }
        } elseif (isset($_GET['email'])) {
            $response = $user->getUserByEmail($_GET['email']);
            if (!$response) {
                $response = ['error' => 'User not found'];
                $status_code = 404;
            }
        } else {
            $response = $user->getAllUsers();
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['name']) && isset($data['email'])&&isset($data['id'])) {
            $result = $user->createUser($data['name'], $data['email']);
            if ($result) {
                $response = ['message' => 'User created successfully', 'id' => $user->db()->lastInsertId()];
                $status_code = 201;
            } else {
                $response = ['error' => 'Failed to create user'];
                $response += $result;
                $status_code = 500;
            }
        } else {
            $response = ['error' => 'Invalid input'];
            $status_code = 400;
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id']) && isset($data['name']) && isset($data['email'])&&isset($data['id'])) {
            $result = $user->updateUser($data['id'], $data['name'], $data['email']);
            if ($result) {
                $response = ['message' => 'User updated successfully'];
            } else {
                $response = ['error' => 'Failed to update user'];
                $status_code = 500;
            }
        } else {
            $response = ['error' => 'Invalid input'];
            $response += $result;
            $status_code = 400;
        }
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])) {
            $result = $user->deleteUser($data['id']);
            if ($result) {
                $response = ['message' => 'User deleted successfully'];
            } else {
                $response = ['error' => 'Failed to delete user'];
                $status_code = 500;
            }
        } else {
            $response = ['error' => 'Invalid input'];
            $status_code = 400;
        }
        break;
    default:
        $response = ['error' => 'Invalid request method'];
        $status_code = 405;
        break;
}

http_response_code($status_code);
echo json_encode($response);