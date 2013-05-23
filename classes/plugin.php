<?php
class BEA_PVC_Plugin {
	public static function activate() {
		global $wpdb;

		if ( ! empty($wpdb->charset) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty($wpdb->collate) )
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
		) $charset_collate;");
	}
	
	public static function deactivate() {
	}
}