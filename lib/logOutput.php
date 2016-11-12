<?php
	/*
	 * Copyright (C) 2016 Ryan Shehee
	 *
	 * Author:		Ryan Shehee
	 * Version:		1.00
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
	 * Function for logging output
	 * Not for error reporting:
	 * In case of failure on some level, 
	 * there's a chance to get it back from the logs
	 * You don't want a well written post lost,
	 * or a crucial roll wasted
	 */
	if (!function_exists('logOutput')) {
		function logOutput( $payloadString, $logFile ) {
			// Open the file to get existing content
			$currentLogContents = file_get_contents($logFile);
			// Append a new person to the file
			$currentLogContents .= date("r").":";
			$currentLogContents .= "\r\n";
			$currentLogContents .= "\t".$payloadString;
			$currentLogContents .= "\r\n\r\n";
			// Write the contents back to the file
			file_put_contents($logFile, $currentLogContents);
		}
	}