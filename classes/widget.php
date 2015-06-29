<?php
class BEA_PVC_Widget extends WP_Widget {

	/**
	 * Constructor, register widget with parent class
	 */
	public function __construct() {
		parent::__construct('widget-bea-pcv', __('Most viewed content', 'bea-post-views-counter'), array('classname' => 'widget-bea-pcv', 'description' => __('The most viewed content on your site', 'bea-post-views-counter')));
	}
	
	/**
	 * Client method, display top recent contents, use template from views or theme/child theme (MVC)
	 * 
	 * @param array $args
	 * @param array $instance
	 * @return boolean
	 */
	public function widget($args, $instance) {
		extract($args);

		// Build or not the name of the widget
		if (!empty($instance['title'])) {
			$title = $instance['title'];
		} else {
			$custom_type = get_post_type_object($instance['post_type']);
			$title = $custom_type->labels->name;
		}
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);

		$items_query = new WP_Query(array(
			'post_type' => $instance['post_type'],
			'post_status' => 'publish',
			'showposts' => $instance['number'],
			'orderby' => $instance['orderby'],
			'order' => $instance['order'],
			'views_interval' => $instance['views_interval']
			));

		$show_counter = ( $instance['show_counter'] == '1' ) ? true : false;

		echo $before_widget;

		// Display the widget, allow take template from child or parent theme
		if (is_file(STYLESHEETPATH . '/widget-views/bea-post-views-counter-widget.php')) { // Use custom template from child theme
			include( STYLESHEETPATH . '/widget-views/bea-post-views-counter-widget.php' );
		} elseif (is_file(TEMPLATEPATH . '/widget-views/bea-post-views-counter-widget.php')) { // Use custom template from parent theme
			include( TEMPLATEPATH . '/widget-views/bea-post-views-counter-widget.php' );
		} else { // Use builtin temlate
			include( BEA_PVC_DIR . 'views/client/widget.php' );
		}

		wp_reset_postdata();

		echo $after_widget;

		return true;
	}

	/**
	 * Update method, save widget fields
	 * 
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		foreach (array('title', 'post_type', 'orderby', 'order', 'views_interval', 'number', 'show_counter') as $val) {
			$instance[$val] = strip_tags(stripslashes($new_instance[$val]));
		}
		return $instance;
	}
	
	/**
	 * Admin form
	 * 
	 * @param array $instance
	 */
	public function form($instance) {
		$defaults = array(
			'title' => __('Most popular', 'bea-post-views-counter'),
			'post_type' => 'all',
			'orderby' => 'views',
			'views_interval' => 'total',
			'order' => 'DESC',
			'number' => 10,
			'show_counter' => '1'
		);
		$instance = wp_parse_args((array) $instance, $defaults);

		include( BEA_PVC_DIR . 'views/admin/widget.php' );
	}
	
	/**
	 * Helper for get ordery fields
	 * 
	 * @return array
	 */
	public static function get_orderby_fields() {
		return array(
			'post_date' => __('Date', 'bea-post-views-counter'),
			'ID' => __('ID', 'bea-post-views-counter'),
			'post_title' => __('Title', 'bea-post-views-counter'),
			'views' => __('Views', 'bea-post-views-counter')
		);
	}

}