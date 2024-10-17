<?php
include_once("../cvmultiply.php");

$n0 = "1234";
$n1 = "5678";
$r = new cvmultiply( $n0, $n1 );
echo $r->raw()."   <- calculated\n";
echo $r->format()." <- calculated & formatted\n";
echo "length of result: ".number_format( $r->num_digits() )."\n";

?>
