<?php

require_once dirname(__FILE__) . '/../lightmvc/ActionController.php';
require_once dirname(__FILE__) . '/../model/Members.php';
require_once dirname(__FILE__) . '/../model/Members.php';

/*
 * Index controller : actions for the index' view(s)
 */

class MobileController extends ActionController {
/**/
	public function indexAction() {
		//$this->pageInfos = Members::getInfosPage($_SERVER['REQUEST_URI']);
		if (isset($_POST['email']) && isset($_POST['password'])) {
			$_POST['email'] = htmlspecialchars($_POST['email']);
			$_POST['password'] = htmlspecialchars($_POST['password']);
			/* Verify the member exists */

			if (Members::signIn($_POST['email'], $_POST['password'])) {
				$this->member = new Member($_POST['email']);
				/* Set the session variable "connected" to true*/
				$_SESSION['connected'] = true;
				$_SESSION['email'] = $this->member->_email;
				$_SESSION['lastName'] = $this->member->_lastName;
				$_SESSION['firstName'] = $this->member->_firstName;
				$_SESSION['isAdmin'] = $this->member->_isAdmin;
				if (Member::isAdmin()==true)
				{
					$_SESSION['isAdmin'] = true;
				}
				if ($this->member->_idMember != NULL)
					$_SESSION['idMember'] = $this->member->_idMember;
				else	$_SESSION['idMember'] = false;
				$this->redirect('/../mobile/index');
			}
			else	$this->fail = true;
		}
		elseif (isset($_SESSION['connected']))
			$this->redirect('/../mobile/index');
	}
	 
	/**
	 * Simple index page which links to the main available actions
	 * Can also sign in the member if he/she exists in the database and redirects him/her to his/her profile's page
	 */
	 
	 /**
	public function indexAction() {

		if (isset($_POST['email']) && isset($_POST['password'])) {
			/* Verify the member exists */ /**
			if (Members::signIn($_POST['email'], $_POST['password'])) {
				$this->member = new Member($_POST['email']);
				/* Set the session variable "connected" to true *//**
				$_SESSION['connected'] = true;
				$_SESSION['isAdmin'] = $this->member->isAdmin();
				$_SESSION['email'] = $this->member->_pseudo;
				if ($this->member->_idMember != NULL)
					$_SESSION['idMember'] = $this->member->_idMember;
				else	$_SESSION['idMember'] = false;
				$this->redirect('/../mobile/index');
			}
			else	$this->fail = true;
		}
	}*/
	
	/**
	 * Simple index page which links to the main available actions
	 * Can also sign in the member if he/she exists in the database and redirects him/her to his/her profile's page
	 */
	public function introductionAction() {
		$this->pageInfos = Members::getInfosPage($_SERVER['REQUEST_URI']);
	}
	
	/**
	 * Simple index page which links to the main available actions
	 * Can also sign in the member if he/she exists in the database and redirects him/her to his/her profile's page
	 */
	public function teamAction() {
		$this->pageInfos = Members::getInfosPage($_SERVER['REQUEST_URI']);
	}
	
	/**
	 * Simple index page which links to the main available actions
	 * Can also sign in the member if he/she exists in the database and redirects him/her to his/her profile's page
	 */
	public function contactAction() {
		$this->pageInfos = Members::getInfosPage($_SERVER['REQUEST_URI']);
	}
}
