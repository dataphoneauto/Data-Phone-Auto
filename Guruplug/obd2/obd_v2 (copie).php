<?php
//
//      obd.php
//              
//      Application permettant de lire les états 
//      des capteurs d'une voiture à l'aide des PIDs de l'ELM327
//
//
//http://publib.boulder.ibm.com/infocenter/aix/v6r1/index.jsp?topic=%2Fcom.ibm.aix.cmds%2Fdoc%2Faixcmds5%2Fstty.htm
    require_once("/var/www/obd2/obdconf.php");              //fichier de configutation de l'application

###############################################################################################################
        //Ouverture des tous les fichiers
debut:
        $filetty  = open_tty(PORT_COM_USB,INIT_COM_USB);        //ouverture du port com
        $filecmd  = open_file_cmd(FILE_CMD);                    //ouverture du fichier de commandes
        $filepid1etat = open_file_erase(FILE_PID_MODE_1_2_ETAT);
        $filepid5etat = open_file_erase(FILE_PID_MODE_5_ETAT);
        $filepid6etat = open_file_erase(FILE_PID_MODE_6_ETAT);
        $filepid9etat = open_file_erase(FILE_PID_MODE_9_ETAT);
        $filelogetat  = open_file_erase(FILE_LOG_ETAT);
        
    ###############################################################################################################
        //configuration de l'application en fonction de la voiture
        //attente que l'utilisateur ce soit déconnecté du wifi et connecté en modem 3G
        //sleep(60);
        while (!feof($filecmd)) {                           //on parcourt toutes les lignes du fichier de commandes
            $cmd = read_file_cmd($filecmd);                 //lecture d'une commande est de son état
                                                            //cmd[0] = PID, cmd[1] = bool, cmd[2] = commentaire
            if($cmd[1] == '1')                              //si le boolean est à 1 on execute la commande
            {
                write_data($filetty, "$cmd[0]\r");          //Ecriture du PID sur port com
                $value = read_tty($filetty);                //lecture port com
                echo"value $value\n";
                if ($value == ""){                          //si le port COM a été débranché
                    closefiles();
                    goto debut;                             //retour au début du programme
                }
                    
                write_data($filelogetat, "$value\n");       //Ecriture de la données dans le log
                
   //$value= ">0100\nSEARCHING...\n41 00 01 00 00 01\n";
                $hex = hexavalue_tty($value);               //on recupère ce qui suit le PID envoyé

                if("NO DATA" != $hex )
                {
                    //Ecriture dans un fichier des PIDs existant dans la voiture en Mode 1
                    if($cmd[0] == "0100")
                    { 
                        if(!write_pid_bool($hex,$filepid1etat,$filetty,$filelogetat,"$cmd[0]"))
                            goto debut;

                    }
    //$value= ">0500\nSEARCHING...\n45 00 10 00 00 00\n";
                    $hex = hexavalue_tty($value);               //on recupère ce qui suit le PID envoyé
                    //Ecriture dans un fichier des PIDs existant dans la voiture en Mode 5
                    if($cmd[0] == "0500")
                    {    
                        if(!write_pid_bool($hex,$filepid5etat,$filetty,$filelogetat,"$cmd[0]"))
                            goto debut;
                    }
                    
                    if($cmd[0] == "0600")
                    {    
                        if(!write_pid_bool($hex,$filepid6etat,$filetty,$filelogetat,"$cmd[0]"))
                            goto debut;
                    }
                    
                    //Ecriture dans un fichier des PIDs existant dans la voiture en Mode 9
                    if($cmd[0] == "0900")
                    {    
                        if(!write_pid_bool($hex,$filepid9etat,$filetty,$filelogetat,"$cmd[0]"))
                            goto debut;
                    }
                }
            }
        }
    ###############################################################################################################
        //Reception des informations de tous le PIDs de la voiture
        //et envoi sur le serveur
        while(true)
        {
            if(!sendEnable_PIDs($filetty,$filepid1etat,$filelogetat))               //lecture information PIDs Mode 1
                goto debut;
            if(!sendEnable_PIDs($filetty,$filepid5etat,$filelogetat))               //lecture information PIDs Mode 5
                goto debut;
            if(!sendEnable_PIDs($filetty,$filepid6etat,$filelogetat))               //lecture information PIDs Mode 6
                goto debut;
            if(!sendEnable_PIDs($filetty,$filepid9etat,$filelogetat))               //lecture information PIDs Mode 9
                goto debut;
            
            sendToServer($filelogetat);                                             //envoi des données sur le serveur
            sleep(recuptimesynchro());                                              //attente en seconde
            //rewind($filelogetat);                                                   //Reset du fichier log
            fclose($filelogetat);
            $filelogetat = open_file_erase(FILE_LOG_ETAT);
        }
        
    ###############################################################################################################
        //fermeture des fichiers ouverts
        closefiles();
