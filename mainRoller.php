<?php
	/*
	 * Determine if $_POST data is set
	 * and if so, try to work with it
	*/
	if( isset( $_POST ) ) {
		$postDataExists = TRUE;
	} else {
		die( "No POST data." );
	}

	/*
	 * EXECUTE!
	 * 
	 * IF form was submitted...
	 * Process form and...
	 * Send webhook
	*/
	if( $postDataExists === TRUE ) {
		$authenticated = authenticatePostData($domainWebhookSettings);
		if( $authenticated === TRUE ) {
			$payloadArray = processRoll($diceDistributionArray);
			$payloadString = constructRollPayload( $payloadArray, $rollerAttachmentsArray );
			if( isset( $payloadString ) ) {
				/*
				 * Log payloadString
				*/
				$logResult = logOutput( $payloadString, $logFile );
				/*
				 *
				*/
				$webhookResponse = sendSlackWebhook( $payloadString, $_POST['response_url'] );
			}
		}
	}
?>