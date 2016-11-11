<?php
	/*
	 * Slack Webhook settings; replace these settings with your own
	*/
	$domainWebhookSettings = array(
		"response_url"		=> "https://hooks.slack.com/services/T########/#########/########################",
		"channelList"		=> array(
								"#ic" => "#ic",
								"#mpic-ggr0_somm" => "#mpic-ggr0_somm",
							),
		"token"				=> "########################",
		"team_id"			=> "T########",
		"team_domain"		=> "_________",
		"owner_id"			=> "U########",		// may be used in the future
	/*
	 * $payloadArray['attachmentsArray']
	 * These are defaults that will be overwritten later probably
	*/
	$payloadArray['attachmentsArray'] = array(
		"fallback"			=> "Payload attachment for SWRPG NPC Messenger & Dice Roller",
		"color"				=> "#761213",
	);
	/*
	 * Associate die types to die face assignments and die face results
	*/
	$diceDistributionArray = array(
		"boost"			=> array(
			1				=> array( NULL ),
			2				=> array( NULL ),
			3				=> array( "success" ),
			4				=> array( "success", "advantage" ),
			5				=> array( "advantage", "advantage" ),
			6				=> array( "advantage" ),
		),
		"setback"		=> array(
			1				=> array( NULL ),
			2				=> array( NULL ),
			3				=> array( "failure" ),
			4				=> array( "failure" ),
			5				=> array( "threat" ),
			6				=> array( "threat" ),
		),
		"ability"		=> array(
			1				=> array( NULL ),
			2				=> array( "success" ),
			3				=> array( "success" ),
			4				=> array( "success", "success" ),
			5				=> array( "advantage" ),
			6				=> array( "advantage" ),
			7				=> array( "success", "advantage" ),
			8				=> array( "advantage", "advantage" ),
		),
		"difficulty"	=> array(
			1				=> array( NULL ),
			2				=> array( "failure" ),
			3				=> array( "failure", "failure" ),
			4				=> array( "threat" ),
			5				=> array( "threat" ),
			6				=> array( "threat" ),
			7				=> array( "threat", "threat" ),
			8				=> array( "failure", "threat" ),
		),
		"proficiency"	=> array(
			1				=> array( NULL ),
			2				=> array( "success" ),
			3				=> array( "success" ),
			4				=> array( "success", "success" ),
			5				=> array( "success", "success" ),
			6				=> array( "advantage" ),
			7				=> array( "success", "advantage" ),
			8				=> array( "success", "advantage" ),
			9				=> array( "success", "advantage" ),
			10				=> array( "advantage", "advantage" ),
			11				=> array( "advantage", "advantage" ),
			12				=> array( "success", "triumph"),
		),
		"challenge"		=> array(
			1				=> array( NULL ),
			2				=> array( "failure" ),
			3				=> array( "failure" ),
			4				=> array( "failure", "failure" ),
			5				=> array( "failure", "failure" ),
			6				=> array( "threat" ),
			7				=> array( "threat" ),
			8				=> array( "failure", "threat" ),
			9				=> array( "failure", "threat" ),
			10				=> array( "threat", "threat" ),
			11				=> array( "threat", "threat" ),
			12				=> array( "failure", "despair"),
		),
		"Force"			=> array(
			1				=> array( "darkSideForcePoint" ),
			2				=> array( "darkSideForcePoint" ),
			3				=> array( "darkSideForcePoint" ),
			4				=> array( "darkSideForcePoint" ),
			5				=> array( "darkSideForcePoint" ),
			6				=> array( "darkSideForcePoint" ),
			7				=> array( "darkSideForcePoint", "darkSideForcePoint" ),
			8				=> array( "lightSideForcePoint" ),
			9				=> array( "lightSideForcePoint" ),
			10				=> array( "lightSideForcePoint", "lightSideForcePoint" ),
			11				=> array( "lightSideForcePoint", "lightSideForcePoint" ),
			12				=> array( "lightSideForcePoint", "lightSideForcePoint" ),
		),
	);
	/*
	 * Associate usernames to icon_urls
	*/
	$messengerCharacterArray = array(
		"NAME 1"							=> "http://placehold.it/512.png?text=NAME+1",
		"NAME 2"							=> "http://placehold.it/512.png?text=NAME+2",
	);