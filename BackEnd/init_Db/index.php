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
                $pdo->query(file_get_contents('initData.sql'));
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
