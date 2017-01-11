<?php // Template Name: Sitemap ?>
<?php get_header(); ?>

<div id="content" class="cf">
	<?php do_action('ast_hook_before_content'); ?>

	<div class="single-wrap sitemap-template cf">

	<?php the_post(); the_content(); ?>

		<h3><?php _e('Pages', 'asteroid'); ?></h3>
			<ul><?php wp_list_pages('title_li='); ?></ul>

		<h3><?php _e('Categories', 'asteroid'); ?></h3>
			<ul><?php wp_list_categories('title_li=', 'sort_column=name&optioncount=1&hierarchical=0&feed=RSS'); ?></ul>

		<h3><?php _e('Recent Posts', 'asteroid'); ?></h3>
			<ul><?php
					$archive_query = new WP_Query('showposts=40&cat=-8');
					while ($archive_query->have_posts()) : $archive_query->the_post();
				?>
					<li><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></li>
				<?php endwhile; ?>
			</ul>
	</div>
	<?php do_action('ast_hook_after_content'); ?>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>