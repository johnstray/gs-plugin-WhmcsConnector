# WHMCS API Connector for GetSimple CMS

This GetSimple CMS plugin allows you to pull information from a WHMCS installation via it's API that you can then use to display on your website.

### Compatibility
- GetSimple CMS versions: v3.3.x, v3.4.0-alpha
- WHMCS versions: v8.0.0+ (v7.x.x not tested, but theoretically should work)

### Installation
- Download the latest version of this plugin from the Releases section of this repository, or from GetSimple Extend.
- Upload `gs-whmcs.php` & `gs-whmcs (folder)` to the `plugins` directory of your GetSimple CMS installation.
- Login to your WHMCS admin area and create an API credential with the required permissions (see below).
- Login to your GetSimple CMS admin area and go to the plugin's settings page and add your API credentials.
- Modify the pages of your website to include the relevant tags to show information (see Wiki for information).

The Wiki contains information on how you can use and display information on your website. You will need to create theme template files which act as the display templates for each of the different supported functions.

### Supported API Functionality
- __GetAnnouncements__ : Show a list of or an individual announcement
- More to come...

### License
This project is licensed under the terms of the GNU General Public License v3 (or later). See LICENCE for more information.

### Repository Branches
- `main` Contains the latest stable and tested code, used as the basis for a version release. This should be the same as the latest released version of the plugin.
- `development` Contains the latest updates to the plugin that are currently undergoing development and testing. This will be merged to `main` when its stable enough and ready for release.

__Note:__ While this plugin is under initial development, the `development` branch will not be available as I will be pushing directly to the master.

### Contributing
I am more than happy for you to contribute to this project and help to improve it. Translators are especially important here. See CONTRIBUTING.md for information on how you can contribute to this project.

__Note:__ I'm not currently accepting contributions. Contributing will be possible from just before the initial release (to allow for translators to do their thing), and from then onwards.

