<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package bloger Lite
 */

?>

</div><!-- #content -->

<footer id="colophon" class="site-footer" role="contentinfo">
    <?php if(is_active_sidebar('bloger_footer_four_sidebar')){ ?>
    <div class="footer_layer_two clearfix">
        <?php dynamic_sidebar('bloger_footer_four_sidebar'); ?>      
    </div>
    <?php } ?>  
    <div class="site-info">
        <div class="bloger-wrapper">
            <div class="footer_social_icon_front">
                <?php 
                $bloger_footer_social_link = get_theme_mod('bloger_footer_social_icon_enable');
                if($bloger_footer_social_link){
                    do_action('bloger_header_footer_social_link_action');
                } ?>
            </div>
            <div class="footer_btm_right">
                <a href="javascript:void(0);" class="move_to_top_bloger"><i class="fa fa-angle-up" aria-hidden="true"></i></a>
            </div>
            <div class="footer_btm_left">
                <?php
                $bloger_footer_copyright_text = get_theme_mod('bloger_footer_text_setting');
                ?><span class="footer_copy_text"><?php  if($bloger_footer_copyright_text){ ?> <span class="footer_text"> <?php echo esc_attr($bloger_footer_copyright_text); ?> </span> <?php } ?> <?php _e(' | WordPress Theme : Bloger By ','bloger');?><a target="_blank" href="<?php echo esc_url('http://accesspressthemes.com/'); ?>"><?php _e('AccessPress Themes','bloger'); ?></a></span>
            </div>
        </div>
    </div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
