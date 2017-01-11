<?php
/**
 * bloger Lite Other Customizer
 *
 * @package bloger Lite
 */
/** customizer start ***/

function bloger_customizer( $wp_customize ) {
    $wp_customize->add_panel(
        'bloger_general_panel',
         array(
            'priority' => 10,
            'capability' => 'edit_theme_options',
            'theme_supports' => '',
            'title' => __( 'General Setting', 'bloger' ),
            'description' => __( 'Default section provided by WordPress customizer.', 'bloger' ),
        )
    );
    $wp_customize->get_section( 'title_tagline' )->panel = 'bloger_general_panel';  
    $wp_customize->get_section( 'background_image' )->panel = 'bloger_general_panel';
    $wp_customize->get_section('header_image')->panel = 'bloger_general_panel';
    $wp_customize->get_section( 'title_tagline' )->panel = 'bloger_general_panel';
    $wp_customize->remove_section("colors");
    
    /** Slider part **/
    $wp_customize -> add_panel(
        'bloger_home_slider_panel',
        array(
            'priority' => 20,
            'capability' => 'edit_theme_options',
            'theme_supports' => '',
            'title' => __('Home Page Setting', 'bloger')
        )
       ); 
    
    // choose category of slider   
    $wp_customize->add_section(
        'bloger_home_slider_cat_choose_section',
        array(
            'title' => __('Slider Setting','bloger'),
            'priority' => 20,
            'description' => __('Slider settings','bloger'),
            'panel' => 'bloger_home_slider_panel',
        )
    );
    
    $wp_customize->add_setting(
        'bloger_slider_category',
        array(
            'sanitize_callback' => 'bloger_category_select',
            )
    );
    
    $bloger_cat_list = bloger_category_lists();
    
    $wp_customize->add_control(
        'bloger_slider_category',array(
            'label' => __('Choose Slider Category','bloger'),
            'section' => 'bloger_home_slider_cat_choose_section',
            'type' => 'select',
            'choices' => $bloger_cat_list,
            'priority'=>2
        )
    );
    $wp_customize->add_setting(
        'bloger_slider_enable',
        array(
            'sanitize_callback' => 'bloger_sanitize_checkbox',
        )
    );
    $wp_customize->add_control(
        'bloger_slider_enable',
        array(
            'label'=> __('Enable Slider','bloger'),
            'priority'=>1,
            'type'=>'checkbox',
            'section'=>'bloger_home_slider_cat_choose_section'
        )
    );
    
    
        
    
    
    // Display post in home page Option
       $wp_customize -> add_section(
        'bloger_home_post_display_section',
        array(
            'title' => __('Home Category Post','bloger'),
            'description' => __('Choose Categories to display posts in Home Page','bloger'),
            'priority' => 20,
            'panel' => 'bloger_home_slider_panel'
        )
    );
    //==========================================================================================================
    if( class_exists( 'WP_Customize_Control' ) ):
/** Exclude Multiple Category Control **/
       class bloger_WP_Customize_Select_Mul_Cat_Control extends WP_Customize_Control {
           public function render_content() {
               $cats = $this->bloger_get_cat_list();
               $values = $this->value();
               
               if ( empty( $cats ) )
               return;
               ?>
               <label>
                   <?php if ( ! empty( $this->label ) ) : ?>
                       <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                   <?php endif;
                   if ( ! empty( $this->description ) ) : ?>
                       <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
                   <?php endif; ?>
                   
                   <?php if ( ! empty( $this->label ) ) : ?>
                       <div class="ex-cat-wrap">
                           <?php $cat_arr = explode(',', $values); array_pop($cat_arr); $count = 1; ?>
                           
                           <?php foreach($cats as $id => $label) : ?>
                               <div class="chk-group <?php if($count++%2 == 0){echo "right";}else{echo "left";} ?>">
                                   <input id="ex-cat-<?php echo absint($id); ?>" type="checkbox" value="<?php echo absint($id); ?>" <?php if(in_array($id,$cat_arr)){ echo "checked"; }; ?> />
                                   <label for="ex-cat-<?php echo absint($id); ?>"><?php echo esc_attr($label); ?></label>
                               </div>
                           <?php endforeach; ?>
                       </div>
                       <input type="hidden" <?php $this->input_attrs(); ?> value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
                   <?php endif; ?>    
               </label>
               <?php
           }
           
           public function bloger_get_cat_list() {
               $catlist = array();
               $categories = get_categories( array('hide_empty' => 0) );
               
               foreach($categories as $cat){
                   $catlist[$cat->term_id] = $cat->name;
               }
               
               return $catlist;
           }
       }
endif;

    /** Blog Page Settings **/
           /** Blog Exclude Category Control  **/
           $wp_customize->add_setting( 'bloger_exclude_cat' , array( 'default' => 0, 'sanitize_callback' => 'sanitize_text_field') );
           $wp_customize->add_control( new bloger_WP_Customize_Select_Mul_Cat_Control(
               $wp_customize,
               'bloger_exclude_cat',
               array(
                   'label'      => __( 'Exclude Category', 'bloger' ),
                   'description' => __('Exclude categories from front page', 'bloger'),
                   'section'    => 'bloger_home_post_display_section',
                   'settings'   => 'bloger_exclude_cat',
               )
           ));
           

       //===============================================================================================
 
    
    // Home Feature Post
    $wp_customize->add_section(
        'bloger_home_feature_post',
        array(
            'title'=>__('Home Feature Post','bloger'),
            'description'=>__('Three Feature Post below of slider','bloger'),
            'priority'=>21,
            'panel'=>'bloger_home_slider_panel'
            
        )
    );
    $wp_customize->add_setting(
        'bloger_feature_post_1',
            array(
                'sanitize_callback'=>'bloger_post_select'
            )
    );
    $bloger_post_list = bloger_post_lists();
    $wp_customize->add_control(
        'bloger_feature_post_1',array(
            'label' => __('Feature Post 1','bloger'),
            'section' => 'bloger_home_feature_post',
            'type' => 'select',
            'choices' => $bloger_post_list,
            'priority'=>2
        )
    );
    $wp_customize->add_setting(
        'bloger_feature_post_2',
            array(
                'sanitize_callback'=>'bloger_post_select'
            )
    );
    
    $wp_customize->add_control(
        'bloger_feature_post_2',array(
            'label' => __('Feature Post 2','bloger'),
            'section' => 'bloger_home_feature_post',
            'type' => 'select',
            'choices' => $bloger_post_list,
            'priority'=>2
        )
    );
    $wp_customize->add_setting(
        'bloger_feature_post_3',
            array(
                'sanitize_callback'=>'bloger_post_select'
            )
    );
    
    $wp_customize->add_control(
        'bloger_feature_post_3',array(
            'label' => __('Feature Post 3','bloger'),
            'section' => 'bloger_home_feature_post',
            'type' => 'select',
            'choices' => $bloger_post_list,
            'priority'=>2
        )
    );
    $wp_customize->add_setting(
        'bloger_feature_post_enable',
        array(
            'sanitize_callback' => 'bloger_sanitize_checkbox',
        )
    );
    $wp_customize->add_control(
        'bloger_feature_post_enable',
        array(
            'label'=> __('Enable Feature post','bloger'),
            'priority'=>1,
            'type'=>'checkbox',
            'section'=>'bloger_home_feature_post'
        )
    );

    
    // Design setting 
    $wp_customize -> add_panel(
        'bloger_design_setting_panel',
        array(
            'priority' => 20,
            'capability' => 'edit_theme_options',
            'theme_supports' => '',
            'title' => __('Design Setting', 'bloger')
        )
    ); 
    
        
    $wp_customize -> add_section(
        'bloger_home_page_layout_section',
        array(
            'title' => __('Home Page Layout','bloger'),
            'priority' => 20,
            'panel' => 'bloger_design_setting_panel'
        )
    );
    
    $wp_customize -> add_setting(
        'bloger_home_page_layout_setting',
        array(
            'default' => 'fullwidth-sidebar-home',
            'sanitize_callback' => 'bloger_sanitize_homelayout_radio'
        )
    );
    
    $wp_customize -> add_control(
        'bloger_home_page_layout_setting',
        array(
            'label' => __('Home Layout Option', 'bloger'),
            'section' => 'bloger_home_page_layout_section',
            'type' => 'radio',
            'choices' => array(
                            'fullwidth-home' => __('FullWidth','bloger'),
                            'gridview-home' => __('Grid view','bloger'),
                            'fullwidth-sidebar-home' => __('Full Width With Sidebar','bloger'),
                        )
        )
    );
     
    
/*** Category page settings ****/    
    // Category page  Setting
    
    $wp_customize -> add_section(
        'bloger_category_page_section',
        array(
            'title' => __('Archive Page Layout','bloger'),
            'priority' => 20,
            'panel' => 'bloger_design_setting_panel'
        )
    );
    
    
    $wp_customize -> add_setting(
        'bloger_category_page_layout_setting',
        array(
            'default' => 'fullwidth-category-page',
            'sanitize_callback' => 'bloger_sanitize_category_radio'
        )
    ); 
    
    $wp_customize -> add_control(
        'bloger_category_page_layout_setting',
        array(
            'label' => __('Category Layout Option', 'bloger'),
            'section' => 'bloger_category_page_section',
            'type' => 'radio',
            'choices' => array(
                            'fullwidth-category-page' => __('FullWidth','bloger'),
                            'gridview-category-page' => __('Grid view','bloger'),
                            'fullwidth-sidebar-category-page' => __('Full Width With Sidebar','bloger'),
                        )
        )
    );
    
    /** category page (End) **/
    
    /*** Single Page settings ****/ 
       
    // Single Page layout
    
    $wp_customize -> add_section(
        'bloger_single_page_layout_section',
        array(
            'title' => __('Single Page Layout','bloger'),
            'priority' => 20,
            'panel' => 'bloger_design_setting_panel'
        )
    );
    
    
    $wp_customize -> add_setting(
        'bloger_single_page_layout_setting',
        array(
            'default' => 'fullwidth-single-page',
            'sanitize_callback' => 'bloger_sanitize_singlepage_radio'
        )
    ); 
    
    $wp_customize -> add_control(
        'bloger_single_page_layout_setting',
        array(
            'label' => __('Single Page Layout Option', 'bloger'),
            'section' => 'bloger_single_page_layout_section',
            'type' => 'radio',
            'choices' => array(
                            'fullwidth-single-page' => __('FullWidth','bloger'),
                            'fullwidth-sidebar-single-page' => __('Full Width With Sidebar','bloger'),
                        )
        )
    );
    
    /** category page (End) **/
    
    
    
    // END OF DESIGN PANEL
    
    // Footer Setting
    $wp_customize -> add_panel(
        'bloger_footer_setting_panel',
        array(
            'priority' => 20,
            'capability' => 'edit_theme_options',
            'theme_supports' => '',
            'title' => __('Footer Setting', 'bloger')
        )
    ); 
    
    $wp_customize -> add_section(
        'bloger_footer_text_section',
        array(
            'title' => __('Footer Text Option','bloger'),
            'priority' => 20,
            'panel' => 'bloger_footer_setting_panel'
        )
    );
    
    $wp_customize -> add_setting(
        'bloger_footer_text_setting',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field'
        )
    );    
    
    $wp_customize -> add_control(
        'bloger_footer_text_setting',
        array(
            'label' => __('Footer Text (Left)','bloger'),
            'section' => 'bloger_footer_text_section',
            'type' => 'text',
        )
    );
    
    
    
    
