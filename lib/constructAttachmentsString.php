<?php
	/*
	 * Construct the attachments string from the attachments array
	*/

	/*
	 * For temporary reference:
	 *
	 * // THIS DOESN'T WORK FOR SOME REASON
	 * $payloadArray['attachmentsArray']['mrkdwn_in'] = array( "pretext", "text", "fields" );
	 * 
	 * // THESE ARE USED (somewhere)
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
			if( is_array( $attachmentsArray ) ) {
				$payloadString = '"attachments": [ {';
				foreach( $attachmentsArray as $attachmentsKey => $attachmentsValue ) {
					if( is_array( $attachmentsValue ) ) {
						$payloadString = '"'.$attachmentsKey.'": [ ';
						foreach( $attachmentsValue as $valueKey => $valueValue ) {
							if($count > 0) {
								$commaDelimiter = ", ";
							}
							$payloadString .= $commaDelimiter.'"'.escapePayloadString($valueValue).'"';
							$count++;
						}
						$payloadString .= ' ]';
					} else {
						$payloadString .= '"'.$attachmentsKey.'": "'.escapePayloadString($attachmentsValue).'",';
					}
				}
				$payloadString .= '} ]';

				return $payloadString;
			} else {
				return NULL;
			}
		}
	}