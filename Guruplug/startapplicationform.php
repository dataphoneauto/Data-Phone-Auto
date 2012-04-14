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

        <p><b>Start/Stop application de diagnostique</b></p>
        <form action="./startapplicationform.php" method="post">
            <TABLE BORDER=0>
            <TR>
            (Le lancement prendra une minute, pour vous laisser le temps <br>  de déconnecter le téléphone du wifi et activer le modem USB)
            </TR>
            <TR>
	            <TD>Start : </TD>
	            <TD>
	            <input type="submit" name="start" value="Start" > 
	            </TD>
	            <TR>
	            <?php
	            // on teste la déclaration de nos variables
                if (isset($_POST['start'])) {
                    echo '<p style="color:orange">demarrage (attendre 1 minute) ...</p> 
                    <p style="color:orange">déconnecter le téléphone du wifi';
                    $functionstartApp = shell_exec("./startstopobd.sh 0 > /dev/null & echo \$!");
                }
	            ?>
	            
	            </TR>
            </TR>
            <TR>
	            <TD>Start Virtuel : </TD>
	            <TD>
	            <input type="submit" name="virtuel" value="Virtuel">
	            </TD>
	            <TR>
	            <?php
	            // on teste la déclaration de nos variables
                if (isset($_POST['virtuel'])) {
                    echo '<p style="color:orange">demarrage (attendre 1 minute) ...</p> 
                    <p style="color:orange">déconnecter le téléphone du wifi et activer la 3G';
                    $functionstartApp = shell_exec("./startstopobd.sh 1 > /dev/null & echo \$!");
                }
	            ?>
            </TR>
            <TR>
	            <TD>Stop : </TD>
	            <TD>
	            <input type="submit" name="stop" value="Stop">
	            </TD>
            </TR>
            <TR>
            <?php
                if (isset($_POST['stop'])) { 
                    echo '<p style="color:orange">Arret de l\'application ...</p>';
                    $functionstartApp = shell_exec('killall -9 php5');
                }
            ?>
            </TR>
            </TABLE>
            <TR>
            <a href="./index.php">Retour au menu principal</a>
            </TR>
   </BODY>
</HTML>
