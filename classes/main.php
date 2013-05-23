<?php
class BEA_PVC_Main {
	public function __construct() {
		add_action('wp_ajax_'.'bea-pvc-counter', array(__CLASS__, 'wp_ajax_callback'));
		add_action('wp_ajax_nopriv_'.'bea-pvc-counter', array(__CLASS__, 'wp_ajax_callback'));
		
		add_action('wp_footer', array(__CLASS__, 'wp_footer'));
		add_action('deleted_post', array(__CLASS__, 'deleted_post'));
	}
	
	public static function wp_ajax_callback() {
		if ( isset($_GET['post_id']) && (int) $_GET['post_id'] > 0 ) {
			$counter = new BEA_PVC_Counter($_GET['post_id']);
			$counter->increment();
			die('1');
		}
	}
	
	public static function wp_footer() {
		global $wpdb;
		
		// Always reset query for have first WP query
		wp_reset_query();
		
		// Increment counter only for view/singular
		if ( !is_single() && !is_singular() ) {
			return false;
		}
		
		$mode = 'wp-integration';
		if ( $mode == 'wp-integration' ) {
			$url = admin_url( 'admin-ajax.php?action=bea-pvc-counter&post_id='.  get_queried_object_id(), 'relative' );
		} elseif ( $mode == 'php-integration' ) {
			$url = BEA_PVC_URL . '/tools/counter.php?post_id='.  get_queried_object_id().'&blog_id='.$wpdb->blogid;
		}
		
		echo "<script type='text/javascript'>";
		echo "(function() {";
		echo "var mtime = new Date().getTime(); var bpvc = document.createElement('script'); bpvc.type = 'text/javascript'; bpvc.async = true;";
		echo "bpvc.src = '{$url}&r='+mtime;";
		echo "var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(bpvc, s);";
		echo "})();";
		echo "</script>";
	}
	
	public static function deleted_post( $post_id = 0 ) {
		$counter = new BEA_PVC_Counter($post_id);
		$counter->delete();
	}
}