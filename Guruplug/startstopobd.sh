#!/bin/bash

#Ce script permet de lancer un processus que s'il n'est pas encore en execution
#option '0' démarrer l'application normalement
#option '1' démarrer l'application avec envoi de données virtuelles

#Test si le processus est déja en cours d'éxecution
processus=`ps -ef | grep "/var/www/obd2/obd_v2.php" |grep "php5"| awk '{print $2}'`
if test -z $processus
then
    # virifie que les options sont non nul
    if [ -z $1 ]
    then
        echo "ajouter option '0' pour un lancement Normal"
        echo "ajouter option '1' pour un lancement Virtuel"
    else
        # Démarrage normal
        if [ $1 -eq "0" ]
        then
            echo normal
            nohup php5 /var/www/obd2/obd_v2.php &
            # Démarrage virtuel
            else if [ $1 -eq "1" ]
            then
                echo virtuel
                nohup php5 /var/www/obd2/obd_v2.php 1&
            fi
        fi
    fi
fi
