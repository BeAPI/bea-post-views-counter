# BEA Post Views Counter #

## Description ##

Enables you to display how many times a post, page and any post type had been viewed. Designed for high-traffic sites, performance, security & scalability.

## Important to know ##

**Contributors:** momo360modena  
**Donate link:** http://beapi.fr/donate/  
**Tags:** counter, hits, postviews, views, count, popular, popular post, stats, view, views, widget  
**Requires at least:** 3.1  
**Tested up to:** 3.5.1  
**Stable tag:** 0.6.9
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

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

## Installation ##

1. Upload `bea-post-views-counter` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

## Frequently asked questions ##

### Are what plugin pollutes the WordPress interface with ads and donation links ? ###

No, this plugin written with poetry, no ads, no viruses, no spyware.

## Screenshots ##

### 1. Plugin settings
![Plugin settings](http://s.wordpress.org/extend/plugins/bea-post-views-counter/screenshot-1.png)

### 2. Shortcode
![Shortcode](http://s.wordpress.org/extend/plugins/bea-post-views-counter/screenshot-2.png)

### 3. Widget
![Widget](http://s.wordpress.org/extend/plugins/bea-post-views-counter/screenshot-3.png)

## Changelog ##

### 0.6.9
*4 Jul 2023
* Update wordpress-settings-api-class library for php8 compatibility

### 0.6.8
*30 may 2023
* Fix an error in the maybe_create_table() function of the plugin.php file
* Fix an error where the mysqli_num_rows() function could be passed a boolean instead of a mysqli_result object

### 0.6.7
*4 Apr 2023
* Fix missing .mo & .pot files after french translation update

### 0.6.6
* 31 Mar 2023
* Fix missing spaces in french counter message translation

### 0.6.5
* 4 Jan 2019
* Fix condition error on mutlisite environment for counters

### 0.6.4
* 1 Oct 2018
* Fix deprecated create_function()

### 0.6.3
* 11 Oct 2017
* Fix deprecated PHP4 style class constructor on Mysqli PHP Class

### 0.6.2
* 10 Oct 2017
* Use Mysqli version librarie

### 0.6.1
* 30 Jun 2017
* Add filter on posts_where to get elements on real current_month, current_day, current_week, current_year not on all counters

### 0.6.0
* 29 Jun 2015
* Add composer
* Fix domain
* Integrate the full php addon

### 0.5.1
* 10 Oct 2013
* Change hook parse_query for pre_get_posts. More compatible.

### 0.5
* Move full PHP implementation into an addon (https://github.com/herewithme/bea-post-views-counter-fullphp-addon)

### 0.4
* Minor changes on pure PHP counter

### 0.3 
* First public version
