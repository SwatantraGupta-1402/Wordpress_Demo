<?php // Template Name: Full, No Sidebar ?>
<?php get_header(); ?>

<div id="content-nosidebar" class="cf">
	<?php do_action('ast_hook_before_content'); ?>

	<!-- Widgets: Before Content -->
	<?php if ( is_active_sidebar('widgets_before_content') ) : ?>
		<div id="widgets-wrap-before-content" class="cf">
			<?php dynamic_sidebar('widgets_before_content'); ?>
		</div>
	<?php endif ; ?>

	<?php
		the_post();
		get_template_part( 'loop', 'single' );
	?>

	<?php do_action('ast_hook_after_content'); ?>
</div>

<?php get_footer(); ?>