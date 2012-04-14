<?php
    
/* retourne en ASCII une chaine hexa*/    
    
    //$hexadecimal = "5a 5a 5a 31 4b 5a 42";
    //$hexadecimal = "00 0e e8 00";
    //echo "$hexadecimal\n";
    //$ascii = hex2ascii($hexadecimal);
    //echo "\n$ascii\n";
    //echo "b = " . intval($b) . '<br />';
    
function hex2ascii($hexadecimal){
//echo "hexa reçu : $hexadecimal<br />";
    $ascii='';
    $caract ='';
    $hexadecimal=str_replace(" ", "", $hexadecimal);
    $hexadecimal=strtoupper($hexadecimal);
    for($i=0; $i<strlen($hexadecimal); $i=$i+2) {
        $caract=$hexadecimal[$i].$hexadecimal[$i+1];
        if(($caract>="30" && $caract<="39") || ($caract>="41" && $caract<="5A") || ($caract>="61" && $caract<="7A"))
            $ascii.=chr(hexdec(substr($hexadecimal, $i, 2)));
    }
    return($ascii);
}

?>
