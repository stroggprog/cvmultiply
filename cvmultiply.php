<?php
class cvmultiply {
	protected $result;
	protected $v0;
	protected $v1;
	protected $calculated = false;

	function __construct( $s0, $s1 ){
		while (strlen($s0) < strlen($s1) ) $s0 = "0".$s0; // normalise values to same length by front-padding with zeros
		while (strlen($s1) < strlen($s0) ) $s1 = "0".$s1;
		$this->res = "";
		$this->v0 = $s0;
		$this->v1 = $s1;
	}

	function raw(){
		if( !$this->calculated ){
			$this->calc();
		}
		return $this->result;
	}

	function num_digits(){
		return strlen( $this->result );
	}

	function calc(){
		$this->uphill();
		$this->downhill();
		$this->tidy();
		$this->calculated = true;
		return $this->result;
	}

	private function uphill(){
		$e = strlen( $this->v0 )+1;
		for( $i = 1; $i < $e; $i++ ){
			$s = substr( $this->v0, 0, $i );
			$t = substr( $this->v1, 0, $i );
			$r = $this->longcalc( $s, $t );
			$this->modulate( $r );
		}
		return 0;
	}

	private function downhill(){
		$e = strlen( $this->v0 );
		for( $i = 1; $i < $e; $i++ ){
			$s = substr( $this->v0, $i );
			$t = substr( $this->v1, $i );
			$r = $this->longcalc( $s, $t );
			$this->modulate( $r );
		}
	}

	private function longcalc( $s, $t ){
		$n = strlen( $s )-1;
		$e = $n+1;
		$int = 0;
		for( $i = 0; $i < $e; $i++ ){
			$a = substr( $s, $i, 1 );
			$b = substr( $t, $n, 1 );
			$x = $a*$b;
			$int += $x;
			$n--;
		}
		return "$int";
	}

	private function modulate( $r ){
		if( $this->result == "" ) $this->result = $r;
		else {
			$i = strlen( $r );
			if( $i > 1 ){
				$this->result .= substr( $r, -1 );

				$v = 2;
				$carry = 0;
				while( $v < $i+1 || $carry ){
					$a = (int) substr( $r, -$v, 1 );
					$b = (int) substr( $this->result, -$v, 1 );
					$c = $a+$b+$carry;
					$carry = 0;
					if( $c > 9 ){
						$carry = (int) "$c";
						$c = substr( "$c", -1 );
						$carry = ($carry - (int)$c)/10;
					}
					else {
						$c = "$c";
					}
					$this->result = substr_replace( $this->result, "$c", -$v, 1 );

					$v++;
					if( $v >= $i+1 && $carry ){
						$r = "0$r";
					}
				}
			}
			else {
				$this->result .= "$r";
			}
		}
	}
	private function tidy(){
		$e = "";
		$test = false;
		$i = 0;
		$l = strlen( $this->result );
		while( $i < $l ){
			$vect = substr( $this->result, $i, 1 );
			if( $test ) $e .= $vect;
			else if( $vect != "0" ){
				$e = $vect;
				$test = true;
			}
			$i++;
		}
		$this->result = $e;
	}

	function format(){
		$e = strlen( $this->result )+1;
		$t = 0;
		$r = "";
		for( $i = 1; $i < $e; $i++ ){
			$v = substr( $this->result, -$i, 1 );
			if( $t == 3 ){
				$r = ",$r";
				$t = 0;
			}
			$r = "$v$r";
			$t++;
		}
		return $r;
	}
}
?>