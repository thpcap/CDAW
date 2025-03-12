<!DOCTYPE html>
<html>
    <head>
        <title>create database</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../../style.css">
    </head>
    <body>
        <h1>database initialization</h1>
        <?php
            if($_SERVER['REQUEST_METHOD']=="GET"){
                echo '
                    <p>Click on the button to initialize the database</p>   
                    <form action="index.php" method="post">
                        <input type="submit" value="Initialize database">
                    </form>';
            }
            
        ?>
        <?php
            if(isset($_POST['submit'])){
                require_once 'initPDO.php';
                $pdo->query(file_get_contents('init_Db.sql'));
                if($pdo->errorCode() != '00000'){
                    echo 'Erreur : '.$pdo->errorInfo()[2];
                }
                else{
                    echo 'Database initialized successfully';
                }
                
                $Users=array(
                    new User(1,'admin','admin@localhost','admin','admin','admin','admin'),
                    new User(2,'user','user@localhost','user','user','user','user')
                );
                foreach($Users as $User){
                    $pdo->query("
                        INSERT INTO User (
                            User_ID,
                            User_Login,
                            User_Email,
                            User_Password,
                            User_Firstname,
                            User_Lastname,
                            User_Role
                        )VALUES("
                            .$User->getUser_ID().",'"
                            .$User->getUser_Login()."','"
                            .$User->getUser_Email()."','"
                            .$User->getUser_Password()."','"
                            .$User->getUser_Firstname()."','"
                            .$User->getUser_Lastname()."','"
                            .$User->getUser_Role().
                        "')"
                    );
                }
                if($pdo->errorCode() != '00000'){
                    echo 'Erreur : '.$pdo->errorInfo()[2];
                }
                else{
                    echo 'Data initialized successfully';
                }
            }
        ?>
    </body>
</html>
<?php
    class User{
        protected $User_ID;
        protected $User_Login;
        protected $User_Email;
        protected $User_Password;
        protected $User_Firstname;
        protected $User_Lastname;
        protected $User_Role;
        
        function __construct($User_ID,$User_Login,$User_Email,$User_Password,$User_Firstname,$User_Lastname,$User_Role){
            $this->User_ID = $User_ID;
            $this->User_Login = $User_Login;
            $this->User_Email = $User_Email;
            $User_Password = password_hash($User_Password,PASSWORD_BCRYPT);
            $this->User_Password = $User_Password;
            $this->User_Firstname = $User_Firstname;
            $this->User_Lastname = $User_Lastname;
            $this->User_Role = $User_Role;
        }

        function getUser_ID(){
            return $this->User_ID;
        }
        function getUser_Login(){
            return $this->User_Login;
        }
        function getUser_Email(){
            return $this->User_Email;
        }
        function getUser_Password(){
            return $this->User_Password;
        }
        function getUser_Firstname(){
            return $this->User_Firstname;
        }
        function getUser_Lastname(){
            return $this->User_Lastname;
        }
        function getUser_Role(){
            return $this->User_Role;
        }
    }