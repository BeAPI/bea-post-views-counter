<?php
function the_post_views_counter( $field = 'total', $before = '', $after = '', $post_id = 0 ) {
	echo get_the_post_views_counter( $field, $before, $after, $post_id );
}
function get_the_post_views_counter( $field = 'total', $before = '', $after = '', $post_id = 0 ) {
	if ( $post_id == 0 ) {
		global $post;
		$post_id = (int) $post->ID;
	}
	
	if ( $post_id == 0 ) {
		return false;
	}

	$counter = new BEA_PVC_Counter( $post_id );
	return $before . $counter->get_data_value( $field ) . $after;
}