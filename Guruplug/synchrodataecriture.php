<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>DataPhoneAuto</title>
	</head>
    <BODY>
        <?php
            require_once('./verif.php'); // si la vérification est ok : on aura accès au reste de la page.

            // on teste la déclaration de nos variables
            if (isset($_POST['time'])) {
                $time = $_POST['time'];
                echo '<p style="color:green">Modification reussie! </p>';
                echo '<p>Les données seront synchronisées toutes les '.$time.' </p>';
                $fp = fopen ("./config/configcron.txt", "w+");
                fputs ($fp,"*/$time * * * * /sbin/sh /root/synchro/ecesynchrodata.sh");
                fputs ($fp,"\n");
                fputs ($fp,"*/1 * * * * /usr/bin/crontab /var/www/config/configcron.txt");
                fputs ($fp,"\n");
                fclose($fp);
                
                $fp2 = fopen ("./config/timesynchro.txt", "w+");
                fputs ($fp2,"$time");
                fclose($fp2);
            }

            echo '<p><a href="./index.php">Page admin</a></p>';
        ?>
    </BODY>
</HTML>
