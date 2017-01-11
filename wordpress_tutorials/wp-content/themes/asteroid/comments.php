<?php if ( post_password_required() ) return; ?>

<div id="comments" class="cf">
	<?php if ( have_comments() ) : ?>

		<div id="comment-title" class="cf">
			<h3><?php _e('Comments', 'asteroid'); ?></h3>
		</div>

		<ol class="comment-list">
			<?php
				$args = array(
					'style'			=> 'ol',
					'short_ping'	=> true,
					'avatar_size'	=> 50,
				);
				wp_list_comments( apply_filters( 'asteroid_comment_list_args', $args ) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav class="comment-navigation">
				<div class="comment-nav"><?php paginate_comments_links(); ?></div>
			</nav>
		<?php endif; ?>

	<?php endif; ?>

	<?php if ( !comments_open() && get_comments_number() ) : ?>
		<div id="respond"><p id="closed"><?php _e('Comments are closed', 'asteroid'); ?></p></div>
	<?php endif; ?>

	<?php comment_form(); ?>
</div>