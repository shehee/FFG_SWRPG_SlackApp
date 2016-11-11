<?php
	/*
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