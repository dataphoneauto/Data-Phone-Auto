<?php
/*
	WARNING

Ce module n'est pas un test,
il permet de recevoir les données envoyées en POST par le guruplug

*/
	define('DSN', 'mysql:host=sql-users.ece.fr;dbname=dataphone;port=3305');
	define('DB_USERNAME', 'dataphone-rw');
	define('DB_PASSWORD', 'zuJref7L');

	if (isset($_POST['data'])) {
		// Immatriculation|idMembre|ID:value:value;..
		$data = explode('|', $_POST['data']);
		$idVoiture = $data[0];
		$email = $data[1];
		$donnees = $data[2];
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		/* Vérifie que la voiture appartient à un membre */
		$query = $bdd->prepare("SELECT * FROM (	SELECT m.email FROM Membre m, Voiture v
							WHERE v.idVoiture = '$idVoiture'
							AND m.idMembre = v.idMembre
							AND m.email = '$email'
						) MembreVoiture");
		$query->execute();
		/* La voiture est reconnue */
		if ($query != false) {
			
			/* MAJ des données */
			$donnees = strtolower($donnees); // mise en minuscule de tous les caractères
			$query = $bdd->prepare("INSERT INTO Donnees(donnees, idVoiture, date) VALUES ('$donnees', '$idVoiture', NOW())");
			$query->execute();
			/* MAJ historique */
			//$query = $bdd->prepare("INSERT INTO Historique(donnees, idDonnees) VALUES ('$donnees', '1')");
			//$query->execute();
			$query = $bdd->prepare("SELECT donnees FROM Donnees WHERE idVoiture = '$idVoiture'");
			$query->execute();
			foreach ($query as $data) {
				$datum = explode(';', $data['donnees']);
				foreach ($datum as $idValues) {
					$idValues = explode('=>', $idValues);
					echo "ID = " . $idValues[0] . ", valeur(s) = ";
					$values = explode(':', $idValues[1]);
					//print_r($values);
					if (count($values) > 1) {
						for ($i = 1; $i < count($values); $i++) {
							if ($i == (count($values)-1))
								echo "$values[$i]\n";
							else {
								$values[$i] = substr($values[$i], 0, strlen($values[$i])-2);
								echo "$values[$i], ";
							}
						}
					}
					else	echo "$values[0]\n";

				}
				echo "\n\n";
			}
			echo "    *************************************************\n";
			echo "    **           	    Réponse du serveur           **\n";
			echo "    ** Voiture et identifiant existant dans la BDD **\n";
			echo "    **    Les données ont été ajoutées à la BDD    **\n";
			echo "    *************************************************\n";
		}
		else {
			echo "voiture non reconnue";
		}
	}
	else	echo "donnees non reçues";

?>
