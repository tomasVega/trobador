Trobador - Translation Memory web service
=========================================

What  is this!
--------------

Trobador is a web service for managing an online translation memory web service. It allows to track translation memories in TMX format for multiple projects, with multiple versions. It allows to specify users, groups and privileges by projects.
Trobador exports an REST web service where you can export the translation memories with TMX format for consuming as a content source.

It also features a Gtranslator plugin that shows translations for a given string by selecting the project you are translating.

Install it!
-----------

### WebService ###
1. Copy it in your public web folder
2. Install the sql schema in your SQL server.
3. Tune the application/configs/application.ini with your database connection parameters

### Gtranslator plugin ###
1. Drop the gtranslator-plugin folder into `.local/share/gtranslator/plugins` within you user folder.
2. Start Gtranslator, go to the Preferences panel and activate the plugin.

Dependencies
------------
This library only depends on PHP 5.3, you have to use namespaces and some other goodies of 5.3 version.

LICENSE
-------
This software is licensed by the MIT License. If you want to read the complete text for the license please go to the MIT-LICENSE file in the root of this project.

And... what else?
-----------------
If you find a bug or want to suggest a new video service, please let us know in [a ticket](http://github.com/frandieguez/trobador/issues).

Thanks!!
