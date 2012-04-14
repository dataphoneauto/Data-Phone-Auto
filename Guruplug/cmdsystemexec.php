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
        if (isset($_POST['powoff'])) {
            echo '<p style="color:orange">Extinction en cours ...</p>';
            $functionpoweroff = shell_exec('/sbin/shutdown -h now');
            require_once('./logout.php');
        }

        if (isset($_POST['reboot'])) { 
            echo '<p style="color:orange">Redemarrage en cours ...</p>';
            $functionpoweroff = shell_exec('/sbin/shutdown -r now');
            require_once('./logout.php');
        }  
            echo '<p><a href="./index.php">Page admin</a></p>';
    ?>
    </BODY>
</HTML>
