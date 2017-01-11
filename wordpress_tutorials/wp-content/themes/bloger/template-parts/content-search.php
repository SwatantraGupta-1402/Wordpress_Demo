<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package bloger Lite
 */

?>
<div class="artical_wraper">
    <article id="post-<?php the_ID(); ?>" <?php post_class('post_content_article'); ?>>
    	
        <?php
        $bloger_comment_post_numbers = get_theme_mod('bloger_comment_number_post_setting','1');    
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
            <a class="bloger_post_title" href="<?php the_permalink(); ?>"><?php echo esc_url(get_the_title()); ?></a>
        </div>
        <div class="excerpt_post_content"><?php
         echo apply_filters('the_content' , wp_kses_post(wp_trim_words(get_the_content(),100,'...')));?>
         </div>
         <div class="read_more_share">
             <a class="continue_link" href="<?php the_permalink(); ?>"><?php  _e('Read More','bloger'); ?> <i class="fa fa-angle-right"></i></a>
         </div>
       </div><!-- .entry-content -->
    
       
    </article><!-- #post-## -->
</div>
