<?php
	/*
	 * Copyright (C) 2016 Ryan Shehee
	 *
	 * Author:		Ryan Shehee
	 * Version:		1.02
	 * Date:		2016-11-18
	 * Repository:	https://github.com/shehee/ffgswrpg-slack-app
	 * License:		GNU GPLv3
	 *
	 * Copyright (C) 2016 Ryan Shehee
	 * 
	 * This program is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation, either version 3 of the License, or
	 * (at your option) any later version.
	 * 
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 * 
	 * You should have received a copy of the GNU General Public License
	 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
	 *
	 * Purpose:
	 * --------
	 * Construct a string consisting of the Roleplaying Dice requested in the format abcdfgkprsuwy instead of 1a1b1c1d1f1g1k1p1r1s1u1w1y
	 */
	if (!function_exists('constructRoleplayingDiceString')) {
		function constructRoleplayingDiceString($replacedTrimmedLowercaseText) {
			if( ctype_alpha( $replacedTrimmedLowercaseText ) ) {
				return $replacedTrimmedLowercaseText;
			} elseif( preg_match('/[abcdfgkprsuwy0-9]+/',$replacedTrimmedLowercaseText ) ) {
				$diceArray['typeAbbreviations'] = array( "a", "b", "c", "d", "f", "g", "k", "p", "r", "s", "u", "w", "y" );
				foreach( $diceArray['typeAbbreviations'] as $typeAbbreviation ) {
					if( preg_match('/(\d+)'.$typeAbbreviation.'/',$replacedTrimmedLowercaseText ) ) {
						$typeAbbreviationCount = preg_match( '/(\d+)'.$typeAbbreviation.'/',$replacedTrimmedLowercaseText,$typeAbbreviationCountArray );
						for($i=1;$i<=$typeAbbreviationCountArray[1];$i++) {
							$roleplayingDiceString .= $typeAbbreviation;
						}
					}
					unset( $typeAbbreviationCountArray );
				}
				return $roleplayingDiceString;
			} else {
				return NULL;
			}
		}
	}