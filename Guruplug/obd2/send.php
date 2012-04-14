<?php
//1. envoi sur une chaine, plaquevoiture;idMembre;code1:value;code2:value;...:...
        
        //where are we posting to?
        
    $url = 'http://dataphoneauto.ece.fr/test.php';

    $postvars='';    
    $postvars= "data=1;1;code1";

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL,$url);
//    curl_setopt($ch,CURLOPT_POST,count($fields));
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);

    //execute post
    for($i=0;$i<10;$i++)
        $result = curl_exec($ch);

    //close connection
    curl_close($ch);


?>

