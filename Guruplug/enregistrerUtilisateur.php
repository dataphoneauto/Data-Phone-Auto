<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>DataPhoneAuto</title>
	</head>
    <BODY>
        <?php
            include('./verif.php'); // si la vérification est ok : on aura accès au reste de la page.
            require_once("./identifiant/identifiant.php");
            echo '<b>Identifiants DataPhoneAuto actuels : </b><br />';
            echo 'email : '.$_dataphoneauto_login.'<br>';
            echo 'Immatriculation : '.$_dataphoneauto_identifiant.'<br>';
        ?>

        <p>
            <br>
            Changer les identifiants ?
            <form method="post" action="ecrireUtilisateur.php">
            <TABLE BORDER=0>
            <TR>
	            <TD>email</TD>
	            <TD>
	            <INPUT type=text name="nom">
	            </TD>
            </TR>

            <TR>
	            <TD>Immatriculation</TD>
	            <TD>
	            <INPUT type=text name="Nidentifiant">
	            </TD>
            </TR>
            <TR>
	            <TD COLSPAN=2>
	            <INPUT type="submit" value="Envoyer">
	            </TD>
            </TR>
            </TABLE>
            </form>
            <TR>
            <a href="./index.php">Retour au menu principal</a>
            </TR>
        </p>
    </BODY>
</HTML>
