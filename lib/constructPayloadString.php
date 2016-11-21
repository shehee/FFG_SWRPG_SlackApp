<?php
	/*
	 * Copyright (C) 2016 Ryan Shehee
	 *
	 * Author:		Ryan Shehee
	 * Version:		1.06
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
	 * Construct the message payload string from the payload array and attachments array
	 */
	if ( !function_exists('constructPayloadString') ) {
		function constructPayloadString($payloadArray,$payloadName=NULL) {
			/*
			 * Step 1:
			 * Open string
			 */
			if( isset($payloadName) ) {
				$payloadString .= '"'.$payloadName.'":[{';
				if($payloadName==='attachments') {
					$payloadString .= '"mrkdwn_in":["pretext","text","fields"],';
				}
			} else {
				/*
				 * mrkdwn_in is such a hassle we'll deal with it here
				 */
				$payloadString .= '{';
			}
			/*
			 * Step 2:
			 * Append each key and value pair
			 */
			foreach( $payloadArray as $payloadKey => $payloadValue ) {
				if( is_string($payloadValue) ) {
					$payloadKeyCount++;
					if($payloadKeyCount > 1) {
						$payloadString .= ',';
					}
					$payloadString .= '"'.$payloadKey.'":"'.trim(escapePayloadString($payloadValue)).'"';
				} elseif( is_array($payloadValue) ) {
					/*
					 * Step 3:
					 * Construct and append attachments & actions using recursive call to constructPayloadString()
					 * Will need to be escaped as needed
					 */
					$payloadString .= ','.constructPayloadString( $payloadArray[$payloadKey], $payloadKey );
				}
			}
			/*
			 * Step 4:
			 * Close string
			 */
			if( isset($payloadName) ) {
				$payloadString .= '}]';
			} else {
				$payloadString .= '}';
			}
			return $payloadString;
		}
	}