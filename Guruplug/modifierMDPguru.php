<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>DataPhoneAuto</title>
	</head>
    <BODY>
        <?php
            require_once('./verif.php'); // si la vérification est ok : on aura accès au reste de la page.

            echo '
            <p>Formulaire de connexion</p>
            <form action="./verifMDPguru.php" method="post">
            <TABLE BORDER=0>
            <TR>
	            <TD>Mot de passe courant : </TD>
	            <TD>
	            <input type="password" name="oldmdp" value="">
	            </TD>
            </TR>

            <TR>
	            <TD>Nouveau mot de passe : </TD>
	            <TD>
	            <input type="password" name="mdp" value="" >
	            </TD>
            </TR>
            <TR>
	            <TD>Confirmer mot de passe :</TD>
	            <TD>
	            <input type="password" name="mdp2" value="">
	            </TD>
            </TR>
            <TR>
	            <TD COLSPAN=2>
	            <INPUT type="submit" value="envoyer">
	            </TD>
            </TR>
            </TABLE>
            <TR>
            <a href="./index.php">Retour au menu principal</a>
            </TR>
            </form> ';
        ?>
    </BODY>
</HTML>
