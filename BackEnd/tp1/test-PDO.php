<?php
    // initialise une variable $pdo connecté à la base locale
	require_once("initPDO.php");    // cf. doc / cours

	$request = $pdo->prepare("select * from users");
    // à vous de compléter...
    // afficher un tableau HTML avec les donnéees en utilisant fetch(PDO::FETCH_OBJ)
    $request->execute();
    $result = $request->fetchAll(PDO::FETCH_OBJ);
    echo "<table>";
    echo "<tr><th>id</th><th>login</th><th>password</th></tr>";
    foreach($result as $row) {
        echo "<tr><th>".$row->id."</th><th>".$row->name."</th><th>".$row->email."</th></tr>";
    }
    echo "</table>";

    /*** close the database connection ***/
    $pdo = null;

    