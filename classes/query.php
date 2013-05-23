<?php
class BEA_PVC_Query {

	static $_intervals = array( 'day' => 'day_counter', 'previous_day' => 'previous_day_counter', 'yesterday' => 'previous_day_counter', 'week' => 'week_counter', 'previous_week' => 'previous_week_counter', 'month' => 'month_counter', 'previous_month' => 'previous_month_counter', 'year' => 'year_counter', 'previous_year' => 'previous_year_counter', 'total' => 'total' );

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

			$views_interval = ( self::_is_allowed_interval( $query->get( 'views_interval' ) ) ) ? $query->get( 'views_interval' ) : 'total';
			$query->set( 'views_interval', $views_interval );
		}
	}

	private static function _is_allowed_interval( $value ) {
		return ( isset( self::$_intervals[$value] ) ) ? true : false;
	}

	private static function _get_db_interval( $value ) {
		return ( isset( self::$_intervals[$value] ) ) ? self::$_intervals[$value] : 'total';
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
			$order_sql = " pvc." . self::_get_db_interval( $query->get( 'views_interval' ) ) . ' ' . $query->get( 'order' ) . ", $wpdb->posts.post_date DESC ";
		}
		
		return $order_sql;
	}

}