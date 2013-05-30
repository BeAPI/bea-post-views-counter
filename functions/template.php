<?php
/**
 * Helper for get_the_post_views_counter() function, just make an echo
 * 
 * @param string $field
 * @param string $before
 * @param string $after
 * @param integer $post_id
 */
function the_post_views_counter( $field = 'total', $before = '', $after = '', $post_id = 0 ) {
	echo get_the_post_views_counter( $field, $before, $after, $post_id );
}

/**
 * Template function for get post views counter for a post
 * 
 * @global type $post
 * @param string $field
 * @param string $before
 * @param string $after
 * @param integer $post_id
 * @return string|boolean
 */
function get_the_post_views_counter( $field = 'total', $before = '', $after = '', $post_id = 0 ) {
	if ( $post_id == 0 ) {
		global $post;
		$post_id = (int) $post->ID;
	}
	
	if ( $post_id == 0 ) {
		return false;
	}
	
	$field = BEA_PVC_Plugin::_get_db_interval( $field );

	$counter = new BEA_PVC_Counter( $post_id );
	return $before . (int) $counter->get_data_value( $field ) . $after;
}