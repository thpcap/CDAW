<?php
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            display();
            break;
        case "POST":
            if(isset($_POST['name']) && isset($_POST['email'])&&isset($_POST['_METHOD']) && $_POST['_METHOD'] == "POST") {
                require_once "initPDO.php";
                $name = $_POST['name'];
                $email = $_POST['email'];
                $request = $pdo->prepare("insert into users (name, email) values (:name, :email)");
                $request->bindParam(':name', $name);
                $request->bindParam(':email', $email);
                $request->execute();
                $pdo = null;
                header("Location: test-PDO-post.php");
            }
            break;
    }
    
    function display(){
        // initialise une variable $pdo connecté à la base locale
        require_once("initPDO.php");    // cf. doc / cours

        $request = $pdo->prepare("select * from users");
        // afficher un tableau HTML avec les donnéees en utilisant fetch(PDO::FETCH_OBJ)
        $request->execute();
        $result = $request->fetchAll(PDO::FETCH_OBJ);
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
                            <form action='test-PDO-post.php' method='GET'>
                                <input type='submit' value='Refresh'>
                            </form>
                        </th>
                    </tr>
                    <tr>
                        <th>id</th>
                        <th>name</th>
                        <th>email</th>
                        <th>Delete</th>
                        <th>Update</th>
                    </tr>";
        
        foreach($result as $row) {
            echo "
                <tr>
                    <td>".$row->id."</td>
                    <td>".$row->name."</td>
                    <td>".$row->email."</td>
                </tr>";

        }
        echo "
                </table>
            </div>";
        /*** close the database connection ***/
        $pdo = null;
        echo '
            <br>
            <div id="create">
                <h2>Create a new user</h2>
                <form action="test-PDO-post.php" method="post">
                    <input type="hidden" name="_METHOD" value="POST">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email">
                    <input type="submit" value="Create">
                </form>
            </div>';
    }
?>