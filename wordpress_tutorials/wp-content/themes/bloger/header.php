<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package bloger Lite
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'bloger' ); ?></a>
    
	<header id="masthead" class="site-header" role="banner">
    <div class="header_social_search_wrap clearfix">
        <div class="bloger-wrapper">
                <div class="header_social_search_wrap_second">
                    <?php $bloger_header_social_link = get_theme_mod('bloger_header_social_icon_enable'); 
                    if($bloger_header_social_link){ ?>
                    <div class="header_social_icon">
                        <?php do_action('bloger_header_footer_social_link_action'); ?>
                    </div>
                    <?php } ?>
                    <div class="search_header">
                        <div class="search_form_wrap">
                            <?php echo get_search_form(); ?>
                            
                        </div>
                    </div>
                </div>
        </div>
         </div>
       
       <?php do_action('bloger_action_custom_logo'); ?>

</header><!-- #masthead -->

<nav id="site-navigation" class="main-navigation" role="navigation">
    <div class="bloger-wrapper">
        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( '', 'bloger' ); ?>
            <span class="menu-bar-wrap">
                <span class="menu-bar"></span>
                <span class="menu-bar bar-middle"></span>
                <span class="menu-bar"></span>
            </span>
        </button>
        <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
    </div>
</nav><!-- #site-navigation -->


<div id="content" class="site-content">

    <!-- slider section -->
    <?php
    
    if(is_home() || is_front_page() ):
    $bloger_slider_cat = get_theme_mod('bloger_slider_category');
    $bloger_slider_enable = get_theme_mod('bloger_slider_enable');
    if($bloger_slider_enable || $bloger_slider_cat){ ?>
        <div class="bloger-slider-wrapper">
            <div class="bloger-container">
                <?php do_action('bloger_home_slider'); ?>
            </div>
        </div>
    <?php }
    endif; ?>

