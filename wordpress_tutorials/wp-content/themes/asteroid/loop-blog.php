<article id="post-<?php the_ID(); ?>" <?php post_class('blog-view'); ?>>

<div class="entry-header">
	<h2 class="entry-title" <?php asteroid_schema( 'entry-title' ); ?>><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
</div>

<?php if ( ( asteroid_option('ast_blog_date') == 1 ) && ( get_post_type() == 'post' ) ) : ?>
	<div class="entry-date" <?php asteroid_schema( 'entry-date' ); ?>><a href="<?php the_permalink(); ?>" class="updated"><?php the_time(get_option('date_format')); ?></a></div>
<?php endif; ?>

<div class="entry-meta-top cf">
	<?php if ( asteroid_option('ast_blog_author') == 1 ) : ?>
		<span class="entry-author author vcard" <?php asteroid_schema( 'entry-author' ); ?>>
			<?php $asteroid_post_author_url = get_the_author_meta('user_url') != '' ? get_the_author_meta('user_url') : get_author_posts_url( get_the_author_meta('ID') ); ?>
			<?php _e('Posted by', 'asteroid'); ?>&nbsp;<a class="url fn" href="<?php echo esc_url( $asteroid_post_author_url ); ?>" <?php asteroid_schema( 'author-name' ); ?>><?php the_author(); ?></a>
		</span>
	<?php endif; ?>

	<span class="entry-categories"><?php the_category(' '); ?></span>
</div>

<div class="entry-content cf" <?php asteroid_schema( 'entry-content' ); ?>>

	<?php if ( ( asteroid_option('ast_post_display_type') == 1 ) && ( (get_post_type() == 'post') || (get_post_type() == 'page') ) ) : ?>

		<?php if ( asteroid_option('ast_excerpt_thumbnails') == 1 ) : ?>
			<?php if ( apply_filters( 'asteroid_has_post_thumbnail', has_post_thumbnail() ) ) : ?>
				<a class="entry-thumbnail" href="<?php the_permalink(); ?>">
					<?php echo apply_filters( 'asteroid_excerpt_thumbnail' , get_the_post_thumbnail( get_the_ID(), 'thumbnail' ) ); ?>
				</a>
			<?php endif; ?>
		<?php endif; ?>

		<div class="entry-excerpt"><?php the_excerpt(); ?></div>

	<?php else : ?>

		<?php the_content(); ?>

	<?php endif; ?>

	<?php wp_link_pages( array(
		'before'           => '<div class="page-nav">' . __('<span>Pages</span>', 'asteroid'),
		'after'            => '</div>',
		'link_before'      => '<span>',
		'link_after'       => '</span>',
		'next_or_number'   => 'number',
		'nextpagelink'     => __('Next page', 'asteroid'),
		'previouspagelink' => __('Previous page', 'asteroid'),
		'pagelink'         => '%',
		'echo'             => 1 )
		);
	?>

</div>

<footer class="entry-footer cf">
	<?php if ( ( asteroid_option('ast_post_display_type') == 1 ) && ( (get_post_type() == 'post') || (get_post_type() == 'page') ) ) : ?>
		<a href="<?php the_permalink(); ?>" class="continue-reading cf">
			<?php $asteroid_continue_reading_text = ( get_post_type() == 'page' ) ? __('Read Page', 'asteroid') : __('Read Post', 'asteroid'); ?>
			<?php echo apply_filters( 'asteroid_continue_reading_text', $asteroid_continue_reading_text ); ?>
		</a>

		<!-- Widgets: Below Excerpts -->
		<?php if ( is_active_sidebar('widgets_below_excerpts') )  : ?>
			<div id="widgets-wrap-below-excerpts" class="cf"><?php dynamic_sidebar('widgets_below_excerpts'); ?></div>
		<?php endif; ?>

	<?php else : ?>
		<div class="entry-tags"><?php the_tags(); ?></div>
	<?php endif; ?>

	<?php if ( ( asteroid_option('ast_blog_comment_links') == 1 ) && ( (get_post_type() == 'post') || (get_post_type() == 'page') ) ) : ?>
		<a class="comment-count cf" href="<?php the_permalink(); ?>#comment-area"><?php comments_number(); ?></a>
	<?php endif; ?>
</footer>

</article>