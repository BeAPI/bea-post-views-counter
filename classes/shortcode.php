<?php
class BEA_PVC_Shortcode {
	/**
	 * Register hooks
	 */
	public function __construct() {
		foreach( array('post_view', 'counter', 'post_counter') as $shortcode_name ) {
			add_shortcode( $shortcode_name, array(__CLASS__, 'shortcode') );
		}
	}
	
	/**
	 * Shortcode callback, build HTML
	 */
	public static function shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'time' => 'total',
			'post_id' => 0
		), $atts );
		
		// If not post_id, get ID from global $post
		if ( (int) $atts['post_id'] == 0 ) {
			global $post;
			$atts['post_id'] = $post->ID;
		}
		
		return get_the_post_views_counter( $atts['time'], $atts['post_id'] );
	}
}