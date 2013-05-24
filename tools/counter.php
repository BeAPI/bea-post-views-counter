<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Timezone
date_default_timezone_set( 'UTC' );

// Constants
define('CONFIG_ONLY', true);
define('SHORTINIT', true);

// Load WP configuration file (without core)
require( dirname(__FILE__) . '/../../../../wp-config.php' );

// Load PHP MySQL Lib
require( dirname(__FILE__) . '/../librairies/php-mysql-class-master/class.MySQL.php' );

// Load counter class for next extend
require( dirname(__FILE__) . '/../classes/counter.php' );

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
		global $table_prefix, $wpdb;
		
		if ( defined('WPINC') ) { // Shortinit, but config_only not work
			return $wpdb->prefix . 'post_views_counter';
		} else { // PURE PURE PHP
			if ( $this->_blog_id == 1 ) {
				return $table_prefix . 'post_views_counter';
			} else {
				return $table_prefix . $this->_blog_id . '_' . 'post_views_counter';
			}
		}
	}
	
	protected function _get_row( $query = "" ) {
		$result = $this->_db->ExecuteSQL( $query );
		$result = ( is_bool($result) ) ? false : $result; // TRUE = FALSE for WP
		return $result;
	}
	
	protected function _insert( $table_name = '', $values = array() ) {
		return $this->_db->Insert( $values, $table_name );
	}
	
	protected function _update( $table_name = '', $values = array(), $where = array() ) {
		return $this->_db->Update( $table_name, $values, $where );
	}
	
}

if ( isset($_GET['post_id']) && (int) $_GET['post_id'] > 0 && isset($_GET['blog_id']) && (int) $_GET['blog_id'] > 0 ) {
	$counter = new BEA_PVC_Counter_Full_PHP( (int) $_GET['post_id'], (int) $_GET['blog_id'] );
	$counter->increment();
	die('1');
}
die('0');
