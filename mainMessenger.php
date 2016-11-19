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
	 *
	 * Purpose:
	 * --------
	 * Determine if form was submitted...
	 * and if so, by what button
	 * then set state as appropriate
	 */
	if( isset( $_POST[ 'formSubmit' ] ) ) {
		$formValues[ 'submitState' ] = TRUE;
		if( $_POST[ 'formSubmit' ] === "Submit" ) {
			$formValues[ 'sendWebhook' ] = TRUE;
		} elseif( $_POST[ 'formSubmit' ] === "Advanced" ) {
			$formValues[ 'displayAdvanced' ] = TRUE;
		} elseif( $_POST[ 'formSubmit' ] === "Basic" ) {
			$formValues[ 'displayAdvanced' ] = NULL;
		}
		if( $_POST[ 'formSubmit' ] === "Submit" && ( isset( $_POST[ 'usernameInput' ] ) || isset( $_POST[ 'iconURLInput' ] ) ) ) {
				$formValues[ 'displayAdvanced' ] = TRUE;
		}
	} else {
		$formValues[ 'submitState' ] = NULL;
		$formValues[ 'displayAdvanced' ] = NULL;
	}

	/*
	 * EXECUTE!
	 * 
	 * IF form was submitted...
	 * Process form and...
	 * Send webhook
	 */
	if( $formValues[ 'submitState' ] === TRUE ) {
		$payloadArray = processMessage( $messengerCharacterArray );
		$payloadString = constructPayloadString( $payloadArray, $messageAttachmentsArray );
		if( $formValues[ 'sendWebhook' ] === TRUE && isset( $payloadString ) ) {
			/*
			 * Log payloadString
			 */
			$logResult = logOutput( $payloadString, $logFile );
			/*
			 * Will return "ok" if all went as planned.
			 * Will return "invalid_payload" if the payload is...invalid.
			 * Will return "missing_text_or_fallback_or_attachments" if no text is set.
			 * Will return "channel_not_found" if it can't fin the channel
			 */
			$webhookResponse = sendSlackWebhook( $payloadString, $domainWebhookSettings[ 'response_url' ] );
		}
	}
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
					<div class="<?echo(isset($formValues[ 'displayAdvanced' ]))?"four":"six"?> columns">
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
					<?php if(isset($formValues[ 'displayAdvanced' ])): ?>
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
				<?php if(isset($formValues[ 'displayAdvanced' ])): ?>
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
