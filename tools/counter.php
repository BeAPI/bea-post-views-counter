<?php
$debug = 0;
if ( $debug == 1 ) {
	error_reporting( E_ALL );
	ini_set( 'display_errors', 1 );
} else {
	ini_set( 'display_errors', 0 );
}

// Define current mode
define( 'BEA_PVC_PHP_MODE', true );

// Shortinit, if bootstrap filter failed
define( 'SHORTINIT', true );

/*
  Define a filter class to strip out the wp-settings require
 */
class stopwpbootstrap_filter extends php_user_filter {

	function filter( $in, $out, &$consumed, $closing ) {
		while ( $bucket = stream_bucket_make_writeable( $in ) ) {
			$bucket->data = str_replace( "require_once(ABSPATH . 'wp-settings.php');", "", $bucket->data );
			$consumed += $bucket->datalen;
			stream_bucket_append( $out, $bucket );
		}
		return PSFS_PASS_ON;
	}

}

// Load WP config file, without WP !
// this filter will strip out the wp-settings require line
// preventing the full WP stack from bootstrapping
stream_filter_register( "stopwpbootstrap", "stopwpbootstrap_filter" );

// Detect wp-config location
$wp_location = dirname(__FILE__) . '/../../../../';
if ( file_exists( $wp_location . 'wp-config.php') ) {
	$wp_location .= 'wp-config.php';
} elseif ( file_exists( dirname($wp_location) . '/wp-config.php' ) && ! file_exists( dirname($wp_location) . '/wp-settings.php' ) ) {
	$wp_location = dirname($wp_location) . '/wp-config.php';
} else { // Config file not exist, stop script
	die('-9');
}

// by reading this file via the php filter protocol,
// we can safely include wp-config.php in our function scope now 
include("php://filter/read=stopwpbootstrap/resource=" . $wp_location);

// Constant are defined ?
if ( !defined('DB_NAME') ) {
	die('-8');
}

// Timezone
date_default_timezone_set( 'UTC' );

// Load PHP MySQL Lib
require( dirname( __FILE__ ) . '/../librairies/php-mysql-class-master/class.MySQL.php' );

// Load counter class for extend it
require( dirname( __FILE__ ) . '/../classes/counter.php' );

/**
 * Pure PHP class
 */
class BEA_PVC_Counter_Full_PHP extends BEA_PVC_Counter {

	protected $_db = null;
	protected $_blog_id = 0;

	public function __construct( $post_id = 0, $blog_id = 0 ) {
		$blog_id = (int) $blog_id;
		if ( $blog_id == 0 ) {
			return false;
		}

		// Keep blog id
		$this->_blog_id = $blog_id;

		// Init SQL connection
		$this->_db = new MySQL( DB_NAME, DB_USER, DB_PASSWORD, DB_HOST );

		// Init parent
		parent::__construct( $post_id, false );

		$this->_fill_data();
	}

	protected function _get_table_name() {
		return $this->_get_prefix() . 'post_views_counter';
	}
	
	private function _get_prefix() {
		global $table_prefix, $wpdb;

		if ( defined( 'WPINC' ) ) { // Shortinit, but config_only not work
			return $wpdb->prefix;
		} else { // PURE PURE PHP
			if ( $this->_blog_id == 1 ) {
				return $table_prefix;
			} else {
				return $table_prefix . $this->_blog_id . '_';
			}
		}
	}

	protected function _get_row( $query = "" ) {
		$result = $this->_db->ExecuteSQL( $query );
		$result = ( is_bool( $result ) ) ? false : $result; // TRUE = FALSE for WP
		return $result;
	}

	protected function _insert( $table_name = '', $values = array( ) ) {
		return $this->_db->Insert( $values, $table_name );
	}

	protected function _update( $table_name = '', $values = array( ), $where = array( ) ) {
		return $this->_db->Update( $table_name, $values, $where );
	}
	
	protected function get_option( $option_name = '' ) {
		$result = $this->_get_row( sprintf("SELECT option_value FROM " . $this->_get_prefix() . "options WHERE option_name = '%s'", $this->_db->SecureData($option_name)) );
		if ( $result != false ) {
			return unserialize($result['option_value']);
		} else {
			return false;
		}
	}
}

if ( isset( $_GET['post_id'] ) && (int) $_GET['post_id'] > 0 && isset( $_GET['blog_id'] ) && (int) $_GET['blog_id'] > 0 ) {
	$counter = new BEA_PVC_Counter_Full_PHP( (int) $_GET['post_id'], (int) $_GET['blog_id'] );
	$result = $counter->increment();
	
	die( ($result == true) ? '1' : '-1' );
}

die( '0' ); // Invalid call
