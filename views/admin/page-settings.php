<?php
// don't load directly
if ( !defined('ABSPATH') )
	die('-1');
?>
<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap">
	<div id="icon-themes" class="icon32"></div>
	<h2><?php _e( 'Post Views Counter - Settings', 'bea-post-views-counter' ); ?></h2>

	<?php
	self::$settings_api->show_navigation();
	self::$settings_api->show_forms();
	?>
	
	<hr />
	
	<h2><?php _e( 'Import counter data from another plugins', 'bea-post-views-counter' ); ?></h2>
	<p>
		<?php _e( 'Supported plugins : <a href="http://wordpress.org/plugins/wp-postviews/">WP-Post-Views</a>, <a href="http://wordpress.org/plugins/baw-post-views-count/faq/">BAW Post Views Count</a>', 'bea-post-views-counter' ); ?>
		<br />
		<?php _e( '<strong>Caution</strong>: This process performs both data import and deletion of old data. Imported values ​​overwrite existing data.', 'bea-post-views-counter' ); ?>
	</p>
	
	<form action="" method="post">
		<p class="submit">
			<?php wp_nonce_field('bea-pvc-import'); ?>
			<input type="submit" name="bea-pvc-import-others-plugins" class="button-primary" value="<?php _e('Start import', 'bea-post-views-counter'); ?>" />
		</p>
	</form>
	<hr />
	<p><?php _e( 'Created by <a href="http://www.herewithme.fr">Amaury Balmer</a> - Funded by <a href="http://beapi.fr">Be API</a> - Ticket, patch, bug, donation and documentation <a href="https://github.com/herewithme/bea-post-views-counter">on Github !</a> ', 'bea-post-views-counter' ); ?></p>
	<p><?php _e( 'Plugin written with poetry, no ads, no viruses, no spyware.', 'bea-post-views-counter' ); ?></p>
</div><!-- /.wrap -->