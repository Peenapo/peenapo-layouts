<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 *
 *
 *
 */
class Playouts_Admin_Settings {

    static $support_layouts_settings;

    static $strings;

	/*
	 *
	 *
	 */
    static function init() {

        # check if the user have enough permissions
        if( ! current_user_can( 'manage_options' ) ) { return; }

        # add menu
        add_action( 'admin_menu', array( 'Playouts_Admin_Settings', 'menu' ), 10 );


    }

    /*
     * add the Peenapo Settings Panel menu item in admin dashboard
     * so we can manage plugin's options, layout post types, etc.
     *
     */
    static function menu() {

        // add our admin menu
        add_menu_page(
            __( 'Peenapo Panel', 'AAA' ),                       # page title
            __( 'Peenapo Layouts', 'AAA' ),                            # menu title
            'manage_options',                                   # capability
            'playouts_options',                                 # menu slug
            array( 'Playouts_Admin_Settings', 'page_settings' ), # callback function
            PL_ASSEST . 'admin/images/peenapo-dash-icon.png'   # icon
        );

        // create the settings submenu
        add_submenu_page(
            'playouts_options',                                 # parent slug name
            __( 'Peenapo Layouts Settings Panel', 'AAA' ),      # page title
            __( 'Settings', 'AAA' ),                    # menu title
            'manage_options',                                   # capability
            'playouts_options'                                  # menu slug
        );

        // create submenu that points to view pl_layout post type
        add_submenu_page(
            'playouts_options',
            __( 'Custom Layouts', 'AAA' ),
            __( 'Custom Layouts', 'AAA' ),
            'manage_options',
            'edit.php?post_type=pl_layout'
        );

        // display additional management buttons for pl_layout post type
        add_action( 'load-edit.php', array( 'Playouts_Admin_Settings', 'panel_categories_section' ) );

        // theme options submenu
        /*add_submenu_page(
            'playouts_options',
            __( 'Theme Options', 'AAA' ),
            __( 'Theme Options', 'AAA' ),
            'manage_options',
            'pl_theme_options',
            array( 'Playouts_Admin_Settings', 'guide_theme_options' )
        );*/

    }

    /*
     * the callback of our options panel page
     * get "bwpb_get_template_settings" template
     *
     */
    static function page_settings() {

        # set the options
        Playouts_Admin::$options = get_option( 'pl_layouts_options' );

        self::set_support();

        self::set_strings();

        self::actions();

        do_action( 'bwpb_get_template_settings' );

        # load footer templates
        add_action( 'admin_footer', array( 'Playouts_Admin', 'footer_templates' ) );
    }

    static function set_support() {

        $support_layouts_settings = array(
            'options' => array( 'label' => __('Options', 'AAA') ),
            'portability' => array( 'label' => __('Portability', 'AAA') ),
        );
        self::$support_layouts_settings = apply_filters( 'bwg_support', $support_layouts_settings );

    }

    static function set_strings() {

        self::$strings = (object) array(
            'not_complete' => __('Not complete', 'AAA'),
            'complete' => __('Complete', 'AAA'),
            'api_not_complete' => __('API Connection: Disconnected', 'AAA'),
            'api_complete' => __('API Connection: Connected', 'AAA'),
            'demo_not_complete' => __('Not Imported', 'AAA'),
            'demo_complete' => __('Imported', 'AAA'),
            'recommended' => __('RECOMMENDED', 'AAA'),
        );

    }

    static function actions() {

        add_action( 'pl_support_options', array( 'Playouts_Admin_Settings', 'support_options' ) );
        add_action( 'pl_support_portability', array( 'Playouts_Admin_Settings', 'support_portability' ) );

    }

    /*
     * get layouts options array
     * and merge values from database get_option values
     *
     */
    /*static function get_layouts_options() {

        $layouts_options_arr = require PL_DIR . 'inc/options.php';
        $layouts_options_filter = apply_filters( 'bwpb_layouts_options', $layouts_options_arr );
        $layouts_options_values = get_option( 'pl_layouts_options' );

        $layouts_options_new = array();
        foreach( $layouts_options_filter as $name => $layouts_option ) {
            # group all fields under "playouts_options"
            $layouts_options_new['playouts_options[' . $name . ']'] = $layouts_option;
            # set the value from database
            if( isset( Playouts_Admin::$options[ $name ] ) ) {
                $layouts_options_new['playouts_options[' . $name . ']']['value'] = Playouts_Admin::$options[ $name ];
            }
        }

        return json_encode( $layouts_options_new );

    }*/

    /*
     * the callback of our theme options panel
     *
     */
    /*static function guide_theme_options() {
        do_action( 'bwpb_get_template_settings_theme_options' );
    }*/

    static function panel_categories_section() {
        $current_screen = get_current_screen();
    	if ( 'edit-pl_layout' === $current_screen->id ) {
    		add_action( 'all_admin_notices', array( 'Playouts_Admin_Settings', 'get_categories_section' ) );
    	}
    }

    static function get_categories_section() {
        ?><a href="<?php echo admin_url( 'edit-tags.php?taxonomy=pl_layout_category' ); ?>" class="bw-manage-layout-categories">
            <?php _e( 'Manage Layout Categories', 'AAA' ); ?>
        </a><?php
    }

    static function support_options() {
        Playouts_Admin_Template_Functions::get_template( 'admin/settings/option-tabs/options' );
    }

    static function support_portability() {
        Playouts_Admin_Template_Functions::get_template( 'admin/settings/option-tabs/portability' );
    }

}
Playouts_Admin_Settings::init()

?>
