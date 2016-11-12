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
	 * Send the webhook
	 *
	 * Will return "ok" if all went as planned.
	 * Will return "invalid_payload" if the payload is...invalid.
	 * Will return "missing_text_or_fallback_or_attachments" if no text is set.
	 * Will return "channel_not_found" if it can't fin the channel
	 * etc.
	 */
	if (!function_exists('sendSlackWebhook')) {
		function sendSlackWebhook($payloadString, $response_url) {
			$ch = curl_init($response_url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); // -X
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // POST
			curl_setopt($ch, CURLOPT_POST, TRUE); // --data-urlencode (?)
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json'
				, 'Content-Length: ' . strlen($payloadString)
				)
			);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE ); // return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadString );
			$webhookResponse[ 'result' ] = curl_exec($ch);
			if ( $webhookResponse[ 'result' ] === FALSE ) {
				$webhookResponse[ 'info' ] = curl_getinfo($ch);
			}
			curl_close($ch);
			return $webhookResponse;
		}
	}