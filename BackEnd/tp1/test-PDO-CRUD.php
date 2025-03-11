<?php
    require_once("initPDO.php");
    User::initPDO($pdo);
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            display();
            break;
        case "POST":
            if(isset($_POST['name']) && isset($_POST['email'])&&isset($_POST['_METHOD']) && $_POST['_METHOD'] == "POST") {
                require_once "initPDO.php";
                $name = $_POST['name'];
                $email = $_POST['email'];
                User::query( "insert into users (name, email) values ('$name', '$email')");
                display();
                break;
            }
            if(isset($_POST['id']) && isset($_POST['_METHOD']) && $_POST['_METHOD'] == "DELETE") {
                $id=$_POST['id'];
                User::query("delete from users where id = $id");
                display();
                break;
            }

            if(isset($_POST['id']) && isset($_POST['_METHOD']) && $_POST['_METHOD'] == "PUT") {
                $id = $_POST['id'];
                $result = User::query("select * from users where id = $id")->fetch(PDO::FETCH_OBJ);
                echo '
                    <link rel="stylesheet" href="../../style.css">
                    <h1>CRUD Users</h1>
                    <div id="update">
                        <h2>Update user</h2>
                        <form action="test-PDO-CRUD.php" method="post">
                            <input type="hidden" name="_METHOD" value="PUT2">
                            <p>Update user <strong>'.$result->name.'</strong></p>
                            Id:
                            <div style="border-radius: 4px; border: 1px solid #ccc; padding: 5px; margin: 5px; background-color: #f2f2f2;">'.$result->id.'</div>
                            <input type="hidden" name="id" value="'.$result->id.'">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" value="'.$result->name.'">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="'.$result->email.'">
                            <br>
                            <input type="submit" value="Update">
                        </form>
                        <form action="test-PDO-CRUD.php" method="GET">
                            <input type="submit" value="Cancel">
                        </form>
                    </div>';
                    break;
            }
            if(isset($_POST['id']) && isset($_POST['_METHOD']) && $_POST['_METHOD'] == "PUT2") {
                $id = $_POST['id'];
                $name = $_POST['name'];
                $email = $_POST['email'];
                User::query("update users set name = '$name', email = '$email' where id = $id");
                display();
                break;
            }
            break;
    }
    
    function display(){
        $result = User::query("select * from users")->fetchAll();
        echo"
            <link rel='stylesheet' href='../../style.css'>
            <h1>CRUD Users</h1>
            <div id='users'>
                <table>
                    <tr>
                        <th colspan='4' style='text-align: center; margin:0;'>
                            <h2>Users</h2>
                        </th>
                        <th>
                            <form  method='GET'>
                                <input type='submit' value='Refresh'>
                            </form>
                        </th>
                    </tr>";
                    $result[0]->keys_To_Html_Table_Row();
        foreach($result as $row) {
            $row->values_To_Html_Table_Row();
        }
        echo "
                </table>
            </div>";
        User::create_User_Html_Form();
    }

    class User {
        protected $properties;
        public static $pdo;
        public function __construct($properties=array()) {
            $this->properties = $properties;
        }

        public static function initPDO($pdo) {
            self::$pdo = $pdo;
        }

        public function __get($name) {
            if (array_key_exists($name, $this->properties)) {
                return $this->properties[$name];
            }
        }
        public function __set($name, $value) {
            $this->properties[$name] = $value;
        }
        protected static function db(){
            return self::$pdo;
        }
        public static function query($sql){
            $st = static::db()->query($sql) or die("sql query error ! request : " . $sql);
            $st->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, get_called_class());
            return $st;
        }
        public function keys_To_Html_Table_Row(){
            echo "<tr>";
            foreach($this->properties as $key => $value) {
                echo "<th>".ucfirst($key)."</th>";
            }
            echo "
                <th>Delete</th>
                <th>Update</th>
            </tr>";
        }
        public function values_To_Html_Table_Row() {
            echo "<tr>";
            foreach($this->properties as $key => $value) {
                echo "<td>".$value."</td>";
            }
            echo "
                <td>
                    <form method='POST'>
                        <input type='hidden' name='_METHOD' value='DELETE'>
                        <input type='hidden' name='id' value='".$this->id."'>
                        <input type='submit' value='Delete'>
                    </form>
                </td>
                <td>
                    <form method='POST'>
                        <input type='hidden' name='_METHOD' value='PUT'>
                        <input type='hidden' name='id' value='".$this->id."'>
                        <input type='submit' value='Update'>
                    </form>
                </td>
            </tr>";
        }
        
        public static function create_User_Html_Form(){
            echo '
            <br>
            <div id="create">
                <h2>Create a new user</h2>
                <form action="test-PDO-CRUD.php" method="post">
                    <input type="hidden" name="_METHOD" value="POST">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email">
                    <input type="submit" value="Create">
                </form>
            </div>';
        }

    }

?>