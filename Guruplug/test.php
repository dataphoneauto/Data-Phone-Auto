<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>DataPhoneAuto</title>
	</head>
    <BODY>
        <?php
            define('DSN', 'mysql:host=localhost;dbname=test;port=80');
            define('DB_USERNAME', 'root');
            define('DB_PASSWORD', 'DGeodjo');

            if (isset($_POST['nom']))
            {
                $temp = $_POST['nom'];
                $key = 'ASK'; 
                echo"$temp";
                $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        		$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);
                //$instert = "insert into test (test) values (AES_ENCRYPT('$temp','1'))";
                //$query = $bdd->prepare("insert into test (test) values (AES_ENCRYPT('$temp','az'))");
                $query = $bdd->  prepare("INSERT INTO test (test) VALUES (AES_ENCRYPT('$temp','$key'))");
                $query->execute();
            }
        ?>

        <p>
            <br />
            Changer les identifiants ?
            <form method="post" action="http://dataphoneauto.ece.fr/test.php">
            <TABLE BORDER=0>
            <TR>
	            <TD>Nom</TD>
	            <TD>
	            boucle
                variable=    fgets(fieleLog)
	            <INPUT type=text name=variable>
	            </TD>
	            
            </TR>
            <TR>
	            <TD COLSPAN=2>
	            cmd(Envoyer);
	            <INPUT type="submit" value="Envoyer">
	            </TD>
            </TR>
            </TABLE>
            </form>
            <TR>
            <a href="./index.php">Retour au menu principal</a>
            </TR>
        </p>
    </BODY>
</HTML>

coucou
