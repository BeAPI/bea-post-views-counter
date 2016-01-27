<?php
class BEA_PVC_Main {
	/**
	 * Register hooks
	 */
	public function __construct() {
		// Load translation
		add_action('init', array(__CLASS__, 'init'));
		add_action( 'rest_api_init', array( __CLASS__, 'rest_api_init' ) );

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

	public static function rest_api_init() {

		_bea_pb_load_files(BEA_PVC_DIR . 'classes/rest/', array('view-controller', 'fields'));
		// Rest API controller.
		$controller = new BEA_PVC_Counter_Rest_Controller;
		$controller->register_routes();

		BEA_PVC_Counter_Rest_Field::rest_api_init();
	}
	
	/**
	 * Callback AJAX for increment counter
	 */
	public static function wp_ajax_callback() {
		if ( isset($_GET['post_id']) && (int) $_GET['post_id'] > 0 ) {
			$counter = new BEA_PVC_Counter($_GET['post_id']);
			$result = $counter->increment();
			
			@header( 'Content-Type:  text/javascript' );
			
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
		/**
		 * @var $wpdb wpdb
		 */
		global $wpdb;
		
		// Always reset query for have first WP query
		wp_reset_query();
		
		// Increment counter only for view/singular
		if ( !is_single() && !is_singular() ) {
			return false;
		}

		$blog_id = $wpdb->blogid === 0 ? 1 : $wpdb->blogid;

		$current_options = get_option('bea-pvc-main');
		if ( isset($current_options['mode']) && $current_options['mode'] == 'inline' ) { // Inline counter
			$counter = new BEA_PVC_Counter( get_queried_object_id() );
			$counter->increment();
			return true;
		} elseif ( isset($current_options['mode']) && $current_options['mode'] == 'js-php' ) { // Pure PHP
			$url = BEA_PVC_URL . 'tools/counter.php';
		} else { // Default JS WP
			$url = admin_url( 'admin-ajax.php', 'relative' );
		}

		// Add the base args
		$url = add_query_arg( array( 'action' => 'bea-pvc-counter', 'post_id' => get_queried_object_id(), 'blog_id' => $blog_id ), $url );

		// Add plugin hook
		$url = apply_filters('bea_pvc_counter_url', $url, $current_options);
		if ( empty($url) ) {
			return false;
		}

		/**
		 * Print the script
		 */
		printf( "<script type='text/javascript'>
		(function() {
		var mtime = new Date().getTime(); var bpvc = document.createElement('script'); bpvc.type = 'text/javascript'; bpvc.async = true;
		bpvc.src = '%s&r='+mtime;
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(bpvc, s);
		})();
		</script>", $url );

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
