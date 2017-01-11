<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php do_action('ast_hook_before_body'); ?>

	<?php if ( is_active_sidebar('widgets_body') ) : ?>
		<div id="body-widgets-wrap"><?php dynamic_sidebar('widgets_body'); ?></div>
	<?php endif ; ?>

<div id="container" class="cf" <?php asteroid_schema( 'container' ); ?>>
	<?php do_action('ast_hook_before_container'); ?>

	<div id="header" class="cf" <?php asteroid_schema( 'header' ); ?>>
		<?php do_action('ast_hook_before_header'); ?>
		<div id="header-info-wrap" class="cf">
			<?php if ( asteroid_option('ast_header_logo') != '' ) : ?>
				<div id="header-logo" class="cf"><a href="<?php echo esc_url( home_url('/') ); ?>">
					<img src="<?php echo asteroid_option('ast_header_logo'); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>" /></a>
				</div>
			<?php else : ?>
				<?php if (! ('blank' == get_header_textcolor() )) : ?>
					<div id="header-text" class="cf">
						<?php $htag = is_singular() ? 'h2' : 'h1'; ?>
						<?php echo '<' . $htag . ' id="site-title">'; ?><a href="<?php echo esc_url( home_url('/') ); ?>"><?php echo get_bloginfo( 'name' ); ?></a><?php echo '</' . $htag . '>'; ?>
						<h4 id="site-description"><?php bloginfo( 'description' ); ?></h4>		
					</div>
				<?php endif; ?>
			<?php endif ; ?>
		</div>

		<?php if ( is_active_sidebar('widgets_header') ) : ?>
			<div id="widgets-wrap-header" class="cf"><?php dynamic_sidebar('widgets_header'); ?></div>
		<?php endif; ?>
		<?php do_action('ast_hook_after_header'); ?>
	</div>

	<?php $menu_style = asteroid_option('ast_menu_style', 'stack'); ?>

	<nav id="nav" class="cf <?php echo $menu_style; ?>" <?php asteroid_schema( 'nav-main' ); ?>>
		<?php do_action('ast_hook_before_nav'); ?>

		<?php if ( $menu_style == 'drop' ) : ?>
			<a href="#" class="drop-toggle">&#9776;</a>
		<?php endif; ?>

		<?php 
			wp_nav_menu( array(
				'theme_location' 	=> 	'ast-menu-primary',
				'container' 		=> 	false,
				'fallback_cb'		=>	'wp_page_menu' )
			); 
		?>

		<?php do_action('ast_hook_after_nav'); ?>
	</nav>

	<?php if ( is_active_sidebar('widgets_below_menu') ) : ?>
		<div id="below-menu" class="cf">
			<div id="widgets-wrap-below-menu" class="cf"><?php dynamic_sidebar('widgets_below_menu'); ?></div>
		</div>
	<?php endif; ?>

<div id="main" class="cf">
<?php do_action('ast_hook_before_main'); ?>