<?php
/**
 * Asteroid Theme Setup & Functions.
 * @package Asteroid
 *
 */
$ast_version = "1.2.2";
/*-------------------------------------
	Setup Theme Options
--------------------------------------*/
require( get_template_directory() . '/includes/theme-options.php' );


/*-------------------------------------
	Register Styles & Scripts
--------------------------------------*/
function asteroid_enqueue_styles() {
	global $ast_version;
	
	if ( is_child_theme() )
		wp_enqueue_style( 'asteroid-parent', get_template_directory_uri() . '/style.css' );
	
	wp_enqueue_style( 'asteroid-main', get_stylesheet_uri(), array(), $ast_version );

	if ( asteroid_option('ast_responsive_disable', 0) != 1 )
		wp_enqueue_style( 'asteroid-responsive', get_template_directory_uri() . '/responsive.css', array(), $ast_version );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'asteroid_enqueue_styles' );


/*-------------------------------------
	Asteroid Theme Setup
--------------------------------------*/
function asteroid_theme_setup() {
	global $content_width;

	load_theme_textdomain( 'asteroid', get_template_directory() . '/languages' );

	register_nav_menu( 'ast-menu-primary', 'Primary' );

	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'title-tag' );

	add_theme_support( 'post-thumbnails' );

	add_theme_support( 'custom-background', array(
		'default-color' => 'FFFFFF',
		'default-image' => get_template_directory_uri() . '/images/bg-grey.png',
	) );

	add_theme_support( 'custom-header', array(
		'default-image'          => '',
		'random-default'         => false,
		'width'                  => asteroid_option('ast_content_width') + asteroid_option('ast_sidebar_width'),
		'height'                 => asteroid_option('ast_header_height'),
		'flex-height'            => true,
		'flex-width'             => true,
		'default-text-color'     => 'FFA900',
		'header-text'            => true,
		'uploads'                => true,
		'wp-head-callback'       => '',
		'admin-head-callback'    => '',
		'admin-preview-callback' => ''
	) );

	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	add_filter( 'widget_text', 'do_shortcode' );

	if ( asteroid_option('ast_responsive_disable', 0) != 1 )
		add_action( 'wp_head', 'asteroid_meta_viewport', 1 );

	add_action( 'wp_head', 'asteroid_meta_charset', 0 );
	add_action( 'wp_head', 'asteroid_link_profile', 1 );
	add_action( 'wp_head', 'asteroid_link_pingback', 1 );

	if ( !isset( $content_width ) ) $content_width = asteroid_option('ast_content_width') - 20;

	add_action( 'wp_head', 'asteroid_print_head_codes' );
	add_action( 'wp_head', 'asteroid_print_layout' );

	add_action( 'wp_head', 'asteroid_header_image' );
	add_action( 'wp_head', 'asteroid_header_text_color' );

	if ( asteroid_option('ast_custom_css') ) add_action( 'wp_head', 'asteroid_print_custom_css', 990 );

	if ( asteroid_option('ast_post_editor_style') == 0 ) asteroid_wp_editor_style();

	if ( asteroid_option('ast_menu_search') == 1 ) add_filter( 'wp_nav_menu_items', 'asteroid_menu_search_form', 10, 2 );

	if ( asteroid_option('ast_responsive_disable', 0) != 1 ) add_action( 'wp_footer', 'asteroid_menu_style_script', 18 );
}
add_action( 'after_setup_theme', 'asteroid_theme_setup' );


/*----------------------------------------
	Asteroid WP Title
-----------------------------------------*/
if ( ! function_exists( '_wp_render_title_tag' ) ) {
	add_action( 'wp_head', 'asteroid_wp_title_render', 1 );
}

function asteroid_wp_title_render() {
?>
<title><?php wp_title( '|', true, 'right' ); ?></title>
<?php
}

