<?php
// don't load directly
if ( !defined('ABSPATH') )
	die('-1');
?>
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'bea-post-views-counter'); ?></label>
	<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($instance['title']); ?>" class="widefat" />
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e('What to show', 'bea-post-views-counter'); ?></label>
	<select id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>" class="widefat">
		<option value="any"><?php _e('Any', 'bea-post-views-counter'); ?></option>
		<?php
		foreach ( get_post_types( array(), 'objects' ) as $post_type ) {
			if ( !$post_type->show_ui || empty($post_type->labels->name) )
				continue;
			
			echo '<option '.selected( $instance['post_type'], $post_type->name, false ).' value="'.esc_attr($post_type->name).'">'.esc_html($post_type->labels->name).'</option>';
		}
		?>
	</select>
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e("Order on which field ?", 'bea-post-views-counter'); ?></label>
	<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>" class="widefat">
		<?php
		foreach( $this->get_orderby_fields() as $optval => $option ) {
			echo '<option '.selected( $instance['orderby'], $optval, false ).' value="'.esc_attr($optval).'">'.esc_html($option).'</option>';
		}
		?>
	</select>
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'views_interval' ); ?>"><?php _e("If you order by views, choose time interval:", 'bea-post-views-counter'); ?></label>
	<select id="<?php echo $this->get_field_id( 'views_interval' ); ?>" name="<?php echo $this->get_field_name( 'views_interval' ); ?>" class="widefat">
		<?php
		foreach( BEA_PVC_Plugin::get_time_intervals() as $optval => $option ) {
			echo '<option '.selected( $instance['views_interval'], $optval, false ).' value="'.esc_attr($optval).'">'.esc_html($option).'</option>';
		}
		?>
	</select>
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e("Order by ?", 'bea-post-views-counter'); ?></label>
	<select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>" class="widefat">
		<?php
		foreach( array('ASC' => __('Ascending', 'bea-post-views-counter'), 'DESC' => __('Descending', 'bea-post-views-counter') ) as $optval => $option ) {
			echo '<option '.selected( $instance['order'], $optval, false ).' value="'.esc_attr($optval).'">'.esc_html($option).'</option>';
		}
		?>
	</select>
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'show_counter' ); ?>"><?php _e("Show counter ?", 'bea-post-views-counter'); ?></label>
	<select id="<?php echo $this->get_field_id( 'show_counter' ); ?>" name="<?php echo $this->get_field_name( 'show_counter' ); ?>" class="widefat">
		<?php
		foreach( array('1' => __('Yes', 'bea-post-views-counter'), '0' => __('No', 'bea-post-views-counter') ) as $optval => $option ) {
			echo '<option '.selected( $instance['show_counter'], $optval, false ).' value="'.esc_attr($optval).'">'.esc_html($option).'</option>';
		}
		?>
	</select>
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e("Number of items to show", 'bea-post-views-counter'); ?></label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo (int) $instance['number']; ?>" class="widefat" />
</p>