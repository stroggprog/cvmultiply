<?php
/*
run using command: php -f universe.php
*/
include_once("../cvmultiply.php");
echo "How big is the universe (in cm)?\n";

// 60 * 60 * 24 = 86400 // 1 day
// 1 year (365.25 days) = 31,557,600 seconds

// exact speed of light is 299,792,458 metres per second - 29,979,245,800 cm

// 31,557,600 x 29,979,245,800 = 1 light year in cm

echo "===(cm in one light year)\n";

$n0 = "31557600";
$n1 = "29979245800";
$r = new cvmultiply( $n0, $n1 );
echo $r->raw()."   <- calculated\n";
echo $r->format()." <- calculated & formatted\n";
echo "length of result: ".number_format( $r->num_digits() )."\n";
echo "------------------\n";

// calculate 13.8billion x 2 (width of the visible universe as we see it)
$x0 = $r->raw();
$x1 = "27600000000"; // 13.8 billion times 2

echo "===(width of visible universe in cm)\n";
$r = new cvmultiply( $x0, $x1 );
echo $r->raw()."   <- calculated\n";
echo $r->format()." <- calculated & formatted\n";
echo "length of result: ".number_format( $r->num_digits() )."\n";
echo "(cm in 27.6 billion light years (13.8x2))\n";
echo "------------------\n";

// visible universe has grown to 93 billion light years over the last 13.8 billion years
echo "===(total size including growth we cannot see)\n";
echo "We can only see back 13.8bn years, but in that time\nthe universe has continued to grow. It is now 93bn ly in width\n";
$x1 = "93000000000"; // 93 billion light years
$r = new cvmultiply( $x0, $x1 );
echo $r->raw()."   <- calculated\n";
echo $r->format()." <- calculated & formatted\n";
echo "length of result: ".number_format( $r->num_digits() )."\n";
echo "(cm in 93 billion light years)\n";
?>
