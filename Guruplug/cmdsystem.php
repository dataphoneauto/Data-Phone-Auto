<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>DataPhoneAuto</title>
	</head>
    <BODY>
        <?php
            require_once('./verif.php'); // si la vérification est ok : on aura accès au reste de la page.
        ?>

        <p>Outils System</p>
        <form action="./cmdsystemexec.php" method="post">
            <TABLE BORDER=0>
            <TR>
	            <TD>Mise hors tension : </TD>
	            <TD>
	            <input type="submit" name="powoff" value="PowerOff" > 
	            </TD>
            </TR>

            <TR>
	            <TD>Redemarrer : </TD>
	            <TD>
	            <input type="submit" name="reboot" value="Reboot">
	            </TD>
            </TR>
            </TABLE>
            <TR>
            <a href="./index.php">Retour au menu principal</a>
            </TR>
        </form> 
    </BODY>
</HTML>
