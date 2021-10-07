<?php
/*
  Plugin Name: TripPlan
  Plugin URI: http://checklist.com/
  Description: Turn your travel post into an interactive visual trip plan and map. Let users Print, Save, Share, Download to Mobile and more. 100% Free. 
  Version: 1.0.10
  Author: checklist
  Author URI: https://checklist.com
  License: GPLv3
  Text Domain: tripplan-com
  Domain Path: /languages
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

if (!defined('TRIPPLAN_ROOT_PATH')) define('TRIPPLAN_ROOT_PATH', dirname(__FILE__));

class WP_TripPlan_POI {
    public $name = '';
    public $long = '';
    public $lat = '';
}

class WP_TripPlan {

private static $toc = null;

// Constructor
function __construct() {

    add_action( 'admin_menu', array( $this, 'wpa_add_menu' ));
    add_action( 'admin_init', array( $this, 'tripplan_com_admin_init'));

    // localization
    add_action( 'init', array( $this, 'plugin_load_textdomain' ) );

    // styles
    add_action( 'admin_enqueue_scripts', array( $this, 'wpa_scripts') );
    add_action( 'enqueue_scripts', array( $this, 'wpf_scripts') );

    // Editor Buttons
    add_filter( 'mce_external_plugins', array( $this, 'wpa_add_buttons' ) );
    add_filter( 'mce_buttons', array( $this, 'wpa_register_buttons' ) );

	function wpdocs_theme_add_editor_styles() {
		add_editor_style( plugins_url('css/custom-editor-style.css', __FILE__) );
	}
	add_action( 'admin_init', 'wpdocs_theme_add_editor_styles' );
}

private static function addToPOIs($poi){
    if (self::$toc == null){
        self::$toc = array ($poi);
    } else {
        if (self::notInPOIs($poi)){
            array_push(self::$toc, $poi);
        }
    }
}

private static function notInPOIs($poi){
    foreach (self::$toc as $item){
        if ($item->placeId === $poi->placeId){
            return false;
        }
    }

    return true;
}

/**
* tp-poi ShortCode
*/
public static function register_tp_poi_shortcode($atts, $content=null){
    $atts = shortcode_atts (
        array (
            'name'       => '',
            'url'       => '',
            'type'       => '',
            'long'       => '',
            'lat'       => '',
            'placeid'       => '',
            'durationqty'       => '',
            'durationunit'       => '',
            'props'       => '',
            'presentation'       => '',
        ), $atts );

    static $counter = -1;
    $counter++;

    $poi = new WP_TripPlan_POI;
    $poi->name = $content;
    $poi->url = $atts["url"];
    $poi->placeId = $atts["placeid"];
    $poi->long = $atts["long"];
    $poi->lat = $atts["lat"];
    $poi->durationQty = $atts["durationqty"];
    $poi->durationUnit = $atts["durationunit"];
    $poi->type = $atts["type"];
    $poi->props = $atts["props"];
    $poi->presentation = $atts["presentation"];
    $poi->index = $counter;

    self::addToPOIs($poi);

    wp_enqueue_style( 'tripplan', plugins_url('css/tripplan.css', __FILE__));

    $validUrl = filter_var($poi->url, FILTER_VALIDATE_URL);

    // schema.org tags
    $schemaUrl = $validUrl ?  '"url" : "'.$poi->url.'"' : '';
    $schema = '
    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "Place",
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": "'.$poi->lat.'",
        "longitude": "'.$poi->long.'"
        '.$schemaUrl.'
      },
      "name": "'.$poi->name.'"
    }
    </script>
    ';

    $data = $validUrl ? ' data-url="'.$poi->url.'"' :'';
    if ($poi->long){
        $data .= ' data-long="'.$poi->long.'"';
    }
    if ($poi->lat){
        $data .= ' data-lat="'.$poi->lat.'"';
    }
    if ($poi->durationQty && $poi->durationUnit){
        $data .= ' data-duration="'.$poi->durationQty.$poi->durationUnit.'"';
    }
    if ($poi->props){
        $data .= ' data-props="'.$poi->props.'"';
    }
    
    if ($poi->presentation === 'span'){
        if ($validUrl){
            // with link
            $elemName = '<span><a href="'.$poi->url.'" target="_blank" rel="nofollow" class="tripplan-poi-name">'.$poi->name.'</a></span>';
        } else {
            // no link
            $elemName = '<span class="tripplan-poi-name">'.$poi->name.'</span>';
        }

        return $schema.'<span class="tripplan-poi"'.$data.'>'.$elemName.'</span>';
    }

    if ($validUrl){
        // with link
        $elemName = '<'.$atts["presentation"].'><input type="checkbox" class="tripplan-checkbox" data-index="'.$poi->index.'"/><a href="'.$poi->url.'" target="_blank" rel="nofollow" class="tripplan-poi-name">'.$poi->name.'</a></'.$atts["presentation"].'>';
    } else {
        // no link
        $elemName = '<'.$atts["presentation"].' class="tripplan-poi-name"><input type="checkbox" class="tripplan-checkbox" data-index="'.$poi->index.'"/>'.$poi->name.'</'.$atts["presentation"].'>';
    }

    if ($poi->durationQty && $poi->durationUnit){
        $duration = '<li><a href="#" class="tripplan-poi-tag">'.$poi->durationQty.$poi->durationUnit.'</a></li>';
    } else {
        $duration = '';
    }

    $props = "";
    if ($poi->props){
        $arrayProps = explode(",", $poi->props);
        foreach($arrayProps as $indx=>$prop){
            $props .= '<li><a href="#" class="tripplan-poi-tag">'.$prop.'</a></li>';
        }
    }

    return '<div class="tripplan-poi"'.$data.'>
            '.$elemName.'
            <ul class="tripplan-poi-tags">
                '.$duration.$props.'
            </ul>
            '.$schema.'
        </div>';
}

