<?php
/*
 Plugin Name: BEA Post Views Counter
 Version: 0.1
 Plugin URI: https://github.com/herewithme/bea-post-views-counter
 Description: Counts views for any post type. Compatible with static cache plugins, and also full PHP implementation
 Author: Amaury Balmer
 Author URI: http://www.beapi.fr
 Domain Path: languages
 Network: false
 Text Domain: bea-post-views-counter

 TODO:
	Include : Everyone, Guests Only, Registered Users Only
	Exclude : Bot Views, IP
	Shortcode
	Import from WP Post Views / Post view counter

 ----

 Copyright 2013 Amaury Balmer (amaury@beapi.fr)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

// don't load directly
if ( !defined('ABSPATH') )
	die('-1');

// Plugin tables
global $wpdb;
$wpdb->tables[]   = 'post_views_counter';
$wpdb->post_views_counter = $wpdb->prefix . 'post_views_counter';

// Plugin constants
define('BEA_PVC_VERSION', '0.1');

// Plugin URL and PATH
define('BEA_PVC_URL', plugin_dir_url ( __FILE__ ));
define('BEA_PVC_DIR', plugin_dir_path( __FILE__ ));

// Function for easy load files
function _bea_pb_load_files($dir, $files, $prefix = '') {
	foreach ($files as $file) {
		if ( is_file($dir . $prefix . $file . ".php") ) {
			require_once($dir . $prefix . $file . ".php");
		}
	}	
}

// Plugin functions
_bea_pb_load_files(BEA_PVC_DIR . 'functions/', array('api', 'template'));

// Plugin client classes
_bea_pb_load_files(BEA_PVC_DIR . 'classes/', array('main', 'plugin', 'widget', 'counter', 'query'));

// Plugin admin classes
if (is_admin()) {
	_bea_pb_load_files(BEA_PVC_DIR . 'classes/admin/', array('main'));
}

// Plugin activate/desactive hooks
register_activation_hook(__FILE__, array('BEA_PVC_Plugin', 'activate'));
register_deactivation_hook(__FILE__, array('BEA_PVC_Plugin', 'deactivate'));

add_action('plugins_loaded', 'init_bea_pvc_plugin');
function init_bea_pvc_plugin() {
	// Load translations
	load_plugin_textdomain('bea-post-views-counter', false, basename(BEA_PVC_DIR) . '/languages');

	// Client
	new BEA_PVC_Main();
	new BEA_PVC_Query();

	// Admin
	if (is_admin()) {
		new BEA_PVC_Admin_Main();
	}

	// Widget
	add_action('widgets_init', create_function('', 'return register_widget("BEA_PVC_Widget");'));
}