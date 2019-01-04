<?php
// Define the Abspath for faking WordPress
define( 'ABSPATH', dirname( __FILE__ ) . '/' );

// Define current mode
define( 'BEA_PVC_PHP_MODE', true );

// Detect wp-config location
// Inspiration : http://boiteaweb.fr/wordpress-bootstraps-ou-comment-bien-charger-wordpress-6717.html
$wp_location = 'wp-config.php';
while( !is_file( $wp_location ) ) {
	if ( is_dir( '..' ) ) {
		chdir( '..' );
	} else {
		die( '-9' ); // Config file not exist, stop script
	}
}
require_once( $wp_location );

// Constant are defined ? WP & Plugin is loaded ?
if ( !defined('DB_NAME') ) {
	die('-8');
}

// Timezone
date_default_timezone_set( 'UTC' );

// Load PHP MySQL Lib
require( ABSPATH . '/../librairies/php-mysql-class-master/class.MySQL.php' );

// Load counter class for extend it
if ( is_file( ABSPATH . '/../classes/counter.php') ) {
	require( ABSPATH . '/../classes/counter.php' );
} else {
	die('-7');
}

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

@header( 'Content-Type:  text/javascript' );

// Init the vars
$id = isset( $_GET['post_id'] ) ? (int)$_GET['post_id'] : 0 ;
$blog_id  = isset( $_GET['blog_id'] ) ? (int)$_GET['blog_id'] : 0 ;

if ( 0 === $id || $blog_id <= 0 ) {
	die( '0' ); // Invalid call
}

// Init counter and increment
$counter = new BEA_PVC_Counter_Full_PHP( $id, $blog_id );
$result = $counter->increment();

die( (true == $result) ? '1' : '-1' );

