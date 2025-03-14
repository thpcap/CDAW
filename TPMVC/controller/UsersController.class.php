<?php

    class UsersController extends Controller {

        public function __construct($name, $request) {
            parent::__construct($name, $request);
        }

        public function processRequest()
        {
            switch ($this->request->getHttpMethod()) {
                
                case 'GET':
                    if($this->request->getControllerName()=="users"){
                        return $this->getAllUsers();
                    }elseif($this->request->getControllerName()=="user"&&isset($this->request->getParameters()[0])){
                        return $this->getUserById($this->request->getParameters()[0]);
                    }
                    
                
            }
            return Response::errorResponse("unsupported parameters or method in users");
        }

        protected function getAllUsers()
        {
            $users = User::getList();
            $users = array_map(
                function($user) {return $user->getProperties();},
                $users
            );
            $json = json_encode($users);
            $response=new Response(200,$json);
            return $response;
        }

        protected function getUserById($id){
            $user= User::getById($id);
            if ($user) {
                $user = $user->getProperties();
                $json = json_encode($user);
                $response = new Response(200, $json);
            } else {
                $response = Response::errorResponse("User not found");
            }
            return $response;
        }
    }