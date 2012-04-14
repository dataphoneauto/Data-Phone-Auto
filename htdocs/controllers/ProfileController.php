<?php

require_once dirname(__FILE__) . '/../lightmvc/ActionController.php';
require_once dirname(__FILE__) . '/../model/Member.php';
require_once dirname(__FILE__) . '/../model/Members.php';
require_once dirname(__FILE__) . '/../model/Car.php';
require_once dirname(__FILE__) . '/../model/Cars.php';
require_once dirname(__FILE__) . '/../php2js.php';
require_once dirname(__FILE__) . '/../hexatoascii.php';

/*
 * Member profile controller : actions for the member's profile view
 */
class ProfileController extends ActionController {
	/**
	 * Simple index page which links to the main available actions
	 */
	public function indexAction() {
		$this->pageInfos = Member::getInfosPage($_SERVER['REQUEST_URI']);
		if (!isset($_SESSION['connected']))
			header ("Refresh: 5;URL=/../index");
		else
			$this->title = $this->pageInfos['titre'] . ' - ' . $_SESSION['lastName'] . ' ' . $_SESSION['firstName'];
}

	/**
	 *  Edit the profile of the logged member
	 */
	public function editAction() {
		$this->pageInfos = Member::getInfosPage($_SERVER['REQUEST_URI']);
		if (!isset($_SESSION['connected']))
			header ("Refresh: 5;URL=/../index");
		else	$this->title = $this->pageInfos['titre'] . ' - ' . $_SESSION['lastName'] . ' ' . $_SESSION['firstName'];
		if (isset($_POST['email']) && isset($_POST['lastName']) && isset($_POST['firstName']) && isset($_POST['password'])) {
			if (($_POST['email'] != '') && ($_POST['lastName'] != '') && ($_POST['firstName'] != '') && ($_POST['password'] != '')) {
				if ($_POST['email'] != $_SESSION['email']) {
					//echo $_SESSION['email'] . '<br />';
					$this->member = new Member($_POST['email']);
					if ($this->member->_idMember == NULL) {
						if (Members::checkEmail($_POST['email'])) {
							$this->checkEmail = true;
							//echo 'SAUVEGARDE 1 - ' . $_POST['email'];
							$this->member = new Member($_SESSION['email']);
							$_SESSION['email'] = htmlspecialchars($_POST['email']);
							$this->member->_email = htmlspecialchars($_POST['email']);
							$this->member->_lastName = htmlspecialchars($_POST['lastName']);
							$_SESSION['lastName'] = htmlspecialchars($_POST['lastName']);
							$this->member->_firstName = htmlspecialchars($_POST['firstName']);
							$_SESSION['firstName'] = htmlspecialchars($_POST['firstName']);
							$this->member->_password = htmlspecialchars($_POST['password']);
							$this->member->save();
						}
						else	{
							$this->member = new Member($_SESSION['email']);
							//echo "EMAIL NON VALIDE";
						}
					}
					else	{
						$this->member = new Member($_SESSION['email']);
						//echo "EMAIL DEJA UTILISEE";
					}
				}
				else {
					//echo "SAUVEGARDE 2";
					$this->member = new Member($_SESSION['email']);
					$this->member->_email = htmlspecialchars($_POST['email']);
					$this->member->_lastName = htmlspecialchars($_POST['lastName']);
					$_SESSION['lastName'] = htmlspecialchars($_POST['lastName']);
					$this->member->_firstName = htmlspecialchars($_POST['firstName']);
					$_SESSION['firstName'] = htmlspecialchars($_POST['firstName']);
					$this->member->_password = htmlspecialchars($_POST['password']);
					$this->member->save();
				}
			}
			else	{
				$this->member = new Member($_SESSION['email']);
				//echo "CHAMPS NON RENSEIGNE(S)";
			}
		}
		else {
			$this->member = new Member($_SESSION['email']);
		}
	}
	
