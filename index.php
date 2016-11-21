<?php
	/*
	 * Copyright (C) 2016 Ryan Shehee
	 *
	 * Author:		Ryan Shehee
	 * Version:		1.07
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

	/*
	 * Step 1:
	 * require_once all necessary files to process request
	 */
	$mainArray['requiredFiles'] = array(
		"lib/authenticatePostData.php",				// needed for slash commands: "Star Wars Roleplaying Dice Roller"
		"lib/constructPayloadString.php",			// universal
		"lib/constructRoleplayingDiceString.php",	// needed for "Star Wars Roleplaying Dice Roller"
		"lib/domainWebhookConfig.php",				// universal - settings
		"lib/escapePayloadString.php",				// universal
		"lib/logOutput.php",						// universal
		"lib/processMessage.php",					// needed for "In-character Messenger"
		"lib/processRoll.php",						// needed for "Star Wars Roleplaying Dice Roller"
		"lib/sendSlackWebhook.php",					// universal
	);
	foreach( $mainArray['requiredFiles'] as $requiredFile ) {
		if(file_exists($requiredFile)) {
			require_once($requiredFile);
		} else {
			echo "File not found: ".$requiredFile;
		}
	}
	/*
	 * Step 2:
	 * set $logFile and
	 * 
	 */
	if ( isset( $_GET['roll'] ) ) {
		$mainArray['logFile'] = "logs/rollerOutput.log";
		$mainArray['requestedProcess'] = "Star Wars Roleplaying Dice Roller";
	} else {
		$mainArray['logFile'] = "logs/messengerOutput.log";
		$mainArray['requestedProcess'] = "In-character Messenger";
	}

	/*
	 * Step 3:
	 * Determine if $_POST data is set
	 * and if so, match requestedProcess and 
	 * try to work with the $_POST data
	 */
	if( isset( $_POST ) && $mainArray['requestedProcess'] === "Star Wars Roleplaying Dice Roller" ) {
		$mainArray['postDataExists'] = TRUE;
		$mainArray[ 'sendWebhook' ] = TRUE;
	} elseif( isset( $_POST[ 'formSubmit' ] ) && $mainArray['requestedProcess'] === "In-character Messenger" ) {
		/*
		 * Step 3a:
		 * Determine if form button was pressed/clicked (formSubmit was submitted)...
		 * and if so, by what button
		 * then set state as appropriate
		 */
		$mainArray['postDataExists'] = TRUE;
		if( $_POST[ 'formSubmit' ] === "Submit" ) {
			$mainArray[ 'sendWebhook' ] = TRUE;
		} elseif( $_POST[ 'formSubmit' ] === "Advanced" ) {
			$mainArray[ 'displayAdvanced' ] = TRUE;
		} elseif( $_POST[ 'formSubmit' ] === "Basic" ) {
			$mainArray[ 'displayAdvanced' ] = NULL;
		}
		/*
		 * Step 3b:
		 * Infer displayAdvanced if usernameInput or iconURLInput are present when 'Submit' button pressed/clicked
		 */
		if( $_POST[ 'formSubmit' ] === "Submit" && ( isset( $_POST[ 'usernameInput' ] ) || isset( $_POST[ 'iconURLInput' ] ) ) ) {
				$mainArray[ 'displayAdvanced' ] = TRUE;
		}
	} else {
		$mainArray['postDataExists'] = NULL;
		$mainArray['displayAdvanced'] = NULL;
	}
	/*
	 * Step 4:
	 * EXECUTE!
	 * 
	 * 4a: If $_POST data--through slash command or website form--was submitted...
	 * 4b: Process $_POST data according to request type and...
	 * 4c: Send webhook
	 */
	if( $mainArray['postDataExists'] === TRUE ) {
		$mainArray['authenticationResult'] = authenticatePostData($domainWebhookSettings);
		// 4b: Process $_POST data according to request type
		if( $mainArray['requestedProcess'] === "Star Wars Roleplaying Dice Roller" && $mainArray['authenticationResult'] === TRUE ) {
			$payloadArray = processRoll($payloadArray,$diceDistributionArray);
			$mainArray['response_url'] = $_POST['response_url'];
		} elseif( $mainArray['requestedProcess'] === "Destiny Point Tracker" && $mainArray['authenticationResult'] === TRUE ) {
			$payloadArray = processDestiny($payloadArray,$domainWebhookSettings);
			$mainArray['response_url'] = $_POST['response_url'];
		} elseif( $mainArray['requestedProcess'] === "In-character Messenger" ) {
			$payloadArray = processMessage($payloadArray,$messengerCharacterArray);
			$mainArray['response_url'] = $domainWebhookSettings[ 'response_url' ];
		}
		// 4b cont: Process $payloadArray into $payloadString
		$payloadString = constructPayloadString($payloadArray);
		// 4c: Send webhook
		if( $mainArray[ 'sendWebhook' ] === TRUE && isset($payloadString) ) {
			/*
			 * Log payloadString
			 */
			$logResult = logOutput($payloadString,$mainArray['logFile']);
			/*
			 * Will return "ok" if all went as planned.
			 * Will return "invalid_payload" if the payload is...invalid.
			 * Will return "missing_text_or_fallback_or_attachments" if no text is set.
			 * Will return "channel_not_found" if it can't fin the channel
			 * (Response only visible if using webpage front end)
			 */
			$webhookResponse = sendSlackWebhook($payloadString,$mainArray['response_url']);
		}
	}

	/*
	 * Step 5:
	 * Display front end if page was not requested by slash command
	 */
	if ( $mainArray['requestedProcess'] === "In-character Messenger" ):