//Header And Footer social link
    $wp_customize->add_section(
        'bloger_social_link',
        array(
            'title' =>__('Header & Footer Social Link','bloger'),
            'panel' =>'bloger_general_panel',
        )
    );
    
    $wp_customize->add_setting(
        'bloger_header_social_icon_enable',
            array(
                'default' => '',
                'sanitize_callback'=>'bloger_sanitize_checkbox'
            )
    );
    $wp_customize->add_control(
        'bloger_header_social_icon_enable',
            array(
                'label' => __('Enable Header Social link','bloger'),
                'section' => 'bloger_social_link',
                'type' => 'checkbox',
                'priority' => 1
            )
    );
    $wp_customize->add_setting(
        'bloger_footer_social_icon_enable',
            array(
                'default' => '',
                'sanitize_callback'=>'bloger_sanitize_checkbox'
            )
    );
    $wp_customize->add_control(
        'bloger_footer_social_icon_enable',
        array(
            'label' => __('Footer Social Link','bloger'),
            'section' => 'bloger_social_link',
            'type' => 'checkbox',
            'priority' => 2
        )
    );
    $wp_customize->add_setting(
        'bloger_facebook_text',
             array(
                'default'=>'',
                'sanitize_callback' => 'sanitize_text_field',
            )
    );
    $wp_customize->add_setting(
        'bloger_twitter_text',
             array(
                'default'=>'',
                'sanitize_callback' => 'sanitize_text_field',
            )
    );
    $wp_customize->add_setting(
        'bloger_youtube_text',
             array(
                'default'=>'',
                'sanitize_callback' => 'sanitize_text_field',
            )
    );
    $wp_customize->add_setting(
        'bloger_pinterest_text',
             array(
                'default'=>'',
                'sanitize_callback' => 'sanitize_text_field',
            )
    );
    $wp_customize->add_setting(
        'bloger_instagram_text',
             array(
                'default'=>'',
                'sanitize_callback' => 'sanitize_text_field',
            )
    );
    $wp_customize->add_setting(
        'bloger_linkedin_text',
             array(
                'default'=>'',
                'sanitize_callback' => 'sanitize_text_field',
            )
    );
    $wp_customize->add_setting(
        'bloger_googleplus_text',
             array(
                'default'=>'',
                'sanitize_callback' => 'sanitize_text_field',
            )
    );
    $wp_customize->add_setting(
        'bloger_flickr_text',
             array(
                'default'=>'',
                'sanitize_callback' => 'sanitize_text_field',
            )
    );
        $wp_customize->add_control(
        'bloger_facebook_text',
            array(
                'label' => __('Facebook Link','bloger'),
                'section' => 'bloger_social_link',
                'type' => 'text',
            )
    );
    $wp_customize->add_control(
        'bloger_twitter_text',
            array(
                'label' => __('Twitter Link','bloger'),
                'section' => 'bloger_social_link',
                'type' => 'text',
            )
    );
    $wp_customize->add_control(
        'bloger_youtube_text',
            array(
                'label' => __('Youtube Link','bloger'),
                'section' => 'bloger_social_link',
                'type' => 'text',
            )
    );
    $wp_customize->add_control(
        'bloger_pinterest_text',
            array(
                'label' => __('Pinterest Link','bloger'),
                'section' => 'bloger_social_link',
                'type' => 'text',
            )
    );
    $wp_customize->add_control(
        'bloger_instagram_text',
            array(
                'label' => __('Instagram Link','bloger'),
                'section' => 'bloger_social_link',
                'type' => 'text',
            )
    );
    $wp_customize->add_control(
        'bloger_linkedin_text',
            array(
                'label' => __('Linkedin Link','bloger'),
                'section' => 'bloger_social_link',
                'type' => 'text',
            )
    );
    $wp_customize->add_control(
        'bloger_googleplus_text',
            array(
                'label' => __('GooglePlus Link','bloger'),
                'section' => 'bloger_social_link',
                'type' => 'text',
            )
    );
    $wp_customize->add_control(
        'bloger_flickr_text',
            array(
                'label' => __('Flickr Link','bloger'),
                'section' => 'bloger_social_link',
                'type' => 'text',
            )
    );
    
    
    //add web page layout
    $wp_customize->add_section(
        'bloger_design_web_layout',
        array(
            'title'         =>  __('Web Layout', 'bloger'),
            'description'   =>  __('', 'bloger'),
            'panel'         =>  'bloger_design_setting_panel'
            )        
        );
    $wp_customize -> add_setting(
        'bloger_layout_option',
        array(
            'default'       =>  'full_width',
            'sanitize_callback' => 'bloger_web_layout',
            )

        );
    $wp_customize -> add_control(
        'bloger_layout_option',
        array(
            'label'         =>  __('Website  Layout','bloger'),
            'description'   =>  __('Make your website either box layout or full width from click away', 'bloger'),
            'type'          =>  'radio',
            'section'       =>  'bloger_design_web_layout',
            'choices'       =>  array(
                'box_layout'    =>  __('Box Layout','bloger'),
                'full_width'    =>  __('Full Width','bloger')
                )
            )

        );
    
    
    //Checkbox sanitization customizer
    function bloger_sanitize_checkbox( $input ) {
        if ( $input == 1 ) {
            return 1;
        } else {
            return '';
        }
    }
    
    //General dropdown sanitize for integer value
    function bloger_category_select( $input ) {
        $bloger_cat_list = bloger_category_lists();
      if ( array_key_exists( $input, $bloger_cat_list ) ) {
       return $input;
     } else {
       return '';
     }
    }
    function bloger_post_select( $input ) {
        $bloger_post_list = bloger_post_lists();
      if ( array_key_exists( $input, $bloger_post_list ) ) {
       return $input;
     } else {
       return '';
     }
    }

    function bloger_web_layout($input){
      $valid_keys = array( 
            'box_layout'    =>  __('Box Layout','bloger'),
            'full_width'    =>  __('Full Width','bloger')
        );
      if ( array_key_exists( $input, $valid_keys ) ) {
       return $input;
     } else {
       return '';
     }

    }
    // function bloger_to sanitize background pattern
    function bloger_sanitize_singlepage_radio($input){
        $valid_keys = array(
			'fullwidth-single-page' => __('FullWidth','bloger'),
            'fullwidth-sidebar-single-page' => __('Full Width With Sidebar','bloger'),
			);
		if ( array_key_exists( $input, $valid_keys)) {
			return $input;
		} else {
			return '';
		}
    }
    // function bloger_to sanitize background pattern
    function bloger_sanitize_category_radio($input){
        $valid_keys = array(
			'fullwidth-category-page' => __('FullWidth','bloger'),
            'gridview-category-page' => __('Grid view','bloger'),
            'fullwidth-sidebar-category-page' => __('Full Width With Sidebar','bloger'),
			);
		if ( array_key_exists( $input, $valid_keys)) {
			return $input;
		} else {
			return '';
		}
    }
    // function bloger_to sanitize background pattern
    function bloger_sanitize_homelayout_radio($input){
        $valid_keys = array(
			'fullwidth-home' => __('fullwidth-home', 'bloger'),
			'gridview-home' => __('gridview-home', 'bloger'),
            'fullwidth-sidebar-home' => __('fullwidth-sidebar-home', 'bloger'),
			);
		if ( array_key_exists( $input, $valid_keys)) {
			return $input;
		} else {
			return '';
		}
    }
    /*****************************/
    } // end of customizer
    add_action( 'customize_register', 'bloger_customizer' );
?>