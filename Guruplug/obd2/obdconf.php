<?php
//
//  obdconf.php   Configuration file.
//

//
//  fichier avec les commandes à executer
//
define("URL","http://dataphoneauto.ece.fr/test.php");
define("FILE_CMD","/var/www/obd2/commandes.conf");

define("FILE_PID_MODE_1_2","/var/www/obd2/libraryPID/PID_MODE_1.conf");
define("FILE_PID_MODE_1_2_ETAT","/var/www/obd2/enablePID/PID_MODE_1_ETAT.conf");

define("FILE_PID_MODE_5","/var/www/obd2/libraryPID/PID_MODE_5.conf");
define("FILE_PID_MODE_5_ETAT","/var/www/obd2/enablePID/PID_MODE_5_ETAT.conf");

define("FILE_PID_MODE_6","/var/www/obd2/libraryPID/PID_MODE_6.conf");
define("FILE_PID_MODE_6_ETAT","/var/www/obd2/enablePID/PID_MODE_6_ETAT.conf");

define("FILE_PID_MODE_9","/var/www/obd2/libraryPID/PID_MODE_9.conf");
define("FILE_PID_MODE_9_ETAT","/var/www/obd2/enablePID/PID_MODE_9_ETAT.conf");

define("FILE_LOG_ETAT","/var/www/obd2/PID_LOG_ETAT.conf");
define("IDENTIFIANT","/var/www/identifiant/file.txt");
define("TIME_SYNCHRO","/var/www/config/timesynchro.txt");

//
//  Com port used to communicate.
//
define("TTY","ttyUSB0");
define("PORT_COM_USB","/dev/ttyUSB0");
define("INIT_COM_USB","stty -F /dev/ttyUSB0 38400 cs8 cread -onlret icrnl -isig -icanon -iexten -echo -echoe -echok -echoctl -echoke -opost -onlcr -brkint -imaxbel");
define("INIT_COM_USB2","setserial /dev/ttyUSB0 spd_cust");

    //38400 baud
    //cs8 nombre de bit, 8bits
    //[-]cread      autorise la réception sur l'entrée
    //[-]inlcr      transforme le saut de ligne en retour de chariot
    //[-]icrnl      transforme le retour de chariot en saut de ligne
    //[-]ocrnl      transforme un retour de chariot par un saut de ligne
    //[-]onlcr      traduit le saut de ligne en retour de chariot-saut de ligne
    //* [-]onlret     le saut de ligne génère un retour de chariot
    //  [-]brkint     break provoque un signal d'interruption

    /*
        Un « - » optionnel avant SETTINGS indique une négation. Un * indique des
        paramètres non-POSIX. Le système sous-jacent détermine les paramètres
        applicables.
    */
    // >man stty pour plus d'info

?>
