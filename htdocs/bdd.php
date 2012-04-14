<?php

	define('DSN', 'mysql:host=sql-users.ece.fr;dbname=dataphone;port=3305');
	define('DB_USERNAME', 'dataphone-rw');
	define('DB_PASSWORD', 'zuJref7L');

	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
	
	for($i = 374; $i < 620; $i++){		
	//	$data = $bdd->prepare("SELECT * FROM Donnees WHERE idDonnees='$i';");
	//	$data->execute();
	//	$count = $data->fetch();
		
	//	if(count($count)==1)
			$query = $bdd->prepare("UPDATE Donnees SET date=NOW() WHERE idDonnees='$i';");
			$query->execute();
			echo "$i\n";
			Sleep(10);
	}




?>
