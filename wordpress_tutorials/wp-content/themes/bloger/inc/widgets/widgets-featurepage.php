<?php
/**
 * Call To Action
 *
 * @package bloger Lite
 */

/**
 * Adds Featuer page display widget.
 */
add_action( 'widgets_init', 'bloger_register_featured_page_widget' );
function bloger_register_featured_page_widget() {
    register_widget( 'bloger_featured_page_widget' );
}
class bloger_Featured_Page_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'bloger_featured_page',
			__('Bloger : Author','bloger'),
			array(
				'description'	=> __( 'A widget To Display Author Profile', 'bloger' )
			)
		);
	}

	/**
	 * Helper function that holds widget fields
	 * Array is used in update and form functions
	 */
	 private function widget_fields() {
		$fields = array(
			'feat_page_title' => array(
                'bloger_widgets_name' => 'feat_page_title',
                'bloger_widgets_title' => __('Page Title','bloger'),
                'bloger_widgets_field_type' => 'text',
                'bloger_widgets_description' => __('Displays the Page Title if left empty','bloger'),
            ),
            'feat_page_id' => array(
                'bloger_widgets_name' => 'feat_page_id',
                'bloger_widgets_title' => __('Author Page','bloger'),
                'bloger_widgets_field_type' => 'selectpage'
            ),
            'feat_page_autograph' => array(
                'bloger_widgets_name' => 'feat_page_autograph',
                'bloger_widgets_title' => __('Signature Image','bloger'),
                'bloger_widgets_field_type' => 'upload'
            ),
            'facebook_link' => array(
                'bloger_widgets_name' => 'facebook_link',
                'bloger_widgets_title' => __('Facebook Link','bloger'),
                'bloger_widgets_field_type' => 'text',
            ),
            'twitter_link' => array(
                'bloger_widgets_name' => 'twitter_link',
                'bloger_widgets_title' => __('Twitter Link','bloger'),
                'bloger_widgets_field_type' => 'text',
            ),
            'youtube_link' => array(
                'bloger_widgets_name' => 'youtube_link',
                'bloger_widgets_title' => __('Youtube Link','bloger'),
                'bloger_widgets_field_type' => 'text',
            ),
            'pinterest_link' => array(
                'bloger_widgets_name' => 'pinterest_link',
                'bloger_widgets_title' => __('Pinterest Link','bloger'),
                'bloger_widgets_field_type' => 'text',
            ),
            'instagram_link' => array(
                'bloger_widgets_name' => 'instagram_link',
                'bloger_widgets_title' => __('Instagram Link','bloger'),
                'bloger_widgets_field_type' => 'text',
            ),
            'linkedin_link' => array(
                'bloger_widgets_name' => 'linkedin_link',
                'bloger_widgets_title' => __('linkedin Link','bloger'),
                'bloger_widgets_field_type' => 'text',
            ),
            'googleplus_link' => array(
                'bloger_widgets_name' => 'googleplus_link',
                'bloger_widgets_title' => __('GooglePlus Link','bloger'),
                'bloger_widgets_field_type' => 'text',
            ),
            'flickr_link' => array(
                'bloger_widgets_name' => 'flickr_link',
                'bloger_widgets_title' => __('Flickr Link','bloger'),
                'bloger_widgets_field_type' => 'text',
            ),
            
		);
		
		return $fields;
	 }


	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
        extract($args);
        if($instance!=null){
        $feat_page_id = $instance['feat_page_id'];
        $autograph = $instance['feat_page_autograph'];
        if($feat_page_id){
        $feat_page_query = new WP_Query('page_id='.$feat_page_id);
        
        $feat_page_title = empty($instance['feat_page_title']) ? false : $instance['feat_page_title'];

        $facebook = $instance['facebook_link'];
        $twitter = $instance['twitter_link'];
        $youtube = $instance['youtube_link'];
        $pinterest = $instance['pinterest_link'];
        $instagram = $instance['instagram_link'];
        $linkedin = $instance['linkedin_link'];
        $googleplus = $instance['googleplus_link'];
        $flickr = $instance['flickr_link'];
        echo $before_widget;
            ?>
            <?php if($feat_page_query->have_posts()) : ?>
                <?php while($feat_page_query->have_posts()) : $feat_page_query->the_post(); ?> 
                <div class="feat-page-wrap">
                    <?php if(!empty($feat_page_title)) : ?>
                        <h2 class="widget-title"><span class="feature_title_wrap"><?php echo esc_attr($feat_page_title); ?></span></h2>
                    <?php else : ?>
                        <h2 class="widget-title"><?php the_title(); ?></h2>
                    <?php endif; ?>
                    <?php $img_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'feature-page-img', false ); ?>
                    <?php if(has_post_thumbnail()): ?>
                        <figure class="feat_image_widget">
                        <img src="<?php echo esc_url($img_src[0]); ?>"/>
                        </figure>
                    <?php endif; ?>
                    <div class="feat-page-content">
                        <?php echo esc_attr(wp_trim_words(get_the_content(),22)); ?>
                    </div>
                    <span class="read_more_feature">
                        <a href="<?php the_permalink() ?>"><?php _e('Read More','bloger') ?><i class="fa fa-long-arrow-right"></i></a>
                    </span>
                    <?php if($autograph){ ?>
                        <div class="feature_autograph">
                            <img src="<?php echo esc_url($autograph); ?>" />
                        </div>
                    <?php } ?>
                         <div class="social_share">
                            <?php 
                                if($facebook){
                                    ?> <a target="_blank" href="<?php echo esc_url($facebook); ?>"><i class="fa fa-facebook"></i></a> <?php
                                }
                                if($twitter){
                                    ?> <a target="_blank" href="<?php echo esc_url($twitter); ?>"><i class="fa fa-twitter"></i></a> <?php
                                }
                                if($youtube){
                                    ?> <a target="_blank" href="<?php echo esc_url($youtube); ?>"><i class="fa fa-youtube"></i></a> <?php
                                }
                                if($pinterest){
                                    ?> <a target="_blank" href="<?php echo esc_url($pinterest); ?>"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a> <?php
                                }
                                if($instagram){
                                    ?> <a target="_blank" href="<?php echo esc_url($instagram); ?>"><i class="fa fa-instagram"></i></a> <?php
                                }
                                if($linkedin){
                                    ?> <a target="_blank" href="<?php echo esc_url($linkedin); ?>"><i class="fa fa-linkedin"></i></a> <?php
                                }
                                if($googleplus){
                                     ?> <a target="_blank" href="<?php echo esc_url($googleplus); ?>"><i class="fa fa-google-plus" aria-hidden="true"></i></a> <?php
                                }
                                if($flickr){
                                    ?> <a target="_blank" href="<?php echo esc_url($flickr); ?>"><i class="fa fa-flickr"></i></a> <?php
                                }
                            ?>
                        </div><!-- .social_share -->
                </div>
                <?php endwhile; ?>
            <?php endif; 
            }
        echo $after_widget;
        }
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param	array	$new_instance	Values just sent to be saved.
	 * @param	array	$old_instance	Previously saved values from database.
	 *
	 * @uses	bloger_widgets_updated_field_value()		defined in widget-fields.php
	 *
	 * @return	array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$widget_fields = $this->widget_fields();

		// Loop through fields
		foreach( $widget_fields as $widget_field ) {

			extract( $widget_field );
	
			// Use helper function to get updated field values
			$instance[$bloger_widgets_name] = bloger_widgets_updated_field_value( $widget_field, $new_instance[$bloger_widgets_name] );
			echo $instance[$bloger_widgets_name];
			
		}
				
		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param	array $instance Previously saved values from database.
	 *
	 * @uses	accesspress_pro_widgets_show_widget_field()		defined in widget-fields.php
	 */
	public function form( $instance ) {
		$widget_fields = $this->widget_fields();

		// Loop through fields
		foreach( $widget_fields as $widget_field ) {
			// Make array elements available as variables 
			extract( $widget_field );
			$bloger_widgets_field_value = isset( $instance[$bloger_widgets_name] ) ? esc_attr( $instance[$bloger_widgets_name] ) : '';
			bloger_widgets_show_widget_field( $this, $widget_field, $bloger_widgets_field_value );
		}	
	}

}