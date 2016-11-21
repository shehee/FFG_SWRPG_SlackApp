<?php
	/*
	 * Copyright (C) 2016 Ryan Shehee
	 *
	 * Author:		Ryan Shehee
	 * Version:		1.06
	 * Date:		2016-11-16
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
	 * Slack Webhook settings; replace these settings with your own
	 */
/* BEGIN PRIVATE DATA */
	$domainWebhookSettings = array(
		"response_url"		=> "https://hooks.slack.com/services/TXXXXXXXX/BXXXXXXXX/XXXXXXXXXXXXXXXXXXXXXXXX",
		"channelList"		=> array(
								"#general" 	=> "#general",
								"#random" 	=> "#random",
							),
		"roll_token"		=> "XXXXXXXXXXXXXXXXXXXXXXXX",
		"team_id"			=> "TXXXXXXXX",
		"team_domain"		=> "_________",
		"owner_id"			=> "UXXXXXXXX",		// may be used in the future
	);
	/*
	 * Associate usernames to icon_urls
	 */
	$messengerCharacterArray = array(
		"GROUP 1"				=>	array( 
			"NAME 1"							=> "http://placehold.it/512.png?text=NAME+1",
			"NAME 2"							=> "http://placehold.it/512.png?text=NAME+2",
		), 
		"GROUP 2"				=>	array( 
			"NAME 3"							=> "http://placehold.it/512.png?text=NAME+3",
			"NAME 4"							=> "http://placehold.it/512.png?text=NAME+4",
		), 
	);
/* END PRIVATE DATA */
	/*
	 * $payloadArray
	 * These are defaults that will be overwritten later probably
	 */
	$payloadArray = array(
		"response_type"		=> "in_channel",
#		"channel"			=> "#ic",
		"username"			=> "Star Wars Roleplayingbot",
#		"icon_emoji"		=> ":speech_balloon:",
#		"text"				=> "Text missing.",
		"attachments"		=> array(
			"fallback"			=> "Payload attachment for SWRPG NPC Messenger & Dice Roller",
			"color"				=> "#761213",
		),
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