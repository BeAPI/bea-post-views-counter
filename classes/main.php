<?php
class BEA_PVC_Main {
	/**
	 * Register hooks
	 */
	public function __construct() {
		// Load translation
		add_action('init', array(__CLASS__, 'init'));
		
		// Ajax count request
		add_action('wp_ajax_'.'bea-pvc-counter', array(__CLASS__, 'wp_ajax_callback'));
		add_action('wp_ajax_nopriv_'.'bea-pvc-counter', array(__CLASS__, 'wp_ajax_callback'));
		
		// Display JS ajax request or inline count
		add_action('wp_footer', array(__CLASS__, 'wp_footer'));
		
		// Delete row on custom when delete post
		add_action('deleted_post', array(__CLASS__, 'deleted_post'));
	}
	
	/**
	 * Load transation
	 */
	public static function init() {			
		load_plugin_textdomain('bea-post-views-counter', false, basename(BEA_PVC_DIR) . '/languages');
	}
	
	/**
	 * Callback AJAX for increment counter
	 */
	public static function wp_ajax_callback() {
		if ( isset($_GET['post_id']) && (int) $_GET['post_id'] > 0 ) {
			$counter = new BEA_PVC_Counter($_GET['post_id']);
			$result = $counter->increment();
	
			die( ($result == true) ? '1' : '-1' );
		}
	}
	
	/**
	 * Footer, display AJAX javascript or make inline incrementation
	 * 
	 * @global WPDB $wpdb
	 * @return boolean
	 */
	public static function wp_footer() {
		global $wpdb;
		
		// Always reset query for have first WP query
		wp_reset_query();
		
		// Increment counter only for view/singular
		if ( !is_single() && !is_singular() ) {
			return false;
		}
		
		$current_options = get_option('bea-pvc-main');
		if ( isset($current_options['mode']) && $current_options['mode'] == 'inline' ) { // Inline counter
			$counter = new BEA_PVC_Counter( get_queried_object_id() );
			$counter->increment();
			return true;
		} elseif ( isset($current_options['mode']) && $current_options['mode'] == 'js-php' ) { // Pure PHP
			$url = BEA_PVC_URL . 'tools/counter.php?post_id='.  get_queried_object_id().'&blog_id='.$wpdb->blogid;
		} else { // Default JS WP
			$url = admin_url( 'admin-ajax.php?action=bea-pvc-counter&post_id='.  get_queried_object_id(), 'relative' );
		}
		
		echo "<script type='text/javascript'>";
		echo "(function() {";
		echo "var mtime = new Date().getTime(); var bpvc = document.createElement('script'); bpvc.type = 'text/javascript'; bpvc.async = true;";
		echo "bpvc.src = '{$url}&r='+mtime;";
		echo "var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(bpvc, s);";
		echo "})();";
		echo "</script>";
		return true;
	}
	
	/**
	 * Delete counter when delete post
	 * 
	 * @param integer $post_id
	 */
	public static function deleted_post( $post_id = 0 ) {
		$counter = new BEA_PVC_Counter($post_id);
		$counter->delete();
	}
}