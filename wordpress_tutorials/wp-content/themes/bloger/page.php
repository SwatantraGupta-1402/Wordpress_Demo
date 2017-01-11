<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package bloger Lite
 */

get_header(); 
$bloger_layout_page_single = get_theme_mod('bloger_single_page_layout_setting');
if($bloger_layout_page_single == ''){
    $bloger_layout_page_single = 'fullwidth-single-page';
}
?>

    <div class="bloger-wrapper <?php echo esc_attr($bloger_layout_page_single).'_page'; ?>">
    	<div id="primary" class="content-area <?php if(has_post_thumbnail()){}else{echo 'no_thumbnail';} ?>">
    		<main id="main" class="site-main clearfix" role="main">
                <?php
                global $post;
                    
                    if($bloger_layout_page_single == 'fullwidth-single-page'){
                        $bloger_layout_page_single = 'fullwidth-home';
                    }else{
                        $bloger_layout_page_single = 'fullwidth-sidebar-home';
                    }
                ?>
                <?php
                    if(have_posts()){
                        while(have_posts()){
                            the_post();
                            get_template_part( 'template-parts/content', 'page' );
                        }
            				// If comments are open or we have at least one comment, load up the comment template
            				if ( comments_open() || get_comments_number() ) :
            					comments_template();
            				endif;
                   } ?>
        
    		</main><!-- #main -->
    	</div><!-- #primary -->
        
            <?php if( $bloger_layout_page_single == 'fullwidth-sidebar-home'){ ?>
     			<?php if(is_active_sidebar('bloger_right_sidebar')) : ?>
                <div class="secondary">
                        <div id="featured-post-container" class="clearfix">
                            <?php dynamic_sidebar('bloger_right_sidebar'); ?>
                        </div>
                </div>
                <?php endif; ?>
            <?php } ?>
        
    </div> <!-- end of bloger-wrapper -->
    
<?php get_footer(); ?>