?>
<!DOCTYPE html>
<html lang="en">
<head>

	<!-- Basic Page Needs
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<meta charset="utf-8">
	<title>FFG SWRGP Slack IC Messenger</title>
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Mobile Specific Metas
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- FONT
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">

	<!-- CSS
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/skeleton.css">
	<link rel="stylesheet" href="../css/ffgswrpg.css">

	<!-- Favicon
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<link rel="icon" type="image/png" href="images/favicon.png">
</head>
<body>
	<!-- Primary Page Layout
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<div class="container">
		<div class="row">
			<h2 class="eote" style="margin-top:1em;">Slack In-Character Messenger</h2>
			<?php if($webhookResponse['result'] === "ok"): ?>
			<div class="row">
				<div class="twelve columns" style="background:#dff0d8;">
					<h5>Success</h5>
					<p>Webhook returned "<?=$webhookResponse['result'];?>".</p>
				</div>
			</div>
			<?php elseif (isset($webhookResponse['result'])): ?>
			<div class="row">
				<div class="twelve columns" style="background:#f2dede;">
					<h5>Failure</h5>
					<p>Webhook returned "<?=$webhookResponse['result'];?>".</p>
				</div>
			</div>
			<?php endif; ?>
			<form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
				<div class="row">
					<div class="<?echo(isset($mainArray[ 'displayAdvanced' ]))?"four":"six"?> columns">
						<label for="channelID">Channel selection</label>
						<select class="u-full-width" id="channelID" name="channelInput">
							<?php foreach( $domainWebhookSettings[ 'channelList' ] as $channelAlias => $channelName ) {
								if( $_POST[ 'channelInput' ] === $channelName ) {
									$channelSelect = " selected";
								} else {
									$channelSelect = NULL;
								}
								echo '<option value="'.htmlspecialchars($channelName).'"'.htmlspecialchars($channelSelect).'>'.htmlspecialchars($channelAlias).'</option>';
							}
							?>
						</select>
					</div>
					<?php if(isset($mainArray[ 'displayAdvanced' ])): ?>
					<div class="four columns">
						<label for="usernameID">Name</label>
						<input class="u-full-width" id="usernameID" type="text" placeholder="Unnamed NPC" name="usernameInput" <?echo(isset($payloadArray['username']))?'value="'.htmlspecialchars($payloadArray['username']).'"':NULL?>>
					</div>
					<div class="four columns">
						<label for="iconURLID">Icon URL</label>
						<input class="u-full-width" id="iconURLID" type="text" placeholder="http://wiki.talesofthephoenix.com/images/e/e7/IT-O.jpg" name="iconURLInput" <?echo(isset($payloadArray['icon_url']))?'value="'.htmlspecialchars($payloadArray['icon_url']).'"':NULL?>>
					</div>
					<?php else: ?>
					<div class="six columns">
						<label for="identityID">Identity selection</label>
						<select class="u-full-width" id="identityID" name="identityInput">
							<?php foreach( $messengerCharacterArray as $groupName => $groupArray ) {
								echo '<optgroup label="'.$groupName.'">';
									foreach( $groupArray as $characterName => $unusedValue ) {
										if( $_POST[ 'identityInput' ] === $characterName ) {
											$npcSelect = " selected";
										} else {
											$npcSelect = NULL;
										}
										echo '<option value="'.htmlspecialchars($characterName).'"'.htmlspecialchars($npcSelect).'>'.htmlspecialchars($characterName).'</option>';
									}
								echo '</optgroup>';
							}
							?>
						</select>
					</div>
					<?php endif; ?>
				</div>
				<label for="messageID">Message</label>
				<textarea autofocus class="u-full-width" cols="80" placeholder="..." id="messageID" maxlength="1000" name="messageInput" rows="12" style="height:12em;" ><?echo(isset($payloadArray['text']))?htmlspecialchars($payloadArray['text']):NULL?></textarea>
				<?php if(isset($mainArray[ 'displayAdvanced' ])): ?>
				<input class="button" name="formSubmit" type="submit" value="Basic">
				<?php else: ?>
				<input class="button" name="formSubmit" type="submit" value="Advanced">
				<?php endif; ?>
				<input class="button-primary" name="formSubmit" type="submit" value="Submit">
			</form>
			<!-- Always wrap checkbox and radio inputs in a label and use a <span class="label-body"> inside of it -->
			<!-- Note: The class .u-full-width is just a utility class shorthand for width: 100% -->
		</div>
	</div>

<!-- End Document
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
</body>
</html>
<?php
	/*
	 * Step 5 end:
	 */
	endif;
?>