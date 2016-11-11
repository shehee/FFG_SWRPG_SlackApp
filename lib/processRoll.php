<?php
	/*
	 * Process $_POST data from Slack request
	 * Begins constructing the payload array to be turned into the payload string
	*/
	if (!function_exists('processRoll')) {
		function processRoll($diceDistributionArray) {
			$lowercaseText = strtolower($_POST['text']);
			$trimmedLowercaseText = trim($lowercaseText);
			$replacedTrimmedLowercaseText = preg_replace('/[^abcdfgkprswyABCDFGKPRSWY0-9+]+/i', '', $trimmedLowercaseText);
			$payloadArray['attachmentsArray']['color'] = "#761213";

			/*
			 * test if "text" is alpha only:
			 * 		ex: /roll abcdfgkprswy
			 * or alphanumeric
			 *		ex: /roll 1g3y1b3p1r2k // may be implemented in the future
			 * 		ex: /roll 1d100
			*/
			if( $trimmedLowercaseText !== $replacedTrimmedLowercaseText ) {
				/*
				 * If the "text" is ill-formatted...
				 * return instructions on how to properly format "text"
				 * this only happens if a character is included other than the following:
				 * 		abcdfgkprswyABCDFGKPRSWY0-9+
				*/
				$payloadArray['text'] = "I tried what you asked: `/roll ".$_POST['text']."`, but it didn't work.\n";
				$payloadArray['text'] .= "Valid `/roll` commands are made up of either:\n";
				$payloadArray['text'] .= "> * letters that describe the kind of Fantasy Flight Games' Star Wars Roleplaying Game dice to roll (either by color or type abbreviation),\n";
				$payloadArray['text'] .= "> * or the number of ordinary dice to roll (either [X]d10 or [Y]d100).\n";
				$payloadArray['text'] .= "For example: `/roll gybprkwadcsf`, `/roll 1d10`, `/roll 1d100`, or `/roll 1d100+10`.";
			/*
			 * elseif ctype_alpha
			 *
			 * /roll abcdfgkprswy
			 * $replacedTrimmedLowercaseText: abcdfgkprswy
			 *
			 * Type or Color abbreviations
			 * [A]bility dice are [G]reen
			 * proficienc[Y] dice are [Y]ellow
			 * [B]oost dice are [B]lue
			 * [D]ifficulty dice are [P]urple
			 * [C]hallenge dice are [R]ed
			 * [S]etback dice are blac[K]
			 * [F]orce dice are [W]hite
			*/
			} elseif( ctype_alpha( $replacedTrimmedLowercaseText ) ) {
				/*
				 * Step 1:
				 * Begin formatting pretext string
				*/
				$payloadArray['attachmentsArray']['fallback'] = "ctype_alpha: " . $_POST['text'];
				$payloadArray['attachmentsArray']['pretext'] = "<@".$_POST['user_id']."|".$_POST['user_name']."> rolled ";
				/*
				 * Step 2:
				 * Count how many of each type of dice are mentioned in the string
				*/
				$diceArray['request']['ability'] = substr_count($replacedTrimmedLowercaseText, 'g') + substr_count($replacedTrimmedLowercaseText, 'a');
				$diceArray['request']['proficiency'] = substr_count($replacedTrimmedLowercaseText, 'y');
				$diceArray['request']['boost'] = substr_count($replacedTrimmedLowercaseText, 'b');
				$diceArray['request']['difficulty'] = substr_count($replacedTrimmedLowercaseText, 'p') + substr_count($replacedTrimmedLowercaseText, 'd');
				$diceArray['request']['challenge'] = substr_count($replacedTrimmedLowercaseText, 'r') + substr_count($replacedTrimmedLowercaseText, 'c');
				$diceArray['request']['setback'] = substr_count($replacedTrimmedLowercaseText, 'k') + substr_count($replacedTrimmedLowercaseText, 's');
				$diceArray['request']['Force'] = substr_count($replacedTrimmedLowercaseText, 'w') + substr_count($replacedTrimmedLowercaseText, 'f');
				/*
				 * Step 3:
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
				 * Step 4:
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
				 * Step 5:
				 * Begin formatting text string based on results of rolls
				*/

				/*
				 * Step 5a:
				 * Successes vs Failures
				*/
				if( $resultArray['success'] > $resultArray['failure'] ) {
					$payloadArray['attachmentsArray']['color'] = "#46A246";
					$resultArray['netSuccesses'] = $resultArray['success'] - $resultArray['failure'];
					for($i=1;$i<=$resultArray['netSuccesses'];$i++) {
						$payloadArray['attachmentsArray']['text'] .= ":success:";
					}
					$payloadArray['attachmentsArray']['footer'] .= $resultArray['netSuccesses'] . " Success".($resultArray['netSuccesses'] > 1 ? "es" : NULL).". ";
				} elseif( $resultArray['failure'] > $resultArray['success'] ) {
					$payloadArray['attachmentsArray']['color'] = "#E11D39";
					$resultArray['netFailures'] = $resultArray['failure'] - $resultArray['success'];
					for($i=1;$i<=$resultArray['netFailures'];$i++) {
						$payloadArray['attachmentsArray']['text'] .= ":failure:";
					}
					$payloadArray['attachmentsArray']['footer'] .= $resultArray['netFailures'] . " Failure".($resultArray['netFailures'] > 1 ? "s" : NULL).". ";
				}
				/*
				 * Step 5b:
				 * Advantages vs Threats
				*/
				if( $resultArray['advantage'] > $resultArray['threat'] ) {
					$resultArray['netAdvantages'] = $resultArray['advantage'] - $resultArray['threat'];
					for($i=1;$i<=$resultArray['netAdvantages'];$i++) {
						$payloadArray['attachmentsArray']['text'] .= ":advantage:";
					}
					$payloadArray['attachmentsArray']['footer'] .= $resultArray['netAdvantages'] . " Advantage".($resultArray['netAdvantages'] > 1 ? "s" : NULL).". ";
				} elseif( $resultArray['threat'] > $resultArray['advantage'] ) {
					$resultArray['netThreats'] = $resultArray['threat'] - $resultArray['success'];
					for($i=1;$i<=$resultArray['netThreats'];$i++) {
						$payloadArray['attachmentsArray']['text'] .= ":threat:";
					}
					$payloadArray['attachmentsArray']['footer'] .= $resultArray['netThreats'] . " Threat".($resultArray['netThreats'] > 1 ? "s" : NULL).". ";
				}
				/*
				 * Step 5c:
				 * Triumphs and Despairs
				*/
				if( $resultArray['triumph'] > 0 ) {
					for($i=1;$i<=$resultArray['triumph'];$i++) {
						$payloadArray['attachmentsArray']['text'] .= ":triumph1:";
					}
					$payloadArray['attachmentsArray']['footer'] .= $resultArray['triumph'] . " Triumph".($resultArray['triumph'] > 1 ? "s" : NULL).". ";
				}
				if( $resultArray['despair'] > 0 ) {
					for($i=1;$i<=$resultArray['despair'];$i++) {
						$payloadArray['attachmentsArray']['text'] .= ":despair:";
					}
					$payloadArray['attachmentsArray']['footer'] .= $resultArray['despair'] . " Despair".($resultArray['despair'] > 1 ? "s" : NULL).". ";
				}
				/*
				 * Step 5d:
				 * Light Side Force Points and Dark Side Force Points
				*/
				if( $resultArray['lightSideForcePoint'] > 0 ) {
					for($i=1;$i<=$resultArray['lightSideForcePoint'];$i++) {
						$payloadArray['attachmentsArray']['text'] .= ":lightside:";
					}
					$payloadArray['attachmentsArray']['footer'] .= $resultArray['lightSideForcePoint'] . " light side Force point".($resultArray['lightSideForcePoint'] > 1 ? "s" : NULL).". ";
				}
				if( $resultArray['darkSideForcePoint'] > 0 ) {
					for($i=1;$i<=$resultArray['darkSideForcePoint'];$i++) {
						$payloadArray['attachmentsArray']['text'] .= ":darkside:";
					}
					$payloadArray['attachmentsArray']['footer'] .= $resultArray['darkSideForcePoint'] . " dark side Force point".($resultArray['darkSideForcePoint'] > 1 ? "s" : NULL).".";
				}
				// end formatting text string based on results of rolls

			// end elseif ctype_alpha

			/*
			 * /roll 1d100+10
			 * $replacedTrimmedLowercaseText: 1d100+10
			*/
			} else {
				$payloadArray['attachmentsArray']['fallback'] = "NOT ctype_alpha: " . $_POST['text'];
				/*
				 * Step 1:
				 * Begin formatting pretext string
				*/
				$payloadArray['attachmentsArray']['pretext'] = "<@".$_POST['user_id']."|".$_POST['user_name']."> rolled ";
				/*
				 * Step 2:
				 * Organize string
				 * Return error if not formatted properly
				*/
				$diceD_Test = explode("d", $replacedTrimmedLowercaseText);
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
				 * Step 3:
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
						$diceArray['rollResult'] = mt_rand(1,$diceArray['diceSides']) + $diceArray['addAmount'];
						$payloadArray['attachmentsArray']['text'] .= "Roll ".$i." result: " . $diceArray['rollResult'] . "\n";
					}
				}
			} // end else
			return $payloadArray;
		} // end function processRoll
	} // end if function_exists