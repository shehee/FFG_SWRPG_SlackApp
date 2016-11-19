<?php
	/*
	 * Copyright (C) 2016 Ryan Shehee
	 *
	 * Author:		Ryan Shehee
	 * Version:		1.02
	 * Date:		2016-11-18
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
	 * Construct the attachments string from the attachments array
	 */

	/*
	 * For temporary reference:
	 *
	 * // THESE ARE USED (somewhere)
	 * $payloadArray['attachmentsArray']['mrkdwn_in'] = array( "pretext", "text", "fields" );
	 * $payloadArray['attachmentsArray']['fallback'] = $_POST['text'];
	 * $payloadArray['attachmentsArray']['color'] = "#761213";
	 * $payloadArray['attachmentsArray']['pretext'] = NULL;
	 * $payloadArray['attachmentsArray']['title'] = NULL;
	 * $payloadArray['attachmentsArray']['text'] = NULL;
	 * $payloadArray['attachmentsArray']['footer'] = NULL;
	 * 
	 * // THESE CAN BE USED (somewhere)
	 * $payloadArray['attachmentsArray']['ts'] = time();
	 * 
	 * // UNUSED
	 * $payloadArray['attachmentsArray']['author_name'] = $_POST['user_name'];
	 * $payloadArray['attachmentsArray']['author_link'] = "https://".$_POST['team_domain'].".slack.com/team/".$_POST['user_name'];
	 * $payloadArray['attachmentsArray']['author_icon'] = NULL;
	 * $payloadArray['attachmentsArray']['title_link'] = NULL;
	 * $payloadArray['attachmentsArray']['image_url'] = NULL;
	 * $payloadArray['attachmentsArray']['thumb_url'] = NULL;
	 * $payloadArray['attachmentsArray']['footer_icon'] = NULL;
	*/

	if (!function_exists('constructAttachmentsString')) {
		function constructAttachmentsString($attachmentsArray) {
			if( is_array($attachmentsArray) ) {
				$payloadString .= '"attachments":[{';
				foreach( $attachmentsArray as $attachmentsKey => $attachmentsValue ) {
					if( is_array( $attachmentsValue ) ) {
						$payloadString .= $attachmentsArray['delimiter'][0].'"'.$attachmentsKey.'":[';
						foreach( $attachmentsValue as $valueValue ) {
							$payloadString .= $attachmentsArray['delimiter'][1].'"'.escapePayloadString($valueValue).'"';
							$attachmentsArray['delimiter'][1] = ",";
						}
						$payloadString .= ']';
					} else {
						$payloadString .= $attachmentsArray['delimiter'][0].'"'.$attachmentsKey.'": "'.escapePayloadString($attachmentsValue).'"';
					}
					$attachmentsArray['delimiter'][0] = ",";
				}
				$payloadString .= '}]';

				return $payloadString;
			} else {
				return NULL;
			}
		}
	}