/**
* tp-trip ShortCode
*/
public static function register_tp_trip_shortcode($atts, $content=null){
    $atts = shortcode_atts (
        array (
            'name'       => '',
            'heading'       => '',
        ), $atts 
    );

    // get a counter or set a new if does not exist
    static $boxCounter = -1;
    $boxCounter++;

    $items = "";
    foreach (self::$toc as $index=>$poi){
        $items = $items. '<li id="checklist-id-'.$index.'"><input type="checkbox" class="tripplan-checkbox" data-index="'.$index.'"/> '.($index+1). '. <a href="#" class="tripplan-trip-poi" data-long="'.$poi->long.'" data-lat="'.$poi->lat.'">'.$poi->name.'</a></li>';
    }

    // get the default style from the plugin settings
    $settings = (array) get_option( 'tripplan_settings' );
    
    // save button
    $saveDefaultText = isset($settings['saveDefaultText']) ? $settings['saveDefaultText'] : esc_html__( 'Save', 'tripplan-com' );
    $saveTextColor = isset($settings['saveTextColor']) ? $settings['saveTextColor'] : '#FFFFFF';
    $saveBackgroundColor = isset($settings['saveBackgroundColor']) ? $settings['saveBackgroundColor'] : '#FF5722';
    $saveStyle = 'color:'.$saveTextColor.' !important; background-color:'.$saveBackgroundColor.' !important;';
          
    // print button
    $printTextColor = isset($settings['printTextColor']) ? $settings['printTextColor'] : '#FFFFFF';
    $printBackgroundColor = isset($settings['printBackgroundColor']) ? $settings['printBackgroundColor'] : '#2196F3';
    $printStyle = 'color:'.$printTextColor.' !important; background-color:'.$printBackgroundColor.' !important;';
    
    // border
    $borderColor = isset($settings['borderColor']) ? $settings['borderColor'] : '#03A9F4';  
    $borderStyle = isset($settings['borderStyle']) ? $settings['borderStyle'] : 'dashed';
    $style = 'border-style:'.$borderStyle.' !important; border-color:'.$borderColor.' !important; padding:20px;';

    // source
    $siteUrl = get_home_url();
    $parseUrl = parse_url($siteUrl);
    $host = $parseUrl['host'];
    $source = '&utm_source='.$host.'&utm_medium=referral&utm_campaign=tp-wordpress';
    
    // powered by
    $poweredBy = isset($settings['poweredBy']) ? $settings['poweredBy'] : 0;
    $powered = "";
    if ($poweredBy == 1){
        $powered = '<div class="checklist-powered">Powered By <a href="https://checklist.com" target="_blank">Checklist</a></div>';
    }

    $instructions =  isset($settings['instructionsDefaultText']) ? "<p class='tripplan-trip-instructions'>".$settings['instructionsDefaultText']."</p>" : "";
    // TODO: reset POIs?

    return '
        <div id="tripplan-id-'.$boxCounter.'" class="tripplan-trip" style="'.$style.'">
            <'.$atts["heading"].' class="checklist-title">'.$atts["name"].'</'.$atts["heading"].'>
            '.$instructions.'
            <ul>'.$items.'</ul>   
            <div class="">
                <a href="https://checklist.com" onclick="commandTripPlan(event, \'customize\', \'tripplan-id-'.$boxCounter.'\', \''.$source.'\', \''.get_permalink().'\');" style="'.$saveStyle.'" class="checklist-save checklist-button" target="_blank" title="Checklist"><img src=\''.plugins_url('images/checklist-icon.php', __FILE__).'?fill='.substr($saveTextColor,1).'\' width="16" height="16" class="svg checklist-image"/> '.$saveDefaultText.'</a>
                <a href="https://checklist.com" onclick="commandTripPlan(event, \'print\', \'tripplan-id-'.$boxCounter.'\', \''.$source.'\', \''.get_permalink().'\');" style="'.$printStyle.'" class="checklist-print checklist-button" title="Printable Checklists"><img src=\''.plugins_url('images/ic_print_white_24px.php', __FILE__).'?fill='.substr($printTextColor,1).'\' width="16" height="16" class="checklist-image"/> '.esc_html__( 'Print', 'tripplan-com' ).'</a>
            </div>
            '.$powered.'
        </div>
    ';
}

