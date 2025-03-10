<?php
    // initialise une variable $pdo connecté à la base locale
	require_once("initPDO.php");    // cf. doc / cours

	$request = $pdo->prepare("select * from users");
    // à vous de compléter...
    // afficher un tableau HTML avec les donnéees en utilisant fetch(PDO::FETCH_OBJ)

    /*** close the database connection ***/
    $pdo = null;