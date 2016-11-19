<?php
	/*
	 * Copyright (C) 2016 Ryan Shehee
	 *
	 * Author:		Ryan Shehee
	 * Version:		1.00
	 * Date:		2016-11-04
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
	 * Construct the roll payload string from the payload array and attachments array
	 */
	if (!function_exists('constructRollPayload')) {
		function constructRollPayload($payloadArray, $rollerAttachmentsArray) {
			$payloadString .= '{';
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