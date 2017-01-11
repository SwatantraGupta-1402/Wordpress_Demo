<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package bloger Lite
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function bloger_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
    
    if (is_page('fullwidth-sidebar-home')|| is_page('full-width-sidebar-category-page')){
        $classes [] = 'fullwidth-sidebar-home';
    }
    
    if (is_page('gridview-home') || is_page('grid-view-category-page')){
        $classes [] = 'gridview-home';
    }
    
    if (is_page('fullwidth-home') || is_page('full-width-category-page')){
        $classes [] = 'fullwidth-home';
    }

	return $classes;
}
add_filter( 'body_class', 'bloger_body_classes' );

// for hom page slider     
    function bloger_home_slider_cb(){
        $slider_cat = get_theme_mod('bloger_slider_category');
        if(!empty($slider_cat)){
            $slider_args = array(
                'post_type' => 'post',
                'cat' => $slider_cat,
                'order'=>'DESC'
            );
            $slider_query = new WP_Query($slider_args);
            if($slider_query->have_posts()){
                ?> <div class="home_slider_header"> <?php
                        while($slider_query->have_posts()){
                            $slider_query->the_post();
                            $image = wp_get_attachment_image_src(get_post_thumbnail_id(),'bloger-homeslider-image-size');
                            $image_src = $image['0'];
                            $content = get_the_content();
                            ?>
                                    <div class="item">
                                        <div class="owl_slider_image"> <img src="<?php echo esc_url($image_src); ?>" /></div>
                                        <div class="slider_contents_wrap">
                                            <div class="slider_inner_wrap">
                                                <div class="owl_slider_title"><?php the_title(); ?></div>
                                                <div class="owl_slider_content"><?php echo wp_trim_words(wp_kses_post($content),'10',''); ?></div>
                                                <div class="owl_slider_date"><?php echo esc_attr(get_the_date()); ?></div>
                                            </div>
                                        </div>
                                        
                                    </div>
                            <?php
                        }
                        ?>
                </div> <?php
                wp_reset_postdata();
            }
   }
}
add_action('bloger_home_slider','bloger_home_slider_cb',10);
function bloger_header_footer_social_link(){
                $social_link = array('facebook','twitter','youtube','pinterest','instagram','linkedin','googleplus','flickr');
                foreach($social_link as $social_links){
                    
                    $social_links_val = get_theme_mod('bloger_'.$social_links.'_text');
                    if($social_links == 'googleplus'){
                        if($social_links_val){
                            echo '<div class="fa_link_wrap">';
                            ?> <a target="_blank" href="<?php echo esc_url($social_links_val); ?>"> <?php
                                echo '<span class="fa_wrap">';
                                    echo '<i class="fa fa-google-plus" aria-hidden="true"></i>';
                                echo '</span>';
                                echo '<div class="link_wrap">';
                                    ?>
                                        <?php echo esc_attr($social_links); ?>  
                                    <?php
                                echo '</div>';
                                ?></a>   <?php
                            echo '</div>';
                        }
                    }
                    elseif($social_links == 'pinterest'){
                        if($social_links_val){
                            echo '<div class="fa_link_wrap">';
                            ?><a target="_blank" href="<?php echo esc_url($social_links_val); ?>"><?php
                                echo '<span class="fa_wrap">';
                                echo '<i class="fa fa-pinterest-p" aria-hidden="true"></i>';
                                echo '</span>';
                                echo '<div class="link_wrap">';
                                    ?>
                                        <?php echo esc_attr($social_links); ?>   
                                    <?php
                                echo '</div>';
                                ?> </a> <?php
                            echo '</div>';
                        }
                    }
                    else{
                            if($social_links_val){
                            echo '<div class="fa_link_wrap">';
                            ?> <a target="_blank" href="<?php echo esc_url($social_links_val) ?>"> <?php
                                echo '<span class="fa_wrap">';
                                    ?>
                                        <i class="fa fa-<?php echo esc_attr($social_links); ?>"></i>
                                    <?php
                                echo '</span>';
                                echo '<div class="link_wrap">';
                                    ?>
                                        <?php echo esc_attr($social_links); ?>    
                                    <?php
                                echo '</div>';
                                ?> </a> <?php
                            echo '</div>';
                        }
                    }
                }
}
add_action('bloger_header_footer_social_link_action','bloger_header_footer_social_link');