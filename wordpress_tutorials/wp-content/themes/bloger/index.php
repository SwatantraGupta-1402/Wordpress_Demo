<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package bloger Lite
 */

get_header(); ?>
<div class="bloger-wrapper default_home">
    <?php $bloger_enable_feature_post = get_theme_mod('bloger_feature_post_enable');
    if($bloger_enable_feature_post){
        $bloger_feature_post_1 = get_theme_mod('bloger_feature_post_1');
        $bloger_feature_post_2 = get_theme_mod('bloger_feature_post_2');
        $bloger_feature_post_3 = get_theme_mod('bloger_feature_post_3');
        $bloger_feature_posts = new WP_Query(array('post_type' => 'post', 'post__in' => array($bloger_feature_post_1, $bloger_feature_post_2, $bloger_feature_post_3)));?>
        <div class="home_feature_post_wrap clearfix">
        <?php 
            if($bloger_feature_posts->have_posts()):
            while($bloger_feature_posts->have_posts()):$bloger_feature_posts->the_post();
            $bloger_img_src = wp_get_attachment_image_src(get_post_thumbnail_id(),'bloger-feature-post-thumb');
        ?>
                <a href="<?php the_permalink(); ?>">
                    <div class="feature_post_contents">
                        <div class="feature_post_image"><img src="<?php echo esc_url($bloger_img_src['0']); ?>" /></div>
                        <div class="title_content_wrap">
                            <div class="title_content_wrap_second">
                                <span class="feature_post_title"><?php the_title(); ?></span>
                                <span class="feature_post_content"><?php echo wp_kses_post(wp_trim_words(get_the_content(),10)); ?></span>
                            </div>
                        </div>
                    </div>
                </a>
        <?php
        endwhile;
        wp_reset_postdata();
        endif;
        ?>
        </div>
<?php } ?>
</div>                        
<div class="bloger-wrapper default_home">
	<div id="primary" class="content-area">
		<main id="main" class="site-main clearfix" role="main">

		<?php if ( have_posts() ) : 
        $bloger_layout_home = get_theme_mod('bloger_home_page_layout_setting');
        if($bloger_layout_home == ''){
            $bloger_layout_home = 'fullwidth-sidebar-home';
        }
        ?>
			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php
					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', $bloger_layout_home );
				?>
			<?php endwhile; ?>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' );
            wp_reset_postdata(); ?>
		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
 if( $bloger_layout_home == 'fullwidth-sidebar-home'){ ?>
    <?php if(is_active_sidebar('bloger_right_sidebar')) : ?>
        <div class="secondary">
            <div id="featured-post-container" class="clearfix">
                <?php dynamic_sidebar('bloger_right_sidebar'); ?>
            </div>
        </div>
    <?php endif; ?>
<?php } ?>
</div>
<?php get_footer(); ?>
