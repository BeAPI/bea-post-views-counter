<?php
// don't load directly
if ( !defined('ABSPATH') )
	die('-1');

if ( isset($title) )
	echo $before_title . $title . $after_title;
?>

<?php if ( !$items_query->have_posts() ) : ?>
	
	<p class="no-result"><?php _e('No items actually for this custom post type.', 'bea-post-views-counter'); ?></p>

<?php else : ?>

	<ul class="relations-list">	
		<?php while ($items_query->have_posts()) : $items_query->the_post(); ?>
			<li>
				<a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>">
					<?php if ( get_the_title() ) the_title(); else the_ID(); ?>
					
					<?php if ( $show_counter == true ) : ?>
						<small>(<?php the_post_views_counter('total', '', __(' views', 'bea-post-views-counter'), get_the_ID()); ?>)</small>
					<?php endif; ?>
				</a>
			</li>
		<?php endwhile; ?>
	</ul>

<?php endif; ?>