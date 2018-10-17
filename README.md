# OJS3 Publons Plugin

For OJS 2 Plugin please visit https://github.com/publons/ojs

Version: 1.0

Developed and maintained by: Publons Ltd.

### About
This plugin provides the ability to send and publish reviews to Publons (https://publons.com).

### License
This plugin is licensed under the GNU General Public License v3.

See the accompanying OJS file docs/COPYING for the complete terms of this license.

### System Requirements
- OJS 3.1
- PHP 5.6 or greater.
- CURL support for PHP.
- ZipArchive support for PHP.

### Installation
To install the plugin:
 - Download the plugin file `publons.tar.gz` from https://github.com/publons/ojs_3/releases/latest
 - On your OJS site go to Settings > Website > Plugins > Upload a New Plugin,
   select the publons.tar.gz file you downloaded  and click "Save"
 - Enable the plugin by going to:  Settings > Website > Plugins > Generic Plugins and ticking "ENABLE" for the "Publons Plugin"
 - Set up correct credentials to post reviews to Publons by going to Settings > Website > Plugins > Generic Plugins and click “CONNECTION” under "Publons Plugin"
   - Enter the username and the password of the Publons user who has API access to Publons so that the plugin can retrieve the authorisation token required.
   - Enter the API key of the journal found on the Publons partner dashboard under 'Integrations'.
   - __Optional__. Add the link to your journal landing page on Publons so users can find more info about this.

### Usage
For the plugin to work correctly the journal should be registered at http://publons.com. (See https://publons.com/partner/info/ for more info). Then the corresponding registration data should be entered in the appropriate fields on the plugin page "Connection": Settings > Website > Plugins > Generic Plugins > Publons.

When the plugin is enabled, a button “Publish my review on Publons” will be present on all submission pages after the reviewer has submitted their review. After the reviewer has clicked on this button and confirmed they want to send their review to Publons, the review data is sent to Publons automatically and reviewer receives an invitation to claim it (or it is automatically added if reviewer has profile with Publons and opted in to automatically add reviews from partnered journals).
The Publons website certifies only the fact the reviewer has completed peer review for the current journal. The text of the review can be disclosed on Publons website only after publication of the article and if both the publication author and journal allow it. To disclose the text of the review, the reviewer should input the DOI of the published article on Publons.

### Contact/Support
Please email us for support, bugfixes or comments.

Email: <ojs@publons.com>

### Version History
- 1.0 - Initial Release
