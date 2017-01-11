<div id="sidebar" class="cf" <?php asteroid_schema( 'sidebar' ); ?>>
	<?php do_action('ast_hook_before_sidebar'); ?>
	<div id="widgets-wrap-sidebar">

		<?php if ( is_active_sidebar('widgets_sidebar') ) : ?>
			<?php dynamic_sidebar('widgets_sidebar'); ?>
		<?php else : ?>
			<?php
				the_widget('WP_Widget_Recent_Posts', 1, array(
					'before_widget'	=> '<div class="widget-sidebar asteroid-widget widget_recent_entries">',
					'after_widget' 	=> '</div>',
					'before_title' 	=> '<h4 class="widget-title">',
					'after_title' 	=> '</h4>'
					) );
				the_widget('WP_Widget_Recent_Comments', 1, array(
					'before_widget'	=> '<div class="widget-sidebar asteroid-widget widget_recent_comments">',
					'after_widget' 	=> '</div>',
					'before_title' 	=> '<h4 class="widget-title">',
					'after_title' 	=> '</h4>'
					) );
				the_widget('WP_Widget_Meta', 1, array(
					'before_widget'	=> '<div class="widget-sidebar asteroid-widget widget_meta">',
					'after_widget' 	=> '</div>',
					'before_title' 	=> '<h4 class="widget-title">',
					'after_title' 	=> '</h4>'
					) );
			?>
		<?php endif; ?>

	</div>
	<?php do_action('ast_hook_after_sidebar'); ?>
</div>