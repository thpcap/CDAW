<?php

    /*
    * Analyses a request, created the right Controller passing it the request
    */
    class Dispatcher {

        public static function dispatch($request) {
            
            if($request->getControllerName()=="users"&&$request->isGET()){
                return new UsersController($request->getControllerName(),$request);
            }elseif($request->getControllerName()=="user"&&$request->isGET()){
                return new UsersController($request->getControllerName(),$request);
            }else{
                return new DefaultController($request->getControllerName(),$request);
            }
            // une requête GET /users doit retourner new UsersController($controllerName, $request)
            // une requête GET /user/1 doit retourner new UserController($controllerName, $request)
        }
    }