<?php
	/*
	 * Process $_POST data from <form>
	 * Begins constructing the payload array to be turned into the payload string
	*/
	if (!function_exists('processMessage')) {
		function processMessage( $messengerCharacterArray ) {
			/*
			 * Populate channel from form
			*/
			if( isset($_POST[ 'channelInput' ]) ) {
				$payloadArray['channel'] = $_POST[ 'channelInput' ];
			} else {
				$payloadArray['channel'] = NULL;
			}

			/*
			 * Populate username from form
			*/
			if( array_key_exists( $_POST[ 'identityInput' ], $messengerCharacterArray ) ) {
				$payloadArray['username'] = $_POST[ 'identityInput' ];
				$payloadArray['icon_url'] = $messengerCharacterArray[ $_POST[ 'identityInput' ] ];
			} elseif( isset( $_POST[ 'usernameInput' ] ) || isset( $_POST[ 'iconURLInput' ] ) ) {
				if( isset( $_POST[ 'usernameInput' ] ) ) {
					$payloadArray['username'] = $_POST[ 'usernameInput' ];
				}
				if( isset( $_POST[ 'iconURLInput' ] ) ) {
					$payloadArray['icon_url'] = $_POST[ 'iconURLInput' ];
				}
			} else {
				$payloadArray['username'] = NULL;
				$payloadArray['icon_url'] = NULL;
			}
			/*
			 * Populate text from form
			*/
			if( isset( $_POST[ 'messageInput' ] ) ) {
				$payloadArray['text'] = $_POST[ 'messageInput' ];
			} else {
				$payloadArray['text'] = NULL;
			}

			$payloadArray[ 'success' ] = TRUE;
			return $payloadArray;
		}
	}