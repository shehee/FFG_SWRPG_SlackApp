<?php
	/*
	 * Function for logging output
	 * Not for error reporting:
	 * In case of failure on some level, 
	 * there's a chance to get it back from the logs
	 * You don't want a well written post lost,
	 * or a crucial roll wasted
	*/
	if (!function_exists('logOutput')) {
		function logOutput( $payloadString, $logFile ) {
			// Open the file to get existing content
			$currentLogContents = file_get_contents($logFile);
			// Append a new person to the file
			$currentLogContents .= date("r").":";
			$currentLogContents .= "\r\n";
			$currentLogContents .= "\t".$payloadString;
			$currentLogContents .= "\r\n\r\n";
			// Write the contents back to the file
			file_put_contents($logFile, $currentLogContents);
		}
	}