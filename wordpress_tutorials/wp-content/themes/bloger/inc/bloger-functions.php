<?php
/**
 * Bloog Lite Functions
 *
 * @package Bloog Lite
 */

if ( ! function_exists( 'is_plugin_active' ) ){
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

    // to insert box or full width design in homepage.
if( ! function_exists( 'bloger_custom_header_tag' ) ){
    function bloger_custom_header_tag($classes){
        if(is_home() || is_front_page()){
            $classes[] = esc_attr(get_theme_mod('bloger_home_page_layout_setting','fullwidth-sidebar-home'));
        }
        return $classes;
    }
}
add_filter( 'body_class', 'bloger_custom_header_tag' );

    // to insert box or full width design in category page.
if( ! function_exists( 'bloger_custom_header_tag_category_page' ) ){
    function bloger_custom_header_tag_category_page($classes){
        if(is_category() || is_archive()){
            $classes[] = esc_attr(get_theme_mod('bloger_category_page_layout_setting','fullwidth-category-page'));
        }
        
        return $classes;
    }
}
add_filter( 'body_class', 'bloger_custom_header_tag_category_page' );


function bloger_custom_header_tag_search_page($classes){
    if(is_search()){
        $classes[] = 'fullwidth-sidebar-home';            
    }
    return $classes;
}
add_filter( 'body_class', 'bloger_custom_header_tag_search_page' );

    // to insert box or full width design in Single page.
if( ! function_exists( 'bloger_custom_header_tag_single_page' ) ){
    function bloger_custom_header_tag_single_page($classes){
        if(is_single() || is_page_template('tpl-about.php') || is_page('contact-us')){
            $classes[] = esc_attr(get_theme_mod('bloger_single_page_layout_setting','fullwidth-single-page'));
        }
        return $classes;
    }
}
add_filter( 'body_class', 'bloger_custom_header_tag_single_page' );


    // to enque jquery 
function bloger_media_uploader()
    {
    $currentscreen = get_current_screen();
    if($currentscreen->id == 'widgets'){
        wp_enqueue_script( 'uploader-script', get_template_directory_uri().'/inc/admin-panel/js/media-uploader.js', array(),false,true);
        wp_enqueue_media();
        }
}
add_action( 'admin_enqueue_scripts', 'bloger_media_uploader' );
/** Exclude Categories from Blog Page **/
function bloger_exclude_category_from_blogpost($query) {
   $exclude_category = esc_attr(get_theme_mod('bloger_exclude_cat')); 
   $ex_cats = explode(',', $exclude_category);
   array_pop($ex_cats);
   
   if ( $query->is_home() ) {
       $query->set('category__not_in', $ex_cats);
   }
   return $query;
}
add_filter('pre_get_posts', 'bloger_exclude_category_from_blogpost');

function bloger_custom_logo() {
    
            if(get_header_image()){ ?>
                <div class="header-logo-container">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php header_image(); ?>" alt="<?php _e('Header Logo','bloger'); ?>" /></a>
                </div>
    			<?php }
            else{
                ?>
                <div class="header-logo-container">
                    <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                    <?php
                    $description = get_bloginfo( 'description','display' );
                    if ( $description || is_customize_preview() ) : ?>
                        <p class="site-description"><?php echo esc_attr($description); /* WPCS: xss ok. */ ?></p>
                    <?php
                    endif;
                ?>
                </div>
                <?php
            }
    }
add_action('bloger_action_custom_logo','bloger_custom_logo');

function bloger_category_lists(){
        $categories = get_categories(
            array(
                'hide_empty' => 0,
                'exclude' => 1
            )
        );
        $category_lists = array();
        $category_lists[''] = __('Select Category', 'bloger');
        foreach($categories as $category) :
            $category_lists[$category->term_id] = $category->name;
        endforeach;
        return $category_lists;
}

function bloger_post_lists(){
        wp_reset_postdata();
        $posts = get_posts(array('posts_per_page'   => -1));
        $post_lists = array();
        $post_lists[] = __('Select post', 'bloger'); 
        foreach($posts as $post) :
            $post_lists[$post->ID] = $post->post_title;
        endforeach;
        return $post_lists;
    }