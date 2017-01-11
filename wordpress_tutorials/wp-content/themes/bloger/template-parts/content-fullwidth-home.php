<?php
/**
 * The template used for displaying latest post in full width in home page
 *
 * @package bloger Lite
 */
?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('post_content_article'); ?>>
    
        <?php
        $bloger_comment_count = get_comments_number();
        $bloger_slide_cat = get_the_category();?>
        <div class="entry-content">
            <?php
                $bloger_img_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'bloger-post-image-withsidebar', false );
                if($bloger_img_src){ 
            ?>
            <div class="bloger_img_wrap">
              <a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url($bloger_img_src[0]); ?>" /></a>
            </div>
            <?php } ?>
            <div class="title_cat_wrap">
                <?php if($bloger_slide_cat){ ?><a class="bloger_cat" href="<?php echo esc_url(get_category_link( $bloger_slide_cat[0]->term_id )); ?>"><?php echo esc_attr($bloger_slide_cat[0]->name);?></a><?php } ?>
                <a class="bloger_post_title" href="<?php the_permalink(); ?>"><?php echo esc_attr(get_the_title()); ?></a>
            </div>
            <div class="date_comment_author">
                <div class="wrap11">
                    <span class="date_post"><?php echo esc_attr(get_the_date()); ?></span>
                    <?php $bloger_author = get_the_author(); ?> 
                    <span class="author_post"><?php echo esc_attr($bloger_author); ?></span>
                    <?php if($bloger_comment_count > '0'){ ?>
                    <span class="post_comment"><i class="fa fa-comments"></i><span><?php echo esc_attr($bloger_comment_count); ?></span><?php  _e('Comment','bloger'); ?></span>
                    <?php } ?>
                </div>        
             </div>
             <div class="excerpt_post_content"><?php
                echo apply_filters('the_content' , wp_kses_post(wp_trim_words(get_the_content(),100,'...')));?>
             </div>
             <div class="read_more_share">
                 <a class="continue_link" href="<?php the_permalink(); ?>"><?php  _e('Read More','bloger'); ?> <i class="fa fa-angle-right"></i></a>
                <footer class="entry-footer">
                    <div class="social_share">
                        <?php 
                            if( is_plugin_active( 'accesspress-social-share/accesspress-social-share.php' ) ) {
                              if(is_active_sidebar('bloger_home_post_social_share')){
                                ?> <span class="share_text"><i class="fa fa-share-alt" aria-hidden="true"></i></span> <?php
                                dynamic_sidebar('bloger_home_post_social_share');
                              }
                            }
                        ?>
                     </div>
                </footer><!-- .entry-footer -->
             </div>
       </div><!-- .entry-content -->
    </article><!-- #post-## -->