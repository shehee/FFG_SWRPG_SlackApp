<?php
	/*
	 * Construct the roll payload string from the payload array and attachments array
	*/
	if (!function_exists('constructRollPayload')) {
		function constructRollPayload($payloadArray, $rollerAttachmentsArray) {
			$payloadString = '{';
			$payloadString .= '"response_type": "in_channel",';
			$payloadString .= '"channel_id": "'.$_POST[ 'channel_id' ].'",';

			if( isset( $_POST[ 'text' ] ) ) {
				$payloadString .= '"text": "'.escapePayloadString($payloadArray['text']).'",';
			} else {
				$payloadString .= '"text": "No text provided.",';
			}

			$payloadString .= constructAttachmentsString( $payloadArray['attachmentsArray'] );

			$payloadString .= '}';

			return $payloadString;
		}
	}