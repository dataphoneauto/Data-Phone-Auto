<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>DataPhoneAuto logout</title>
	</head>
    <BODY>
        <?php
            // On appelle la session
            session_start();

            // On écrase le tableau de session
            $_SESSION = array();

            // On détruit la session
            session_destroy();
            // On redirige le visiteur vers la page d'accueil
            header ('location: index.php');
        ?>
    </BODY>
</HTML>
