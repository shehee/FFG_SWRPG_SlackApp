<?php
	/*
	 * Authenticate the $_POST data
	 * If it doesn't authenticate, tell us why
	*/
	if (!function_exists('authenticatePostData')) {
		function authenticatePostData($domainWebhookSettings) {
			if( $_POST['token'] !==  $domainWebhookSettings['token'] ) {
				die( "Authentication failed: Token mismatch." );
			} elseif( $_POST['team_id'] !==  $domainWebhookSettings['team_id'] ) {
				die( "Authentication failed: Team ID mismatch." );
			} elseif( $_POST['team_domain'] !== $domainWebhookSettings['team_domain'] ) {
				die( "Authentication failed: Team domain mismatch." );
			} else {
				return TRUE;
			}
		}
	}