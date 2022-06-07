<h1 align="center">
	<img src="http://get-simple.info/data/uploads/getsimple-logo-2.png" style="height:48px" /><br />
	WHMCS API Connector
</h1>
<h6 align="center">Advanced User Management features for GetSimple CMS</h6>

<!-- This is intentional to create blank space -->
<p>&nbsp;</p>

<p align="center">
	<img src="https://img.shields.io/github/v/release/johnstray/gs-plugin-WhmcsConnector?label=latest%20release" alt="Latest release version" />
	<img src="https://img.shields.io/github/downloads/johnstray/gs-plugin-WhmcsConnector/total" alt="Total GitHub release downloads" />
	<img src="https://img.shields.io/github/license/johnstray/gs-plugin-WhmcsConnector" alt="License" />
	<img src="https://img.shields.io/github/issues-raw/johnstray/gs-plugin-WhmcsConnector?logo=github" alt="GitHub open issues" />
	<img src="https://img.shields.io/github/last-commit/johnstray/gs-plugin-WhmcsConnector?logo=github" alt="GitHub last commit" />
</p>

<p align="center">
	<a href="#about">About</a> &nbsp;&nbsp;&bull;&nbsp;&nbsp;
	<a href="#installation-and-usage">Installation and Usage</a> &nbsp;&nbsp;&bull;&nbsp;&nbsp;
	<a href="#contributions">Contributions</a> &nbsp;&nbsp;&bull;&nbsp;&nbsp;
	<a href="supported-languages">Supported Languages</a>
</p>

<!-- This is intentional to create blank space -->
<p>&nbsp;</p>

## About
This GetSimple CMS plugin allows you to pull information from a WHMCS installation via it's API that you can then use to display on your website.

#### Compatibility
- GetSimple CMS versions: v3.3.x, v3.4.0-alpha
- WHMCS versions: v8.0.0+ (v7.x.x not tested, but theoretically should work)

## Installation
- Download the latest version of this plugin from the Releases section of this repository, or from GetSimple Extend.
- Upload `gs-whmcs.php` & `gs-whmcs (folder)` to the `plugins` directory of your GetSimple CMS installation.
- Login to your WHMCS admin area and create an API credential with the required permissions (see below).
- Login to your GetSimple CMS admin area and go to the plugin's settings page and add your API credentials.
- Modify the pages of your website to include the relevant tags to show information (see Wiki for information).

The Wiki contains information on how you can use and display information on your website. You will need to create theme template files which act as the display templates for each of the different supported functions.

### Supported API Functionality
- __GetAnnouncements__ : Show a list of or an individual announcement
- More to come...

## License
This project is licensed under the terms of the GNU General Public Licence v3 (or later).

This program comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistrbute it and monify it under certain conditions. See [LICENCE](LICENCE) for details.

## Contributions
I am more than happy for you to contribute to this project and help to improve it. Translators are especially important here. See CONTRIBUTING.md for information on how you can contribute to this project.

### Repository Branches
- `main` Contains the latest stable and tested code, used as the basis for a version release. This should be the same as the latest released version of the plugin.
- `development` Contains the latest updates to the plugin that are currently undergoing development and testing. This will be merged to `main` when its stable enough and ready for release.<br>
  <small>__Note:__ While this plugin is under initial development, the `development` branch will not be available as I will be pushing directly to the master.</small>

__Note:__ I'm not currently accepting contributions. Contributing will be possible from just before the initial release (to allow for translators to do their thing), and from then onwards.

