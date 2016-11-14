# ffgswrpg-slack-app
**ffgswrpg-slack-app** stands for "Fantasy Flight Games Star Wars Roleplaying Game Slack App." This is an in-character messenger (for PCs or NPCs) and a **["Star Wars Roleplaying Dice"](https://www.fantasyflightgames.com/en/products/star-wars-edge-of-the-empire-beginner-game/)** dice roller for [Slack](https://slack.com/), written in [PHP](http://php.net/manual/en/intro-whatis.php), HTML, and CSS.

_Note:_ This is not a proper Slack "App." It is a cludge I built to have fun with my players. Furthermore, **I am a hobbyist, not a professional.** I am aware this source code is not very good; I am sure it is filled with security flaws and improper coding techniques. However, it works for my needs. I am providing it "as is" so that others can use it or modify it for their own games. See the disclaimer and license for further details.

## Requirements
While I tried to make these instructions as user-friendly as possible, some understanding of basic PHP, HTML, CSS, and the Slack API may be required.

To use this you will need:

1. a PHP server to run the source code, and 
2. a Slack Team to display the output.

You will also need to provide your own images for the **die types**, **die faces**, and **die results** (if you want the die roller to look decent in Slack). **_I do not provide these in an attempt to avoid copyright infringement._**
* _Note:_ To make my images and emojis, I used the "*[Dice Sticker Sheet](https://images-cdn.fantasyflightgames.com/ffg_content/StarWarsRPG/edge-of-the-empire/beta/support/SWR01_Dice%20Stickers%20(high-res).pdf)*" (pdf, 1.7 MB) available from the [FFG Product Archive](https://www.fantasyflightgames.com/en/more/product-document-archive/) and matched up the faces with the results found on Table 1-2: "Standard to Star Wars Roleplaying Dice Conversion," found in every Core Rulebook (see below). It took me a couple hours using [Inkscape](https://inkscape.org/en/) and [GIMP](https://www.gimp.org/).

Furthermore, the HTML front-end for the messenger was built using [Skeleton](http://getskeleton.com/): a "Responsive CSS Boilerplate" along with my own CSS that emulates the *Edge of the Empire Core Rulebook* style. These are not required.

I recommend using Skeleton, especially if you will want to access the messenger on a mobile device. To do so simply download the latest version and upload `normalize.css` and `skeleton.css` to the `css` directory.

***

## Messenger
The messenger is accessed through a front end: a **form** called by **index.php**. It will auto-generate the dropdown menus depending on the values of the `$domainWebhookSettings` (specifically the `$channelList` array) and `$messengerCharacterArray` variables located in the settings file, `lib/domainWebhookConfig.php`.

Once installed and setup the web interface will look similar to this:

![index.php payload generator](https://raw.githubusercontent.com/shehee/ffgswrpg-slack-app/master/img/index.php%20payload%20generator.png "Obi-Wan narrates his attempt to Influence a stormtrooper: The webhook is sent successfully.")

_Note:_ [Skeleton](http://getskeleton.com/) is used for the formatting and my own CSS accounts for the *Edge of the Empire* styling. **_These are not included._**

The incoming webhook will look similar to this:

![Slack incoming webhook.png](https://raw.githubusercontent.com/shehee/ffgswrpg-slack-app/master/img/Slack%20incoming%20webhook.png "Obi-Wan narrates his attempt to Influence a stormtrooper: The incoming webhook result.")

### Setup
To setup the messenger, first you will need to **upload the files to a PHP server** (and sort out any errors; if you've already set up the dice roller, you're fine). Then you will need to **create** or **log in to your Slack Team in a web browser**, then go to [https://api.slack.com/](https://api.slack.com/) and follow these steps:

1. Add a "Custom integration"
	1. Click the green **"Start building custom integrations"** button on the right.
	2. Scroll to the green **"Set up an incoming webhook"** button and click on it.
	3. Click the green **"Add configuration"** button on the left.
	4. Click the "Choose a channel..." dropdown menu and choose any channel.
		* _Note:_ The actual channel doesn't matter; it will be selected using the webpage dropdown/`$channelList` array.
	5. Click the (now) green **"Add Incoming Webhooks integration"** (if you agree to the [Slack API Terms of Service](https://shehee.slack.com/terms-of-service/api)).
	6. Copy the "Webhook URL" for use in the following step (2.1).
		* _Note:_ It will be formatted along the lines of: `https://hooks.slack.com/services/T########/#########/########################`
	7. Customize the rest of the options on the page however you see fit.
2. Edit the settings file, `lib/domainWebhookConfig.php`.
	1. Edit the `response_url` **value** of the `$domainWebhookSettings` array: change the **value** of this **key** to the "Webhook URL" you copied in step 1.6.
	2. Manually add any channels you want to show up in the form dropdown menu to the `channelList` array.
		1. The **key** will be what is displayed in the **dropdown**, 
		2. the **value** is the actual **channel name**.
		* _Note:_ Keep them the same if you want to avoid a hassle.
3. Edit the `$messengerCharacterArray` to include a list of all the PCs or NPCs you will be selecting from in the form.
	1. The **key** will be the name of the character displayed in Slack, 
	2. the **value** should be the URL of an image associated with the character (a placeholder is used by default).

### Use
To use the messenger, point your web browser to "index.php" on the PHP server you uploaded the files to.

1. Select the channel you want to post to.
2. Select the character you want to appear as.
3. Write the message in the big text box.
4. Hit submit.

***

## Dice Roller
The dice roller does not have a front end, like the messenger. Instead, it is accessed through a **"slash command"** inside Slack itself. It takes a bit more setup than the messenger because of the images involved.

Once installed and setup the dice roller can be called by typing something similar to the following (for the Star Wars Roleplaying Dice):

![Star Wars Roleplaying Dice slash command result.png](https://raw.githubusercontent.com/shehee/ffgswrpg-slack-app/master/img/Star%20Wars%20Roleplaying%20Dice%20slash%20command.png "Obi-Wan attempts to Influence a stormtrooper by rolling his opposed Discipline check and Influence Force power dice")

or (for the critical and Morality checks):

![critical injury slash command.png](https://raw.githubusercontent.com/shehee/ffgswrpg-slack-app/master/img/critical%20injury%20slash%20command.png "A player rolls a critical and adds twenty to the result.")

The Star Wars Roleplaying Dice slash command result will look similar to this:

![Star Wars Roleplaying Dice slash command result.png](https://raw.githubusercontent.com/shehee/ffgswrpg-slack-app/master/img/Star%20Wars%20Roleplaying%20Dice%20slash%20command%20result.png "Obi-Wan successfully Influences a stormtrooper")

_Note:_ By default, the dice roller sends a string of various dice properties that is formatted to take advantage of the ability to display emojis on the client side. You will need to provide your own images for the **die types**, **die faces**, and **die results**. **_I do not provide these in an attempt to avoid copyright infringement._** (This also saves bandwidth after the initial load.) **_These are not included._**

The critical and Morality check slash command results will look similar to this:

![critical injury slash command result.png](https://raw.githubusercontent.com/shehee/ffgswrpg-slack-app/master/img/critical%20injury%20slash%20command%20result.png "The result of the players critical roll.")

### Setup
To setup the dice roller, first you will need to **upload the files to a PHP server** (and sort out any errors; if you've already set up the messenger, you're fine). Then you will need to create or log in to your Slack Team in a web browser, then go to [https://api.slack.com/](https://api.slack.com/) and follow these steps:

1. Add a "Custom integration"
	1. Click the green **"Start building custom integrations"** button on the right.
	2. Scroll to the green **"Set up a slash command"** button and click on it.
	3. Click the green **"Add configuration"** button on the left.
	4. Type **"/roll"** into the text box next to "Choose a Command."
		* *_Note:_* This can be anything but the **defaults assume "/roll"**.
	5. Click the (now) green **"Add Slash Command Integration"** (if you agree to the [Slack API Terms of Service](https://shehee.slack.com/terms-of-service/api)).
	6. Scroll to **"URL"** under the *"Integration Settings"* section and add the full URL of the PHP server where `index.php` is hosted, followed by `?roll`.
		* **Example:** `https://example.com/index.php?roll`
	7. Copy the **"Token"** under the *"Integration Settings"* section for use in the following step (3.1).
		* _Note:_ It will be formatted along the lines of: `########################`
	8. Customize the rest of the options on the page however you see fit.
	9. Click the green **"Save Integration"** button toward the bottom of the page.
2. Get your Team ID.
	1. Go to [https://api.slack.com/methods/team.info/test](https://api.slack.com/methods/team.info/test) (or use cURL).
	2. Choose your Team from the `Value` dropdown on the right.
		* _Note:_ If it says (no token) next to your Team name, you will need to generate a token for development: see https://get.slack.help/hc/en-us/articles/215770388-Create-and-regenerate-API-tokens for steps on how to do that.
	3. Click the green **"Test Method"** button.
	4. In the response, find the line `"id": "T########",` (the #'s will be a unique alphanumeric sequence)
		* _Note:_ It will be formatted along the lines of: `T########`
3. Edit the settings file, `lib/domainWebhookConfig.php`.
	1. Edit the `token` **key** of the `$domainWebhookSettings` array: change the **value** of this **key** to the **"Token"** you copied in step 1.7.
	2. Edit the `team_id` **key** of the `$domainWebhookSettings` array: change the **value** of this **key** to the **"id"** you copied in step 2.4.
	3. Edit the `team_domain` **key** of the `$domainWebhookSettings` array: change the **value** of this **key** to the **domain** of your Slack Team.
		* **Example:** In the URL `https://example.slack.com/`, the domain is `example`.
4. Add your Emojis
	1. Go to your Team's **"home"** URL.
		* **Example:** `https://example.slack.com/home`; replacing `example` with your Team's domain
	2. Click the **"Customize Slack"** option toward the middle of the page (or go to `https://example.slack.com/customize/emoji`; replacing `example` with your Team's domain)
	3. To upload an emoji you will need to type the name of the emoji in the **"1) Choose a name:"** text box, then click the **"choose file"** button under **"2) Choose an emoji:"**
		1. For **each image** in the following list you will need to upload an emoji (or the results you get in Slack will look weird).
			* _Note:_ Only use the name provided, don't add colons to the name.
			* *Note 2:* The die face numbering correlates to Table 1-2: "Standard to Star Wars Roleplaying Dice Conversion," found in *Edge of the Empire Core Rulebook*, Page 12; *Age of Rebellion Core Rulebook*, Page 18; or *Force and Destiny Core Rulebook*, Page 18.
			* *Note 3:* If a numbered die face is not listed here, make an alias to the next lower numbered image. **Example:** `ability3` should alias to `ability2` and `difficulty6` should alias to `difficulty4`.
			* *Die types*
				* ability
				* boost
				* challenge
				* difficulty
				* force
				* proficiency
				* setback
			* *Die faces*
				* ability1
				* ability2
				* ability4
				* ability5
				* ability7
				* ability8
				* boost1
				* boost3
				* boost4
				* boost5
				* boost6
				* challenge1
				* challenge2
				* challenge4
				* challenge6
				* challenge8
				* challenge10
				* challenge12
				* difficulty1
				* difficulty2
				* difficulty3
				* difficulty4
				* difficulty7
				* difficulty8
				* force1
				* force7
				* force8
				* force10
				* proficiency1
				* proficiency2
				* proficiency4
				* proficiency6
				* proficiency7
				* proficiency10
				* proficiency12
				* setback1
				* setback3
				* setback5
			* *Die results*
				* advantage
				* darkside
				* despair
				* failure
				* lightside
				* success
				* threat
				* triumph1

### Use
To use the dice roller, simply type a message inside the Slack application or web interface using one of the following formats:

1. To **roll** the **"Star Wars Roleplaying Dice"**, type `/roll abcdfgkprswy`, or some combination thereof.
	1. **Type** (and **color**) **abbreviations** are as follow:
		* [**A**]bility dice are [**G**]reen
		* proficienc[Y] dice are [**Y**]ellow
		* [**B**]oost dice are [**B**]lue
		* [**D**]ifficulty dice are [**P**]urple
		* [**C**]hallenge dice are [**R**]ed
		* [**S**]etback dice are blac[**K**]
		* [**F**]orce dice are [**W**]hite
	2. For **multiple dice** of the same type, simply repeat the number of letters required
		* **Example:** To roll 2 [A]bility dice and 2 [D]ifficulty, type `/roll aadd`, `/roll ggpp`, or any combination thereof (`/roll agdp` would roll the same thing).
2. To **roll** a **critical**, type `/roll 1d100`.
	1. Previous criticals can be added by typing `/roll 1d100+10`, where `10` is a multiple of the number of criticals already sustained.
3. To **roll 1d10** at the end of a session to determine **Morality**, type `/roll 1d10`.

***

##### Terms and Conditions
Last updated: 2016-11-12

Please read these Terms and Conditions ("Terms") carefully before using the ffgswrpg-slack-app source code. Your access to and use of the ffgswrpg-slack-app source code is conditioned on your acceptance of and compliance with these Terms. These Terms apply to all visitors, users and others who access or use the ffgswrpg-slack-app source code. By accessing or using the ffgswrpg-slack-app source code you agree to be bound by these Terms. If you disagree with any part of the terms then you may not access the ffgswrpg-slack-app source code.

###### Disclaimer
The **ffgswrpg-slack-app source code** is provided "as is" without warranty of any kind, either expressed or implied and as such is to be used at your own risk. Ryan Shehee ("Author") has no control over, and assumes no responsibility for, the content, privacy policies, or practices of any third party web sites or services. By using this source code you acknowledge and agree that the Author shall not be responsible or liable, directly or indirectly, for any injury, damage, loss, expense, accident, delay, or other wrongful act caused or alleged to be caused by any party using this source code or in connection with use of or reliance on any such content, goods or services available on or through any such web sites or services.

The Author reserves the right, at their sole discretion, to modify or replace these Terms at any time. If a revision is material the Author will try to provide at least 15 days' notice prior to any new terms taking effect. What constitutes a material change will be determined at the Author's sole discretion.

###### License
The **ffgswrpg-slack-app source code** is licensed under the GNU General Public License Version 3 (GNU GPLv3):

	Copyright (C) 2016 Ryan Shehee

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.