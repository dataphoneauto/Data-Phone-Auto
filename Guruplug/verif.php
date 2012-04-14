<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>DataPhoneAuto</title>
	</head>
    <BODY>
        <?php
            session_start();
            // on inclu la page de config
            require_once("./config/config.php");

            if(!isset($_SESSION['_login']) || !isset($_SESSION['_pass']))
            {
                // si on ne détecte aucune sessions, c'est que cette personne n'est pas connecté
                // on affiche le formulaire de connexion
                echo '<p><b style="color:red">Espace securise</b><br />Connectez vous pour acceder a cette page</p>';
                require_once("./form.html");
                exit();
            }
            else
            {
                // les sessions existe ... reste à savoir si les informations sont correct ou non
                if(($_admin_login != $_SESSION['_login']) || ($_SESSION['_pass'] != $_admin_pass))
                {
                    echo '<p><b style="color:red">Vos identifiants ne semblent pas valides</b></p>';
                    require_once("form.html");
                    exit();
                }
            }
        ?>
    </BODY>
</HTML>
