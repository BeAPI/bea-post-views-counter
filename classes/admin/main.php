<?php
class BEA_PVC_Admin_Main {

	public function __construct() {
		add_filter( 'manage_pages_columns', array( __CLASS__, 'manage_items_columns' ) );
		add_filter( 'manage_posts_columns', array( __CLASS__, 'manage_items_columns' ) );

		add_action( 'manage_pages_custom_column', array( __CLASS__, 'manage_items_custom_column' ), 10, 2 );
		add_action( 'manage_posts_custom_column', array( __CLASS__, 'manage_items_custom_column' ), 10, 2 );
	}

	function manage_items_columns( $posts_columns ) {
		$posts_columns['bea-pvc'] = __( 'Counter' );
		return $posts_columns;
	}

	function manage_items_custom_column( $column_name, $post_id ) {
		if ( $column_name == 'bea-pvc' ) {
			the_post_views_counter( 'total', '', __( ' views', 'bea-post-views-counter' ), $post_id );
		}
	}

}