/**
* tp-map ShortCode
*/
public static function register_tp_map_shortcode($atts, $content=null){
    
    $atts = shortcode_atts (
        array (
            'height'       => '400px',
        ), $atts 
    );

    $settings = (array) get_option( 'tripplan_settings' );
    $googleMapsAPIKey = $settings['googleMapsAPIKey'];

    wp_enqueue_script('tripplan', plugins_url('js/tripplan.js', __FILE__), array('jquery'),'', true);
    wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key='. $googleMapsAPIKey .'&callback=initMap&libraries=places', array('tripplan'));
    
    wp_enqueue_style( 'tripplan', plugins_url('css/tripplan.css', __FILE__));
    wp_localize_script('tripplan', 'tripplanScript', array(
        'pluginsUrl' => plugins_url('', __FILE__),
    ));
    // TODO: reset POIs?

    return '
        <div class="tripplan-map-tag">
            <div id="tripplan-map" style="height:'.$atts["height"].'";"></div>
        </div>
    ';
}

function plugin_load_textdomain() {
    load_plugin_textdomain( 'tripplan-com', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}

/**
* Styling: loading stylesheets for the plugin admin.
*/
public function wpa_scripts( $page ) {
    
    wp_enqueue_style( 'wp-tripplan-admin', plugins_url('css/tripplan-admin.css', __FILE__));

    wp_enqueue_style( 'wp-color-picker' ); 
    wp_enqueue_script( 'wp-tripplan-js',  plugins_url('js/tripplan-admin.js', __FILE__), array('wp-color-picker'), null, true);	
    add_action ( 'after_wp_tiny_mce', array( $this, 'wpa_tripplan_tinymce_extra_vars' )) ;

}

public function wpa_add_buttons($plugin_array){
    $plugin_array['tripplan'] = plugins_url( '/js/tinymce-plugin.js',__FILE__ );
    return $plugin_array;    
}

public function wpa_register_buttons( $buttons ) {
    array_push( $buttons, 'tripplanMenu'); 
    return $buttons;
}

public function wpa_tripplan_tinymce_extra_vars(){

    $settings = (array) get_option( 'tripplan_settings' );
    $saveDefaultText = isset($settings['saveDefaultText']) ? $settings['saveDefaultText'] : esc_html__( 'Save', 'tripplan-com' );
    $googleMapsAPIKey = $settings['googleMapsAPIKey'];
    ?>

    <script type="text/javascript">
        var tripplan_obj = <?php echo json_encode(
            array(
                'googleMapsAPIKey' => $googleMapsAPIKey,
                'markerText' => __('Drag me around to set a precise location for your POI', 'tripplan-com'),
                'tripplanBtn' => __( 'Trip Plan', 'tripplan-com' ),
                'tripplanBtnPOI' => __( 'Add POI', 'tripplan-com' ),
                'tripplanBtnPlan' => __( 'Add Plan', 'tripplan-com' ),
                'tripplanBtnMap' => __( 'Add Map', 'tripplan-com' ),
            )
        );
        ?>;
    </script><?php
}

/**
    * Admin pages 
    */

/*
* Actions perform at loading of admin menu
*/
public function wpa_add_menu() {

    add_menu_page( 
        'Trip Plan',                // The value used to populate the browser's title bar when the menu page is active
        'Trip Plan',                // The text of the menu in the administrator's sidebar
        'manage_options',           // What roles are able to access the menu
        'tripplan_settings',            // The ID used to bind submenu items to this menu 
        array(                      // The callback function used to render this menu
                        $this,
                        'wpa_page_file_path'
                    ), 
        plugins_url('images/icon-white-16.png', __FILE__), // the icon
        124                       // the position (anything above 100 is good)
    );

    add_submenu_page( 
        'tripplan_com',             // The ID of the top-level menu page to which this submenu item belongs
        'Trip Plan Settings',   // The value used to populate the browser's title bar when the menu page is active
        'Settings',                 // The label of this submenu item displayed in the menu
        'manage_options',            // What roles are able to access this submenu item
        'tripplan_settings',        // The ID used to represent this submenu item
        array(                       // The callback function used to render the options for this submenu item
            $this,
            'wpa_page_file_path'
        ), 
        plugins_url('images/icon-white-16.png', __FILE__)
    );
        
}

    /*
    * Actions perform on loading of menu pages
    */
    public static function wpa_page_file_path() {

        $screen = get_current_screen();
        if ( strpos( $screen->base, 'tripplan_settings' ) !== false ) {
            include( TRIPPLAN_ROOT_PATH . '/includes/tripplan-settings.php' );
        } 
        else {
            include( dirname(__FILE__) . '/includes/tripplan-dashboard.php' );
        }
    }

    public function tripplan_com_settings_api_key_section_callback() {
        echo "<p>".__( 'To use Google Maps a website must obtain a Google Maps API key. Follow our <a href="https://checklist.com/how-to-generate-a-google-maps-api-key/" target="_blank">simple explanation</a> on how to obtain one.', 'tripplan-com' )."</p>";
    }

    public function tripplan_com_settings_instructions_section_callback() {
        echo "<p>".esc_html__( 'Give your visitors clear instructions on how to save the trip plan. Feel free to translate / edit the following:', 'tripplan-com' )."</p>";
    }

    public function tripplan_com_settings_save_section_callback() {
        echo "<p>".esc_html__( 'Select the default text for the Save button. You can also set the button\'s text and background colors.', 'tripplan-com' )."</p>";
    }

    public function tripplan_com_settings_print_section_callback() {
        echo "<p>".esc_html__( 'Select the colors for the Print button.', 'tripplan-com' )."</p>";
    }

    public function tripplan_com_settings_extra_section_callback() {
        echo "<p>".esc_html__( 'The EXTRA button allows you to link to additional info or even further monetize your list.', 'tripplan-com' )."</p>";
    }

    public function tripplan_com_settings_border_section_callback() {
        echo "<p>".esc_html__( 'Define the default settings for your Trip Plan box border.', 'tripplan-com' )."</p>";
    }

    public function tripplan_com_settings_powered_section_callback() {
        echo "<p>".esc_html__( 'Play nice and let your users know in advance that the interactive Checklists are powered by Checklist.com.', 'tripplan-com' )."</p>";
    }

    public function tripplan_com_settings_text_callback($args) {
        extract( $args );
        echo '<div class="wrap">';
        echo "<input type='text' name='$name' value='$value' />";
        echo (!empty($desc))?' <span class="description">'.$desc.'</span>':'';
        echo "</div>";
    }

    public function tripplan_com_settings_textarea_callback($args) {
        extract( $args );
        echo '<div class="wrap">';
        echo "<textarea name='$name' rows='5' style='width:80%'>$value</textarea>";
        echo "</div>";
    }

    public function tripplan_com_settings_color_picker_callback($args) {
        extract( $args );
        echo '<div class="wrap">';
        echo '<input type="text" name="'.$name.'" id="'.$id.'" value="'.$value.'" class="tripplan-admin-color-picker" style="width:70px;"/>';
        echo (!empty($desc))?' <span class="description tripplan-admin-color-description">'.$desc.'</span>':'';
        echo '</div>';
    }

    public function tripplan_com_settings_checkbox_callback($args) {
        extract( $args );
        echo '<div class="wrap">';
        echo '<input name="'.$name.'" type="checkbox" value="1" '.checked( "1", $value, false ).' />';
        echo (!empty($desc))?' <span class="description">'.$desc.'</span>':'';
        echo '</div>';
    }

    public function tripplan_com_settings_border_style_callback($args) {
        extract( $args );
        $options = array( 'none', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset' );
        echo '<div class="wrap">';
        echo "<select class='post_form' name='".$name."' value='true'>";
		for( $i=0; $i<count($options); $i++ ) {
            echo '<option '
             . ( $value == $options[$i] ? 'selected="selected"' : '' ) . '>' 
             . $options[$i] 
             . '</option>';
        }
		echo "</select>";
        echo (!empty($desc))?' <span class="description">'.$desc.'</span>':'';
        echo '</div>';
    }

    public function tripplan_com_admin_init(){

        // If the theme options don't exist, create them.
	    if( false == get_option( 'tripplan_settings' ) ) {	
            // error_log("tripplan_settings options");
	    	add_option( 'tripplan_settings' );
	    }

        $settings = (array) get_option( 'tripplan_settings' );

	    // First, we register a section. This is necessary since all future options must belong to a 
        add_settings_section(
	    	'tripplan_settings_api_key',			        // ID used to identify this section and with which to register options
            esc_html__( 'Google Maps Api Key', 'tripplan-com' ),	 // Title to be displayed on the administration page
            array( $this, 'tripplan_com_settings_api_key_section_callback'),	// Callback used to render the description of the section
            'tripplan_settings'		                // Page on which to add this section of options
        );
        
        $googleMapsAPIKey = isset($settings['googleMapsAPIKey']) ? $settings['googleMapsAPIKey'] : '';
        add_settings_field(	
            'googleMapsAPIKey',				                    // ID used to identify the field throughout the theme		
            esc_html__( 'Google Maps API Key ', 'tripplan-com' ),							        // The label to the left of the option interface element
            array( $this, 'tripplan_com_settings_text_callback'),  // The name of the function responsible for rendering the option interface	
            'tripplan_settings',                       // The page on which this option will be displayed
            'tripplan_settings_api_key',                  // The section it belongs to
            array(                                      // args to be passed to call back function
                'name' => 'tripplan_settings[googleMapsAPIKey]' ,
                'value' => $googleMapsAPIKey,
                'desc' => __( '(Required) To get your API key <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">click here</a>.', 'tripplan-com' )
            )                                        
        );

        add_settings_section(
	    	'tripplan_settings_instructions',			        // ID used to identify this section and with which to register options
            esc_html__( 'Instructions', 'tripplan-com' ),	 // Title to be displayed on the administration page
            array( $this, 'tripplan_com_settings_instructions_section_callback'),	// Callback used to render the description of the section
            'tripplan_settings'		                // Page on which to add this section of options
        );
        
        $instructionsDefaultText = isset($settings['instructionsDefaultText']) ? $settings['instructionsDefaultText'] : 'Save this trip plan so you can later customize it and take it on your upcoming trip';
        add_settings_field(	
            'instructionsDefaultText',				                    // ID used to identify the field throughout the theme		
            esc_html__( 'Instructions', 'tripplan-com' ),							        // The label to the left of the option interface element
            array( $this, 'tripplan_com_settings_textarea_callback'),  // The name of the function responsible for rendering the option interface	
            'tripplan_settings',                       // The page on which this option will be displayed
            'tripplan_settings_instructions',                  // The section it belongs to
            array(                                      // args to be passed to call back function
                'name' => 'tripplan_settings[instructionsDefaultText]' ,
                'value' => $instructionsDefaultText,
            )                                        
        );

        add_settings_section(
	    	'tripplan_settings_save',			        // ID used to identify this section and with which to register options
            esc_html__( 'Save Button', 'tripplan-com' ),	 // Title to be displayed on the administration page
            array( $this, 'tripplan_com_settings_save_section_callback'),	// Callback used to render the description of the section
            'tripplan_settings'		                // Page on which to add this section of options
        );
        
        $saveDefaultText = isset($settings['saveDefaultText']) ? $settings['saveDefaultText'] : 'Save Trip Plan';
        add_settings_field(	
            'saveDefaultText',				                    // ID used to identify the field throughout the theme		
            esc_html__( 'Default Text', 'tripplan-com' ),							        // The label to the left of the option interface element
            array( $this, 'tripplan_com_settings_text_callback'),  // The name of the function responsible for rendering the option interface	
            'tripplan_settings',                       // The page on which this option will be displayed
            'tripplan_settings_save',                  // The section it belongs to
            array(                                      // args to be passed to call back function
                'name' => 'tripplan_settings[saveDefaultText]' ,
                'value' => $saveDefaultText,
                'desc' => esc_html__( 'This is the default text for the Save button. You can set it to anything you like.', 'tripplan-com' )
            )                                        
        );

        $saveTextColor = isset($settings['saveTextColor']) ? $settings['saveTextColor'] : '#FFFFFF';
        add_settings_field(	
            'saveTextColor',				                    // ID used to identify the field throughout the theme		
            esc_html__( 'Text Color', 'tripplan-com' ),							        // The label to the left of the option interface element
            array( $this, 'tripplan_com_settings_color_picker_callback'),  // The name of the function responsible for rendering the option interface	
            'tripplan_settings',                       // The page on which this option will be displayed
            'tripplan_settings_save',                  // The section it belongs to
            array(                                      // args to be passed to call back function
                'name' => 'tripplan_settings[saveTextColor]' ,
                'value' => $saveTextColor,
                'id' => 'tripplan-picker-saveTextColor',
                'desc' => esc_html__( 'This is the text color of the Save button', 'tripplan-com' )
            )                                        
        );

        $saveBackgroundColor = isset($settings['saveBackgroundColor']) ? $settings['saveBackgroundColor'] : '#FF5722';
        add_settings_field(	
            'saveBackgroundColor',				                    // ID used to identify the field throughout the theme		
            esc_html__( 'Background Color', 'tripplan-com' ),							        // The label to the left of the option interface element
            array( $this, 'tripplan_com_settings_color_picker_callback'),  // The name of the function responsible for rendering the option interface	
            'tripplan_settings',                       // The page on which this option will be displayed
            'tripplan_settings_save',                  // The section it belongs to
            array(                                      // args to be passed to call back function
                'name' => 'tripplan_settings[saveBackgroundColor]' ,
                'value' => $saveBackgroundColor,
                'id' => 'tripplan-picker-saveBackgroundColor',
                'desc' => esc_html__( 'This is the background color of the Save button', 'tripplan-com' )
            )                                        
        );

	    add_settings_section(
	    	'tripplan_settings_print',			        // ID used to identify this section and with which to register options
            esc_html__( 'Print Button', 'tripplan-com' ),					        // Title to be displayed on the administration page
            array( $this, 'tripplan_com_settings_print_section_callback'),	// Callback used to render the description of the section
            'tripplan_settings'		                // Page on which to add this section of options
        );
        
        $printTextColor = isset($settings['printTextColor']) ? $settings['printTextColor'] : '#FFFFFF';
        add_settings_field(	
            'printTextColor',				                    // ID used to identify the field throughout the theme		
            esc_html__( 'Text Color', 'tripplan-com' ),							        // The label to the left of the option interface element
            array( $this, 'tripplan_com_settings_color_picker_callback'),  // The name of the function responsible for rendering the option interface	
            'tripplan_settings',                       // The page on which this option will be displayed
            'tripplan_settings_print',                  // The section it belongs to
            array(                                      // args to be passed to call back function
                'name' => 'tripplan_settings[printTextColor]' ,
                'value' => $printTextColor,
                'id' => 'tripplan-picker-printTextColor',
                'desc' => esc_html__( 'This is the text color of the Print button', 'tripplan-com' )
            )                                        
        );

        $printBackgroundColor = isset($settings['printBackgroundColor']) ? $settings['printBackgroundColor'] : '#2196F3';
        add_settings_field(	
            'printBackgroundColor',				                    // ID used to identify the field throughout the theme		
            esc_html__( 'Background Color', 'tripplan-com' ),							        // The label to the left of the option interface element
            array( $this, 'tripplan_com_settings_color_picker_callback'),  // The name of the function responsible for rendering the option interface	
            'tripplan_settings',                       // The page on which this option will be displayed
            'tripplan_settings_print',                  // The section it belongs to
            array(                                      // args to be passed to call back function
                'name' => 'tripplan_settings[printBackgroundColor]' ,
                'value' => $printBackgroundColor,
                'id' => 'tripplan-picker-printBackgroundColor',
                'desc' => esc_html__( 'This is the background color of the Print button', 'tripplan-com' )
            )                                        
        );

        add_settings_section(
	    	'tripplan_settings_extra',			        // ID used to identify this section and with which to register options
            esc_html__( 'Extra Button', 'tripplan-com' ),					        // Title to be displayed on the administration page
            array( $this, 'tripplan_com_settings_extra_section_callback'),	// Callback used to render the description of the section
            'tripplan_settings'		                // Page on which to add this section of options
        );
        
        $extraTextColor = isset($settings['extraTextColor']) ? $settings['extraTextColor'] : '#FFFFFF';
        add_settings_field(	
            'extraTextColor',				                    // ID used to identify the field throughout the theme		
            esc_html__( 'Text Color', 'tripplan-com' ),							        // The label to the left of the option interface element
            array( $this, 'tripplan_com_settings_color_picker_callback'),  // The name of the function responsible for rendering the option interface	
            'tripplan_settings',                       // The page on which this option will be displayed
            'tripplan_settings_extra',                  // The section it belongs to
            array(                                      // args to be passed to call back function
                'name' => 'tripplan_settings[extraTextColor]' ,
                'value' => $extraTextColor,
                'id' => 'tripplan-picker-extraTextColor',
                'desc' => esc_html__( 'This is the text color of the extra button', 'tripplan-com' )
            )                                        
        );

        $extraBackgroundColor = isset($settings['extraBackgroundColor']) ? $settings['extraBackgroundColor'] : '#2196F3';
        add_settings_field(	
            'extraBackgroundColor',				                    // ID used to identify the field throughout the theme		
            esc_html__( 'Background Color', 'tripplan-com' ),							        // The label to the left of the option interface element
            array( $this, 'tripplan_com_settings_color_picker_callback'),  // The name of the function responsible for rendering the option interface	
            'tripplan_settings',                       // The page on which this option will be displayed
            'tripplan_settings_extra',                  // The section it belongs to
            array(                                      // args to be passed to call back function
                'name' => 'tripplan_settings[extraBackgroundColor]' ,
                'value' => $extraBackgroundColor,
                'id' => 'tripplan-picker-extraBackgroundColor',
                'desc' => esc_html__( 'This is the background color of the extra button', 'tripplan-com' )
            )                                        
        );

        add_settings_section(
	    	'tripplan_settings_border',			        // ID used to identify this section and with which to register options
            esc_html__( 'Border', 'tripplan-com' ),					        // Title to be displayed on the administration page
            array( $this, 'tripplan_com_settings_border_section_callback'),	// Callback used to render the description of the section
            'tripplan_settings'		                // Page on which to add this section of options
        );
        
        $borderColor = isset($settings['borderColor']) ? $settings['borderColor'] : '#03A9F4';
        add_settings_field(	
            'borderColor',				                    // ID used to identify the field throughout the theme		
            esc_html__( 'Border Color', 'tripplan-com' ),							        // The label to the left of the option interface element
            array( $this, 'tripplan_com_settings_color_picker_callback'),  // The name of the function responsible for rendering the option interface	
            'tripplan_settings',                       // The page on which this option will be displayed
            'tripplan_settings_border',                  // The section it belongs to
            array(                                      // args to be passed to call back function
                'name' => 'tripplan_settings[borderColor]' ,
                'value' => $borderColor,
                'id' => 'tripplan-picker-borderColor',
                'desc' => esc_html__( 'This is the color of border around the trip plan', 'tripplan-com' )
            )                                        
        );

        $borderStyle = isset($settings['borderStyle']) ? $settings['borderStyle'] : 'dashed';
        add_settings_field(	
            'borderStyle',				                    // ID used to identify the field throughout the theme		
            esc_html__( 'Border Style', 'tripplan-com' ),							        // The label to the left of the option interface element
            array( $this, 'tripplan_com_settings_border_style_callback'),  // The name of the function responsible for rendering the option interface	
            'tripplan_settings',                       // The page on which this option will be displayed
            'tripplan_settings_border',                  // The section it belongs to
            array(                                      // args to be passed to call back function
                'name' => 'tripplan_settings[borderStyle]' ,
                'value' => $borderStyle,
                'desc' => esc_html__( 'This is the style of the border around the trip plan', 'tripplan-com' )
            )                                        
        );

        add_settings_section(
	    	'tripplan_settings_powered',			        // ID used to identify this section and with which to register options
            'Powered By',					        // Title to be displayed on the administration page
            array( $this, 'tripplan_com_settings_powered_section_callback'),	// Callback used to render the description of the section
            'tripplan_settings'		                // Page on which to add this section of options
        );
        
        $poweredBy = isset($settings['poweredBy']) ? $settings['poweredBy'] : 0;
        add_settings_field(	
            'poweredBy',				                    // ID used to identify the field throughout the theme		
            'Powered By',							        // The label to the left of the option interface element
            array( $this, 'tripplan_com_settings_checkbox_callback'),  // The name of the function responsible for rendering the option interface	
            'tripplan_settings',                       // The page on which this option will be displayed
            'tripplan_settings_powered',                  // The section it belongs to
            array(                                      // args to be passed to call back function
                'name' => 'tripplan_settings[poweredBy]' ,
                'value' => $poweredBy,
                'desc' => esc_html__( 'Let people know the Trip Plans are powered by Checklist.com', 'tripplan-com' )
            )                                        
        );


        // Finally, we register the fields with WordPress
        register_setting(
            'tripplan_group',
            'tripplan_settings'
        );
    }

    /*
     * Actions perform on activation of plugin
     */
     public static function wpa_install() {
    }

    /*
     * Actions perform on de-activation of plugin
     */
    public static function wpa_uninstall() {
        // delete any settings we have made
        unregister_setting(
            'tripplan_group',
            'tripplan_settings'
        );
    }
}

new WP_TripPlan();

register_activation_hook( __FILE__, array( 'WP_TripPlan', 'wpa_install' ) );
register_deactivation_hook( __FILE__, array( 'WP_TripPlan', 'wpa_uninstall' ) );

add_shortcode('tp-trip', array('WP_TripPlan', 'register_tp_trip_shortcode') );
add_shortcode('tp-poi', array('WP_TripPlan', 'register_tp_poi_shortcode') );
add_shortcode('tp-map', array('WP_TripPlan', 'register_tp_map_shortcode') );

// for development only:

// remove wp version param from any enqueued scripts
// add_filter( 'style_loader_src', 'tripplan_remove_version' );
// add_filter( 'script_loader_src', 'tripplan_remove_version' );

// function tripplan_remove_version( $url )
// {
//     return remove_query_arg( 'ver', $url );
// }
?>