/*----------------------------------------
	Register Sidebars
-----------------------------------------*/
function asteroid_register_sidebars() {
	register_sidebar(array(
		'name' 			=> __('Sidebar', 'asteroid'),
		'id' 			=> 'widgets_sidebar',
		'before_widget' => '<div id="%1$s" class="widget-sidebar asteroid-widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h4 class="widget-title">',
		'after_title' 	=> '</h4>') );

	register_sidebar(array(
		'name' 			=> __('Header', 'asteroid'),
		'id' 			=> 'widgets_header',
		'before_widget' => '<div id="%1$s" class="widget-header asteroid-widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h4 class="widget-title">',
		'after_title' 	=> '</h4>') );

	register_sidebar(array(
		'name' 			=> __('Footer: Full Width', 'asteroid'),
		'id' 			=> 'widgets_footer_full',
		'description'	=> __('Widget spans the entire width of the footer. Ideal for horizontal banners & 728x90 ads.', 'asteroid'),
		'before_widget' => '<div id="%1$s" class="widget-footer-full asteroid-widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h4 class="widget-title">',
		'after_title' 	=> '</h4>') );

	register_sidebar(array(
		'name' 			=> __('Footer: 3 Column', 'asteroid'),
		'id' 			=> 'widgets_footer_3',
		'description'	=> __('Widgets are arranged into 3 columns.', 'asteroid'),
		'before_widget' => '<div id="%1$s" class="widget-footer-3 asteroid-widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h4 class="widget-title">',
		'after_title' 	=> '</h4>') );

	if ( asteroid_option('ast_widget_body') == 1 ) {
		register_sidebar(array(
			'name' 			=> __('Body', 'asteroid'),
			'id' 			=> 'widgets_body',
			'before_widget' => '<div id="%1$s" class="widget-body asteroid-widget %2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<h4 class="widget-title">',
			'after_title' 	=> '</h4>') );
	}

	if ( asteroid_option('ast_widget_below_menu') == 1 ) {
		register_sidebar(array(
			'name' 			=> __('Below Menu', 'asteroid'),
			'id' 			=> 'widgets_below_menu',
			'description'	=> __('Widget spans the entire width of the container. Ideal for horizontal banners & 728x90 ads.', 'asteroid'),
			'before_widget' => '<div id="%1$s" class="widget-below-menu asteroid-widget %2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<h4 class="widget-title">',
			'after_title' 	=> '</h4>') );
	}

	if ( asteroid_option('ast_widget_before_content') == 1 ) {
		register_sidebar(array(
			'name' 			=> __('Before Content', 'asteroid'),
			'id' 			=> 'widgets_before_content',
			'before_widget' => '<div id="%1$s" class="widget-before-content asteroid-widget %2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<h4 class="widget-title">',
			'after_title' 	=> '</h4>') );
	}

	if ( asteroid_option('ast_widget_below_excerpts') == 1 ) {
		register_sidebar(array(
			'name' 			=> __('Below Excerpts', 'asteroid'),
			'id' 			=> 'widgets_below_excerpts',
			'before_widget' => '<div id="%1$s" class="widget-below-excerpts asteroid-widget %2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<h4 class="widget-title">',
			'after_title' 	=> '</h4>') );
	}

	if ( asteroid_option('ast_widget_before_post') == 1 ) {
		register_sidebar(array(
			'name' 			=> __('Before Post', 'asteroid'),
			'id' 			=> 'widgets_before_post',
			'before_widget' => '<div id="%1$s" class="widget-before-post asteroid-widget %2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<h4 class="widget-title">',
			'after_title' 	=> '</h4>') );
	}

	if ( asteroid_option('ast_widget_before_post_content') == 1 ) {
		register_sidebar(array(
			'name' 			=> __('Before Post - Content', 'asteroid'),
			'id' 			=> 'widgets_before_post_content',
			'before_widget' => '<div id="%1$s" class="widget-before-post-content asteroid-widget %2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<h4 class="widget-title">',
			'after_title' 	=> '</h4>') );
	}

	if ( asteroid_option('ast_widget_after_post_content') == 1 ) {
		register_sidebar(array(
			'name' 			=> __('After Post - Content', 'asteroid'),
			'id' 			=> 'widgets_after_post_content',
			'before_widget' => '<div id="%1$s" class="widget-after-post-content asteroid-widget %2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<h4 class="widget-title">',
			'after_title' 	=> '</h4>') );
	}

	if ( asteroid_option('ast_widget_after_post') == 1 ) {
		register_sidebar(array(
			'name' 			=> __('After Post', 'asteroid'),
			'id' 			=> 'widgets_after_post',
			'before_widget' => '<div id="%1$s" class="widget-after-post asteroid-widget %2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<h4 class="widget-title">',
			'after_title' 	=> '</h4>') );
	}
}
add_action( 'widgets_init', 'asteroid_register_sidebars' );


