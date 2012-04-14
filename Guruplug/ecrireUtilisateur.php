<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>DataPhoneAuto</title>
	</head>
    <BODY>
        <?php
            require_once('./verif.php'); // si la vérification est ok : on aura accès au reste de la page.
            require_once("./identifiant/identifiant.php");

            $newnom = $_POST['nom'];
            $newmun = $_POST['Nidentifiant'];
            // on teste la déclaration de nos variables
            if (isset($_POST['nom']) && isset($_POST['Nidentifiant'])) { 
               if(empty($_POST['nom'])||(empty($_POST['Nidentifiant'])))
               {
                echo "Veuillez remplir tous les champs <br />";
                require_once('./enregistrerUtilisateur.php');
               }
               else{
                   
                   echo '<p style="color:green">Modification reussi! </p>';
                   // on affiche nos résultats  
                   echo 'Vos identifiants DataPhoneAuto :<br>';
                   echo 'email : '.$_POST['nom'];
                   echo '<br>';
                   echo 'Immatriculation : '.$_POST['Nidentifiant'];
                   //enregistrement dans un fichier text
                   $fp = fopen ("./identifiant/file.txt", "w+");
                   fputs ($fp,$_POST['nom']);
                   fputs ($fp,"\n");
                   fputs ($fp,$_POST['Nidentifiant']);
                   fclose ($fp);
                   
                   $fpphp = fopen ("./identifiant/identifiant.php", "w+");
                   fputs ($fpphp,"<?php");
                   fputs ($fpphp,"\n");
                   fputs ($fpphp,stripslashes("\$_dataphoneauto_login = \'$newnom\'\;"));
                   fputs ($fpphp,"\n");
                   fputs ($fpphp,stripslashes("\$_dataphoneauto_identifiant = \'$newmun\'\;"));
                   fputs ($fpphp,"\n");
                   fputs ($fpphp,"?>");
                   fclose ($fpphp);
                }
            }  
            echo '<p><a href="./index.php">Page admin</a></p>';
        ?>
    </BODY>
</HTML>
