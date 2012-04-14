
<?php
    $hexadecimal = "5a 5a 5a 31 4b 5a 42";
    $ascii = hex2ascii($hexadecimal);
    
    echo "\n$ascii\n";
    


function hex2ascii($hexadecimal){
    $ascii='';
    $test ='';
    $hexadecimal=str_replace(" ", "", $hexadecimal);
    $hexadecimal=strtoupper($hexadecimal);
    for($i=0; $i<strlen($hexadecimal); $i=$i+2) {
        $test=$hexadecimal[$i].$hexadecimal[$i+1];
        if(($test>="30" && $test<="39") || ($test>="41" && $test<="5A") || ($test>="61" && $test<="7A"))
            $ascii.=chr(hexdec(substr($hexadecimal, $i, 2)));
    }
    return($ascii);
}

?>
