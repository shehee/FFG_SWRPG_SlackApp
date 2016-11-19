<?php
	/*
	 * Copyright (C) 2016 Ryan Shehee
	 *
	 * Author:		Ryan Shehee
	 * Version:		1.04
	 * Date:		2016-11-19
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
	 * Process $_POST data from Slack request
	 * Begins constructing the payload array to be turned into the payload string
	 */
	if (!function_exists('processRoll')) {
		function processRoll($diceDistributionArray) {
			/*
			 * Step -1:
			 * Process $_POST['text'] into usable and matchable string
			 * Use regular expressions to match the roll type
			 */
			$lowercaseText = strtolower($_POST['text']);
			$trimmedLowercaseText = trim($lowercaseText);
			$replacedTrimmedLowercaseText = preg_replace('/[^abcdfgkprsuwy\d+]+/', '', $trimmedLowercaseText);
			if( $trimmedLowercaseText === $replacedTrimmedLowercaseText ) {
				if( preg_match('/^[\d]+[d][\d]+[+][\d]+$/',$replacedTrimmedLowercaseText ) ) {
					$diceArray['numberedDiceString'] = $replacedTrimmedLowercaseText;
					$diceArray['type'] = "Numbered Dice with Addition";
				} elseif( preg_match('/^[\d]+[d][\d]+$/',$replacedTrimmedLowercaseText ) ) {
					$diceArray['numberedDiceString'] = $replacedTrimmedLowercaseText;
					$diceArray['type'] = "Numbered Dice";
				} else {
					$diceArray['roleplayingDiceString'] = constructRoleplayingDiceString($replacedTrimmedLowercaseText);
					if( preg_match('/^[abcdfgkprsuwy]+$/',$diceArray['roleplayingDiceString'] ) ) {
						$diceArray['type'] = "Star Wars Roleplaying Dice";
					} else {
						$diceArray['type'] = "Unknown";
					}
				}
			} else {
				$diceArray['type'] = "Invalid";
			}
			/*
			 * Step 0:
			 * Begin formatting $payloadArray, 
			 * esp. pretext string
			 */
			$payloadArray['attachmentsArray']['mrkdwn_in'] = array( "pretext", "text", "fields" );
			$payloadArray['attachmentsArray']['color'] = "#761213";
			$payloadArray['attachmentsArray']['fallback'] = $diceArray['type'] .": " . $_POST['text'];
			$payloadArray['attachmentsArray']['pretext'] = "<@".$_POST['user_id']."|".$_POST['user_name']."> rolled ";

			/*
			 * test if "text" is alpha only:
			 * 		ex: /roll abcdfgkprsuwy
			 * or alphanumeric
			 *		ex: /roll 1g3y1b3p1r2k // may be implemented in the future
			 * 		ex: /roll 1d100
			 */
			if( $diceArray['type'] === "Invalid" || $diceArray['type'] === "Unknown" ) {
				/*
				 * If the "text" is ill-formatted...
				 * return instructions on how to properly format "text"
				 * this only happens if a character is included other than the following:
				 * 		abcdfgkprsuwyABCDFGKPRSUWY0-9+
				 */
				$payloadArray['text'] = "I tried what you asked: `/roll ".$_POST['text']."`, but it didn't work.\n";
				$payloadArray['text'] .= "Valid `/roll` commands are made up of either:\n";
				$payloadArray['text'] .= "> * letters that describe the kind of Fantasy Flight Games' Star Wars Roleplaying Game dice to roll (either by color or type abbreviation),\n";
				$payloadArray['text'] .= "> * or the number of ordinary dice to roll (either [X]d10 or [Y]d100).\n";
				$payloadArray['text'] .= "For example: `/roll gybprkwadcsf`, `/roll 1d10`, `/roll 1d100`, or `/roll 1d100+10`.";
			/*
			 * elseif ctype_alpha
			 *
			 * /roll abcdfgkprsuwy
			 * $diceArray['roleplayingDiceString']: abcdfgkprsuwy
			 *
			 * Type or Color abbreviations
			 * [A]bility dice are [G]reen
			 * proficienc[Y] dice are [Y]ellow
			 * [B]oost dice are [B]lue (bl[U]e can also be used...because somebody played Magic: The Gathering once)
			 * [D]ifficulty dice are [P]urple
			 * [C]hallenge dice are [R]ed
			 * [S]etback dice are blac[K]
			 * [F]orce dice are [W]hite
			 */
			} elseif( $diceArray['type'] === "Star Wars Roleplaying Dice" ) {
				/*
				 * Step 1:
				 * Count how many of each type of dice are mentioned in the string
				 */
				$diceArray['request']['ability'] = substr_count($diceArray['roleplayingDiceString'], 'g') + substr_count($diceArray['roleplayingDiceString'], 'a');
				$diceArray['request']['proficiency'] = substr_count($diceArray['roleplayingDiceString'], 'y');
				$diceArray['request']['boost'] = substr_count($diceArray['roleplayingDiceString'], 'b') + substr_count($diceArray['roleplayingDiceString'], 'u');
				$diceArray['request']['difficulty'] = substr_count($diceArray['roleplayingDiceString'], 'p') + substr_count($diceArray['roleplayingDiceString'], 'd');
				$diceArray['request']['challenge'] = substr_count($diceArray['roleplayingDiceString'], 'r') + substr_count($diceArray['roleplayingDiceString'], 'c');
				$diceArray['request']['setback'] = substr_count($diceArray['roleplayingDiceString'], 'k') + substr_count($diceArray['roleplayingDiceString'], 's');
				$diceArray['request']['Force'] = substr_count($diceArray['roleplayingDiceString'], 'w') + substr_count($diceArray['roleplayingDiceString'], 'f');
				/*
				 * Step 2:
				 * Roll results of each $diceArray['request']
				 * Further format pretext string
				 */
				foreach( $diceArray['request'] as $dieType => $diceRequested ) {
					for($i=1;$i<=$diceRequested;$i++) {
						$diceArray['dieFace'] = mt_rand(1,count($diceDistributionArray[$dieType]));
						$diceArray['dieRoll'][] = $diceDistributionArray[$dieType][ $diceArray['dieFace'] ]; // this new array will be used later as $rollArray
						$payloadArray['attachmentsArray']['title'] .= ":".strtolower($dieType).$diceArray['dieFace'].": "; // add die results string to "title" as they get generated
					}
					if( $diceRequested > 0 ) {
						$pretextComma = ($pretextCount > 0 ? ", " : NULL);
						$diceArray['dicePluralString'] = ($diceRequested > 1 ? "dice" : "die");
						$payloadArray['attachmentsArray']['pretext'] .= $pretextComma.--$i." ".ucfirst($dieType)." ".$diceArray['dicePluralString']." :".strtolower($dieType).":"; // add die results string to "title" as they get generated
						$pretextCount++;
					}
// unset($diceArray);
				}
				$payloadArray['attachmentsArray']['pretext'] .= ". "; // finish formatting pretext string
				/*
				 * Step 3:
				 * Count each result and organize them in an array
				 */
				if( is_array( $diceArray['dieRoll'] ) ) {
					foreach( $diceArray['dieRoll'] as $rollArray ) {
						foreach( $rollArray as $result ) {
							switch($result) {
								case "success":
									$resultArray['success']++;
									break;
								case "advantage":
									$resultArray['advantage']++;
									break;
								case "triumph":
									$resultArray['triumph']++;
									break;
								case "failure":
									$resultArray['failure']++;
									break;
								case "threat":
									$resultArray['threat']++;
									break;
								case "despair":
									$resultArray['despair']++;
									break;
								case "lightSideForcePoint":
									$resultArray['lightSideForcePoint']++;
									break;
								case "darkSideForcePoint":
									$resultArray['darkSideForcePoint']++;
									break;
								default:
									//$resultArray['null'] = NULL;
							}
						}
					}
				}
				/*
				 * Step 4:
				 * Begin formatting text string based on results of rolls
				 */

				/*
				 * Step 4a:
				 * Successes vs Failures
				 */
				if( $resultArray['success'] > $resultArray['failure'] ) {
					$payloadArray['attachmentsArray']['color'] = "#46A246";
					$resultArray['netSuccesses'] = $resultArray['success'] - $resultArray['failure'];
					for($i=1;$i<=$resultArray['netSuccesses'];$i++) {
						$payloadArray['attachmentsArray']['text'] .= ":success:";
					}
					$payloadArray['attachmentsArray']['footer'] .= $resultArray['netSuccesses']." Success".($resultArray['netSuccesses'] > 1 ? "es" : NULL);
					$prependFooter = ", ";
				} elseif( $resultArray['failure'] > $resultArray['success'] ) {
					$payloadArray['attachmentsArray']['color'] = "#E11D39";
					$resultArray['netFailures'] = $resultArray['failure'] - $resultArray['success'];
					for($i=1;$i<=$resultArray['netFailures'];$i++) {
						$payloadArray['attachmentsArray']['text'] .= ":failure:";
					}
					$payloadArray['attachmentsArray']['footer'] .= $resultArray['netFailures']." Failure".($resultArray['netFailures'] > 1 ? "s" : NULL);
					$prependFooter = ", ";
				}
				/*
				 * Step 4b:
				 * Advantages vs Threats
				 */
				if( $resultArray['advantage'] > $resultArray['threat'] ) {
					$resultArray['netAdvantages'] = $resultArray['advantage'] - $resultArray['threat'];
					for($i=1;$i<=$resultArray['netAdvantages'];$i++) {
						$payloadArray['attachmentsArray']['text'] .= ":advantage:";
					}
					$payloadArray['attachmentsArray']['footer'] .= $prependFooter.$resultArray['netAdvantages']." Advantage".($resultArray['netAdvantages'] > 1 ? "s" : NULL);
					$prependFooter = ", ";
				} elseif( $resultArray['threat'] > $resultArray['advantage'] ) {
					$resultArray['netThreats'] = $resultArray['threat'] - $resultArray['advantage'];
					for($i=1;$i<=$resultArray['netThreats'];$i++) {
						$payloadArray['attachmentsArray']['text'] .= ":threat:";
					}
					$payloadArray['attachmentsArray']['footer'] .= $prependFooter.$resultArray['netThreats']." Threat".($resultArray['netThreats'] > 1 ? "s" : NULL);
					$prependFooter = ", ";
				}
				/*
				 * Step 4c:
				 * Triumphs and Despairs
				 */
				if( $resultArray['triumph'] > 0 ) {
					for($i=1;$i<=$resultArray['triumph'];$i++) {
						$payloadArray['attachmentsArray']['text'] .= ":triumph1:";
					}
					$payloadArray['attachmentsArray']['footer'] .= $prependFooter.$resultArray['triumph']." Triumph".($resultArray['triumph'] > 1 ? "s" : NULL);
					$prependFooter = ", ";
				}
				if( $resultArray['despair'] > 0 ) {
					for($i=1;$i<=$resultArray['despair'];$i++) {
						$payloadArray['attachmentsArray']['text'] .= ":despair:";
					}
					$payloadArray['attachmentsArray']['footer'] .= $prependFooter.$resultArray['despair']." Despair".($resultArray['despair'] > 1 ? "s" : NULL);
					$prependFooter = ", ";
				}
				/*
				 * Step 4d:
				 * Light Side Force Points and Dark Side Force Points
				 */
				if( $resultArray['lightSideForcePoint'] > 0 ) {
					for($i=1;$i<=$resultArray['lightSideForcePoint'];$i++) {
						$payloadArray['attachmentsArray']['text'] .= ":lightside:";
					}
					$payloadArray['attachmentsArray']['footer'] .= $prependFooter.$resultArray['lightSideForcePoint']." light side Force point".($resultArray['lightSideForcePoint'] > 1 ? "s" : NULL);
					$prependFooter = ", ";
				}
				if( $resultArray['darkSideForcePoint'] > 0 ) {
					for($i=1;$i<=$resultArray['darkSideForcePoint'];$i++) {
						$payloadArray['attachmentsArray']['text'] .= ":darkside:";
					}
					$payloadArray['attachmentsArray']['footer'] .= $prependFooter.$resultArray['darkSideForcePoint']." dark side Force point".($resultArray['darkSideForcePoint'] > 1 ? "s" : NULL);
				}
				// end formatting text string based on results of rolls

			// end elseif ctype_alpha

			/*
			 * /roll 1d100+10
			 * $diceArray['numberedDiceString']: 1d100+10
			 */
			} elseif( $diceArray['type'] === "Numbered Dice" || $diceArray['type'] === "Numbered Dice with Addition" ) {
				/*
				 * Step 1:
				 * Organize string
				 * Return error if not formatted properly
				 */
				$diceD_Test = explode("d", $diceArray['numberedDiceString']);
				$diceArray['dicePluralString'] = ($diceD_Test[0] > 1 ? "dice" : "die");
				$diceArray['addAmount'] = 0;
// 1d100
				if( count( $diceD_Test ) === 2 && is_numeric( $diceD_Test[0] ) && is_numeric( $diceD_Test[1] ) ) {
					$diceArray['diceAmount'] = $diceD_Test[0];
					$diceArray['diceSides'] = $diceD_Test[1];
// 1d100+10
				} elseif( count( $diceD_Test ) === 2 && is_numeric( $diceD_Test[0] ) && strpos($diceD_Test[1], '+') >= 0 ) {
					$dicePLUS_Test = explode("+", $diceD_Test[1]);
					if( count( $dicePLUS_Test ) === 2 && is_numeric( $dicePLUS_Test[1] ) && is_numeric( $dicePLUS_Test[1] ) ) {
						$diceArray['diceAmount'] = $diceD_Test[0];
						$diceArray['diceSides'] = $dicePLUS_Test[0];
						$diceArray['addAmount'] = $dicePLUS_Test[1];
					}
				}
				/*
				 * Step 2:
				 * Calculate the result
				 */
				if( isset( $diceArray ) ) {
					$payloadArray['attachmentsArray']['pretext'] .= $diceArray['diceAmount']." ".$diceArray['diceSides']."-sided ".$diceArray['dicePluralString'];
					if( $diceArray['addAmount'] ) {
						$payloadArray['attachmentsArray']['pretext'] .= ", adding ".$diceArray['addAmount']." to the result.";
					} else {
						$payloadArray['attachmentsArray']['pretext'] .= ".";
					}
					for($i=1;$i<=$diceArray['diceAmount'];$i++) {
						$diceArray['rollResult'] = mt_rand(1,$diceArray['diceSides']);
						$diceArray['rollTotal'] = $diceArray['rollResult'] + $diceArray['addAmount'];
						$payloadArray['attachmentsArray']['text'] .= "Roll ".$i." result: `" . str_pad( $diceArray['rollResult'] , 3 , " " , STR_PAD_LEFT );
						if( $diceArray['addAmount'] > 0 ) {
							$payloadArray['attachmentsArray']['text'] .= "` + `" . str_pad( $diceArray['addAmount'] , 3 , " " , STR_PAD_LEFT ) . "` = `" . str_pad( $diceArray['rollTotal'] , 3 , " " , STR_PAD_LEFT );
						}
						$payloadArray['attachmentsArray']['text'] .= "`\n";
						$payloadArray['attachmentsArray']['footer'] .= ( $i > 1 ? ", " : NULL ) . $diceArray['rollTotal'];
					}
				}
			} // end else
			return $payloadArray;
		} // end function processRoll
	} // end if function_exists