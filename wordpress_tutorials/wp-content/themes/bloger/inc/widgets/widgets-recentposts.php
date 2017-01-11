<?php
/**
 * Recent Post
 *
 * @package bloger Lite
 */

/**
 * Adds Recent post display widget.
 */
add_action( 'widgets_init', 'bloger_register_recent_posts_widget' );
function bloger_register_recent_posts_widget() {
    register_widget( 'bloger_recent_posts_widget' );
}
class bloger_recent_posts_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'bloger_recent_posts',
			__('Bloger : Recent Posts','bloger'),
			array(
				'description'	=> __( 'A widget To Display Recent Posts', 'bloger' )
			)
		);
	}

	/**
	 * Helper function that holds widget fields
	 * Array is used in update and form functions
	 */
	 private function widget_fields() {
		$fields = array(
            'recent_post_title' => array(
                'bloger_widgets_name' => 'recent_post_title',
                'bloger_widgets_title' => __('Title','bloger'),
                'bloger_widgets_field_type' => 'text',
            ),
			'recent_post_show_num' => array(
                'bloger_widgets_name' => 'recent_post_show_num',
                'bloger_widgets_title' => __('No of posts to show','bloger'),
                'bloger_widgets_field_type' => 'number',
                'bloger_widgets_description' => __('Displays the latest five post if left empty','bloger'),
            ),
            'recent_post_show_img' => array(
                'bloger_widgets_name' => 'recent_post_show_img',
                'bloger_widgets_title' => __('Display post image?','bloger'),
                'bloger_widgets_field_type' => 'checkbox',
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
        $post_num = empty($instance['recent_post_show_num']) ? '5' : $instance['recent_post_show_num'];
        
        $recent_args = array(
                        'post_type' =>'post',
                            'posts_per_page' => $post_num,
                            'order' => 'DESC',
                            'status' => 'publish',
                        );
        $recent_post_query = new WP_Query($recent_args);
        
        $recent_post_title = empty($instance['recent_post_title']) ? false : $instance['recent_post_title'];
        $show_img = empty($instance['recent_post_show_img']) ? false : $instance['recent_post_show_img'];
        
        echo $before_widget;
            ?>
            <?php if(!empty($recent_post_title)){ ?>
                <h2 class="widget-title"><span class="recent_post_wrap"><?php echo esc_attr($recent_post_title); ?></span></h2>
            <?php }else{ ?>
                <h2 class="widget-title"><span class="recent_post_wrap"><?php _e('Recent Posts','bloger') ?></span></h2>
            <?php } ?>
            <?php if($recent_post_query->have_posts()) : ?>
                <?php while($recent_post_query->have_posts()) : $recent_post_query->the_post(); ?> 
                <div class="recent-post-wrap">
                    <?php $img_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'bloger-recent-post-thumb', false ); ?>
                    <?php if(has_post_thumbnail() && !empty($show_img)): ?>
                    <div class="image_wrap_recent">
                        <a href="<?php the_permalink(); ?>" class="img_recent_post_img"><img src="<?php echo esc_url($img_src[0]); ?>"/></a>
                    </div>
                    <?php endif; ?>
                    <div class="recent-post-content">
                        <a href="<?php the_permalink(); ?>" class="recent-post-title-widget"><?php the_title(); ?></a>
                        <span class="date_recent_post"><?php echo get_the_date(); ?></span>
                    </div>
                </div>
                <?php endwhile; 
                wp_reset_postdata();?>
            <?php endif; ?>
            <?php
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