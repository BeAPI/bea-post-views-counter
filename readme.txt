=== BEA Post Views Counter ===
Contributors: momo360modena
Donate link: http://beapi.fr/donate/
Tags: counter, hits, postviews, views, count, popular, popular post, stats, view, views, widget
Requires at least: 3.1
Tested up to: 3.5.1
Stable tag: 0.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables you to display how many times a post, page and any post type had been viewed. Designed for high-traffic sites, performance, security & scalability.

== Description ==

Counters are available at several intervals, current day, day -1, current week, week -1, current month, -1 month, current year, year -1 and total off course!

This plugin offers various features like a widget that lists the most popular content, a shortcode display counters.

You can sort the content by popularity with the class WP_Query and query_posts function on the time interval of your choice.

You can choose to include or not connected visitors, site administrators, excluding robots and why not ban an IP address.

Finally, this plugin offers a unique feature that will delight blog owners with a lot of traffic, it is possible to have a visitor counter, asynchronous ajax, but is exclusively developed in PHP. This mode does not require any installation on your modifiction and is 10 times more efficient than the traditional version of AJAX.

In bulk :

* 3 integration : inline, ajax, ajax full PHP (into an addon)
* Data save on custom table (better performance compared to the meta table)
* Developped with WP_DEBUG to TRUE
* POO, MVC
* Commented, i18n
* Import data from WP-PostViews / BAW Post View Counter

Plugin written with poetry, no ads, no viruses, no spyware.

== Installation ==

1. Upload `bea-post-views-counter` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Usage ==

=== Functions ===

the_post_views_counter()

Display the post view counter

get_the_post_views_counter()

Return the post view counter (string)


=== Shortcode ===

[counter]
This content has been viewed 54 times in total

[counter time="month" after=" times in current month"]
This content has been viewed 54 times in current month

[counter time="day" after=" times in current day"]
This content has been viewed 6 times in current day

== Frequently asked questions ==

= Are what plugin pollutes the WordPress interface with ads and donation links ? =

No, this plugin written with poetry, no ads, no viruses, no spyware.

= Why this plugin create a custom table ? =

This was not an easy decision.

On one side, you can enjoy the meta API, it retains the basic scheme. That's less code to maintain, but many more lines in BDD.

For each content, 1 line with custom table or 10 lines with the meta table.
This last is already solicited by countless plugins ca seems to me a better solution to choose to create a table.

= Are it is possible to have more detailed stats ? or by day ? =

No, this is a plug-in counters views. Not a plugin web statistics. 
If you need more stats, and many take a dedicated tool like Piwik (open-source) or Google Analytics/WordPress.com (hosted).

If your site does not generate too much traffic, you can also a WordPress plugin ... like WP SlimStat

== Screenshots ==

1. Plugin settings
2. Shortcode
2. Widget

== Changelog ==

* Version 0.5
	* Move full PHP implementation into an addon (https://github.com/herewithme/bea-post-views-counter-fullphp-addon)
* Version 0.4
	* Minor changes on pure PHP counter
* Version 0.3 
	* First public version