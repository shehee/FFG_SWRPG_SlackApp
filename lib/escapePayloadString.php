<?php
	/*
	 * Escape the payload string provided so it doesn't throw an error
	 * The only characters that have trouble by default are \ and "
	*/
	if (!function_exists('escapePayloadString')) {
		function escapePayloadString($payloadString) {
			$payloadTextSearch  = array('\\', '"');
			$payloadTextReplace = array('\\\\', '\"');
			return str_replace($payloadTextSearch, $payloadTextReplace, $payloadString);
		}
	}