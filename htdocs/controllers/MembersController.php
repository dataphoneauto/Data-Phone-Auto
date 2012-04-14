<?php

require_once dirname(__FILE__) . '/../lightmvc/ActionController.php';
require_once dirname(__FILE__) . '/../model/Member.php';
require_once dirname(__FILE__) . '/../model/Members.php';

/*
 * Members controller : actions for the members' view
 */
class MembersController extends ActionController {
	/**
	 * Simple index page which links to the main available actions
	 */
	public function indexAction() {
		$this->pageInfos = Members::getInfosPage($_SERVER['REQUEST_URI']);
	}

	/*
	 * Registrer the member in the database if he/she respects all the conditions
	 */
	public function signupAction() {
		$this->pageInfos = Members::getInfosPage($_SERVER['REQUEST_URI']);
		if ($_SESSION['connected'] == true)
			$this->redirect('/../profile/edit');
		if (isset($_POST['email'])) {
			$_POST['email'] = htmlspecialchars($_POST['email']);
			if (Members::checkEmail($_POST['email'])) {
				$this->emailExisting = true;
				/* All the fields from the form are filled */
				if (isset($_POST['lastName']) && isset($_POST['firstName']) && isset($_POST['email']) && isset($_POST['password'])) {
					if (($_POST['lastName'] != "") && ($_POST['firstName'] != "") && ($_POST['email'] != "") && ($_POST['password'] != "")) {
						$this->member = new Member($_POST['email']);
						/* Verify the member doesn't already exist in the database */
						if ($this->member->_idMember == NULL) {
							/* Save the member into the database */
							$this->member->save();
							$destinataire = htmlspecialchars($_POST['email']);
							$sujet = "Data Phone Auto" ;
							$entete = "From: dataphoneauto@free.fr";
				
							// Le lien d'activation est composé du login(log) et de la clé(cle)
							$message = 'Bienvenue '.$_POST['firstName'].' '.$_POST['lastName'].',

Vous voici inscrit sur http://dataphoneauto.ece.fr

votre mail pour vous identifier : '.$_POST['email'].'
votre mot de passe : '.$_POST['password'].'

c\'est cette adresse mail que vous devrez renseigner pour configurer le Guruplug

Merci de ne pas repondre a ce mail, il est genere automatiquement
';


							
							ini_set("SMTP", FAI);
							ini_set("sendmail_from", EMAIL);
				
							mail($destinataire, $sujet, $message, $entete) ; // Envoi du mail					
							header ("Refresh: 5;URL=/../members/index");
						}
					}
				}
			}
			else	$this->emailExisting = false;
		}
	}
	
	/*
	 * Sign in the member if he/she exists in the database and redirects him/her to his/her profile's page
	 * /!\ Moved to indexAction from IndexController (JavaScript)
	 */
	public function signinAction() {
		$this->pageInfos = Members::getInfosPage($_SERVER['REQUEST_URI']);
		if (isset($_POST['email']) && isset($_POST['password'])) {
			$_POST['email'] = htmlspecialchars($_POST['email']);
			$_POST['password'] = htmlspecialchars($_POST['password']);
			/* Verify the member exists */ 
			if (Members::signIn($_POST['email'], $_POST['password'])) {
				$this->member = new Member($_POST['email']);
				/* Set the session variable "connected" to true */
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
                                $this->redirect('/../members/signin');
				$this->redirect('/../profile/index');
			}
		}
		elseif (isset($_SESSION['connected']))
			$this->redirect('/../profile/index');
	}
	



	/**
	* Destroy the session variables to disconnect the user and redirects him/her to the index page
	*/
	public function signoutAction() {
		$this->pageInfos = Members::getInfosPage($_SERVER['REQUEST_URI']);
		if (isset($_SESSION['connected']) && ($_SESSION['connected'] == true)) {
			session_destroy();
			header ("Refresh: 5;URL=/../index");
		}
		else	$this->redirect('/../index');
	}
	
	public function lostpassAction() {
		$this->pageInfos = Members::getInfosPage($_SERVER['REQUEST_URI']);
		if (isset($_POST['email'])) {
			$_POST['email'] = htmlspecialchars($_POST['email']);
			$this->member = new Member($_POST['email']);
			if ($this->member->_idMember != NULL) {
				$destinataire = htmlspecialchars($_POST['email']);
				$sujet = "Data Phone Auto" ;
				$pass=$this->member->_password;
				$entete = "From: dataphoneauto@free.fr";
				$message = 'Bonjour,

Voici vos identifiants sur http://dataphoneauto.ece.fr

votre mail pour vous identifier : '.$_POST['email'].'
votre mot de passe : '.$pass.'

c\'est cette adresse mail que vous devrez renseigner pour configurer le Guruplug

Merci de ne pas repondre a ce mail, il est genere automatiquement
';


							
							ini_set("SMTP", FAI);
							ini_set("sendmail_from", EMAIL);
				
							mail($destinataire, $sujet, $message, $entete) ; // Envoi du mail	
			}
		}
				elseif (isset($_SESSION['connected']))
			$this->redirect('/../profile/index');
	}	
			
	
}
