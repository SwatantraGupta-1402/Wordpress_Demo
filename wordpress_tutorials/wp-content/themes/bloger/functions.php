<?php
/**
 * bloger Lite functions and definitions
 *
 * @package bloger Lite
 */

if ( ! function_exists( 'bloger_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function bloger_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on bloger Lite, use a find and replace
	 * to change 'bloger' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'bloger', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'bloger' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'bloger_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // bloger_setup

/**
 *  Cropping image to a required size
*/
 add_image_size('bloger-post-image-size', 940, 627, true); // post image size 
 add_image_size('bloger-homeslider-image-size', 1170, 500, true); // post image home slider size
 add_image_size('bloger-post-image-withsidebar', 1170, 697, true); // home page with sidebar
 add_image_size('bloger-about-us-page-img', 840, 840, true); // home page with sidebar
 add_image_size('bloger-grid-view-img', 300, 200, true); // home page with sidebar
 add_image_size ('bloger-feature-page-img', 292, 169); // feature page image display in sidebar and footer sidebar
 add_image_size ('bloger-recent-post-thumb', 480, 300, true);
 add_image_size('bloger-feature-post-thumb' , 290, 180, true);
 

add_action( 'after_setup_theme', 'bloger_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function bloger_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'bloger_content_width', 640 );
}
add_action( 'after_setup_theme', 'bloger_content_width', 0 );

//adding class to body boxed/full-width
function bloger_bodyclass($classes){
	$classes[]= esc_attr(get_theme_mod('bloger_layout_option'));
	return $classes;
}
add_filter('body_class','bloger_bodyclass' );

/**
 * Enqueue scripts and styles.
 */
function bloger_scripts() {
	$query_args = array('family' => 'Playfair+Display:400,300,700,serif|Raleway:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic,sans-serif|Lato:400,300,700,900,100,sans-serif|Merriweather:400,300,700,900,100,Arial,Helvetica,sans-serif');
	wp_enqueue_style('google-fonts', add_query_arg($query_args, "//fonts.googleapis.com/css"));
	wp_enqueue_style('bloger-style', get_stylesheet_uri() );
    wp_enqueue_style('font-awesome',get_template_directory_uri().'/css/fawesome/css/font-awesome.css');
    wp_enqueue_style('bloger-responsive', get_template_directory_uri() . '/css/responsive.css', array() );
    wp_enqueue_style('owl-carousel',get_template_directory_uri().'/js/owl-carousel/owl.carousel.css');

	wp_enqueue_script('owl-carousel',get_template_directory_uri().'/js/owl-carousel/owl.carousel.js',array('jquery'));
    wp_enqueue_script('theia-sticky-sidebar', get_template_directory_uri().'/js/theia-sticky-sidebar.js',array('jquery'));
    wp_enqueue_script('navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
    wp_enqueue_script('bloger-custom', get_template_directory_uri() . '/js/custom.js', array('jquery','owl-carousel','theia-sticky-sidebar'), '4.2.2', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bloger_scripts' );
function bloger_custom_customize_enqueue() {
       wp_enqueue_script( 'bloger-custom-customize', get_template_directory_uri() . '/js/customizer-js.js', array( 'jquery', 'customize-controls' ), false );
   }
   add_action( 'customize_controls_enqueue_scripts', 'bloger_custom_customize_enqueue' );


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';
/**
 * Bloger function
 */
require get_template_directory() . '/inc/bloger-functions.php';

/**
 * Load customizer line
 */
require get_template_directory() . '/inc/bloger-customizer.php';

/**
 *  Load bloger widgets in site
 */
 require get_template_directory() . '/inc/bloger-widgets.php';

 // Enable the use of shortcodes in text widgets.
add_filter( 'widget_text', 'do_shortcode' );
 /**
  * Plugin Activation suggest
  * */
 require get_template_directory().'/inc/tgm-plugin-activation.php';
function bloger_register_required_plugins() {

$plugins = array(
    array(
        'name'      => 'AccessPress Custom CSS',
        'slug'      => 'accesspress-custom-css',
        'required'  => false,
    ),
    array(
        'name'      => 'Accesspress Instagram Feed',
        'slug'      => 'accesspress-instagram-feed',
        'required'  => false,
    ),
    array(
        'name'      => 'AccessPress Social Share',
        'slug'      => 'accesspress-social-share',
        'required'  => false,
    ),
    array(
        'name'      => 'Accesspress Pinterest',
        'slug'      => 'accesspress-pinterest',
        'required'  => false,
    ),
    
);

$config = array(
    'id'           => 'bloger',                 // Unique ID for hashing notices for multiple instances of TGMPA.
    'default_path' => '',                      // Default absolute path to pre-packaged plugins.
    'menu'         => 'tgmpa-install-plugins', // Menu slug.
    'parent_slug'  => 'themes.php',            // Parent menu slug.
    'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
    'has_notices'  => true,                    // Show admin notices or not.
    'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
    'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
    'is_automatic' => true,                   // Automatically activate plugins after installation or not.
    'message'      => '',                      // Message to output right before the plugins table.
    'strings'      => array(
        'page_title'                      => __( 'Install Required Plugins', 'bloger' ),
        'menu_title'                      => __( 'Install Plugins', 'bloger' ),
        'installing'                      => __( 'Installing Plugin: %s', 'bloger' ), // %s = plugin name.
        'oops'                            => __( 'Something went wrong with the plugin API.', 'bloger' ),
        'notice_can_install_required'     => _n_noop(
            'This theme requires the following plugin: %1$s.',
            'This theme requires the following plugins: %1$s.',
            'bloger'
        ), // %1$s = plugin name(s).
        'notice_can_install_recommended'  => _n_noop(
            'This theme recommends the following plugin: %1$s.',
            'This theme recommends the following plugins: %1$s.',
            'bloger'
        ), // %1$s = plugin name(s).
        'notice_cannot_install'           => _n_noop(
            'Sorry, but you do not have the correct permissions to install the %1$s plugin.',
            'Sorry, but you do not have the correct permissions to install the %1$s plugins.',
            'bloger'
        ), // %1$s = plugin name(s).
        'notice_ask_to_update'            => _n_noop(
            'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
            'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
            'bloger'
        ), // %1$s = plugin name(s).
        'notice_ask_to_update_maybe'      => _n_noop(
            'There is an update available for: %1$s.',
            'There are updates available for the following plugins: %1$s.',
            'bloger'
        ), // %1$s = plugin name(s).
        'notice_cannot_update'            => _n_noop(
            'Sorry, but you do not have the correct permissions to update the %1$s plugin.',
            'Sorry, but you do not have the correct permissions to update the %1$s plugins.',
            'bloger'
        ), // %1$s = plugin name(s).
        'notice_can_activate_required'    => _n_noop(
            'The following required plugin is currently inactive: %1$s.',
            'The following required plugins are currently inactive: %1$s.',
            'bloger'
        ), // %1$s = plugin name(s).
        'notice_can_activate_recommended' => _n_noop(
            'The following recommended plugin is currently inactive: %1$s.',
            'The following recommended plugins are currently inactive: %1$s.',
            'bloger'
        ), // %1$s = plugin name(s).
        'notice_cannot_activate'          => _n_noop(
            'Sorry, but you do not have the correct permissions to activate the %1$s plugin.',
            'Sorry, but you do not have the correct permissions to activate the %1$s plugins.',
            'bloger'
        ), // %1$s = plugin name(s).
        'install_link'                    => _n_noop(
            'Begin installing plugin',
            'Begin installing plugins',
            'bloger'
        ),
        'update_link'                     => _n_noop(
            'Begin updating plugin',
            'Begin updating plugins',
            'bloger'
        ),
        'activate_link'                   => _n_noop(
            'Begin activating plugin',
            'Begin activating plugins',
            'bloger'
        ),
        'return'                          => __( 'Return to Required Plugins Installer', 'bloger' ),
        'plugin_activated'                => __( 'Plugin activated successfully.', 'bloger' ),
        'activated_successfully'          => __( 'The following plugin was activated successfully:', 'bloger' ),
        'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'bloger' ),  // %1$s = plugin name(s).
        'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'bloger' ),  // %1$s = plugin name(s).
        'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'bloger' ), // %s = dashboard link.
        'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'bloger' ),

        'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
    )
);

tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'bloger_register_required_plugins' );