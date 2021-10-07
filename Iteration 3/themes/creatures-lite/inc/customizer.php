<?php
/**
 * Creatures Lite Theme Customizer
 *
 * @package Creatures Lite
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function creatures_lite_customize_register( $wp_customize ) {
	function creatures_lite_sanitize_checkbox( $checked ) {
		// Boolean check.
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}

	$wp_customize->get_setting( 'blogname' )->creatures_lite         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->creatures_lite  = 'postMessage';

	$wp_customize->selective_refresh->add_partial( 'blogname', array(
	    'selector' => '.site-name-desc a',
	    'render_callback' => 'creatures-lite_customize_partial_blogname',
	) );

	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
	    'selector' => '.site-name-desc p',
	    'render_callback' => 'creatures-lite_customize_partial_blogdescription',
	) );

	/*========================
	==	Theme Colors Start
	========================*/

	$wp_customize->add_setting('color_scheme', array(
		'default' => '#f15e42',
		'sanitize_callback'	=> 'sanitize_hex_color',
	));
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'color_scheme',array(
			'label' => __('Color Scheme','creatures-lite'),
			'description'	=> __('Select theme main color from here.','creatures-lite'),
			'section' => 'colors',
			'settings' => 'color_scheme'
		))
	);
	
	$wp_customize->add_setting('color_sub_scheme', array(
		'default' => '#27304b',
		'sanitize_callback'	=> 'sanitize_hex_color',
	));
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'color_sub_scheme',array(
			'description'	=> __('Select theme sub color from here.','creatures-lite'),
			'section' => 'colors',
			'settings' => 'color_sub_scheme'
		))
	);

	$wp_customize->add_setting('creatureheaderbg-color', array(
		'default' => '#ffffff',
		'sanitize_callback'	=> 'sanitize_hex_color',
	));
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'creatureheaderbg-color',array(
			'label' => __('Header Background Color','creatures-lite'),
			'description'	=> __('Select background color for header.','creatures-lite'),
			'section' => 'colors',
			'settings' => 'creatureheaderbg-color'
		))
	);

	$wp_customize->add_setting('creaturefooter-color', array(
		'default' => '#f15e42',
		'sanitize_callback'	=> 'sanitize_hex_color',
	));
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'creaturefooter-color',array(
			'label' => __('Footer Background Color','creatures-lite'),
			'description'	=> __('Select background color for footer.','creatures-lite'),
			'section' => 'colors',
			'settings' => 'creaturefooter-color'
		))
	);

	/*========================
	==	Theme Colors End
	========================*/

	/*========================
	==	Top Header Start
	========================*/
	$wp_customize->add_section(
        'creature_tphead_info',
        array(
            'title' => __('Setup Top Header', 'creatures-lite'),
            'priority' => null,
			'description'	=> __('Add top header information here.','creatures-lite'),	
        )
    );
	
	$wp_customize->add_setting( 'creature-wlcm-txt', array(
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('creature-wlcm-txt',array(
		'type'	=> 'text',
		'label'	=> __('Add welcome text here.','creatures-lite'),
		'section'	=> 'creature_tphead_info'
	));
	
	$wp_customize->add_setting( 'creature-email-txt', array(
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('creature-email-txt',array(
		'type'	=> 'text',
		'label'	=> __('Add email address here here.','creatures-lite'),
		'section'	=> 'creature_tphead_info'
	));

	$wp_customize->add_setting('facebook',array(
		'sanitize_callback'	=> 'esc_url_raw'
	));

	$wp_customize->add_control('facebook',array(
		'type'	=> 'url',
		'label'	=> __('Add facebook link here.','creatures-lite'),
		'section'	=> 'creature_tphead_info'
	));

	$wp_customize->add_setting('twitter',array(
		'sanitize_callback'	=> 'esc_url_raw'
	));

	$wp_customize->add_control('twitter',array(
		'type'	=> 'url',
		'label'	=> __('Add twitter link here.','creatures-lite'),
		'section'	=> 'creature_tphead_info'
	));

	$wp_customize->add_setting('instagram',array(
		'sanitize_callback'	=> 'esc_url_raw'
	));

	$wp_customize->add_control('instagram',array(
		'type'	=> 'url',
		'label'	=> __('Add instagram link here.','creatures-lite'),
		'section'	=> 'creature_tphead_info'
	));

	$wp_customize->add_setting('linkedin',array(
		'sanitize_callback'	=> 'esc_url_raw'
	));

	$wp_customize->add_control('linkedin',array(
		'type'	=> 'url',
		'label'	=> __('Add linkedin link here.','creatures-lite'),
		'section'	=> 'creature_tphead_info'
	));

	$wp_customize->add_setting('google',array(
		'sanitize_callback'	=> 'esc_url_raw'
	));

	$wp_customize->add_control('google',array(
		'type'	=> 'url',
		'label'	=> __('Add google plus link here.','creatures-lite'),
		'section'	=> 'creature_tphead_info'
	));
	
	$wp_customize->add_setting('youtube',array(
		'sanitize_callback'	=> 'esc_url_raw'
	));

	$wp_customize->add_control('youtube',array(
		'type'	=> 'url',
		'label'	=> __('Add youtube channel link here.','creatures-lite'),
		'section'	=> 'creature_tphead_info'
	));

	$wp_customize->add_setting('creature_tophide',array(
		'default' => true,
		'sanitize_callback' => 'creatures_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 

	$wp_customize->add_control( 'creature_tophide', array(
	   'settings' => 'creature_tophide',
	   'section'   => 'creature_tphead_info',
	   'label'     => __('Check this to hide Top Header Information.','creatures-lite'),
	   'type'      => 'checkbox'
	));
	
	/*========================
	==	Top Header End
	========================*/
	
	/*========================
	==	Header Info Start
	========================*/
	$wp_customize->add_section(
        'creature_head_info',
        array(
            'title' => __('Setup Header Information', 'creatures-lite'),
            'priority' => null,
			'description'	=> __('Add header information here.','creatures-lite'),	
        )
    );
	
	$wp_customize->add_setting( 'creature-location-txt', array(
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('creature-location-txt',array(
		'type'	=> 'text',
		'label'	=> __('Add address heading here.','creatures-lite'),
		'section'	=> 'creature_head_info'
	));

	$wp_customize->add_setting( 'creature-location', array(
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('creature-location',array(
		'type'	=> 'text',
		'label'	=> __('Add address here.','creatures-lite'),
		'section'	=> 'creature_head_info'
	));
	
	$wp_customize->add_setting( 'creature-wrkhrs-txt', array(
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('creature-wrkhrs-txt',array(
		'type'	=> 'text',
		'label'	=> __('Add working hours heading here.','creatures-lite'),
		'section'	=> 'creature_head_info'
	));
	
	$wp_customize->add_setting( 'creature-wrkhrs', array(
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('creature-wrkhrs',array(
		'type'	=> 'text',
		'label'	=> __('Add working hours timing here.','creatures-lite'),
		'section'	=> 'creature_head_info'
	));
	
	$wp_customize->add_setting( 'creature-phn-txt', array(
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('creature-phn-txt',array(
		'type'	=> 'text',
		'label'	=> __('Add phone heading here.','creatures-lite'),
		'section'	=> 'creature_head_info'
	));
	
	$wp_customize->add_setting( 'creature-phn', array(
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('creature-phn',array(
		'type'	=> 'text',
		'label'	=> __('Add phone number here.','creatures-lite'),
		'section'	=> 'creature_head_info'
	));
	
	$wp_customize->add_setting( 'creature-cta-btn', array(
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('creature-cta-btn',array(
		'type'	=> 'text',
		'label'	=> __('Add label for CTA button on navigation.','creatures-lite'),
		'section'	=> 'creature_head_info'
	));
	
	$wp_customize->add_setting( 'creature-cta-btn-link', array(
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('creature-cta-btn-link',array(
		'type'	=> 'text',
		'label'	=> __('Add link for CTA button on navigation.','creatures-lite'),
		'section'	=> 'creature_head_info'
	));
	
	$wp_customize->add_setting('creature_headinfohide',array(
		'default' => true,
		'sanitize_callback' => 'creatures_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 

	$wp_customize->add_control( 'creature_headinfohide', array(
	   'settings' => 'creature_headinfohide',
	   'section'   => 'creature_head_info',
	   'label'     => __('Check this to hide Header Information.','creatures-lite'),
	   'type'      => 'checkbox'
	));

	/*========================
	==	Header Info End
	========================*/	
	
	/*========================
	==	Slider Start
	========================*/
	$wp_customize->add_section(
        'creature_theme_slider',
        array(
            'title' => __('Setting Up Theme Slider', 'creatures-lite'),
            'priority' => null,
			'description'	=> __('Recommended image size (1600x900). Slider will work only when you select the static front page.','creatures-lite'),	
        )
    );

    $wp_customize->add_setting('slidesmlttl',array(
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('slidesmlttl',array(
		'type'	=> 'text',
		'label'	=> __('Add sub title for all slides','creatures-lite'),
		'section'	=> 'creature_theme_slider'
	));

	$wp_customize->add_setting('theme-slide1',array(
		'default' => '0',
		'capability' => 'edit_theme_options',
		'sanitize_callback'	=> 'absint'
	));

	$wp_customize->add_control('theme-slide1',array(
		'type'	=> 'dropdown-pages',
		'label'	=> __('Select page for slide one:','creatures-lite'),
		'section'	=> 'creature_theme_slider'
	));	

	$wp_customize->add_setting('theme-slide2',array(
		'default' => '0',
		'capability' => 'edit_theme_options',	
		'sanitize_callback'	=> 'absint'
	));

	$wp_customize->add_control('theme-slide2',array(
		'type'	=> 'dropdown-pages',
		'label'	=> __('Select page for slide two:','creatures-lite'),
		'section'	=> 'creature_theme_slider'
	));	

	$wp_customize->add_setting('theme-slide3',array(
		'default' => '0',
		'capability' => 'edit_theme_options',	
		'sanitize_callback'	=> 'absint'
	));

	$wp_customize->add_control('theme-slide3',array(
		'type'	=> 'dropdown-pages',
		'label'	=> __('Select page for slide three:','creatures-lite'),
		'section'	=> 'creature_theme_slider'
	));	
	
	$wp_customize->add_setting('slide_read_more',array(
		'default'	=> __('','creatures-lite'),
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('slide_read_more',array(
		'label'	=> __('Add slider link button text.','creatures-lite'),
		'section'	=> 'creature_theme_slider',
		'setting'	=> 'slide_read_more',
		'type'	=> 'text'
	));
	
	$wp_customize->add_setting('hide_theme_slider',array(
		'default' => true,
		'sanitize_callback' => 'creatures_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	)); 

	$wp_customize->add_control( 'hide_theme_slider', array(
	   'settings' => 'hide_theme_slider',
	   'section'   => 'creature_theme_slider',
	   'label'     => __('Check this to hide slider.','creatures-lite'),
	   'type'      => 'checkbox'
    ));
	/*========================
	==	Slider End
	========================*/
	
	/*========================
	==	fisrt Section Start
	========================*/

	$wp_customize->add_section(
        'creature_service_sec',
        array(
            'title' => __('Setting Up Services Section', 'creatures-lite'),
            'priority' => null,
			'description'	=> __('Select pages for setting up fist/services section. This section will be displayed only when you select the static front page.','creatures-lite'),	
        )
    );

    $wp_customize->add_setting('creature_ser_ttl',array(
		'default'	=> __('','creatures-lite'),
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('creature_ser_ttl',array(
		'label'	=> __('Add section title here','creatures-lite'),
		'section'	=> 'creature_service_sec',
		'setting'	=> 'creature_ser_ttl',
		'type'	=> 'text'
	));

	$wp_customize->add_setting('creature_service1',array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
	));
	
	$wp_customize->add_control('creature_service1',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for 1st column','creatures-lite'),
			'section'	=> 'creature_service_sec'
	));

	$wp_customize->add_setting('creature_service2',array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
	));
	
	$wp_customize->add_control('creature_service2',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for 2nd column','creatures-lite'),
			'section'	=> 'creature_service_sec'
	));
	
	$wp_customize->add_setting('creature_service3',array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
	));
	
	$wp_customize->add_control('creature_service3',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for 3rd column','creatures-lite'),
			'section'	=> 'creature_service_sec'
	));

	$wp_customize->add_setting('creature_ser_hide',array(
			'default' => true,
			'sanitize_callback' => 'creatures_lite_sanitize_checkbox',
			'capability' => 'edit_theme_options',
	)); 

	$wp_customize->add_control( 'creature_ser_hide', array(
		   'settings' => 'creature_ser_hide',
    	   'section'   => 'creature_service_sec',
    	   'label'     => __('Check this to hide section.','creatures-lite'),
    	   'type'      => 'checkbox'
     ));

	/*========================
	==	First Section End
	========================*/
	
	/*========================
	==	Second Section Start
	========================*/

	$wp_customize->add_section(
        'creature_about_section',
        array(
            'title' => __('Setting Up About Section', 'creatures-lite'),
            'priority' => null,
			'description'	=> __('Select page for setting up about / second section. This section will be displayed only when you select the static front page.','creatures-lite'),	
        )
    );

    $wp_customize->add_setting('creature_about_sbttl',array(
		'default'	=> __('','creatures-lite'),
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('creature_about_sbttl',array(
		'label'	=> __('Add sub title here','creatures-lite'),
		'section'	=> 'creature_about_section',
		'setting'	=> 'creature_about_sbttl',
		'type'	=> 'text'
	));
	
	$wp_customize->add_setting('creature_about_sec',array(
		'default' => '0',
		'capability' => 'edit_theme_options',
		'sanitize_callback'	=> 'absint'
	));
	
	$wp_customize->add_control('creature_about_sec',array(
		'type'	=> 'dropdown-pages',
		'label'	=> __('Select page for this section','creatures-lite'),
		'section'	=> 'creature_about_section'
	));

	$wp_customize->add_setting('creature_about_more',array(
		'default'	=> __('','creatures-lite'),
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('creature_about_more',array(
		'label'	=> __('Add read more button text.','creatures-lite'),
		'section'	=> 'creature_about_section',
		'setting'	=> 'creature_about_more',
		'type'	=> 'text'
	));
	
	$wp_customize->add_setting('creature_hide_aboutsec',array(
		'default' => true,
		'sanitize_callback' => 'creatures_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 

	$wp_customize->add_control( 'creature_hide_aboutsec', array(
	   'settings' => 'creature_hide_aboutsec',
	   'section'   => 'creature_about_section',
	   'label'     => __('Check this to hide section.','creatures-lite'),
	   'type'      => 'checkbox'
     ));

	/*========================
	==	Second Section End
	========================*/
	
}
add_action( 'customize_register', 'creatures_lite_customize_register' );	

function creatures_lite_css(){
		?>
        <style>
			a, 
			.tm_client strong,
			.postmeta a:hover,
			#sidebar ul li a:hover,
			.blog-post h3.entry-title,
			a.blog-more:hover,
			#commentform input#submit,
			input.search-submit,
			.blog-date .date,
			.sitenav ul li.current_page_item a,
			.sitenav ul li a:hover, 
			.sitenav ul li.current_page_item ul li a:hover,
			.site-name-desc p,
			.header-right .headinfo .headinfo-icon,
			.section_head .sec-separator .secicon,
			.service-data h3 span{
				color:<?php echo esc_attr(get_theme_mod('color_scheme','#f15e42')); ?>;
			}
			h3.widget-title,
			.nav-links .current,
			.nav-links a:hover,
			p.form-submit input[type="submit"],
			.social a:hover,
			.cta-button a,
			.caption-holder h4,
			.caption-holder a.slide-button,
			.about{
				background-color:<?php echo esc_attr(get_theme_mod('color_scheme','#f15e42')); ?>;
			}
			
			.top-header-bar,
			.navigation,
			.inner-about-content .about-more-btn,
			.sitenav ul li.menu-item-has-children:hover > ul,
			.sitenav ul li.menu-item-has-children:focus > ul,
			.sitenav ul li.menu-item-has-children.focus > ul{
				background-color:<?php echo esc_attr(get_theme_mod('color_sub_scheme','#27304b')); ?>;
			}
			
			.header-right .headinfo .headinfo-text h4,
			.cta-button a:hover,
			h2.section_title,
			.service-data h3 a,
			.inner-about-content h3 span{
				color:<?php echo esc_attr(get_theme_mod('color_sub_scheme','#27304b')); ?>;
			}
			
			#header{
				background-color:<?php echo esc_attr(get_theme_mod('creatureheaderbg-color','#ffffff')); ?>;
			}
			.copyright-wrapper{
				background-color:<?php echo esc_attr(get_theme_mod('creaturefooter-color','#f15e42')); ?>;
			}				
		</style>
	<?php }
add_action('wp_head','creatures_lite_css');

function creatures_lite_customize_preview_js() {
	wp_enqueue_script( 'creatures-lite-customize-preview', get_template_directory_uri() . '/js/customize-preview.js', array( 'customize-preview' ), '20141216', true );
}
add_action( 'customize_preview_init', 'creatures_lite_customize_preview_js' );