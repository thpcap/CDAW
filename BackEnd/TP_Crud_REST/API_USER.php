<?php
    /**
     * API_USER.php
     * GET : get user by id : /API_USER.php?id=1 output : {id: 1, name: 'John', email: 'test@test.test'}
     * GET : get user by name : /API_USER.php?name=John output : {id: 1, name: 'John', email: 'test@test.test'}
     * GET : get user by email : /API_USER.php?email=test@test.test output : {id: 1, name: 'John', email: 'test@test.test'}
     * GET : get all users : /API_USER.php output : [{id: 1, name: 'John', email: 'test@test.test'}, {id: 2, name: 'Doe', email: 'test2@test.test'}]
     * POST : create user : /API_USER.php, data input : {name: 'John', email: 'test@test.test'} output : {id: 1, name: 'John', email: 'test2@test.test'}
     * PUT : update user : /API_USER.php, data input : {id: 1, name: 'John', email: 'test@test.test'} output : {id: 1, name: 'John', email: 'test2@test.test'}
     * DELETE : delete user : /API_USER.php, data input : {id: 1} output : {id: 1, name: 'John', email: 'test2@test.test'}
     */
    require_once 'initPDO.php';
    require_once 'User_Class.php';
    User::initPDO($pdo);
    // REST API
    header('Content-Type: application/json');
    switch($_SERVER['REQUEST_METHOD']){
        // GET
        case 'GET':
            if(isset($_GET['id'])){
                $user = new User($_GET['id']);
                if($user->id == null){
                    header('HTTP/1.1 404 Not Found');
                    echo json_encode(['error' => 'User not found']);
                }else{
                    echo json_encode($user);
                }
            }elseif(isset($_GET['name'])){
                $st = User::query("SELECT * FROM users WHERE name = '".$_GET['name']."'");
                $users = $st->fetchAll();
                if(count($users) == 0){
                    header('HTTP/1.1 404 Not Found');
                    echo json_encode(['error' => 'User not found']);
                }else{
                    echo json_encode($users);
                }
            }elseif(isset($_GET['email'])){
                $st = User::query("SELECT * FROM users WHERE email = '".$_GET['email']."'");
                $users = $st->fetchAll();
                if(count($users) == 0){
                    header('HTTP/1.1 404 Not Found');
                    echo json_encode(['error' => 'User not found']);
                }else{
                    echo json_encode($users);
                }
            }else{
                $st = User::query("SELECT * FROM users");
                $users = $st->fetchAll();
                if($st->rowCount() == 0){
                    header('HTTP/1.1 404 Not Found');
                    echo json_encode(['error' => 'No user found']);
                }else{
                    echo json_encode($users);
                }
            }
            break;
        // POST
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if(!isset($data['name']) || !isset($data['email'])){
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(['error' => 'Missing parameters']);
                break;
            }
            $st = User::query("INSERT INTO users (name, email) VALUES ('".$data['name']."', '".$data['email']."')");
            if($st->rowCount() == 0){
                header('HTTP/1.1 500 Internal Server Error');
                echo json_encode(['error' => 'User not created']);
                break;
            }
            echo json_encode($st->fetch());
            break;
        // PUT
        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            if(!isset($data['id']) || !isset($data['name']) || !isset($data['email'])){
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(['error' => 'Missing parameters']);
                break;
            }elseif(User::query("SELECT * FROM users WHERE id = ".$data['id'])->rowCount() == 0){
                header('HTTP/1.1 404 Not Found');
                echo json_encode(['error' => 'User not found']);
                break;
            }
            $st = User::query("UPDATE users SET name = '".$data['name']."', email = '".$data['email']."' WHERE id = ".$data['id']);
            if($st->rowCount() == 0){
                header('HTTP/1.1 500 Internal Server Error');
                echo json_encode(['error' => 'User not updated']);
                break;
            }
            echo json_encode($st->fetch());
            break;
        // DELETE
        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            if(!isset($data['id'])){
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(['error' => 'Missing parameters']);
                break;
            }elseif(User::query("SELECT * FROM users WHERE id = ".$data['id'])->rowCount() == 0){
                header('HTTP/1.1 404 Not Found');
                echo json_encode(['error' => 'User not found']);
                break;
            }
            $st = User::query("DELETE FROM users WHERE id = ".$data['id']);
            if($st->rowCount() == 0){
                header('HTTP/1.1 500 Internal Server Error');
                echo json_encode(['error' => 'User not deleted']);
                break;
            }
            echo json_encode($st->fetch());
            break;
        // OPTIONS, HEAD, PATCH, TRACE, CONNECT
        default:
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: GET, POST, PUT, DELETE');
            break;
    }