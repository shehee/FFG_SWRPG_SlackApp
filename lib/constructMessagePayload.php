<?php
	/*
	 * Copyright (C) 2016 Ryan Shehee
	 *
	 * Author:		Ryan Shehee
	 * Version:		1.00
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
	if (!function_exists('constructMessagePayload')) {
		function constructMessagePayload($payloadArray, $messageAttachmentsArray) {
			$payloadString = '{';
			if( isset( $payloadArray['channel'] ) ) {
				$payloadString .= '"channel": "'.$payloadArray['channel'].'",';
			} else {
				$payloadString .= '"channel": "#ic",';
			}

			if( isset( $payloadArray['username'] ) ) {
				$payloadArray['escapedUsername'] = escapePayloadString($payloadArray['username']);
				$payloadString .= '"username": "'.$payloadArray['escapedUsername'].'",';
			} else {
				$payloadString .= '"username": "Unnamed NPC",';
			}

			if( isset( $payloadArray['icon_url'] ) ) {
				$payloadArray['escapedIconURL'] = escapePayloadString($payloadArray['icon_url']);
				$payloadString .= '"icon_url": "'.$payloadArray['escapedIconURL'].'",';
			} elseif( isset( $payloadArray['icon_emoji'] ) ) {
				$payloadString .= '"icon_emoji": "'.$payloadArray['icon_emoji'].'",';
			} else {
				$payloadString .= '"icon_emoji": ":speech_balloon:",';
			}

			if( isset( $payloadArray['text'] ) ) {
				$payloadArray['escapedText'] = escapePayloadString($payloadArray['text']);
//				$payloadString .= '"text": "'.$payloadArray['text'].'",'; // WORKS EXCEPT FOR \ and " characters
//				$payloadString .= '"text": "'.addslashes($payloadArray['text']).'",'; // WORKS EXCEPT FOR ' character
				$payloadString .= '"text": "'.$payloadArray['escapedText'].'",'; // WORKS
			} else {
				$payloadString .= '"text": "..."';
			}

			$payloadString .= constructAttachmentsString( $payloadArray['attachmentsArray'] );

			$payloadString .= '}';

			return $payloadString;
		}
	}