	/**
	 *  Edit the profile of the logged member
	 */
	public function carsAction() {
		$this->address = split("\?page=", $_SERVER['REQUEST_URI']);
		if ($this->address == NULL)
			$this->pageInfos = Member::getInfosPage($_SERVER['REQUEST_URI']);
		else
			$this->pageInfos = Member::getInfosPage($this->address[0]);
		if (!isset($_SESSION['connected']))
			header ("Refresh: 5;URL=/../index");
		else
			$this->title = $this->pageInfos['titre'] . ' - ' . $_SESSION['lastName'] . ' ' . $_SESSION['firstName'];
		$this->cars = Members::getAllCars($_SESSION['idMember']);
		if (isset($_GET['page'])) {
			if (($_GET['page'] < 1) || ($_GET['page'] > count($this->cars)))
				$this->redirect("/../profile/cars");
		        $this->carAllData = Members::getCarData($this->cars[$_GET['page']-1]['idVoiture']);
		        /* Get the number of loops to do (Max is 30) */
		        if (count($this->carAllData) > 30)
		        	$this->loop = 30;
		        else	$this->loop = count($this->carAllData);
		        $tmpAllData = array();
		        $allGraphDate = array();
		        for ($i = 0; $i < $this->loop; $i++) {
		        	$tmpCarData = $this->carAllData[$i];
		        	$allGraphDate[$i] = $this->carAllData[$i]['date'];
		        	//echo "DATE = " . $allGraphDate[$i] . '<br />';
		        	/* Split the data into different lines */
		        	$tmpCarDatum = split(";", $tmpCarData['donnees']);
		        	/* Filtering data : "data", "?", "elm327", "unable" and some PID are removed */
		        	$tmpToSave = $tmpCarDatum;
		        	for ($j = 0; $j < count($tmpCarDatum); $j++) {
		        		if (preg_match("/data|\?|unable|elm327|end|0100|0120|0140|0160|0180|01a0|01C0|0600|0620|0640|0660|0680|06a0|06C0|0900|09a0|0980|09a0|09C0/i", $tmpToSave[$j]))
		        			unset($tmpToSave[$j]);
		        	}
		        	$tmpAllData[] = $tmpToSave;
		        }
		        // Table $tmpAllData contains all filtered data
		        $tmpDataPids = array();
		        $tmpDataDescriptions = array();
		        $sortedData = array();
		        for ($i = 0; $i < count($tmpAllData); $i++) {
		        	for ($j = 0; $j < count($tmpAllData[$i]); $j++) {
					$tmpPid = split("=>", $tmpAllData[$i][$j]);
					if (count($tmpPid) > 1) {
						array_push($tmpDataPids, $tmpPid[0]); // PIDs
						$tmpDataValues = explode(':', $tmpPid[1]); // Data
						if (count($tmpDataValues) > 1) {
							for ($k = 1; $k < count($tmpDataValues); $k++) {
								if ($k == (count($tmpDataValues)-1)) {
									//print_r($tmpDataValues[$k]); echo '<br />';
									$sortedData[$tmpPid[0]][$i] = $sortedData[$tmpPid[0]][$i] . ' ' . $tmpDataValues[$k];
								}
								else	{
									$tmpDataValues[$k] = substr($tmpDataValues[$k], 0, strlen($tmpDataValues[$k])-2);
									//echo "[$i][$k] la : $tmpDataValues[$k]"; echo '<br />';
									$sortedData[$tmpPid[0]][$i] = $sortedData[$tmpPid[0]][$i] . ' ' . $tmpDataValues[$k];
									//echo $tmpPid[0] . " : " . $sortedData[$tmpPid[0]][$i]; echo '<br />';
									//print_r($sortedData[$tmpPid[0]][$i]); echo "[$i][$k]<br />";
								}
							}
						}
						else	$sortedData[$tmpPid[0]][$i] = $tmpPid[1];
					}
		        	}
		        }
		        /* Table $sortedData is a 2-D table :
				- 1st dimension corresponds to the PID
				- 2nd dimension corresponds to the different values
		        */
		        /*foreach($sortedData as $key => $Data) {
		        	echo "$key => ";
		        	foreach($Data as $data)
		        		echo "$data<br />";
		        }*/
		        // Get the whole table PidObd2 and filter it into $filteredPidObd2
		        $AllPidObd2 = Cars::getAllPidObd2();
		        $filteredPidObd2 = array();
		        foreach ($AllPidObd2 as $PidObd2) {
		        	//print_r($PidObd2);
		        	if (array_key_exists($PidObd2['pid'], $sortedData))
		        		$filteredPidObd2[$PidObd2['pid']] = $PidObd2;
		        }
		        /*foreach ($filteredPidObd2 as $pid) {
		        	echo $pid['formule'] . '<br />';
		        }*/
		        /*foreach($sortedData as $key => $Data) {
		        	echo $key . " : " . $filteredPidObd2[$key]['description'] . '<br />';
		        	foreach($Data as $data)
		        		echo "$data<br />";
		        }*/
		        $sortedAndRemovedData = array();
		        foreach($sortedData as $key => $Data) {
		        	//print_r($Data); echo '<br />';
		        	for ($i = 0; $i < count($Data); $i++) {
					$tmpData = substr($Data[$i], 5);
					$sortedAndRemovedData[$key][$i] = $tmpData;
		        	}
		        }
		        $allGraphInfos = array(); // Contains all informations / PID to be shown in graph
		        $allGraphData = array(); // Contains all data / PID to be shown in graph
		        $abcd = array();
		        //print_r($sortedData);
		        foreach($sortedAndRemovedData as $key => $Data) {
	        		if ($filteredPidObd2[$key]['unite'] != NULL) {
	        			if ($filteredPidObd2[$key]['formule'] != "complique") {
	        				//echo "CLE : $key, " . $filteredPidObd2[$key]['unite'] . "<br />";
						$allGraphInfos[$key] = $filteredPidObd2[$key];
						$allGraphData[$key] = $Data;
						for ($i = 0; $i < count($allGraphData[$key]); $i++) {
							/* SPLITER LA VALEUR HEXA AVEC COMME SEPARATEUR " "
							 * 0 <=> A
							 * 1 <=> B
							 * 2 <=> C
							 * 3 <=> D
							 * FAIRE LE CALCUL EN REMPLACANT CHAQUE LETTRE DE LA FORMULE PAR LE TABLEAU A
							 * TEL INDICE
							 */
							if ($allGraphData[$key][$i][0] == " ")
								$allGraphData[$key][$i] = substr($allGraphData[$key][$i],1); // ENLEVE LE 1ER CARACTERE
							$abcd = split(" ", $allGraphData[$key][$i]); // SPLIT
							$result = "";
							if (count($abcd) == 1) {
								$tmpResult = str_replace("A", hexdec($abcd[0]), $filteredPidObd2[$key]['formule']); // Conversion HEX -> DEC
								eval('$result = ' . $tmpResult . ';'); // $result
							}
							elseif (count($abcd) == 2) {
								$tmpResult = str_replace("A", hexdec($abcd[0]), $filteredPidObd2[$key]['formule']); // Conversion HEX -> DEC
								if ($abcd[1] != '')
									$tmpResult = str_replace("B", hexdec($abcd[1]), $tmpResult); // Conversion HEX -> DEC
								eval('$result = ' . $tmpResult . ';'); // $result
							}
							elseif (count($abcd) == 3) {
								$tmpResult = str_replace("A", hexdec($abcd[0]), $filteredPidObd2[$key]['formule']); // Conversion HEX -> DEC
								$tmpResult = str_replace("B", hexdec($abcd[1]), $tmpResult); // Conversion HEX -> DEC
								if ($abcd[2] != '')
									$tmpResult = str_replace("C", hexdec($abcd[2]), $tmpResult); // Conversion HEX -> DEC
								eval('$result = ' . $tmpResult . ';'); // $result
							}
							elseif (count($abcd) == 4 || count($abcd) == 5) {
								$tmpResult = str_replace("A", hexdec($abcd[0]), $filteredPidObd2[$key]['formule']); // Conversion HEX -> DEC
								$tmpResult = str_replace("B", hexdec($abcd[1]), $tmpResult); // Conversion HEX -> DEC
								$tmpResult = str_replace("C", hexdec($abcd[2]), $tmpResult); // Conversion HEX -> DEC
								if ($abcd[3] != '')
									$tmpResult = str_replace("D", hexdec($abcd[3]), $tmpResult); // Conversion HEX -> DEC
								eval('$result = ' . $tmpResult . ';'); // $result
							}
							$allGraphData[$key][$i] = $result; //$result à stocker
							//echo "RESULTAT[" . $key . "][" . $i . "] = " . $allGraphData[$key][$i] . "<br />";
						}
					}
		        	}
		        	/*for ($i = 0; $i < count($Data); $i++) {
		        		// Testing if the key is a graph or a raw data
					$sortedAndRemovedData[$key][$i] = hex2ascii($sortedAndRemovedData[$key][$i]);
					//echo $sortedAndRemovedData[$key][$i] . '<br />';
		        	}*/
		        }
		        //print_r($sortedAndRemovedData);
		        /* sortedAndRemovedData contains all sorted data (first 6 cars are removed)
		         * pidObd2 contains the necessary PID OBD2
		         */
		        /*$this->carData = $sortedAndRemovedData;
		        $this->pidObd2 = $filteredPidObd2;*/
		        $this->allGraphInfos = $allGraphInfos;
		        $this->allGraphData = $allGraphData;
		        $this->allGraphDate = $allGraphDate;
		}
		$this->tmpVin = hex2ascii($sortedAndRemovedData["0902"][5]);
	        $this->test = Members::getOne($_SESSION['idMember']);
	}

