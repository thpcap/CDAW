<?php
    include_once __ROOT_DIR . '/libs/php-jwt/src/BeforeValidException.php';
    include_once __ROOT_DIR . '/libs/php-jwt/src/ExpiredException.php';
    include_once __ROOT_DIR . '/libs/php-jwt/src/SignatureInvalidException.php';
    include_once __ROOT_DIR . '/libs/php-jwt/src/JWT.php';
    use \Firebase\JWT\JWT;
    class loginModel{
        
        public static function check_password($User_Password,$id){
            $user2=UserModel::getUserById($id);
            $pass=$user2->USER_PASSWORD;
            return password_verify($User_Password,$pass);
        }
        public static function createTocken($id){
            $user=UserModel::getUserById($id);
            $firstname=$user->USER_FIRSTNAME;
            $lastname=$user->USER_LASTNAME;
            $email=$user->USER_EMAIL;

            $secret_key = "YOUR_SECRET_KEY";
            $issuer_claim = "THE_ISSUER"; // this can be the servername
            $audience_claim = "THE_AUDIENCE";
            $issuedat_claim = time(); // issued at
            $notbefore_claim = $issuedat_claim + 10; //not before in seconds
            $expire_claim = $issuedat_claim + 60; // expire time in seconds
            $token = array(
                "iss" => $issuer_claim,
                "aud" => $audience_claim,
                "iat" => $issuedat_claim,
                "nbf" => $notbefore_claim,
                "exp" => $expire_claim,
                "data" => array(
                    "id" => $id,
                    "firstname" => $firstname,
                    "lastname" => $lastname,
                    "email" => $email
            ));
            http_response_code(200);
    
            $jwt = JWT::encode($token, $secret_key,"ES384");
            return json_encode(
                array(
                    "message" => "Successful login.",
                    "jwt" => $jwt,
                    "email" => $email,
                    "expireAt" => $expire_claim
                ));
        }
    }