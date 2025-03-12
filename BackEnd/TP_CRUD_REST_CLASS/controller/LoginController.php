<?php

include_once __ROOT_DIR . '/libs/php-jwt/src/BeforeValidException.php';
include_once __ROOT_DIR . '/libs/php-jwt/src/ExpiredException.php';
include_once __ROOT_DIR . '/libs/php-jwt/src/SignatureInvalidException.php';
include_once __ROOT_DIR . '/libs/php-jwt/src/JWT.php';
use \Firebase\JWT\JWT;

class LoginController{

    protected $request;
    protected $data;

   public function __construct($request,$data) {
      $this->data=$data;
      $this->request=$request;
   }

	public function processRequest() {
        switch($this->request){
            case 'Post':
                if(isset($this->data['password'])&&(isset($this->data['id']))){
                    if(loginModel::check_password($this->data['password'],$this->data['id'])){
                        return loginModel::createTocken();
                    }
                }
                break;
            default:

            break
        }
	}
}