	/**
	 * Add a like  
	 */
	public function addcarAction() {
		$this->pageInfos = Member::getInfosPage($_SERVER['REQUEST_URI']);
		$this->allBrandts = Cars::getAllBrandts();
		$this->oldestYear = 1996;
		$this->newestYear = date('Y');
		if (!isset($_SESSION['connected']))
			header ("Refresh: 5;URL=/../index");
		else
			$this->title = $this->pageInfos['titre'] . ' - ' . $_SESSION['lastName'] . ' ' . $_SESSION['firstName'];
		if (isset($_POST['brandt']) && isset($_POST['model']) && isset($_POST['licensePlate']) && isset($_POST['year'])) {
			if (($_POST['model'] != '') && ($_POST['licensePlate'] != '')&& ($_POST['year'] != '')) {
				$this->car = new Car($_POST['licensePlate']);
				if ($this->car->_licensePlate == NULL) {
					$this->car->addCar($_POST['licensePlate']);	
				}
			}
		}
	}

	/**
	 * Delete a car
	 */
	public function deleteAction() {
		$this->pageInfos = Member::getInfosPage($_SERVER['REQUEST_URI']);
		if (isset($_SESSION['connected'])) {
			$this->title = $this->pageInfos['titre'] . ' - ' . $_SESSION['lastName'] . ' ' . $_SESSION['firstName'];
			$this->cars = Members::getAllCars($_SESSION['idMember']);
			if (isset($_POST['car'])) {
				Members::delete($_POST['car']);
				$this->cars = Members::getAllCars($_SESSION['idMember']);
				
			}
		}
		else	header ("Refresh: 5;URL=/../members/index");
	}
	
	
	public function listAction() {
	        $this->pageInfos = Member::getInfosPage($_SERVER['REQUEST_URI']);
	        if (isset($_SESSION['connected']) && ($_SESSION['isAdmin'] == true)) 
	                  $this->pageMember = Members::getAll();
			if(isset($_POST['idMember']))
		{
		        if ($_SESSION['idMember'] != $_POST['idMember']) 
		        {
			        $this->exists = Members::getOne($_POST['idMember']);
			        if ($this->exists != NULL)
			        {
			                Members::deleteMember($_POST['idMember']);
			                header ("Refresh: 2;URL=/../profile/list");
			        }
				}

	    }
	        elseif ($_SESSION['isAdmin'] != true)
			header ("Refresh: 2;URL=/../profile/edit");
			
	}

	public function graphAction() {
	        $this->test = Members::getOne($_SESSION['idMember']);
	        //$this->test = "58";
	}

	/**
	 * Update the wall of a member without reloading the whole page
	 */	
	public function updateAction() {
		$this->_includeTemplate = false;
		$this->updates = Update::getAll($_SESSION['lastMaj'], $_SESSION['lastIdMember']);
		//$this->newVideos = Update::getLikes($_SESSION['lastMaj'], $_SESSION['lastPseudo']);
		if ($this->updates != '') {
			$this->existUpdate = count($this->updates);
			//$this->existUpdate = $this->existUpdate + count($this->videos);
			if ($this->existUpdate > 0) {
				$this->updates = array_reverse($this->updates);
				//if ($this->updates[0]['date'] > $this->videos[0]['date'])
				//	$_SESSION['lastMaj'] = $this->updates[0]['date'];
				//else	$_SESSION['lastMaj'] = $this->videos[0]['date'];
			}
		}
	}

}
