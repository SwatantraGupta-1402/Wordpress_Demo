<?php
/**
 * The template for displaying all single posts.
 *
 * @package bloger Lite
 */

get_header(); ?>
    
    <div class="bloger-wrapper">
	<div id="primary" class="content-area <?php if(has_post_thumbnail()){}else{echo 'no_thumbnail';} ?>">
		<main id="main" class="site-main clearfix" role="main">
            <?php            
            global $post;
                $bloger_layout_single_page = get_theme_mod('bloger_single_page_layout_setting');
                if($bloger_layout_single_page = ''){
                    $bloger_layout_single_page = 'fullwidth-single-page';
                }
                if($bloger_layout_single_page == 'fullwidth-single-page'){
                    $bloger_layout_single_page = 'fullwidth-home';
                }else{
                    $bloger_layout_single_page = 'fullwidth-sidebar-home';
                }
            ?>
            <?php
                if(have_posts()){
                    while(have_posts()){
                        the_post();
                        get_template_part( 'template-parts/content', 'single' );
                    }

                    ?>
                    <div class="home_pagination_link">
                    <?php                    
                            the_post_navigation();                   
                   ?>
                   </div>
                   <?php
            				// If comments are open or we have at least one comment, load up the comment template
            				if ( comments_open() || get_comments_number() ) :
            					comments_template();
            				endif;
            			?>
                   <?php
               } ?>
    
		</main><!-- #main -->
	</div><!-- #primary -->
    
        <?php if( $bloger_layout_single_page == 'fullwidth-sidebar-single-page'){ ?>
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
