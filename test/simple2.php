<?php
include_once("../cvmultiply-dec.php");

$n0 = "1234";
$n1 = "5678";

for( $i = 0; $i < 5; $i++ ){
    $r = new cvmultiply( $n0, $n1/*, millSep: "'", decToken: ",", decSep: "-" */ );
    echo "$n0 x $n1\n";
    echo $r->raw()."   <- calculated\n";
    echo $r->format()." <- calculated & formatted\n";
    //echo "length of result: ".number_format( $r->num_digits() )."\n";
    echo "----------------\n";
    $n0 = $n0/10;
    $n1 = $n1/10;
}

?>
