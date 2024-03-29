=== Settings API Class ===

Contributors: tareq1988, momo360modena
Donate link: http://tareq.wedevs.com/
Tags: settings, options, admin, class, settings-api
Requires at least: 3.5
Tested up to: 6.2.x
Stable tag: 0.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A PHP class wrapper for handling WordPress settings API. Gives a very handy way to build theme or plugins option panel.

== Description ==

= This plugin is for developers, not general users. =

This is mainly a PHP class that you can use to build your themes and plugins option panel. It's a wrapper class that uses
[Settings API](http://codex.wordpress.org/Settings_API) under the hood. All you need is to just pass the sections and fields
you need as an PHP array. And your options panel will be build automatically on the fly with a tiny PHP class.

This is mainly a demo plugin that uses the Settings API PHP class.

Visit the [Github](https://github.com/tareq1988/wordpress-settings-api-class) for the latest development snapshot.

= Usage Example =

A nice tutorial is presented can be found [here](http://tareq.wedevs.com/2012/06/wordpress-settings-api-php-class/) about how to use this PHP class

== Installation ==

1. Upload and install the plugin
1. You'll have a new options page under the 'Settings' area

== Frequently Asked Questions ==

= What this plugin for? =

It's mainly a plugin that demonstrates the Settings API PHP class

= Whats the facility? =

A plugin or theme developer can build their options panel with Settings API easily

= What is Settings API ? =

Settings API is a functionality from WordPress that helps developers to save their options data very easily and securely.
More about [Settings API](http://codex.wordpress.org/Settings_API).

== Screenshots ==

1. The options panel build on the fly using the PHP Class

== Changelog ==

= 0.5.O =

* Add PHP8.0 compat (thanks to @f2cmbeapi)

= 0.4.2 =

* Add possibility to make inline description (new desc_type field)

= 0.4.1 =

* Hide tabs navigation if only one registered

= 0.4 =

* Allow to specifiy custom title for tabs
* Somes changes on metabox field, autofill ID for each metabox

= 0.3 =

* Switch to classic tabs (remove JS)
* Use WP 3.5 media gallery uploader
* Allow to set label/description for checkbox
* Add metabox/html fields
* Cleanup code

= 0.1 =

* Initial release

== Upgrade Notice ==

Nothing for the moment.