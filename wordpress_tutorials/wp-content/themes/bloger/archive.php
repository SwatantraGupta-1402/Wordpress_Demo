<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package bloger Lite
 */

get_header(); ?>
<div class="bloger-wrapper">
   <div id="primary" class="content-area">
          <main id="main" class="site-main clearfix" role="main">
            <?php
            global $post;
            $bloger_layout_home = get_theme_mod('bloger_category_page_layout_setting','fullwidth-category-page');
            if($bloger_layout_home == 'fullwidth-category-page'){
                $bloger_layout_home = 'fullwidth-home';
            }
            elseif( $bloger_layout_home == 'gridview-category-page'){
                $bloger_layout_home = 'gridview-home';
            }
            else{
                $bloger_layout_home = 'fullwidth-sidebar-home';
            }
            
            if(have_posts()){
                while(have_posts()){
                    the_post();
                    get_template_part( 'template-parts/content', $bloger_layout_home );
                }
                ?>
                <div class="home_pagination_link">
                    <?php the_posts_pagination(); ?>
                </div>
                <?php
            }
            ?>
        </main><!-- #main -->
    </div><!-- #primary -->
        <?php if( $bloger_layout_home == 'fullwidth-sidebar-home'){ ?>
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