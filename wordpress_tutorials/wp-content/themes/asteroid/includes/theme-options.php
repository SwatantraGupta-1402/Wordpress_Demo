<?php
function asteroid_admin_bar_menu() {
	if ( current_user_can( 'edit_theme_options' ) ) {
		global $wp_admin_bar;
		$wp_admin_bar->add_menu( array(
			'parent' => false,
			'id' => 'asteroid_admin_bar', 
			'title' => __( 'Asteroid Options', 'asteroid' ), 
			'href' => admin_url('themes.php?page=asteroid-options')
		));
		$wp_admin_bar->add_menu( array(
			'parent' => 'appearance',
			'id' => 'theme_editor_admin_bar', 	
			'title' => __( 'Editor', 'asteroid' ), 
			'href' => admin_url('theme-editor.php')
		));
		$wp_admin_bar->add_menu( array(
			'parent' => 'appearance',
			'id' => 'plugins_admin_bar', 	
			'title' => __( 'Plugins', 'asteroid' ), 
			'href' => admin_url('plugins.php')
		));
	}
}
add_action( 'admin_bar_menu', 'asteroid_admin_bar_menu', 88 );


class Asteroid_Theme_Options {
	
	private $sections;
	private $checkboxes;
	private $settings;

	public function __construct() {

		$this->checkboxes = array();
		$this->settings = array();
		$this->get_option();
		
		$this->sections['general']     	 	= __( 'General', 'asteroid' );
		$this->sections['appearance']  	 	= __( 'Appearance', 'asteroid' );
		$this->sections['post-page']   		= __( 'Posts & Pages', 'asteroid' );
		$this->sections['widget-areas']		= __( 'Widget Areas', 'asteroid' );
		$this->sections['custom-css']  	 	= __( 'Custom CSS', 'asteroid' );
		$this->sections['misc']   			= __( 'Misc', 'asteroid' );
		$this->sections['reset']        	= __( 'Reset', 'asteroid' );
		
		add_action( 'admin_menu', array( &$this, 'ast_add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		
		if ( ! get_option( 'asteroid_options' ) )
			$this->initialize_settings();
		
	}
	
	/* Add page(s) to the admin menu */
	public function ast_add_pages() {
		$admin_menu = add_theme_page( __('Asteroid Options', 'asteroid'), __('Asteroid Options', 'asteroid'), 'edit_theme_options', 'asteroid-options', array( &$this, 'display_page' ) );
		
		add_action( 'admin_print_scripts-' . $admin_menu, array( &$this, 'scripts' ) );
		add_action( 'admin_print_styles-' . $admin_menu, array( &$this, 'styles' ) );	
	}
	
	/* Create settings field */
	public function create_setting( $args = array() ) {

		$defaults = array(
			'id'      => 'default_field',
			'title'   => 'Default Field',
			'desc'    => 'This is a default description.',
			'std'     => '',
			'type'    => 'text',
			'section' => 'general',
			'choices' => array(),
			'class'   => ''
		);

		extract( wp_parse_args( $args, $defaults ) );

		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class
		);

		if ( $type == 'checkbox' )
			$this->checkboxes[] = $id;

		add_settings_field( $id, $title, array( $this, 'display_setting' ), 'asteroid-options', $section, $field_args );

	}

	/*-------------------------------------
	   HTML to display the options page
	--------------------------------------*/
	public function display_page() {

	echo '<div class="wrap">
	<div class="icon32" id="icon-themes"></div>
	<h2>' . __( 'Asteroid Options', 'asteroid' ) . '</h2>';

		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true )
			echo '<div class="updated fade"><p>' . __( 'Theme options updated.', 'asteroid' ) . '</p></div>';

		echo '<form action="options.php" method="post" enctype="multipart/form-data">';

		settings_fields( 'asteroid_options' );
		echo '<div class="ui-tabs">
			<ul class="ui-tabs-nav">';

		foreach ( $this->sections as $section_slug => $section )
			echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';

		echo '</ul><div id="submit-top"><input name="Submit" type="submit" class="button-save" value="' . __( 'SAVE', 'asteroid' ) . '" /></div>';
		do_settings_sections( $_GET['page'] );
		echo '</div>
	</form>';

	echo '
	<script type="text/javascript">
	jQuery(document).ready(function() {
    jQuery(".st_upload_button").click(function() {
         targetfield = jQuery(this).prev(".upload-url");
         tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
         return false;
    });
    window.send_to_editor = function(html) {
         imgurl = jQuery("img",html).attr("src");
         jQuery(targetfield).val(imgurl);
         tb_remove();
    }
	});
	</script>
	<script type="text/javascript" src="' . get_template_directory_uri() . '/includes/js/jscolor/jscolor.js"></script>';

	echo '<script type="text/javascript">
		jQuery(document).ready(function($) {
			var sections = [];';

			foreach ( $this->sections as $section_slug => $section )
				echo "sections['$section'] = '$section_slug';";

			echo 'var wrapped = $(".wrap h2").wrap("<div class=\"ui-tabs-panel\">");
			wrapped.each(function() {
				$(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
			});
			$(".ui-tabs-panel").each(function(index) {
				$(this).attr("id", sections[$(this).children("h2").text()]);
				if (index > 0)
					$(this).addClass("ui-tabs-hide");
			});
			$(".ui-tabs").tabs({
				fx: { opacity: "toggle", duration: "fast" }
			});

			$("input[type=text], textarea").each(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
					$(this).css("color", "#999");
			});

			$("input[type=text], textarea").focus(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
					$(this).val("");
					$(this).css("color", "#000");
				}
			}).blur(function() {
				if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
					$(this).val($(this).attr("placeholder"));
					$(this).css("color", "#999");
				}
			});