/*-------------------------------------
	Add Common <head> Items
--------------------------------------*/
function asteroid_meta_charset() {
	echo '<meta charset="' . get_bloginfo( 'charset' ) . '" />' . "\n";
}

function asteroid_meta_viewport() {
	echo '<meta name="viewport" content="initial-scale=1.0" />' . "\n";
}

function asteroid_link_profile() {
	echo '<link rel="profile" href="http://gmpg.org/xfn/11" />' . "\n";
}

function asteroid_link_pingback() {
	echo '<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '" />' . "\n";
}


/*-------------------------------------
	Print Head Codes - Theme Setup
--------------------------------------*/
function asteroid_print_head_codes() {
	global $ast_version;
echo '
<!-- Asteroid Head -->
<meta property="Asteroid Theme" content="' . $ast_version . '" />' . "\n";

if ( asteroid_option('ast_favicon') ) {
	echo '<link rel="icon" href="' . esc_url( asteroid_option('ast_favicon') ) . '" type="image/x-icon" />' . "\n";
}
echo asteroid_option('ast_head_codes') . "\n";
echo '<!-- Asteroid Head End -->' . "\n";
}

/*-------------------------------------
	Print Layout CSS - Theme Setup
--------------------------------------*/
function asteroid_print_layout() {
	$content_x = asteroid_option('ast_content_width');
	$sidebar_x = asteroid_option('ast_sidebar_width');
	
echo '
<style type="text/css" media="screen">
	#container {width: ' . ( $content_x + $sidebar_x + 16 ) . 'px;}
	#header {
		min-height: ' . asteroid_option('ast_header_height') . 'px;
		background-color: #' . asteroid_option('ast_header_bgcolor') . ';
	}
	#content {
		width: ' . $content_x . 'px;
		max-width: ' . $content_x . 'px;
		background-color: #' . asteroid_option('ast_content_bgcolor') . ';
	}
	#sidebar {
		width: ' . $sidebar_x . 'px;
		max-width: ' . $sidebar_x . 'px;
		background-color: #' . asteroid_option('ast_sidebar_bgcolor') . ';
	}
</style>' . "\n\n";
}

/*-------------------------------------
	Header Background - Theme Setup
--------------------------------------*/
function asteroid_header_image() {
	if ( get_header_image() == '' ) return;
echo '
<style type="text/css" media="screen">
	#header {
		background-image: url(\'' . get_header_image() . '\');
		background-size: ' . get_custom_header()->width . 'px ' . get_custom_header()->height . 'px;
	}
</style>' . "\n\n";
}

/*-------------------------------------
	Header Text Color - Theme Setup
--------------------------------------*/
function asteroid_header_text_color() {
	if ( get_header_textcolor() == 'FFA900' ) return;
echo '
<style type="text/css" media="screen">
	#site-title a, #site-description {color:#' . get_header_textcolor() . ';}
</style>' . "\n\n";
}

/*-------------------------------------
	Custom CSS - Theme Setup
--------------------------------------*/
function asteroid_print_custom_css() {
echo '
<!-- Asteroid Custom CSS -->
<style type="text/css" media="screen">
' . asteroid_option('ast_custom_css') . '
</style>
<!-- Asteroid Custom CSS End -->' . "\n\n";
}

/*----------------------------------------
	Add Custom CSS to Post Editor
-----------------------------------------*/
function asteroid_wp_editor_style() {
	add_editor_style();
	add_action( 'before_wp_tiny_mce', 'asteroid_tinymce_width' );
}

