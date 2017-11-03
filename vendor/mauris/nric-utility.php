<?php

namespace Mauris\Utility;

/**
 * A NRIC utility class implemented in PHP. For validating NRIC numbers or generating random NRIC numbers.
 *
 * @package Mauris\Utility
 * @link https://gist.github.com/mauris/6484795
 */
class NricUtility {
	public static function validate( $nric ) {
		$nric = strtoupper( $nric );
		if ( strlen( $nric ) == 9 ) {
			$hash = self::checksum( substr( $nric, 0, 8 ) );

			return $hash == $nric[ 8 ];
		}

		return false;
	}

	public static function checksum( $nric ) {
		$nric = strtoupper( $nric );
		if ( strlen( $nric ) == 8 ) {
			$prefix = $nric[ 0 ];
			$nric   = substr( $nric, 1 );
			$number = $nric[ 0 ] * 2 + $nric[ 1 ] * 7 + $nric[ 2 ] * 6
			          + $nric[ 3 ] * 5 + $nric[ 4 ] * 4 + $nric[ 5 ] * 3
			          + $nric[ 6 ] * 2;
			if ( $prefix == 'T' || $prefix == 'G' ) {
				$number += 4;
			}
			$mod  = $number % 11;
			$hash = array(
				array( 'J', 'Z', 'I', 'H', 'G', 'F', 'E', 'D', 'C', 'B', 'A' ),
				array( 'X', 'W', 'U', 'T', 'R', 'Q', 'P', 'N', 'M', 'L', 'K' )
			);
			if ( in_array( $prefix, array( 'S', 'T' ) ) ) {
				return $hash[ 0 ][ $mod ];
			} elseif ( in_array( $prefix, array( 'F', 'G' ) ) ) {
				return $hash[ 1 ][ $mod ];
			}
		}
	}

	public static function generate( $limit = 1, $prefixes = array( 'S' ) ) {
		$result = array();
		for ( $i = 0; $i < $limit; ++ $i ) {
			$number   = sprintf( '%1$07d', mt_rand( 0, 9999999 ) );
			$prefix   = $prefixes[ mt_rand( 0, count( $prefixes ) - 1 ) ];
			$check    = self::checksum( $prefix . $number );
			$result[] = $prefix . $number . $check;
		}

		return $result;
	}
}
