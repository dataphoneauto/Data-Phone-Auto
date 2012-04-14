<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>DataPhoneAuto</title>
	</head>
    <BODY>
        <?php
        session_start(); // on initialise les sessions PHP

        // on inclu la page de config
        require_once("./config/config.php");

        if($_POST && !empty($_POST['login']) && !empty($_POST['mdp']))
        {
            // on crypt le mot de passe envoyer par le formulaire
            $password_md5 = md5($_POST['mdp'].$salt);

                if(($_admin_login == $_POST['login']) && ($password_md5 == $_admin_pass))
                {
                    $_SESSION['_login'] = $_admin_login;
                    $_SESSION['_pass'] = $password_md5;

                    echo '<p style="color:green">Connexion reussie! </p>';
                    echo '<p><a href="./index.php">Page admin</a></p>';

                }
                else
                {
                    echo '<p style="color:red">Mauvais login ou mot de passe</p>';
                    require_once("./form.html");
                    exit();
                }
        }
        else
        {
            echo '<p style="color:red">Mauvais login ou mot de passe</p>';
                    require_once("./form.html");
                    exit();
        }


        ?>
    </BODY>
</HTML>
