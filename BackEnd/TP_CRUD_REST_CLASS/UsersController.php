<?php

class UsersController {

    private $requestMethod;
    private $data;

    public function __construct($requestMethod,$data)
    {
        $this->requestMethod = $requestMethod;
        $this->data = $data;
    }

    public function processRequest()
    {
        
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->getAllUsers();
                break;
            case 'POST':
                $response = $this->addUser();
                break;
            case 'PUT':
                $response = $this->updateUser();
                break;
            case 'DELETE':
                $response = $this->deleteUser();
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllUsers()
    {
        return UserModel::getAlluser();
    }

    private function updateUser(){
        if(isset($data['name']) && isset($data['email'])&&isset($data['id'])){
            return UserModel::updateUser($this->data['id'],$this->data['name'],$this->data['email']);            
        }else{
            $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
            $response['body'] = null;
            return $response;
        }
    }

    private function deleteUser(){
        if(isset($this->data['id'])){
            return UserModel::deleteUser($this->data['id']);
        }else{
            $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
            $response['body'] = null;
            return $response;
        }
    }
    private function addUser(){
        if(isset($this->data['name']) && isset($this->data['email'])){
            return UserModel::createUser($this->data["name"],$this->data['email']);
        }else{
            $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
            $response['body'] = null;
            return $response;
        }
    }
    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}