##################################################################################################
//Ouvrir fichier de commandes
    function open_file_cmd($file)
    {
        //ouverture du port com
        echo "Tentative ouverture du fichier de commande $file \n";
    
        if (!$fp = fopen($file, "r+"))                   //ouverture en lecture ecriture
        {
            echo "Erreur ouverture du fichier $file\n";
        }
        else
        {
            echo "Succes ouverture du fichier $file\n";
        }
        return $fp;
    }
##################################################################################################
//Ouvrir fichier erase au début
    function open_file_erase($file)
    {
        echo "Tentative ouverture $file \n";
    
        if (!$fp = fopen($file, "w+"))                      //ouverture du port COM en lecture ecriture
        {
            echo "Erreur ouverture $file\n";
            exit;
        }
        else
        {
            echo "Succes ouverture $file\n";
        }
        return $fp;
    }

##################################################################################################
//Lecture fichier de commandes
    function read_file_cmd($fp)
    {
        $filtre = array();
        $code_bool = array();
        
        $ligne = fgets($fp, 200);                           //lecture de la ligne
      
        //est-ce que la ligne n'est pas null
        if (($ligne == "") || ($ligne == "\n") || ($ligne[0] == "#")){
            $code_bool[0] = "";
            $code_bool[1] = 0 ;
            $code_bool[2] = "";
        }    
        else{

            $filtre = explode(';',$ligne);                  //garder la partie avant le ';'
            $filtre[0] = str_replace(' ','',$filtre[0]);    //enlever tous les espaces et tabulations
            $filtre[0] = str_replace("\t",'',$filtre[0]);
            $code_bool = explode("=",$filtre[0]);           //Enregistrer le code et l'état du booléan
            $code_bool[2] = $filtre[1];                     //copie du commentaire à la suite
        }
        return $code_bool;
    }



##################################################################################################
//Ecrire le fichier avec les PID utiles
    //function write_pid_bool($valuehex,$filepidread,$filepidwrite,$filetty,$filelog,$start)
    function write_pid_bool($valuehex,$filepidwrite,$filetty,$filelog,$start)
    {
        $compteur = 0;
        $oldbin   = "";
        $oldcode  = 0;
        
        do
        {
            $hex_= str_replace(' ','',$valuehex);               //enlève les espaces
            $hex = substr($hex_,4);                             //enlève le mode et le pid pour ne garder que la valeur
            $bin = hexbin($hex);                                //convertie en bin la valeur
            $mode = "0".substr($hex_,1,1);                      //Mode du code envoyé
            $pid  = substr($hex_,2,2);                          //PID envoyé
            $pid  = hexdec($pid);

            for($j = 0; $j < strlen($bin)-1; $j++)              //parcourir tous les bits
            {
                if($bin[$j] == '1')
                {
                   // $code = 0;
                    $code = $j + 1;
                    $code = $code + $pid;
                    
                    if($code < 16)
                    {
                        $code = dechex($code);
                        $code = "0".$code;
                    }
                    else
                    {
                        $code = dechex($code);
                    }
                    write_data($filepidwrite, "$mode$code = $bin[$j] ; detailles\n");
                }
            }

            $oldbin  = $bin;
            $oldcode = $code;

            if($bin[strlen($bin)-1] == 1)                       //Si le dernier bit est à 1 il faut tester les n
            {                                                   //capteurs suivant
                $code = $pid + strlen($bin);
                $code = dechex($code);
                $size = strlen($oldbin)-1;

                write_data($filetty, "$mode$code\r");           //Ecriture du PID sur port com
                $valuehex = read_tty($filetty);                 //lecture port com
                echo"value $valuehex\n";
                if (!$valuehex){                                //si le port COM a été débranché
                    closefiles();
                    return 0;                                   //retour au début du programme
                }
                write_data($filelog, "$valuehex\r");            //Ecriture du PID sur port com
                
    /*if($compteur >= 1)
        $valuehex = ">0140\nSEARCHING...\n41 40 00 00 00 10\n";
    else
        $valuehex = ">0120\nSEARCHING...\n41 20 11 00 00 01\n";
                echo "$valuehex\n";*/
                
                $valuehex = hexavalue_tty($valuehex);               //on recupère ce qui suit le PID envoyé
            }
            $compteur++;
            echo "compteur $compteur\n";
            
        }while($oldbin[strlen($oldbin)-1] == 1);
        return 1;
    }                   
