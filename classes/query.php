<?php
class BEA_PVC_Query {

	public function __construct() {
		add_filter( 'query_vars', array( __CLASS__, 'query_vars' ), 10, 1 );
		add_action( 'parse_query', array( __CLASS__, 'parse_query' ), 10, 1 );
		add_filter( 'posts_join', array( __CLASS__, 'posts_join' ), 10, 2 );
		add_filter( 'posts_orderby', array( __CLASS__, 'posts_orderby' ), 10, 2 );
	}

	public static function query_vars( $vars ) {
		$vars[] = 'views_interval';
		return $vars;
	}

	public static function parse_query( $query ) {
		if ( $query->get( 'orderby' ) == 'views' ) {
			$query->bea_pvc = true;
			$query->set( 'orderby', 'none' );
			$query->set( 'views_interval', BEA_PVC_Plugin::_get_db_interval( $query->get( 'views_interval' ) ) );
		}
	}

	public static function posts_join( $join_sql = '', $query = null ) {
		global $wpdb;

		if ( isset( $query->bea_pvc ) && $query->bea_pvc == true ) {
			$join_sql .= " LEFT JOIN $wpdb->post_views_counter AS pvc ON $wpdb->posts.ID = pvc.post_id ";
		}

		return $join_sql;
	}

	public static function posts_orderby( $order_sql = '', $query = null ) {
		global $wpdb;

		if ( isset( $query->bea_pvc ) && $query->bea_pvc == true ) {
			$order_sql = " pvc." . $query->get( 'views_interval' ) . ' ' . $query->get( 'order' ) . ", $wpdb->posts.post_date DESC ";
		}

		return $order_sql;
	}

}