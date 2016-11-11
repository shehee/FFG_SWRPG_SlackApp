<?php
	/*
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