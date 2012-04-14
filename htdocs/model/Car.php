<?php
require_once ('config.php');

class Car {

	/*
	 *
	 */
	public function __construct($licensePlate) {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		if ($licensePlate != NULL) {
			$query = $bdd->prepare("SELECT * FROM Voiture WHERE idVoiture = '$licensePlate';");
			$query->execute();
			if ($query != false) {
				$data = $query->fetch();
				/* The car exists in the database */
				if ($data['idVoiture'] != NULL) {
					$this->_licensePlate = $data['idVoiture'];
					$this->_model = $data['modele'];
					$this->_year = $data['annee'];
					$this->_idMember = $data['idMembre'];
					$this->_idBrandt = $data['idMarque'];
					$query = $bdd->prepare("SELECT * FROM Marque WHERE idMarque = '$this->_idBrandt';");
					$query->execute();
					$data = $query->fetch();
					$this->_brandt = $data['constructeur'];
					$this->_idMember = $_SESSION['idMember'];
				}
				/* The car doesn't exist in the database */
				else {
					$this->_brandt = $_POST['brandt'];
					$query = $bdd->prepare("SELECT * FROM Marque WHERE constructeur = '$this->_brandt';");
					$query->execute();
					$data = $query->fetch();
					$this->_idBrandt = $data['idMarque'];
					$this->_model = $_POST['model'];
					//$this->_licensePlate = $_POST['licensePlate'];
					$this->_year = $_POST['year'];
					$this->_idMember = $_SESSION['idMember'];
				}
				return $this;
			}
			$query->closeCursor();
		}
	}

	/*
	 *
	 */
	public function addCar($licensePlate) {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		$query = $bdd->prepare("INSERT INTO Voiture (idVoiture, modele, annee, idMembre, idMarque) VALUES ('$licensePlate', '$this->_model', '$this->_year', '$this->_idMember', '$this->_idBrandt');");
		$query->execute();
	}

	/*
	 *
	 */
	public function deleteCar($licensePlate) {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		$query = $bdd->prepare("DELETE FROM Voiture WHERE idVoiture = '$licensePlate';");
		$result = $query->execute();
		return $result;
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
