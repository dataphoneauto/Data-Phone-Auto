<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>DataPhoneAuto</title>
	</head>
    <BODY>

        <?php
        require_once('./verif.php'); // si la vérification est ok : on aura accès au reste de la page.
        
        $fp = fopen ("./config/configcron.txt", "r");
         
        while(!feof($fp)) {
           // On récupère une ligne
		   $Ligne = fgets($fp,255);
            
           //recherche de la ligne contenant la régle de synchro
		   if (preg_match("#ecesynchrodata#", $Ligne))
		   {
               preg_match_all('#[0-9]+#',$Ligne,$extract); // récupération du temps
               $nombre = $extract[0][0];
		   }   
        }
        fclose ($fp);
        ?>

        <p>Envoyer le diagnostic de la voiture au serveur<br>
        actuellement toutes les <?php echo "$nombre";?> minutes
        </p>

        <form action="./synchrodataecriture.php" method="post">
        <TABLE BORDER=0>
        <TR>
            <TD>Changer la période d'envoi : </TD>
        </TR>
        <TR>
            <TD>
                <select name="time">
                    <option>10 sec</option>
                    <option selected>20 sec</option>
                    <option>30 sec</option>
                    <option>45 sec</option>
                    <option>1 min</option>                                                            
                    <option>5 min</option>
                    <option>10 min</option>
                    <option>15 min</option>
                    <option>20 min</option>
                    <option>30 min</option>
                </select> 
                
            </TD>
        </TR>

        <TR>
            <TD>
                <input type="submit" name="sychro" value="Valider">
            </TD>
        </TR>
        </TABLE>
        <TR>
            <br>
            <a href="./index.php">Retour au menu principal</a>
        </TR>
        </form> 
    </BODY>
</HTML>
