<?php
class BEA_PVC_Widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct( 'widget-bea-pcv', __('Widget title', 'bea-post-views-counter'),
			array( 'classname' => 'widget-bea-pcv', 'description' => __('Widget description', 'bea-post-views-counter' ) )
		);
	}
	
	public function widget( $args, $instance ) {
		extract( $args );
		
		// Get data from instance
		$title = $instance['title'];
		// TODO
		
		echo $before_widget;
		
		// Display the widget, allow take template from child or parent theme
		if ( is_file(STYLESHEETPATH .'/widget-views/bea-post-views-counter-widget.php') ) { // Use custom template from child theme
			include( STYLESHEETPATH .'/widget-views/bea-post-views-counter-widget.php' );
		} elseif ( is_file(TEMPLATEPATH .'/widget-views/bea-post-views-counter-widget.php' ) ) { // Use custom template from parent theme
			include( TEMPLATEPATH .'/widget-views/bea-post-views-counter-widget.php' );
		} else { // Use builtin temlate
			include( BEA_PVC_DIR . 'views/client/widget.php' );
		}
		
		echo $after_widget;
		
		return true;
	}
	

	
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] 		= stripslashes($new_instance['title']);
		// TODO
		
		return $instance;
	}
	
	public function form( $instance ) {
		// TODO
		$defaults = array( 'title' => __('Sample title', 'bea-post-views-counter') );
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		include( BEA_PVC_DIR . 'views/admin/widget.php' );
	}
}