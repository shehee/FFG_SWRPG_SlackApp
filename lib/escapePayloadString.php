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
	 * Escape the payload string provided so it doesn't throw an error
	 * The only characters that have trouble by default are \ and "
	 */
	if (!function_exists('escapePayloadString')) {
		function escapePayloadString($payloadString) {
			$payloadTextSearch  = array('\\', '"');
			$payloadTextReplace = array('\\\\', '\"');
			return str_replace($payloadTextSearch, $payloadTextReplace, $payloadString);
		}
	}