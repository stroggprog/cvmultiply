<?php
/*
	cvmultiply v1.2
	Author: Philip Ide
	Copyright Philip Ide 2024
	Released under the MIT Licence
*/

class cvmultiply {
	protected $result;
	protected $v0;
	protected $v1;
	protected $calculated = false;
	protected $decimal = 0;
	protected $decToken;
	protected $decSep;
	protected $millSep;

	function __construct( $s0, $s1, $decToken = ".", $decSep = " ", $millSep = "," ){
		while (strlen($s0) < strlen($s1) ) $s0 = "0".$s0; // normalise values to same length by front-padding with zeros
		while (strlen($s1) < strlen($s0) ) $s1 = "0".$s1;
		$this->res = "";
		$this->v0 = $this->decimal_decode( $s0 );
		$this->v1 = $this->decimal_decode( $s1 );
		$this->decToken = $decToken;
		$this->decSep = $decSep;
		$this->millSep = $millSep;
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
		if( $this->decimal ){
			$this->result = substr_replace( $this->result, ".", 0-$this->decimal, 0 );
			$this->tidyDecimal();
			if( substr( $this->result, 0, 1 ) == "." ){
				$this->result = "0$this->result";
			}
		}
		$this->calculated = true;
		return $this->result;
	}

	private function decimal_decode( $subject ){
		if( ($i = strpos( $subject, ".")) !== false ){
			$this->decimal += strlen( $subject ) - ($i+1);
		}
		return str_replace( ".", "", $subject );
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

	private function tidyDecimal(){
		while( substr( $this->result, -1 ) == "0" ){
			$this->result = substr( $this->result, 0, strlen( $this->result ) -1 );
		}
		if( substr( $this->result, -1 ) == "." ){
			// decimal value is zero, so get rid of decimal point and make an integer
			$this->result = substr( $this->result, 0, strlen( $this->result ) -1 );
		}
		return $this;
	}

	function format(){
		if( !$this->calculated ){
			$this->calc();
		}

		$int = $this->result;
		$dec = "";
		$dpos = strpos( $this->result, "." );
		if( $dpos !== false ){
			$int = substr($this->result, 0, $dpos );
			$dec = substr( $this->result, $dpos+1 );
			$dec = $this->formatDec( $dec );
			if( $int == "" ){
				$int = "0";
			}
		}
		$int = $this->formatInt( $int );
		if( strlen( $dec ) > 0 ){
			$int .= "$this->decToken$dec";
		}
		return $int;
	}

	private function formatDec( $dec ){
		$i = 3;
		while( $i < strlen( $dec ) ){
			$dec = substr_replace( $dec, $this->decSep, $i, 0 );
			$i += 4;
		}
		return $dec;
	}

	private function formatInt( $int ){
		$e = strlen( $int )+1;
		$t = 0;
		$r = "";
		for( $i = 1; $i < $e; $i++ ){
			$v = substr( $int, -$i, 1 );
			if( $t == 3 ){
				$r = "$this->millSep$r";
				$t = 0;
			}
			$r = "$v$r";
			$t++;
		}
		return $r;
	}
}
?>