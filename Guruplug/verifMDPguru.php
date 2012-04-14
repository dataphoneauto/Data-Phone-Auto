<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>DataPhoneAuto</title>
	</head>
    <BODY>
        <?php
            require_once('./verif.php'); // si la vérification est ok : on aura accès au reste de la page.
            require_once("./config/config.php");

            $file = "./config/config.php";
            $newmdp = $_POST['mdp'];
            $newlogin = "admin";

            if($_POST && !empty($_POST['oldmdp']) && !empty($_POST['mdp']) && !empty($_POST['mdp2']))
            {
                $password_md5 = md5($_POST['oldmdp'].$salt);

                if(($_POST['mdp'] == $_POST['mdp2']) && ($password_md5 == $_admin_pass)){
                
                    //enregistrement dans un fichier text
                    chmod($file, 0766);
                    $fp = fopen ($file, "w+");
                    fputs ($fp,"<?php");
                    fputs ($fp,"\n");
                    fputs ($fp,stripslashes("\$salt = \'BwGk15l8WX\'; // \$salt permet d'avoir un mot de passe plus sécurisé"));
                    fputs ($fp,"\n");
                    fputs ($fp,stripslashes("\$_admin_pass = md5(\'$newmdp\'.\$salt); // on crypt pour pouvoir comparer - plus sécurisé"));
                    fputs ($fp,"\n");
                    fputs ($fp,stripslashes("\$_admin_login = \'$newlogin\'\;"));
                    fputs ($fp,"\n");
                    fputs ($fp,"?>");
                    fclose ($fp);
                    chmod($file, 0744);
                    
                    echo '<p><b style="color:green">Mot de passe utilisateur modifie</b></p>';
                    echo '<a href="./index.php">Retour au menu principal</a>';
                }
                else{
                    echo '<p><b style="color:red">Mots de passe differents</b></p>';
                    require_once("./modifierMDPguru.php");
                }
            }
            else{
                echo '<p><b style="color:red">Pas mot de passe vide</b></p>';
                require_once("./modifierMDPguru.php");
            }
        ?>
    </BODY>
</HTML>
