<?php // Template Name: Blank ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div id="post-<?php the_ID(); ?>">
		<?php the_post(); the_content(); ?>
	</div>

	<?php wp_footer(); ?>
</body>
</html>