##################################################################################################
//Ouvrir port COM
    function open_tty($tty,$initialisation)
    {
        echo "Tentative ouverture du Port COM $tty \n";
        exec($initialisation);

        while(!$fd = fopen($tty, "r+"))                       //ouverture du port COM en lecture ecriture
        {
            echo "Erreur ouverture du port COM ... nouvelle tentative dans 10s\n";
            sleep(2);
        }
        
        exec(INIT_COM_USB2);

        return $fd;
    }
##################################################################################################
//Lire port COM
    function read_tty($fp)
    {
        $Timeout = 0;
        $Compteur = 0;
        $value  = "";
        $read = "";
        do{
            $read = fgets($fp);                             //lecture de la ligne
            if($read != "\n")
            {
                $value .= $read;
                $Compteur++;                                //Imcrement de Compteur si la chaine est un saut à la ligne
            }
            if($read == false)
            {
                $Timeout++;
                if ($Timeout > 20)
                    return 0;      
            }
            
        }while(  ($Compteur < 2) ||  ($read != "\n") );      //tant que moins de deux saut détecter et que chaine different de saut à la ligne

        return $value;
    }
##################################################################################################
//filtre uniquement une ligne des valeurs Hexa du port COM
    function hexavalue_tty($value)
    {
        $i = 0;
        $hexa = "";
        
        while($value[$i] != "\n")
            $i++;
        $i++;
        while($value[$i] != "\n")
        {
            $hexa .= $value[$i];
            $i++;
            if($hexa == "SEARCHING...")             //si l'ELM327 met du temps à obtenir l'information 
            {                                       //il emet "SEARCHING ..."
                $hexa="";
                $i++;
                while($value[$i] != "\n")
                {
                    $hexa .= $value[$i];
                    $i++;   
                }
            }
        }
        return $hexa;
    }
##################################################################################################    
//Ecrire sur port COM ou fichier
    function write_data($fp,$value)
    {
        fputs($fp, $value);
    }
##################################################################################################  
//Hexa to bin (formaté sur 4 bits par chiffre hexa)
    function hexbin($value)
    {
        $i = 0;
        $countZero = 0;                                     //compte les zeros au début de la chaine
        $nbZeroBin = "";                                    //contient les zeros à ajoutée à la fin
        $hexaValue = str_replace(' ','',$value);            //Supprime les espaces
        
        while($hexaValue[$i] == '0')                        //recherche des zeros au début de la chaine
        {
            $countZero++;                                   //comptage des zeros
            $i++;
        }
        
        for($i = 0; $i < $countZero*4 ; $i++)               //Ajout des zeros dans une chaine
        {
            $nbZeroBin.="0";
        }
        
        $decValue = hexdec($hexaValue);                     //conversion hexa to decimal

        if($decValue != 0)                                  //si cette valeur est differente de "0"
        {
            $binValue = decbin($decValue);                  //conversion decimal to bin

            switch(strlen($binValue) % 4) {                 // Modulo 4 pour reformater l'affichage sur 4 bits par valeur
                case 1: $finalBinValue = "000" . $binValue; // On rajoute 3 zeros
                    break;
                case 2: $finalBinValue = "00" . $binValue;  // On rajoute 2 zeros
                    break;
                case 3: $finalBinValue = "0" . $binValue;   // On rajoute 1 zero
                    break;
                default: $finalBinValue = $binValue;
            }
        
            $finalBinValue = $nbZeroBin . $finalBinValue;   //concatenation des zeros du début au début et de la chaine final
        }else{                                              //si cette valeur est égale à "0"
            $finalBinValue = $nbZeroBin;                    //la valeur final est égale à la chaine contenant les zeros 
        }
        return $finalBinValue;                          //retour de la valeur convertie hexa to bin
    }
