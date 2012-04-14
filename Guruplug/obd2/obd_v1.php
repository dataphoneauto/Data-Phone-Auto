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

        $filetty  = open_tty(PORT_COM_USB,INIT_COM_USB);  //ouverture du port com
        
        $filecmd  = open_file_cmd(FILE_CMD);               //ouverture du fichier de commandes
        
        $filepid1 = open_file_cmd(FILE_PID_MODE_1_2);        //ouverture du fichier des PID Mode 1
        $filepid1etat = open_file_erase(FILE_PID_MODE_1_2_ETAT);
        
        $filepid5 = open_file_cmd(FILE_PID_MODE_5);        //ouverture du fichier des PID Mode 5
        $filepid5etat = open_file_erase(FILE_PID_MODE_5_ETAT);

        $filepid6 = open_file_cmd(FILE_PID_MODE_6);        //ouverture du fichier des PID Mode 6
        $filepid6etat = open_file_erase(FILE_PID_MODE_6_ETAT);
        
        $filepid9 = open_file_cmd(FILE_PID_MODE_9);        //ouverture du fichier des PID Mode 9
        $filepid9etat = open_file_erase(FILE_PID_MODE_9_ETAT);
        
        $filelogetat = open_file_erase(FILE_LOG_ETAT);
    ###############################################################################################################
        //configuration de l'application en fonction de la voiture

write_data($filetty, '\r');          //Ecriture du PID sur port com
        while (!feof($filecmd)) {                           //on parcourt toutes les lignes du fichier de commandes
            $cmd = read_file_cmd($filecmd);                 //lecture d'une commande est de son état
                                                            //cmd[0] = PID, cmd[1] = bool, cmd[2] = commentaire
            if($cmd[1] == '1')                              //si le boolean est à 1 on execute la commande
            {
                write_data($filetty, "$cmd[0]\r");          //Ecriture du PID sur port com
                $value = read_tty($filetty);                //lecture port com
                write_data($filelogetat, "$value\n");       //
                echo "$value\n";
                
                //$value= ">0100\nSEARCHING...\n41 00 98 3B A0 13\n";
                $hex = hexavalue_tty($value);               //on recupère ce qui suit le PID envoyé
 
                if("NO DATA" != $hex )
                {
                    //Ecriture dans un fichier des PIDs existant dans la voiture en Mode 1
                    if($cmd[0] == "0100" || $cmd[0] == "0120" || $cmd[0] == "0140" || $cmd[0] == "0160"|| $cmd[0] == "0180")
                    {    
                        write_pid_bool($hex,$filepid1,$filepid1etat,"$cmd[0]");
                    }
                    
                    //Ecriture dans un fichier des PIDs existant dans la voiture en Mode 5
                    if($cmd[0] == "0500")
                    {    
                        write_pid_bool($hex,$filepid5,$filepid5etat,"$cmd[0]");
                    }
                    
                    //Ecriture dans un fichier des PIDs existant dans la voiture en Mode 6
                    if($cmd[0] == "0600" || $cmd[0] == "0620" || $cmd[0] == "0640" || $cmd[0] == "0660"|| $cmd[0] == "0680")
                    {    
                        write_pid_bool($hex,$filepid6,$filepid6etat,"$cmd[0]");
                    }
                    
                    //Ecriture dans un fichier des PIDs existant dans la voiture en Mode 9
                    if($cmd[0] == "0900")
                    {    
                        write_pid_bool($hex,$filepid9,$filepid9etat,"$cmd[0]");
                    }
                }
            }
        }
    ###############################################################################################################
        //Reception des informations de tous le PIDs de la voiture
        
        sendEnable_PIDs($filetty,$filepid1etat,$filelogetat);                    //lecture information PIDs Mode 1
        sendEnable_PIDs_MOD2($filetty,$filepid1etat,$filelogetat);               //lecture information PIDs Mode 2
        sendEnable_PIDs($filetty,$filepid5etat,$filelogetat);                    //lecture information PIDs Mode 5
        sendEnable_PIDs($filetty,$filepid6etat,$filelogetat);                    //lecture information PIDs Mode 6
        sendEnable_PIDs($filetty,$filepid9etat,$filelogetat);                    //lecture information PIDs Mode 9    
    
        
    ###############################################################################################################
        //fermeture des fichiers ouverts
        fclose($filetty);                        //fermeture du port com
        fclose($filepid1);                       //fermeture fichier PID 1
        fclose($filepid1etat);                   //fermeture fichier PID 1 avec les états des capteurs
        fclose($filepid5);                       //fermeture fichier PID 5
        fclose($filepid5etat);                   //fermeture fichier PID 5 avec les états des capteurs
        fclose($filepid6);                       //fermeture fichier PID 6
        fclose($filepid6etat);                   //fermeture fichier PID 6 avec les états des capteurs
        fclose($filepid9);                       //fermeture fichier PID 9
        fclose($filepid9etat);                   //fermeture fichier PID 9 avec les états des capteurs
        fclose($filelogetat);                    //fermeture fichier LOG
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
    function write_pid_bool($valuehex,$filepidread,$filepidwrite,$start)
    {
        $bin = hexbin($valuehex);
        //echo "$bin\n";
        
        rewind($filepidread);                               //Chercher depuis le début du fichier

        do
        {
            $ligne = read_file_cmd($filepidread);           //fait avancer le poiteur de fichier
        }
        while($ligne[0] != $start && !feof($filepidread) ); //tant qu'on n'a pas le bon code
                                                        

        //read_file_cmd($filepidread);           //lire le lignes qui suivent le code
        for($j = 0; $j < strlen($bin)-1; $j++)              //parcourir tous les bits
        {
            $ligne = read_file_cmd($filepidread);           //lire le lignes qui suivent le code
            if(!feof($filepidread)  && ($bin[$j]) == '1')   //ecrire la ligne que si on n'est pas à la fin du fichier 
            {                                               //ou ça valeur binaire est à '1'
                write_data($filepidwrite, "$ligne[0] = $bin[$j] ;$ligne[2]");
            }
        }
        read_file_cmd($filepidread);           //lire le lignes qui suivent le code
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
            //shell_exec($INIT_COM_USB2);
        }
        exec($initialisation);
        shell_exec(INIT_COM_USB2);
//        exec("setserial /dev/ttyUSB0 spd_cust");
        //$fd = fopen($tty, "r+");
        /*if (!$fd = fopen($tty, "r+"))                       //ouverture du port COM en lecture ecriture
        {
            echo "Erreur ouverture du port COM\n";
            exit;
            //echo "Erreur ouverture du port COM ... nouvelle tentative dans 10s\n";
            //sleep(10);
            //exec($initialisation);
        }
        else
        {
            echo "Succes ouverture du port COM\n";
        }*/
        return $fd;
    }
##################################################################################################
//Lire port COM
    function read_tty($fp)
    {
        $Timeout = 0;
        $value  = "";
        $read = "";
        do{
            $read = fgets($fp);                         //lecture de la ligne
            if($read != "\n")
            {
                $value .= $read;
            
                $Timeout++;                               //Imcrement de Timeout si la chaine et un saut à la ligne
            }
            
        }while(  ($Timeout < 2) ||  ($read != "\n") );      //tant que moins de deux saut détecter et que chaine different de saut à la ligne
        
        
        

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
                $test = substr($test,5);
                $test =  hexavalue_tty($value);
                echo "$value dec :$test*\n\n";
                write_data($filelog, "$value\n");          //Ecriture du PID sur port com
            }
        }
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
                write_data($filelog, "$value\r");          //Ecriture du PID sur port com
            }
        }
    }
?>
