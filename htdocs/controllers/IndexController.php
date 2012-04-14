<?php

require_once dirname(__FILE__) . '/../lightmvc/ActionController.php';
require_once dirname(__FILE__) . '/../model/Members.php';

/*
 * Index controller : actions for the index' view(s)
 */
class IndexController extends ActionController {
	/**
	 * Simple index page which links to the main available actions
	 * Can also sign in the member if he/she exists in the database and redirects him/her to his/her profile's page
	 */
	public function indexAction() {
		$this->pageInfos = Members::getInfosPage($_SERVER['REQUEST_URI']);
		if (isset($_POST['pseudo']) && isset($_POST['pass'])) {
			/* Verify the member exists */ 
			if (Members::signIn($_POST['pseudo'], $_POST['pass'])) {
				$this->member = new Member($_POST['pseudo']);
				/* Set the session variable "connected" to true */
				$_SESSION['connected'] = true;
				$_SESSION['isAdmin'] = $this->member->isAdmin();
				$_SESSION['pseudo'] = $this->member->_pseudo;
				if ($this->member->_idMember != NULL)
					$_SESSION['idMember'] = $this->member->_idMember;
				else	$_SESSION['idMember'] = false;
				$this->redirect('/../profile/edit');
			}
			else	$this->fail = true;
		}
	}
	
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
