<?php
require_once ('config.php');

class Cars {
	/*
	 * Return an array of all brandts stored in database.
	 */
	public static function getAllBrandts() {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		$query = $bdd->prepare("SELECT * FROM Marque ORDER BY constructeur;");
		$query->execute();
		$data = $query->fetchAll();
		return $data;
	}

	/*
	 * Return an array of all brandts stored in database.
	 */
	public static function getMemberCars($idMember) {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		$query = $bdd->prepare("SELECT * FROM Voiture WHERE idMembre = '$idMember';");
		$query->execute();
		$data = $query->fetchAll();
		return $data;
	}

	/*
	 * Get all PidObd2
	 */
	public static function getAllPidObd2() {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		$query = $bdd->prepare("SELECT * FROM PidObd2;");
		$query->execute();
		$data = $query->fetchAll();
		return $data;
	}

	/*
	 * Get page informations
	 */
	public static function getInfosPage($address) {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		$query = $bdd->prepare("SELECT * FROM Page WHERE adresse = '$address'");
		$query->execute();
		$data = $query->fetch();
		$query->closeCursor();
		return $data;
	}
}
