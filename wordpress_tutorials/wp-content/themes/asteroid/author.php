<?php get_header(); ?>

<div id="content" class="cf">

	<div id="author-<?php the_author_meta( 'ID' ); ?>">

		<div class="author-info-box">
			<h4 class="author-info-box-title "><?php _e('About the Author', 'asteroid'); ?></h4>
			<div class="author-info">
				<div class="author-avatar">
					<?php echo get_avatar( get_the_author_meta('ID'), 96 ); ?>
				</div>
				<div class="author-description">
					<h4><?php echo get_the_author_link(); ?></h4>
					<div><?php echo get_the_author_meta('description'); ?></div><br />
					<?php _e('Number of Posts:', 'asteroid'); ?>&nbsp;<?php the_author_posts(); ?>
				</div>
			</div>
		</div>

		<div class="author-latest-posts">
			<h4 class="author-latest-posts-title"><?php _e('Latest Posts by the Author', 'asteroid'); ?></h4>
			<?php
				$postsQuery = new WP_Query( array(
					'posts_per_page' 	=> 20,
					'author' 			=> get_the_author_meta( 'ID' ),
					'orderby'			=> 'date',
					'suppress_filters'	=> 0 )
				);

				if ( $postsQuery->have_posts() ) :
			?>

			<ol class="author-latest-posts-list">
				<?php while ( $postsQuery->have_posts() ) : $postsQuery->the_post(); ?>
					<li><a href="<?php the_permalink(); ?>"><?php echo get_the_title() != '' ? get_the_title() : __('Untitled', 'asteroid'); ?></a></li>
				<?php endwhile; ?>
			</ol>

			<?php endif; wp_reset_postdata(); ?>
		</div>

	</div>

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>