			$(".wrap h2, .wrap table").show();

			// This will make the "warning" checkbox class really stand out when checked.
			// I use it here for the Reset checkbox.
			$(".warning").change(function() {
				if ($(this).is(":checked"))
					$(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
				else
					$(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
			});

			// Browser compatibility
			if ($.browser.mozilla) 
			         $("form").attr("autocomplete", "off");
			});
		</script>

		<div class="theme-meta-wrap">
		<div id="donate" class="postbox">
			<h4>Support the Developer</h4>
			<div class="inside">
				<p>If you liked this theme, please consider donating a small amount.</p>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_donations">
					<input type="hidden" name="business" value="U92LEYCWW973S">
					<input type="hidden" name="lc" value="PH">
					<input type="hidden" name="item_name" value="Asteroid Theme">
					<input type="hidden" name="currency_code" value="USD">
					<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHosted">
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>	
			</div>
		</div>

		<div id="theme-info" class="postbox">
			<h4>About Asteroid Theme</h4>
			<div class="inside">
				<div>&#9679;&nbsp;&nbsp;<a href="' . esc_url( 'http://ronangelo.com/asteroid/' ) . '" target="_blank">Asteroid Theme Page</a></div>
				<div>&#9679;&nbsp;&nbsp;<a href="' . esc_url( 'http://ronangelo.com/asteroid/theme-documentation/' ) . '" target="_blank">Asteroid Documentation</a></div>
				<div>&#9679;&nbsp;&nbsp;<a href="' . esc_url( 'http://ronangelo.com/asteroid/theme-changelog/' ) . '" target="_blank">Asteroid Changelog</a></div>
				<p>Have any questions or suggestions? Post them here on the theme\'s <a href="' . esc_url( 'http://ronangelo.com/forums/' ) . '" target="_blank">support forum</a> or on <a href="' . esc_url( 'http://wordpress.org/support/theme/asteroid/' ) . '" target="_blank">wordpress.org</a></p>
				<p>Note: Check the theme changelog page linked above before updating to a newer version of the theme.</p>
			</div>
		</div>
		</div>

