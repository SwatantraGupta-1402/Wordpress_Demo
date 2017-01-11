<?php get_header();?>

<div id="content" class="cf" <?php asteroid_schema( 'content' ); ?>>
	<?php do_action('ast_hook_before_content'); ?>

	<!-- Widgets: Before Content -->
	<?php if ( is_active_sidebar('widgets_before_content') )  : ?>
		<div id="widgets-wrap-before-content" class="cf"><?php dynamic_sidebar('widgets_before_content'); ?></div>
	<?php endif; ?>

	<?php if ( is_archive() || is_search() ) : ?>
		<div class="archive-info">
			<?php
				if ( is_search() ) {
					echo '<h4 class="archive-title">' . sprintf(	__('Search Results for &ndash; &quot;<span>%s</span>&quot;', 'asteroid'), get_search_query() ) . '</h4>';
				}
				else {
					the_archive_title( '<h4 class="archive-title">', '</h4>' );
					the_archive_description( '<div class="archive-description">', '</div>' );
				}
			?>
		</div>
	<?php endif; ?>

	<!-- Start the Loop -->
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<?php if ( !is_singular() ) : ?>
					<?php get_template_part( 'loop', 'blog' ); ?>
			<?php else : ?>
					<?php get_template_part( 'loop', 'single' ); ?>
			<?php endif; ?>

	<?php endwhile; else: ?>

		<div class="wrap-404-box cf">
			<?php
				$asteroid_nothing_found_content = '<h2>' . __('Nothing Found', 'asteroid') . '</h2>';
				$asteroid_nothing_found_content .= '<p>' . __('Try a new keyword.', 'asteroid') . '</p>';
				$asteroid_nothing_found_content .= get_search_form( false );
				echo apply_filters( 'asteroid_nothing_found_content', $asteroid_nothing_found_content );
			?>
		</div>

	<!-- End Loop -->
	<?php endif; ?>

	<?php do_action('ast_hook_after_content'); ?>

	<!-- Bottom Post Navigation -->
	<?php if ( !is_singular() ) : ?>

		<div id="bottom-navi" class="cf">
			<?php if ( function_exists('wp_pagenavi') ):?>
				<?php wp_pagenavi(); ?>
			<?php else : ?>
				<div class="link-prev"><?php next_posts_link( __('&laquo; Older posts', 'asteroid') ); ?></div>
				<div class="link-next"><?php previous_posts_link( __('Newer posts &raquo;', 'asteroid') ); ?></div>
			<?php endif; ?>
		</div>

	<?php endif; ?>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>