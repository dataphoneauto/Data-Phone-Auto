<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>DataPhoneAuto</title>
	</head>
    <BODY>
    <td rowspan="2" bgcolor="#FFFFFF">
        <img src="logo.png" width="250" height="68"/>
    </td>
        <?php
        require_once('./verif.php'); // si la vérification est ok : on aura accès au reste de la page.
        ?>
        <p>
            <b>Bienvenue</b><br />
            Vous etes connecte a l'outil de configuration du Guruplug</b><br /><br /> 
        </p>
        <p>
            <a href="./startapplicationform.php">Demarrer l'application DataPhoneAuto</a>
        </p>
        <p>
            <a href="./enregistrerUtilisateur.php">Vos identifiants DataPhoneAuto</a>
        </p>
        <p>
            <a href="./synchrodata.php">Periodicite d'envoi des donnees</a>
        </p>
        <p>
            <a href="./modifierMDPguru.php">Modifier le mot de passe de cet outil de configuration</a>
        </p>
        <p>
            <a href="./cmdsystem.php">Outils systeme</a>
        </p>

        <form action="./logout.php" method="post">
           	<input type="submit" name="logout" value="Deconnexion" > 
        </form>
    </BODY>
</HTML>
