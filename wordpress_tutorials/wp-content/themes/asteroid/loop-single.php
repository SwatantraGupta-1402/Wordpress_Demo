<article id="post-<?php the_ID(); ?>" <?php post_class('single-view'); ?> <?php asteroid_schema( 'article' ); ?>>

<div class="entry-header">
	<h1 class="entry-title" <?php asteroid_schema( 'entry-title' ); ?>><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
</div>

<!-- Widgets: Before Post -->
<?php if ( ( is_active_sidebar('widgets_before_post') ) && is_singular() ) : ?>
	<div id="widgets-wrap-before-post" class="cf"><?php dynamic_sidebar('widgets_before_post'); ?></div>
<?php endif; ?>

<!-- Date & Author -->
<div class="entry-meta-top cf">
	<?php if ( asteroid_option('ast_single_edit_link') == 1 ) edit_post_link( __('Edit', 'asteroid') ); ?>
	<?php if (
		( asteroid_option('ast_single_date') == 1 && is_singular('post') ) ||
		( asteroid_option('ast_single_date') == 2 && is_page() ) ||
		( asteroid_option('ast_single_date') == 3 && is_singular(array( 'post', 'page' )) )
		) :
	?>
		<div class="entry-date" <?php asteroid_schema( 'entry-date' ); ?>><?php the_date(); ?></div>
	<?php endif; ?>

	<?php if (
		( asteroid_option('ast_single_author') == 1 && is_singular('post') ) ||
		( asteroid_option('ast_single_author') == 2 && is_page() ) ||
		( asteroid_option('ast_single_author') == 3 && is_singular(array( 'post', 'page' )) )
		) :
	?>
		<div class="entry-author author vcard" <?php asteroid_schema( 'entry-author' ); ?>>
			<?php $asteroid_post_author_url = get_the_author_meta('user_url') != '' ? get_the_author_meta('user_url') : get_author_posts_url( get_the_author_meta('ID') ); ?>
			<?php _e('Posted by', 'asteroid'); ?>&nbsp;<a class="url fn" href="<?php echo esc_url( $asteroid_post_author_url ); ?>" <?php asteroid_schema( 'author-name' ); ?>><?php the_author(); ?></a>
		</div>
	<?php endif; ?>
</div>

<div class="entry-content cf" <?php asteroid_schema( 'entry-content' ); ?>>

	<!-- Widgets: Before Post Content -->
	<?php if ( ( is_active_sidebar('widgets_before_post_content') ) && is_singular() ) : ?>
		<div id="widgets-wrap-before-post-content" class="cf"><?php dynamic_sidebar('widgets_before_post_content'); ?></div>
	<?php endif; ?>

	<?php the_content(); ?>

	<!-- Widgets: After Post Content -->
	<?php if ( ( is_active_sidebar('widgets_after_post_content') ) && is_singular() ) : ?>
		<div id="widgets-wrap-after-post-content" class="cf"><?php dynamic_sidebar('widgets_after_post_content'); ?></div>
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

<div class="entry-meta-bottom cf">

	<?php if (
		( asteroid_option('ast_date_modified') == 1 && is_singular('post') ) ||
		( asteroid_option('ast_date_modified') == 2 && is_page() ) ||
		( asteroid_option('ast_date_modified') == 3 && is_singular(array( 'post', 'page' )) )
		) :
	?>
		<div class="updated" <?php asteroid_schema( 'entry-updated' ); ?>><?php _e('Updated:', 'asteroid'); ?>&nbsp;<?php the_modified_date(); ?>&nbsp;<?php _e('at', 'asteroid'); ?>&nbsp;<?php the_modified_time(); ?></div>
	<?php endif; ?>

	<div class="entry-tags"><?php the_tags(); ?></div>

	<?php if ( is_attachment() ) : ?>
		<div class="attachment-nav cf">
			<div class="link-prev"><?php previous_image_link(0,__('&laquo; Previous Image', 'asteroid')) ?></div>
			<div class="link-next"><?php next_image_link(0,__('Next Image &raquo;', 'asteroid')) ?></div>
		</div>
	<?php endif; ?>

	<!-- Widgets: After Post -->
	<?php if ( ( is_active_sidebar('widgets_after_post') ) && is_singular() ) : ?>
		<div id="widgets-wrap-after-post" class="cf"><?php dynamic_sidebar('widgets_after_post'); ?></div>
	<?php endif; ?>

	<?php if ( is_singular('post') || is_attachment() ) : ?>
		<div class="post-nav cf">
			<div class="link-prev"><?php previous_post_link('&#x25C0; %link'); ?></div>
			<div class="link-next"><?php next_post_link('%link &#x25B6;'); ?></div>
		</div>
	<?php endif; ?>
</div>

<?php if ( is_singular('post') && ( asteroid_option('ast_post_author_info_box') == 1 ) ) : ?>
	<div class="author-info cf">
		<?php
			$asteroid_author_info = '<h4 class="title">' . __('About the Author', 'asteroid') . '</h4>';
			$asteroid_author_info .= '<div class="author-avatar">' . get_avatar( get_the_author_meta('ID'), 64 ) . '</div>';
			$asteroid_author_info .= '<div class="author-description"><h4>' . get_the_author_link() . '</h4>' . get_the_author_meta('description') . '</div>';

			echo apply_filters( 'asteroid_author_info', $asteroid_author_info );
		?>
	</div>
<?php endif; ?>

<?php if ( ( !is_page() && asteroid_option('ast_post_comments') == 1 ) || ( is_page() && asteroid_option('ast_page_comments') == 1 ) ) : ?>
	<div id="comment-area" class="cf"><?php comments_template(); ?></div>
<?php endif; ?>

</article>