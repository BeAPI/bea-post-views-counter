<?php
class BEA_PVC_Admin_Main {

	public function __construct() {
		add_filter( 'manage_pages_columns', array( __CLASS__, 'manage_items_columns' ) );
		add_filter( 'manage_posts_columns', array( __CLASS__, 'manage_items_columns' ) );

		add_action( 'manage_pages_custom_column', array( __CLASS__, 'manage_items_custom_column' ), 10, 2 );
		add_action( 'manage_posts_custom_column', array( __CLASS__, 'manage_items_custom_column' ), 10, 2 );
		
		add_filter( 'admin_init', array( __CLASS__, 'admin_init' ) );
		
	}

	public static function manage_items_columns( $posts_columns ) {
		$posts_columns['bea-pvc'] = __( 'Counter' );
		return $posts_columns;
	}

	public static function manage_items_custom_column( $column_name, $post_id ) {
		if ( $column_name == 'bea-pvc' ) {
			the_post_views_counter( 'total', '', __( ' views', 'bea-post-views-counter' ), $post_id );
		}
	}
	
	public static function admin_init() {
		if ( isset($_POST['bea-pvc-import-others-plugins']) ) {
			check_admin_referer('bea-pvc-import');
			
			$counter = 0;
			$counter += self::import_wp_post_views();
			$counter += self::import_baw_post_views_counter();
			
			if ( $counter > 0 ) {
				add_settings_error( 'bea-pvc-import-op', 'bea-pvc-import-op', sprintf(__('Counters views were imported successfully! %d lines.', 'bea-post-views-counter'), $counter), 'updated' );
			} else {
				add_settings_error( 'bea-pvc-import-op', 'bea-pvc-import-op', __('No data has been imported. Either you have not installed these plugins, or the data has been purged.', 'bea-post-views-counter'), 'error' );
			}
		}
	}
	
	public static function import_wp_post_views() {
		global $wpdb;
		
		$results = $wpdb->get_results("SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = 'views'");
		if ( $results == false ) {
			return 0;
		}
		
		$counter = 0;
		foreach ( $results as $result ) {
			// Set new data
			$counter = new BEA_PVC_Counter( $result->post_id );
			$counter->load_default_data();
			$counter->set_data_value( 'total', $result->meta_value );
			$counter->commit();
			
			// Delete key
			delete_post_meta( $result, 'views' );
			
			$counter++;
		}
		
		return $counter;
	}
	
	public static function import_baw_post_views_counter() {
		// TODO
		return 0;
	}
}