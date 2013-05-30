<?php
class BEA_PVC_Query {
	/**
	 * Register hooks
	 */
	public function __construct() {
		add_filter( 'query_vars', array( __CLASS__, 'query_vars' ), 10, 1 );
		add_action( 'parse_query', array( __CLASS__, 'parse_query' ), 10, 1 );
		add_filter( 'posts_join', array( __CLASS__, 'posts_join' ), 10, 2 );
		add_filter( 'posts_orderby', array( __CLASS__, 'posts_orderby' ), 10, 2 );
	}
	
	/**
	 * Add new query vars for allow time interval
	 * 
	 * @param array $vars
	 * @return array
	 */
	public static function query_vars( $vars ) {
		$vars[] = 'views_interval';
		return $vars;
	}
	
	/**
	 * Parse query, check if ordery by is by VIEWS
	 * 
	 * @param WP_Query $query
	 */
	public static function parse_query( $query ) {
		if ( $query->get( 'orderby' ) == 'views' ) {
			$query->bea_pvc = true;
			$query->set( 'orderby', 'none' );
			$query->set( 'views_interval', BEA_PVC_Plugin::_get_db_interval( $query->get( 'views_interval' ) ) );
		}
	}
	
	/**
	 * Make SQL join with custom table
	 * 
	 * @global type $wpdb
	 * @param string $join_sql
	 * @param WP_Query $query
	 * @return string
	 */
	public static function posts_join( $join_sql = '', $query = null ) {
		global $wpdb;

		if ( isset( $query->bea_pvc ) && $query->bea_pvc == true ) {
			$join_sql .= " LEFT JOIN $wpdb->post_views_counter AS pvc ON $wpdb->posts.ID = pvc.post_id ";
		}

		return $join_sql;
	}

	/**
	 * Make QL order by on custom fields of custom table !
	 * 
	 * @global type $wpdb
	 * @param string $order_sql
	 * @param WP_Query $query
	 * @return string
	 */
	public static function posts_orderby( $order_sql = '', $query = null ) {
		global $wpdb;

		if ( isset( $query->bea_pvc ) && $query->bea_pvc == true ) {
			$order_sql = " pvc." . $query->get( 'views_interval' ) . ' ' . $query->get( 'order' ) . ", $wpdb->posts.post_date DESC ";
		}

		return $order_sql;
	}

}