<?php
	/*
	 * Copyright (C) 2016 Ryan Shehee
	 *
	 * Author:		Ryan Shehee
	 * Version:		1.03
	 * Date:		2016-11-19
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
	 */
	$requiredFiles = array(
		"lib/authenticatePostData.php",				// needed for slash commands: mainRoller.php & mainTracker.php
		"lib/constructAttachmentsString.php",		// universal
		"lib/constructPayloadString.php",			// universal
		"lib/constructRoleplayingDiceString.php",	// needed for mainRoller.php
		"lib/domainWebhookConfig.php",				// universal - settings
		"lib/escapePayloadString.php",				// universal
		"lib/logOutput.php",						// universal
		"lib/processMessage.php",					// needed for mainMessager.php
		"lib/processRoll.php",						// needed for mainRoller.php
		"lib/sendSlackWebhook.php",					// universal
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