<?php get_header(); ?>

<?php 
if (
	( ( get_post_type() == 'topic' ) && ( asteroid_option('ast_bbpress_topic_full_width') == 1 ) ) ||
	( ( get_post_type() != 'topic' ) && ( asteroid_option('ast_bbpress_forum_full_width') == 1 ) ) 
) :
?>
	<div id="content-nosidebar" class="cf">
<?php else : ?>
	<div id="content" class="cf">
<?php endif; ?>
	<?php do_action('ast_hook_before_content'); ?>

    <?php
		the_post();
		get_template_part( 'loop', 'single' );
    ?>

	<?php do_action('ast_hook_after_content'); ?>
	</div>

<?php
if (
	( ( get_post_type() == 'topic' ) && ( asteroid_option('ast_bbpress_topic_full_width') == 0 ) ) ||
	( ( get_post_type() != 'topic' ) && ( asteroid_option('ast_bbpress_forum_full_width') == 0 ) ) 
) :
?>
	<?php get_sidebar(); ?>
<?php endif; ?>

<?php get_footer(); ?>