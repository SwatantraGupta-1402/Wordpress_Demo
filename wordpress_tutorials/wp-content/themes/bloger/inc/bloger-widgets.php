<?php
/**
 * bloger Lite Custom Sidebar
 *
 * @package bloger Lite
 */
 add_action('widgets_init','bloger_additional_widgets');
 
 function bloger_additional_widgets(){
    
    // Registering main right sidebar
	register_sidebar( array(
		'name' 				=> __( 'Right Sidebar', 'bloger' ),
		'id' 					=> 'bloger_right_sidebar',
		'description'   	=> __( 'Shows widgets at Right side.', 'bloger' ),
		'before_widget' 	=> '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  	=> '</aside>',
		'before_title'  	=> '<h3 class="widget-title"><span>',
		'after_title'   	=> '</span></h3>'
	) );	


    // Registering footer for layer 2
	register_sidebar( array(
		'name' 				=> __( 'Footer Area', 'bloger' ),
		'id' 					=> 'bloger_footer_four_sidebar',
		'description'   	=> __( 'Shows widgets at footer layer two', 'bloger' ),
		'before_widget' 	=> '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  	=> '</aside>',
		'before_title'  	=> '<div class="bloger-wrapper"><h3 class="widget-title"><span>',
		'after_title'   	=> '</span></div></h3>'
	) );

    // Registering footer for layer 3
    register_sidebar( array(
		'name' 				=> __( 'Home Category Post Social Share', 'bloger' ),
		'id' 					=> 'bloger_home_post_social_share',
		'description'   	=> __( 'Shows widgets at footer layer three', 'bloger' ),
		'before_widget' 	=> '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  	=> '</aside>',
		'before_title'  	=> '<h3 class="widget-title"><span>',
		'after_title'   	=> '</span></h3>'
	) );
 
        
 } // END OF bloger Lite REGISTER SIDEBAR FUNCTION

/**
 * bloger Lite Custom Widgets
 *
 * @package bloger Lite
 */

function bloger_widgets_updated_field_value( $widget_field, $new_field_value ) {
    
	extract( $widget_field );
	
	// Allow only integers in number fields
	if( $aglite_widgets_field_type == 'number' ) {
		return absint( $new_field_value );
		
	// Allow some tags in textareas
	} elseif( $aglite_widgets_field_type == 'textarea' ) {
		// Check if field array specifed allowed tags
		if( !isset( $aglite_widgets_allowed_tags ) ) {
			// If not, fallback to default tags
			$aglite_widgets_allowed_tags = '<p><strong><em><a>';
		}
		return strip_tags( $new_field_value, $aglite_widgets_allowed_tags );
		
	// No allowed tags for all other fields
	} else {
		return strip_tags( $new_field_value );
	}

}

/**
 * Include helper functions that display widget fields in the dashboard
 *
 * @since bloger Lite Widget Pack 1.0
 */
require get_template_directory() . '/inc/widgets/widgets-fields.php';

/**
 * Feature Page Preview Widget
 *
 * @since bloger Lite Widget Pack 1.0
 */
require get_template_directory() . '/inc/widgets/widgets-featurepage.php';

/**
 * Register Post Preview Widget
 *
 * @since bloger Lite Widget Pack 1.0
 */
require get_template_directory() . '/inc/widgets/widgets-recentposts.php';


/**
 * Widget for twitter email and phone
 *
 * @since bloger Lite Widget Pack 1.0
 */