##################################################################################################  
//Envoi des PIDs valide à la voiture
    function sendEnable_PIDs($filetty,$filePID,$filelog)
    {
        echo"###########################\n###########################\n###########################\n";
        rewind($filePID);                                   //retour au début du fichier
        while (!feof($filePID)) {                           //on parcourt toutes les lignes du fichier de commandes
            $cmd = read_file_cmd($filePID);                 //lecture d'une commande est de son état
                                                            //cmd[0] = PID, cmd[1] = bool, cmd[2] = commentaire
            if($cmd[1] == '1')                              //si le boolean est à 1 on execute la commande
            {
                write_data($filetty, "$cmd[0]\r");          //Ecriture du PID sur port com
                $value = read_tty($filetty);                //lecture port com
                if (!$value){                                //si le port COM a été débranché
                    closefiles();
                    return 0;                                   //retour au début du programme
                }
                $test  = substr($value,5);                  //retirer le code envoyé
                $test  = hexavalue_tty($value);             //recupérer la valeur hexa
                echo "$value dec :$test*\n\n";
                write_data($filelog, "$value\n");           //Ecriture des data dans le log
            }
        }
        return 1;
    }
    
    function sendEnable_PIDs_MOD2($filetty,$filePID)        //fonction propre à l'envoi des PIDs MODE 2
    {
        echo"###########################\n###########################\n###########################\n";
        rewind($filePID);                                   //retour au début du fichier
        while (!feof($filePID)) {                           //on parcourt toutes les lignes du fichier de commandes
            $cmd = read_file_cmd($filePID);                 //lecture d'une commande est de son état
                                                            //cmd[0] = PID, cmd[1] = bool, cmd[2] = commentaire
            if($cmd[1] == '1')                              //si le boolean est à 1 on execute la commande
            {
                $cmd[0] = substr($cmd[0],2);                //suppression du mode
                $cmd[0] = "02".$cmd[0];                     //mode remplacé par mode 2
                write_data($filetty, "$cmd[0]\r");          //Ecriture du PID sur port com
                $value = read_tty($filetty);                //lecture port com
                echo "$value\n";
                if (!$value){                               //si le port COM a été débranché
                    closefiles();
                    return 0;                               //retour au début du programme
                }
                write_data($filelog, "$value\r");           //Ecriture des data dans le log
            }
        }
        return 1;        
    }
##################################################################################################  
//Envoi des PIDs valide à la voiture
function sendToServer($filelogetat)
{
    $postvars='';
    $result = "";    
    $pid = "";
    $data = array();
    
//$filelogetat = fopen("/var/www/obd2/PID_LOG_ETAT (copie).conf", "r");
    rewind($filelogetat);
    echo"*****************************\n*****************************\n*****************************\n";
    while(!feof($filelogetat))
    {
        $i = 0;
        $hexa  = "";
        $value = "";

        $value = fgets($filelogetat);
        
        if($value != "\n")
        {
            $size = strlen($value);
            if($value[0] == '>')
            {
                $pid = "";
                
                for($j=1 ; $j < strlen($value)-1;$j++)
                    $pid .= $value[$j];
                
                $data[$pid] = ""; 
                $result = "";
            }
            else
            {
                $value = str_replace("\n", "", $value);
                $result .= $value;
                $data[$pid] = $result;
            }
        }
    }
    $senddata = "";
    foreach($data as $key=>$result1)
    {
        $senddata .= "$key=>$result1;";
    }
    $senddata .= "END";

    echo"send data POST $senddata\n";
    echo "\ntaille data = " . strlen($senddata);
    
    $identifiant = recupidentifiant();                      //identifiant[0] = ID, identifiant[1] = voiture
//    $postvars= "data=11-EEE-75|4|$senddata";
    $postvars = "data=$identifiant[1]|$identifiant[0]|$senddata";
    echo "$postvars\n";

    //open connection
    $ch = curl_init();
    
    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL,URL);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);

    //execute post
    $result = curl_exec($ch);

    //close connection*/
    curl_close($ch);
}

function recupidentifiant()
{
    $id = array();
    $file = fopen(IDENTIFIANT,"r");
    $id[0] = fgets($file);
    $id[1] = fgets($file);
    fclose($file);

    return $id;
}

function recuptimesynchro()
{
    $file = fopen(TIME_SYNCHRO,"r");
    $time = fgets($file);
    fclose($file);
    $time *= 60;                            //conversion minutes en secondes
    
    return $time;
}

function closefiles()
{
    global $filetty;
    global $filepid1etat;
    global $filepid5etat;
    global $filepid6etat;
    global $filepid9etat;
    global $filelogetat;

    fclose($filetty);                        //fermeture du port com
    fclose($filepid1etat);                   //fermeture fichier PID 1 avec les états des capteurs
    fclose($filepid5etat);                   //fermeture fichier PID 5 avec les états des capteurs
    fclose($filepid6etat);                   //fermeture fichier PID 6 avec les états des capteurs
    fclose($filepid9etat);                   //fermeture fichier PID 9 avec les états des capteurs
    fclose($filelogetat);                    //fermeture fichier LOG
}
?>
