<?php
require_once ('config.php');

class Members {
	/*
	 * if $mode = true or no arg given, return the last three profiles created
	 * if $mode = false, return three random profile
	 * A profile is defined by an array with two keys : image, pseudo
	 */
	public static function getFrontProfiles($mode = true) {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		if ($mode == true) {
			$query = $bdd->prepare("SELECT * FROM Member ORDER BY idMember DESC LIMIT 3;");
			$query->execute();
			return $query;
		}
		else {
			$query = $bdd->prepare("SELECT * FROM Member ORDER BY rand() LIMIT 3;");
			$query->execute();
			return $query;
		}
	}
	
	public static function checkEmail($email) {
		if(eregi("^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]", $email))
			return FALSE;
		list($Username, $Domain) = split("@",$email);
		if(getmxrr($Domain, $MXHost))
			return TRUE;
		else {
			if(fsockopen($Domain, 25, $errno, $errstr, 30))
				return TRUE;
			else
				return FALSE;
		}
	}

	/*
	 * If ID and password is in database, return TRUE. Otherwise, return false
	 */ 
	public static function signIn($email, $password) {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		$query = $bdd->prepare("SELECT * FROM Membre WHERE email = '$email' AND password = '$password';");
		$answer = $query->execute();
		if ($answer == TRUE) {
			$data = $query->fetchAll();
			if (count($data) == 1) {
				$query->closeCursor();
				return true;
			}
			else {
				$query->closeCursor();
				return false;
			}
		}
		return false;
	}

	/*
	 * Return an array of all members stored in database. If $number is different from 0, limit the size of the array
	 */
	public static function getAll() 
	{
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		$query = $bdd->prepare("SELECT * FROM Membre ORDER BY idMembre;");
		$query->execute();
		$data = $query->fetchAll();
		return $data;
	}
	
	/*
	 * Get all the user cars
	 */
	public static function getAllCars($idMember) {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		$query = $bdd->prepare("SELECT * FROM Voiture WHERE idMembre = '$idMember';");
		$query->execute();
		$data = $query->fetchAll();
		return $data;
	}
	
	/*
	 * Return a member
	 */
	public static function getOne($idMember) {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		$query = $bdd->prepare("SELECT * FROM Membre WHERE idMembre = '$idMember';");
		$query->execute();
		$data = $query->fetch();
		return $data['idMembre'];
	}

	/*
	 * Delete the given member, if $idMember is not empty
	 */
	public static function delete($idCar) {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		$idMember = $_SESSION['idMember'];
		$req = $bdd->prepare("DELETE FROM Voiture WHERE idVoiture = '$idCar' AND idMembre = '$idMember'");
		$req->execute();
		$req = $bdd->prepare("DELETE FROM Donnees WHERE idVoiture = '$idCar'");
		$req->execute();
		//return $result;
	}

	/*
	 * 
	 */
	public static function getCarData($idCar) {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		$query = $bdd->prepare("SELECT * FROM Donnees WHERE idVoiture = '$idCar'");
		$query->execute();
		$data = $query->fetchAll();
		return $data;
	}

	/*
	 * Delete the given member, if $idMember is not empty
	 */
	public static function deleteMember($idMember) {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		$query = $bdd->prepare("SELECT * FROM Voiture WHERE idMembre = '$idMember'");
		$query->execute();
		$data = $query->fetchAll();
		foreach ($data as $datum)
		{
			$req = $bdd->prepare("SELECT * FROM Donnees WHERE idVoiture = '$datum[idVoiture]'");
			$req->execute();
			$donnees = $req->fetchAll();
			foreach ($donnees as $donnee)
			{
				$req2 = $bdd->prepare("DELETE FROM Donnees WHERE idVoiture = '$datum[idVoiture]'");
				$req2->execute();
			}
			$req = $bdd->prepare("DELETE FROM Voiture WHERE idVoiture = '$datum[idVoiture]'");
			$req->execute();
		}
		$req = $bdd->prepare("DELETE FROM Membre WHERE idMembre = '$idMember'");
		$req->execute();

		//return $result;
	}
	
	/**
	 * Get the 10 last profiles from the database
	 */
	public static function getRss() {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		$query = $bdd->prepare("SELECT * FROM Member ORDER BY idMember DESC LIMIT 10;");
		$query->execute();
		return $query;
	}

	/*
	 * Get page informations
	 */
	public static function getInfosPage($address) {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		$req = $bdd->prepare("SELECT * FROM Page WHERE adresse = '$address'");
		$req->execute();
		$data = $req->fetch();
		$req->closeCursor();
		return $data;
	}

}
