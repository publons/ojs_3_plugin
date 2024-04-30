# OJS 3.x Publons Reviewer Recognition plugin

Developed and maintained by: Publons Ltd.

### About
This plugin provides the ability to send and publish reviews to Web of Science Researcher Profiles (formerly known as Publons, https://webofscience.com).

### License
This plugin is licensed under the GNU General Public License v3.

See the accompanying OJS file docs/COPYING for the complete terms of this license.

### System Requirements
- OJS 3.1, 3.2, or early versions of 3.3 (there are known issues with 3.3.0-16, plugin doesn't currently work with this version)
- PHP 5.6 or greater.
- CURL support for PHP.
- ZipArchive support for PHP.

### Installation
To install the plugin:
 - Download the plugin file `publons.tar.gz` from https://github.com/publons/ojs_3_plugin/releases
 - On your OJS site go to Settings > Website > Plugins > Upload a New Plugin,
   select the publons.tar.gz file you downloaded  and click "Save"
 - Enable the plugin by going to:  Settings > Website > Plugins > Generic Plugins and ticking "ENABLE" for the "Publons Plugin"
 - Set up correct credentials to post reviews to Publons by going to Settings > Website > Plugins > Generic Plugins and click “CONNECTION” under "Publons Plugin"
   - Enter the Authorization Token of the Publons user who has API access to Publons. Authorization Token can be found here: https://publons.com/api/v2 (note: you need to be logged in to see this).
   - Enter the Journal Token provided by Publons
   - __Optional__. Add the link to your journal landing page on Publons so users can find more info about this.

### Usage
For the plugin to work correctly the journal should be an official partner of our Reviewer Recognition Service (please see information about purchasing this service [here](https://publons.com/benefits/publishers)) and be registered at https://publons.com. Then the corresponding registration data should be entered in the appropriate fields on the plugin page "Connection": Settings > Website > Plugins > Generic Plugins > Publons.


When the plugin is enabled, a button “Send your review to Publons” will be present on "Completed" tab after the reviewer has submitted their review. After the reviewer has clicked on this button and confirmed they want to send their review to Publons, the review data is sent to Publons automatically and reviewer receives an invitation to claim it (or it is automatically added if reviewer has profile with Publons and opted in to automatically add reviews from partnered journals).
The Publons website certifies only the fact the reviewer has completed peer review for the current journal. The text of the review can be disclosed on Publons website only after publication of the article and if both the publication author and journal allow it. To disclose the text of the review, the reviewer should input the DOI of the published article on Publons.

### Contact
For enquiries regarding usage, support, bugfixes, or comments please email:
reviewservices@clarivate.com

### OJS 2 compatibility 
For an OJS 2 compatible version of the plugin please visit:
https://github.com/publons/ojs_2_plugin
