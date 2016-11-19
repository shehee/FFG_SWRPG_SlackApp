<?php
	/*
	 * Copyright (C) 2016 Ryan Shehee
	 *
	 * Author:		Ryan Shehee
	 * Version:		1.05
	 * Date:		2016-11-04
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
	 * Process $_POST data from <form>
	 * Begins constructing the payload array to be turned into the payload string
	 */
	if (!function_exists('processMessage')) {
		function processMessage( $payloadArray, $messengerCharacterArray ) {
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
			$ImgURLArray = array_column($messengerCharacterArray,$_POST[ 'identityInput' ]);
			if( isset( $ImgURLArray ) ) {
				$payloadArray['username'] = $_POST[ 'identityInput' ];
				$payloadArray['icon_url'] = $ImgURLArray[0];
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