	</div>';

	}


	/*-------------------------------------
	   HTML Output
	--------------------------------------*/
	public function display_setting( $args = array() ) {

		extract( $args );

		$options = get_option( 'asteroid_options' );

		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;

		$field_class = '';
		if ( $class != '' )
			$field_class = ' ' . $class;


		switch ( $type ) {

			case 'heading':
				echo '<h4 class="opt-heading">' . $desc . '</h4>';
				break;

			case 'checkbox':
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="asteroid_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '"> &nbsp;' . $desc . '</label>';
				break;

			case 'select':
				echo '<select class="select' . $field_class . '" name="asteroid_options[' . $id . ']">';

				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';

				echo '</select>';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="asteroid_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}

				if ( $desc != '' )
					echo '<span class="description">' . $desc . '</span>';

				break;

			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="asteroid_options[' . $id . ']" placeholder="' . $std . '" rows="8" cols="64" wrap="off">' . format_for_editor( $options[$id] ) . '</textarea>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'textarea-css':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="asteroid_options[' . $id . ']" placeholder="' . $std . '" rows="18" cols="72" wrap="off">' . format_for_editor( $options[$id] ) . '</textarea>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				break;

			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="asteroid_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				break;

			case 'text':
		 		echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="asteroid_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		break;

			case 'text-int':
		 		echo '<input class="text-int' . $field_class . '" type="text" id="' . $id . '" name="asteroid_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
		 		
		 		if ( $desc != '' )
		 			echo '&nbsp;<span class="description">' . $desc . '</span>';
		 		break;

			case 'upload':
				echo '<input id="' . $id . '" class="upload-url' . $field_class . '" type="text" name="asteroid_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />
					 <input id="st_upload_button" class="st_upload_button" type="button" name="upload_button" value="Upload" />';
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				break;

			case 'color':
		 		echo '<input class="color' . $field_class . '" type="text" id="' . $id . '" name="asteroid_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
		 		
		 		if ( $desc != '' )
		 			echo '&nbsp;<span class="description">' . $desc . '</span>';
		 		break;	
		}
	}

	/*-------------------------------------
	   Define settings and their defaults
	--------------------------------------*/
	public function get_option() {

		/* General Settings
		===========================================*/
		$this->settings['ast_menu_search'] = array(
			'section' => 'general',
			'title'   => __( 'Search Box on Menu', 'asteroid' ),
			'desc'    => __( 'Display a Search box on the Main Menu.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);

		$this->settings['ast_menu_style'] = array(
			'section' => 'general',
			'title'   => __( 'Responsive Menu Style', 'asteroid' ),
			'desc'    => __( 'Menu style to show on small screens. Responsive Layout must be enabled.', 'asteroid' ),
			'type'    => 'radio',
			'std'     => 'stack',
			'choices' => array(
				'stack' => __( 'Stacked', 'asteroid' ),
				'drop' 	=> __( 'Drop-down', 'asteroid' )
				)
		);

		$this->settings['ast_post_display_type'] = array(
			'section' => 'general',
			'title'   => __( 'Display on Blog View', 'asteroid' ),
			'desc'    => __( 'Show excerpts or full posts on non-singular pages.', 'asteroid' ),
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array(
				'1' => __( 'Excerpt', 'asteroid' ),
				'2' => __( 'Full Post', 'asteroid' )
				)
		);

		$this->settings['ast_head_codes'] = array(
			'title'   => __( 'Custom &lt;Head&gt; Codes', 'asteroid' ),
			'desc'    => __( 'Insert &lt;Head&gt; codes here. &nbsp; e.g. Google Analytics, Metas, Fonts, Scripts and what not.', 'asteroid' ),
			'std'     => '',
			'type'    => 'textarea',
			'section' => 'general'
		);

		$this->settings['ast_hook_footer_links'] = array(
			'title'   => __( 'Footer Links', 'asteroid' ),
			'desc'    => __( 'Insert your footer links here. &nbsp; Accepts html codes.', 'asteroid' ),
			'std'     => '',
			'type'    => 'textarea',
			'section' => 'general'
		);

		/* Appearance
		===========================================*/
		$this->settings['ast_header_logo'] = array(
			'section' => 'appearance',
			'title'   => __( 'Header Logo', 'asteroid' ),
			'desc'    => __( 'The URL of your logo. This replaces the Title & Tagline.', 'asteroid' ),
			'type'    => 'upload',
			'std'     => ''
		);

		$this->settings['ast_favicon'] = array(
			'section' => 'appearance',
			'title'   => __( 'Favicon', 'asteroid' ),
			'desc'    => __( 'The URL of your favicon. It should be 16x16 pixels.', 'asteroid' ),
			'type'    => 'upload',
			'std'     => ''
		);

		$this->settings['ast_header_height'] = array(
			'title'   => __( 'Height of Header', 'asteroid' ),
			'desc'    => 'px. ' . __( 'Set the height of the Header.', 'asteroid' ),
			'std'     => '120',
			'type'    => 'text-int',
			'section' => 'appearance'
		);

		$this->settings['ast_content_width'] = array(
			'title'   => __( 'Width of Content', 'asteroid' ),
			'desc'    => 'px. ' . __( 'Set the width of the Content or Post Area.', 'asteroid' ),
			'std'     => '640',
			'type'    => 'text-int',
			'section' => 'appearance'
		);

		$this->settings['ast_sidebar_width'] = array(
			'title'   => __( 'Width of Sidebar', 'asteroid' ),
			'desc'    => 'px. ' . __( 'Set the width of the Sidebar.', 'asteroid' ),
			'std'     => '320',
			'type'    => 'text-int',
			'section' => 'appearance'
		);

		$this->settings['ast_header_bgcolor'] = array(
			'title'   => __( 'Color of Header', 'asteroid' ),
			'desc'    => __( 'Choose a background color for the #header container.', 'asteroid' ),
			'std'     => 'FFFFFF',
			'type'    => 'color',
			'section' => 'appearance'
		);

		$this->settings['ast_content_bgcolor'] = array(
			'title'   => __( 'Color of Content', 'asteroid' ),
			'desc'    => __( 'Choose a background color for the #content container.', 'asteroid' ),
			'std'     => 'FFFFFF',
			'type'    => 'color',
			'section' => 'appearance'
		);

		$this->settings['ast_sidebar_bgcolor'] = array(
			'title'   => __( 'Color of Sidebar', 'asteroid' ),
			'desc'    => __( 'Choose a background color for the #sidebar container.', 'asteroid' ),
			'std'     => 'FFFFFF',
			'type'    => 'color',
			'section' => 'appearance'
		);

		/* Posts & Pages
		===========================================*/
		$this->settings['ast_excerpt_thumbnails'] = array(
			'section' => 'post-page',
			'title'   => __( 'Excerpt Thumbnails', 'asteroid' ),
			'desc'    => __( 'Show Thumbnails on excerpts. Featured image will be used.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 1
		);

		$this->settings['ast_blog_date'] = array(
			'section' => 'post-page',
			'title'   => __( 'Blog View Publish Date', 'asteroid' ),
			'desc'    => __( 'Show Publish Date on Blog, Archives, and Searches.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 1
		);

		$this->settings['ast_blog_author'] = array(
			'section' => 'post-page',
			'title'   => __( 'Blog View Author', 'asteroid' ),
			'desc'    => __( 'Show Post Author on Blog, Archives, and Searches.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 0
		);

		$this->settings['ast_blog_comment_links'] = array(
			'section' => 'post-page',
			'title'   => __( 'Blog View Comments', 'asteroid' ),
			'desc'    => __( 'Show comment count below excerpts.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 0
		);

		$this->settings['ast_single_date'] = array(
			'section' => 'post-page',
			'title'   => __( 'Single View Publish Date', 'asteroid' ),
			'desc'    => __( 'Display the publish date on Posts and Pages.', 'asteroid' ),
			'type'    => 'select',
			'std'     => 1,
			'choices' => array(
				0	=> __('Hidden', 'asteroid'),
				1	=> __('On Posts', 'asteroid'),
				2	=> __('On Pages', 'asteroid'),
				3	=> __('Both Posts & Pages', 'asteroid')
				)
		);

		$this->settings['ast_single_author'] = array(
			'section' => 'post-page',
			'title'   => __( 'Single View Author', 'asteroid' ),
			'desc'    => __( 'Display the author on Posts and Pages.', 'asteroid' ),
			'type'    => 'select',
			'std'     => 1,
			'choices' => array(
				0	=> __('Hidden', 'asteroid'),
				1	=> __('On Posts', 'asteroid'),
				2	=> __('On Pages', 'asteroid'),
				3	=> __('Both Posts & Pages', 'asteroid')
				)
		);

		$this->settings['ast_date_modified'] = array(
			'section' => 'post-page',
			'title'   => __( 'Show Date Modified', 'asteroid' ),
			'desc'    => __( 'Display date when the Post or Page was modified.', 'asteroid' ),
			'type'    => 'select',
			'std'     => 1,
			'choices' => array(
				0	=> __('Hidden', 'asteroid'),
				1	=> __('On Posts', 'asteroid'),
				2	=> __('On Pages', 'asteroid'),
				3	=> __('Both Posts & Pages', 'asteroid')
				)
		);

		$this->settings['ast_post_comments'] = array(
			'section' => 'post-page',
			'title'   => __( 'Post Comments', 'asteroid' ),
			'desc'    => __( 'Show the comments and comment form on Posts.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 1
		);

		$this->settings['ast_page_comments'] = array(
			'section' => 'post-page',
			'title'   => __( 'Page Comments', 'asteroid' ),
			'desc'    => __( 'Show the comments and comment form on Pages.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 1
		);

		$this->settings['ast_post_author_info_box'] = array(
			'section' => 'post-page',
			'title'   => __( 'Show Author Info Box', 'asteroid' ),
			'desc'    => __( 'Show "About the Author" box below each post.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 0
		);

		$this->settings['ast_single_edit_link'] = array(
			'section' => 'post-page',
			'title'   => __( 'Edit Link', 'asteroid' ),
			'desc'    => __( 'Show "Edit" link below titles on posts and pages.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 0
		);

		/* Custom Widgets
		===========================================*/
		$this->settings['ast_widget_body'] = array(
			'section' => 'widget-areas',
			'title'   => __( 'Body', 'asteroid' ),
			'desc'    => __( 'Allow widgets on the Body', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 0
		);

		$this->settings['ast_widget_below_menu'] = array(
			'section' => 'widget-areas',
			'title'   => __( 'Below Menu', 'asteroid' ),
			'desc'    => __( 'Allow widgets below the main menu.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 0
		);

		$this->settings['ast_widget_before_content'] = array(
			'section' => 'widget-areas',
			'title'   => __( 'Before Content', 'asteroid' ),
			'desc'    => __( 'Allow widgets on top of the content.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 0
		);

		$this->settings['ast_widget_below_excerpts'] = array(
			'section' => 'widget-areas',
			'title'   => __( 'Below Excerpts', 'asteroid' ),
			'desc'    => __( 'Allow widgets below the excerpts.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 0
		);

		$this->settings['ast_widget_before_post'] = array(
			'section' => 'widget-areas',
			'title'   => __( 'Before Post', 'asteroid' ),
			'desc'    => __( 'Allow widgets to show after the post-title.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 1
		);	

		$this->settings['ast_widget_before_post_content'] = array(
			'section' => 'widget-areas',
			'title'   => __( 'Before Post - Content', 'asteroid' ),
			'desc'    => __( 'Allow widgets to show before the post-content.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 1
		);

		$this->settings['ast_widget_after_post_content'] = array(
			'section' => 'widget-areas',
			'title'   => __( 'After Post - Content', 'asteroid' ),
			'desc'    => __( 'Allow widgets to show after the post-content.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 1 
		);

		$this->settings['ast_widget_after_post'] = array(
			'section' => 'widget-areas',
			'title'   => __( 'After Post', 'asteroid' ),
			'desc'    => __( 'Allow widgets to show at the post-footer.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 1
		);

		/* Custom CSS
		===========================================*/	
		$this->settings['ast_custom_css'] = array(
			'title'   => __( 'Custom CSS Codes', 'asteroid' ),
			'desc'    => __( 'Enter custom CSS here to apply to the theme. This should override any other stylings.', 'asteroid' ),
			'std'     => '',
			'type'    => 'textarea-css',
			'section' => 'custom-css',
			'class'   => 'textarea-css'
		);

		/* Miscellaneous
		===========================================*/
		$this->settings['ast_responsive_disable'] = array(
			'section' => 'misc',
			'title'   => __( 'Disable Responsive Layout', 'asteroid' ),
			'desc'    => __( 'Check if you do not want the layout to resize and adapt to the screen size.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 0
		);

		$this->settings['ast_post_editor_style'] = array(
			'section' => 'misc',
			'title'   => __( 'Disable Post Editor Style', 'asteroid' ),
			'desc'    => __( 'Disable theme styles on the visual editor. Disables resizing of editor width.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 0
		);

		$this->settings['ast_remove_theme_link'] = array(
			'section' => 'misc',
			'title'   => __( 'Remove Theme URL', 'asteroid' ),
			'desc'    => __( 'Remove the Asteroid Theme URL on the footer.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 0
		);

		$this->settings['ast_bbpress_forum_full_width'] = array(
			'section' => 'misc',
			'title'   => __( 'bbPress Forum Width', 'asteroid' ),
			'desc'    => __( 'Make bbPress forum full-width with no sidebar.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 0
		);

		$this->settings['ast_bbpress_topic_full_width'] = array(
			'section' => 'misc',
			'title'   => __( 'bbPress Topic Width', 'asteroid' ),
			'desc'    => __( 'Make bbPress topics full-width with no sidebar.', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 0
		);

		/* Reset
		===========================================*/
		$this->settings['ast_reset_theme'] = array(
			'section' => 'reset',
			'title'   => __( 'Reset theme', 'asteroid' ),
			'desc'    => __( 'Check and click "Save" to reset theme options. This deletes customizations!', 'asteroid' ),
			'type'    => 'checkbox',
			'std'     => 0,
			'class'   => 'warning' // Custom class for CSS
		);

	}

	/*-------------------------------------
	   Description for pages
	--------------------------------------*/
	public function display_section() {
		// No Description
	}

	public function display_about_section() {
		// No Description	
	}

	/*-------------------------------------
	   Initialize Settings to Defaults
	--------------------------------------*/
	public function initialize_settings() {
		
		$default_settings = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' )
				$default_settings[$id] = $setting['std'];
		}

		update_option( 'asteroid_options', $default_settings );

	}

	/*-------------------------------------
	   Register Settings
	--------------------------------------*/
	public function register_settings() {

		register_setting( 'asteroid_options', 'asteroid_options', array ( &$this, 'validate_settings' ) );

		foreach ( $this->sections as $slug => $title ) {
			if ( $slug == 'about' )
				add_settings_section( $slug, $title, array( &$this, 'display_about_section' ), 'asteroid-options' );
			else
				add_settings_section( $slug, $title, array( &$this, 'display_section' ), 'asteroid-options' );
		}

		$this->get_option();

		foreach ( $this->settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}

	}

	/*-------------------------------------
	   jQuery Tabs
	--------------------------------------*/
	public function scripts() {
		wp_print_scripts( 'jquery-ui-tabs' );

		wp_enqueue_script('my-upload');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_register_script('my-upload', get_template_directory_uri() . '/js/uploader.js', array('jquery','media-upload','thickbox'));
	}

	/*-------------------------------------
	   Styling for the theme options page
	--------------------------------------*/
	public function styles() {	
		wp_register_style( 'mytheme-admin', get_template_directory_uri() . '/includes/theme-options.css' );
		wp_enqueue_style( 'mytheme-admin' );
		wp_enqueue_style('thickbox');
	}

	/*-------------------------------------
	   Validate Settings
	--------------------------------------*/
	public function validate_settings( $input ) {

		if ( ! isset( $input['ast_reset_theme'] ) ) {
			$options = get_option( 'asteroid_options' );

			foreach ( $this->checkboxes as $id ) {
				if ( isset( $options[$id] ) && ! isset( $input[$id] ) )
					unset( $options[$id] );
			}
			return $input;
		}
		return false;		
	}

}

$asteroid_options = new Asteroid_Theme_Options();

function asteroid_option( $option ) {
	$options = get_option( 'asteroid_options' );
	if ( isset( $options[$option] ) )
		return $options[$option]; 
	else
		return false;
}