function asteroid_tinymce_width() {
	global $content_width;
?>
<script type="text/javascript">
jQuery( document ).ready( function() {
	var editor_width = '.mce-content-body {width: <?php echo $content_width; ?>px;}';
	var checkInterval = setInterval(
		function() {
			if ( 'undefined' !== typeof( tinyMCE ) ) {
				if ( tinyMCE.activeEditor && ! tinyMCE.activeEditor.isHidden() ) {
					jQuery( '#content_ifr' ).contents().find( 'head' ).append( '<style>' + editor_width + '</style>' );
					clearInterval( checkInterval );
				}
			}
	}, 500 );
} );
</script>
<?php
}

function asteroid_menu_style_script() {
	if ( asteroid_option('ast_menu_style', 'stack') == 'stack' ) : ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.menu-item-has-children').click(function(){
		$('.menu > .menu-item-has-children').not(this).removeClass('toggle-on');
		$(this).not().parents().removeClass('toggle-on');
		$(this).toggleClass('toggle-on');
	});
});
</script>
<?php else : ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#nav .drop-toggle').click(function(){
		$('#nav').toggleClass('dropped');
	});
});
</script>
<?php endif;
}

function asteroid_menu_search_form( $items, $args ) {
	if( $args->theme_location == 'ast-menu-primary' ) {
		$sf = '<li class="menu-item menu-item-search">';
		$sf .= '<form id="nav-search-form" role="search" method="get" action="' . home_url( '/' ) . '">';
		$sf .= '<label><span class="screen-reader-text">Search for:</span><input type="search" value="Search" onfocus="if (this.value == \'Search\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \'Search\';}" name="s" /></label>';
		$sf .= '<input type="submit" value="" />';
		$sf .= '</form>';
		$sf .= '</li>';
		$items = $items . $sf;
	}
	return $items;
}

/*-------------------------------------
	Schema.org Markup
--------------------------------------*/
function asteroid_schema( $name ) {
	echo apply_filters( 'asteroid_schema', asteroid_get_schema( $name ) );
}

function asteroid_get_schema( $name ) {

	$output = '';
	$itemprop = '';
	$itemscope = false;
	$itemtype = '';

	switch ( $name ) {

		case 'container' :
			$itemscope = true;

			switch ( true ) {
				case ( is_author() ) :
					$itemtype = 'ProfilePage';
					break;

				case ( is_search() ) :
					$itemtype = 'SearchResultsPage';
					break;

				default:
					$itemtype = 'WebPage';
			}
			break;

		case 'header' :
			$itemscope = true;
			$itemtype = 'WPHeader';
			break;

		case 'nav-main' :
			$itemscope = true;
			$itemtype = 'SiteNavigationElement';
			break;

		case 'content' :
			if ( !is_single() ) return;
			$itemscope = true;
			$itemtype = 'Blog';
			break;

		case 'sidebar' :
			$itemscope = true;
			$itemtype = 'WPSideBar';
			break;

		case 'footer' :
			$itemscope = true;
			$itemtype = 'WPFooter';
			break;

		case 'article' :
			if ( !is_single() ) return;
			$itemprop = 'blogPost';
			$itemscope = true;
			$itemtype = 'BlogPosting';
			break;

		case 'entry-title' :
			$itemprop = 'headline';
			break;

		case 'entry-author' :
			$itemprop = 'author';
			$itemscope = true;
			$itemtype = 'Person';
			break;

		case 'entry-date' :
			$itemprop = 'datePublished';
			break;

		case 'entry-content' :
			$itemprop = 'text';
			break;

		case 'author-name' :
			$itemprop = 'name';
			break;

		case 'entry-updated' :
			$itemprop = 'dateModified';
			break;
	}


	if ( $itemprop != '' ) {
		$output .= 'itemprop="' . $itemprop . '" ';
	}

	if ( $itemscope == true ) {
		$output .= 'itemscope ';
	}

	if ( $itemtype != '' ) {
		$output .= 'itemtype="http://schema.org/' . $itemtype . '"';
	}

	return $output;
}