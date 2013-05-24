<?php
// don't load directly
if ( !defined('ABSPATH') )
	die('-1');
?>
<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap">
	<div id="icon-themes" class="icon32"></div>
	<h2><?php _e( 'Post Views Counter - Settings', 'bea-post-views-counter' ); ?></h2>
	<?php //settings_errors(); ?>

	<?php
	self::$settings_api->show_navigation();
	self::$settings_api->show_forms();
	?>
	
	<p><?php _e( 'Created by <a href="http://www.herewithme.fr">Amaury Balmer</a> - Funded by <a href="http://beapi.fr">Be API</a> - Ticket, patch, bug, donation and documentation <a href="https://github.com/herewithme/bea-post-views-counter">on Github !</a> ', 'bea-post-views-counter' ); ?></p>
	<p><?php _e( 'Plugin written with poetry, no ads, no viruses, no spyware.', 'bea-post-views-counter' ); ?></p>
</div><!-- /.wrap -->