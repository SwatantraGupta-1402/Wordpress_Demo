<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package bloger Lite
 */

?>
<div class="artical_wraper">
<article id="post-<?php the_ID(); ?>" <?php post_class('post_content_article single_page_wrap'); ?>>
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
        <a class="bloger_post_title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </div>
    <div class="excerpt_post_content"><?php
     the_content(); ?>
     </div>
   </div><!-- .entry-content -->
</article><!-- #post-## -->
</div>