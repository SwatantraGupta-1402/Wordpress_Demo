<?php get_header(); ?>

<div id="content" class="cf">
	<?php do_action('ast_hook_before_content'); ?>

	<div class="wrap-404-box cf">
		<?php
			$asteroid_404_content = '<h2>' . __('Error 404', 'asteroid') . '</h2>';
			$asteroid_404_content .= '<p>' . __('Sorry. The Page or File you were looking for was not found.', 'asteroid') . '</p>';
			$asteroid_404_content .= get_search_form( false );
			echo apply_filters( 'asteroid_404_content', $asteroid_404_content );
		?>
	</div>

	<?php do_action('ast_hook_after_content'); ?>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>