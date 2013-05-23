<?php
class BEA_PVC_Plugin {

	public static function activate() {
		global $wpdb;

		if ( !empty( $wpdb->charset ) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( !empty( $wpdb->collate ) )
			$charset_collate .= " COLLATE $wpdb->collate";

		// Add one library admin function for next function
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		// Data table
		maybe_create_table( $wpdb->post_views_counter, "CREATE TABLE IF NOT EXISTS `{$wpdb->post_views_counter}` (
			`post_id` bigint(20) NOT NULL,
			`day_counter` int(20) NOT NULL DEFAULT '0',
			`day_date` datetime NOT NULL,
			`previous_day_counter` int(20) NOT NULL DEFAULT '0',.
			`week_counter` int(20) NOT NULL DEFAULT '0',
			`week_date` datetime NOT NULL,
			`previous_week_counter` int(20) NOT NULL DEFAULT '0',
			`month_counter` int(20) NOT NULL DEFAULT '0',
			`month_date` datetime NOT NULL,
			`previous_month_counter` int(20) NOT NULL DEFAULT '0',
			`year_counter` int(20) NOT NULL DEFAULT '0',
			`year_date` datetime NOT NULL,
			`previous_year_counter` int(20) NOT NULL DEFAULT '0',
			`total` bigint(20) NOT NULL DEFAULT '0',
			PRIMARY KEY `post_id` (`post_id`)
		) $charset_collate;" );
	}

	public static function deactivate() {
		
	}

	public static function get_time_intervals() {
		return array(
			'day' => __( 'Current day', 'bea-post-views-counter' ),
			'yesterday' => __( 'Yesterday', 'bea-post-views-counter' ),
			'week' => __( 'Current week', 'bea-post-views-counter' ),
			'previous_week' => __( 'Previous week', 'bea-post-views-counter' ),
			'month' => __( 'Current month', 'bea-post-views-counter' ),
			'previous_month_counter' => __( 'Previous month', 'bea-post-views-counter' ),
			'year' => __( 'Current year', 'bea-post-views-counter' ),
			'previous_year' => __( 'Previous year', 'bea-post-views-counter' ),
			'total' => __( 'Total', 'bea-post-views-counter' )
		);
	}

	public static function get_allowed_time_intervals() {
		return array(
			'day' => 'day_counter',
			'previous_day' => 'previous_day_counter',
			'yesterday' => 'previous_day_counter',
			'week' => 'week_counter',
			'previous_week' => 'previous_week_counter',
			'month' => 'month_counter',
			'previous_month' => 'previous_month_counter',
			'year' => 'year_counter',
			'previous_year' => 'previous_year_counter',
			'total' => 'total'
		);
	}

	public static function _is_allowed_interval( $value ) {
		$intervals = self::get_allowed_time_intervals();
		return ( isset( $intervals[$value] ) ) ? true : false;
	}

	public static function _get_db_interval( $value ) {
		$intervals = self::get_allowed_time_intervals();
		return ( isset( $intervals[$value] ) ) ? $intervals[$value] : 'total';
	}

}