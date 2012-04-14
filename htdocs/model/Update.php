<?php
require_once ('config.php');

class Update {
	public $_idUpdate;
	public $_content;
	public $_date;
	public $_service;
	public $_idMember;

	/*
	 * Save the update into the database, if the id property is null, create a new Update
	 * If not, just update it
	 */
	public function save($com, $idUpdate = NULL) {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		// INSERT, UPDATE
		/*if ($this->_idUpdate != NULL) {
			// UPDATE (Update already exists)
			$req = $bdd->prepare("UPDATE Update SET content = '$com', date = UNIX_TIMESTAMP(), service = '$this->_service' WHERE idUpdate = '$this->_idUpdate';");
			$req->execute();
			$req->closeCursor();
		}
		// INSERT (Update doesn't exist in database)
		else {*/
		$idMember = $_SESSION['idMember'];
		$req = $bdd->prepare("INSERT INTO `Update` (content, service, idMember) VALUES ('$com', 'MeetMe', '$idMember')");
		$req->execute();
		$req->closeCursor();
		//}
	}

	/*
	 * Get all the status updates from a member
	 */
	public static function getAll($date, $idMember) {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
		if ($date == NULL) {
			$query = $bdd->prepare("SELECT * FROM `Update` WHERE idMember = '$idMember';");
			$query->execute();
			$data = $query->fetchAll();
			if ($data != NULL)
				return $data;
			else	return false;
		}
		else {
			$query = $bdd->prepare("SELECT * FROM `Update` WHERE idMember = '$idMember' AND date > '$date';");
			$query->execute();
			$data = $query->fetchAll();
			return $data;
		}
	}
	
	/*
	 * Get all the liked videos from a member
	 */
	public static function getLikes($date, $pseudo) {
		$likes = "http://vimeo.com/api/v2/$pseudo/likes.xml";
		$xmlString = @file_get_contents($likes);
		if ($xmlString != false) {
			$xmlObject = simplexml_load_string($xmlString);
			if ($xmlObject->video == "")
				return false;
			$videos = array(array());
			if ($date == NULL) {
				foreach ($xmlObject->video as $video) {
					$videos[(string) $video->liked_on]['image'] = $video->thumbnail_small;
					$videos[(string) $video->liked_on]['url'] = $video->url;
					$videos[(string) $video->liked_on]['title'] = $video->title;
					$videos[(string) $video->liked_on]['date'] = $video->liked_on;
				}
			}
			else {
				foreach ($xmlObject->video as $video) {
					if ($date < $video->liked_on) {
						$videos[(string) $video->liked_on]['image'] = $video->thumbnail_small;
						$videos[(string) $video->liked_on]['url'] = $video->url;
						$videos[(string) $video->liked_on]['title'] = $video->title;
						$videos[(string) $video->liked_on]['date'] = $video->liked_on;
					}
				}
			}
			return $videos;
		}
		return false;
	}
	
}
