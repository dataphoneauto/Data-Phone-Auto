<?php
require_once ('config.php');

class Member {

	/*
	 * If the email is not null, get the data from database and fill the properties
	 * If the email is null, do nothing
	 */
	public function __construct($email = NULL) {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		if ($email != NULL) {
			$query = $bdd->prepare("SELECT * FROM Membre WHERE email = '$email';");
			$query->execute();
			if ($query != false) {
				$data = $query->fetch();
				/* The member exists in the database */
				if ($data['idMembre'] != NULL) {
					$this->_idMember = $data['idMembre'];
					$this->_lastName = $data['nom'];
					$this->_firstName = $data['prenom'];
					$this->_password = $data['password'];
					$this->_email = $data['email'];
					$this->_isAdmin = $data['isAdmin'];
					
				}
				/* The member doesn't exist in the database */
				else {
					$this->_lastName = $_POST['lastName'];
					$this->_firstName = $_POST['firstName'];
					$this->_password = $_POST['password'];
					$this->_email = $_POST['email'];
					$this->_isAdmin = 0;
				}
				return $this;
			}
			$query->closeCursor();
		}
	}

	/*
	 * Save the member into the database. If the id property is null, create a new member
	 * If not, just update it
	 */
	public function save() {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		/* The member exists in the database */
		if ($this->_idMember != NULL) {
			/* Verify the email doesn't already exist in the database */
			$query = $bdd->prepare("SELECT * FROM Membre WHERE idMembre = '$this->_idMember';");
			$query->execute();
			$data = $query->fetchAll();
			if (count($data) == 1) {
				// UPDATE (Member already exists)
				$query = $bdd->prepare("UPDATE Membre SET nom = '$this->_lastName', prenom = '$this->_firstName', password = '$this->_password', email = '$this->_email' WHERE idMembre = '$this->_idMember';");
				$query->execute();
				$query->closeCursor();
			}
		}
		/* The member doesn't exist in the database */
		else {
			// INSERT (Member doesn't exist in database)
			$req = $bdd->prepare("INSERT INTO Membre (nom, prenom, password, email, isAdmin) VALUES ('$this->_lastName', '$this->_firstName', '$this->_password', '$this->_email', '$this->_isAdmin')");
			$req->execute();
			$req->closeCursor();
		}
	}

	/* Is the current member admin ? */
	public static function isAdmin() {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
                $idMember = $_SESSION['idMember'];
		$query = $bdd->prepare("SELECT isAdmin FROM Membre WHERE idMembre = '$idMember';");
		$query->execute();
		$data = $query->fetch();
		if ($data['isAdmin'])
			return true;
		else	return false;
		$query->closeCursor();
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
