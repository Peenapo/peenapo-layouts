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
            __( 'Peenapo Panel', 'peenapo-layouts-txd' ),           # page title
            __( 'Peenapo Layouts', 'peenapo-layouts-txd' ),         # menu title
            'manage_options',                                       # capability
            'playouts_options',                                     # menu slug
            array( 'Playouts_Admin_Settings', 'page_settings' ),    # callback function
            PLAYOUTS_ASSEST . 'admin/images/peenapo-dash-icon.png'  # icon
        );

        // create the settings submenu
        add_submenu_page(
            'playouts_options',                                     # parent slug name
            __( 'Peenapo Layouts Settings Panel', 'peenapo-layouts-txd' ), # page title
            __( 'Settings', 'peenapo-layouts-txd' ),                # menu title
            'manage_options',                                       # capability
            'playouts_options'                                      # menu slug
        );

        // create submenu that points to view playouts_layout post type
        add_submenu_page(
            'playouts_options',
            __( 'Custom Layouts', 'peenapo-layouts-txd' ),
            __( 'Custom Layouts', 'peenapo-layouts-txd' ),
            'manage_options',
            'edit.php?post_type=playouts_layout'
        );

        // display additional management buttons for playouts_layout post type
        add_action( 'load-edit.php', array( 'Playouts_Admin_Settings', 'panel_categories_section' ) );

    }

    /*
     * the callback of our options panel page
     * get "playouts_get_template_settings" template
     *
     */
    static function page_settings() {

        # set the options
        Playouts_Admin::$options = get_option( 'playouts_options' );

        self::set_support();

        self::set_strings();

        self::actions();

        do_action( 'playouts_get_template_settings' );

        # load footer templates
        add_action( 'admin_footer', array( 'Playouts_Admin', 'footer_templates' ) );
    }

    static function set_support() {

        $support_layouts_settings = array(
            'options'       => array( 'label' => __( 'Options', 'peenapo-layouts-txd' ) ),
            'fonts'         => array( 'label' => __( 'Fonts', 'peenapo-layouts-txd' ) ),
            //'portability'   => array( 'label' => __( 'Portability', 'peenapo-layouts-txd' ) ),
        );
        self::$support_layouts_settings = apply_filters( 'plg_support', $support_layouts_settings );

    }

    static function set_strings() {

        self::$strings = (object) array(
            'not_complete' => __('Not complete', 'peenapo-layouts-txd'),
            'complete' => __('Complete', 'peenapo-layouts-txd'),
            'api_not_complete' => __('API Connection: Disconnected', 'peenapo-layouts-txd'),
            'api_complete' => __('API Connection: Connected', 'peenapo-layouts-txd'),
            'demo_not_complete' => __('Not Imported', 'peenapo-layouts-txd'),
            'demo_complete' => __('Imported', 'peenapo-layouts-txd'),
            'recommended' => __('RECOMMENDED', 'peenapo-layouts-txd'),
        );

    }

    static function actions() {

        add_action( 'playouts_support_options', array( 'Playouts_Admin_Settings', 'support_options' ) );
        add_action( 'playouts_support_fonts', array( 'Playouts_Admin_Settings', 'support_fonts' ) );
        add_action( 'playouts_support_portability', array( 'Playouts_Admin_Settings', 'support_portability' ) );

    }

    static function panel_categories_section() {
        $current_screen = get_current_screen();
    	if ( 'edit-playouts_layout' === $current_screen->id ) {
    		add_action( 'all_admin_notices', array( 'Playouts_Admin_Settings', 'get_categories_section' ) );
    	}
    }

    static function get_categories_section() {
        ?><a href="<?php echo admin_url( 'edit-tags.php?taxonomy=playouts_layout_category' ); ?>" class="bw-manage-layout-categories">
            <?php _e( 'Manage Layout Categories', 'peenapo-layouts-txd' ); ?>
        </a>
        <style type="text/css">
            /* panel categories section */
            .bw-manage-layout-categories, .bw-manage-layout-categories:focus {position:relative;top:-3px;display:inline-block;padding:5px 8px;margin-top:20px;background-color:#f93d66;color:#fff;font-size:13px;font-weight:600;text-decoration:none;border:none;border-radius:3px;text-shadow:none;box-shadow:none;}
            .bw-manage-layout-categories:hover {background-color:#e82d55;color:#fff;}
        </style>
        <?php
    }

    static function support_options() {
        Playouts_Admin_Template_Functions::get_template( 'admin/settings/option-tabs/general' );
    }

    static function support_fonts() {
        Playouts_Admin_Template_Functions::get_template( 'admin/settings/option-tabs/fonts' );
    }

    static function support_portability() {
        Playouts_Admin_Template_Functions::get_template( 'admin/settings/option-tabs/portability' );
    }

}
Playouts_Admin_Settings::init()

?>
