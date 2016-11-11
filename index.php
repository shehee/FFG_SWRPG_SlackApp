<?php
	$requiredFiles = array(
		"lib/authenticatePostData.php",			// needed for mainRoller.php
		"lib/constructAttachmentsString.php",	// universal
		"lib/constructMessagePayload.php",		// needed for mainMessager.php
		"lib/constructRollPayload.php",			// needed for mainRoller.php
		"lib/domainWebhookConfig.php",			// universal - settings
		"lib/escapePayloadString.php",			// universal
		"lib/logOutput.php",					// universal
		"lib/processMessage.php",				// needed for mainMessager.php
		"lib/processRoll.php",					// needed for mainRoller.php
		"lib/sendSlackWebhook.php",				// universal
	);
	/*
	 * Determine if form was submitted...
	 * and if so, by what means
	 * then set state as appropriate
	*/
	if ( isset( $_GET['roll'] ) ) {
		$logFile = "logs/rollerOutput.log";
		$requiredFiles[] = "mainRoller.php";
	} else {
		$logFile = "logs/messengerOutput.log";
		$requiredFiles[] = "mainMessenger.php";
	}
	foreach( $requiredFiles as $file ) {
		if(file_exists($file)) {
			require_once($file);
		} else {
			echo "File not found: ".$file;
		}
	}