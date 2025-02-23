<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Numbers {
	
	function ordinal($num)
	{
		// Special case "teenth"
		if ( ($num / 10) % 10 != 1 )
		{
			// Handle 1st, 2nd, 3rd
			switch( $num % 10 )
			{
				case 1: return $num . 'st';
				case 2: return $num . 'nd';
				case 3: return $num . 'rd';  
			}
		}
		// Everything else is "nth"
		return $num . 'th';
	}
	
	
	function convert_number_to_words($number) {
    
		$hyphen      = '-';
		$conjunction = '  ';
		$separator   = ', ';
		$negative    = 'negative ';
		$decimal     = ' &  ';
		$dictionary  = array(
			0                   => 'zero',
			1                   => 'one',
			2                   => 'two',
			3                   => 'three',
			4                   => 'four',
			5                   => 'five',
			6                   => 'six',
			7                   => 'seven',
			8                   => 'eight',
			9                   => 'nine',
			10                  => 'ten',
			11                  => 'eleven',
			12                  => 'twelve',
			13                  => 'thirteen',
			14                  => 'fourteen',
			15                  => 'fifteen',
			16                  => 'sixteen',
			17                  => 'seventeen',
			18                  => 'eighteen',
			19                  => 'nineteen',
			20                  => 'twenty',
			30                  => 'thirty',
			40                  => 'forty',
			50                  => 'fifty',
			60                  => 'sixty',
			70                  => 'seventy',
			80                  => 'eighty',
			90                  => 'ninety',
			100                 => 'hundred',
			1000                => 'thousand',
			1000000             => 'million',
			1000000000          => 'billion',
			1000000000000       => 'trillion',
			1000000000000000    => 'quadrillion',
			1000000000000000000 => 'quintillion'
		);
		
		if (!is_numeric($number)) {
			return false;
		}
		
		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			// overflow
			trigger_error(
				'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
				E_USER_WARNING
			);
			return false;
		}

		if ($number < 0) {
			return $negative . convert_number_to_words(abs($number));
		}
		
		$string = $fraction = null;
		
		if (strpos($number, '.') !== false) {
			$num = $number;
			list($number, $fraction) = explode('.', $number);
		}
		
		switch (true) {
			case $number < 21:
				$string = $dictionary[$number];
				break;
			case $number < 100:
				$tens   = ((int) ($number / 10)) * 10;
				$units  = $number % 10;
				$string = $dictionary[$tens];
				if ($units) {
					$string .= $hyphen . $dictionary[$units];
				}
				break;
			case $number < 1000:
				$hundreds  = $number / 100;
				$remainder = $number % 100;
				$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
				if ($remainder) {
					$string .= $conjunction . $this->convert_number_to_words($remainder);
				}
				break;
			default:
				$baseUnit = pow(1000, floor(log($number, 1000)));
				$numBaseUnits = (int) ($number / $baseUnit);
				$remainder = $number % $baseUnit;
				$string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
				if ($remainder) {
					$string .= $remainder < 100 ? $conjunction : $separator;
					$string .= $this->convert_number_to_words($remainder);
				}
				break;
		}
		
		if (null !== $fraction && is_numeric($fraction)) {
			$string .= $decimal;
			
			if(strlen($fraction) == 1)
				$fraction = $fraction.'0';
			$string .= $fraction."/100";
			//$words = array();
			//foreach (str_split((string) $fraction) as $number) {
				//$words[] = $dictionary[$number];
			//}
			//$string .= implode(' ', $words);
		}
		
		return $string;
	}
	
	
} ?>