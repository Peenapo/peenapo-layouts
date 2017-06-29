<?php
/*
Plugin Name: Peenapo Layouts
Plugin URI: http://peenapo.com
Description: Peenapo Layouts is a drag &amp; drop back-end, shortcode based layout builder for WordPress.
Version: 1.0
Author: Peenapo
Author URI: https://www.peenapo.com
Text Domain: peenapo-layouts-txd
License: GNU General Public License v3+
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) { exit; } // exit if accessed directly

/*
 * prints human-readable information
 *
 */
if( ! function_exists( 'd' ) ) {
	function d( $what ) {
		print '<pre>';
		print_r( $what );
		print '</pre>';
	}
}

/*
 * set content directories
 *
 */
if( ! defined( 'PL_DIR' ) ) { define( 'PL_DIR', plugin_dir_path( __FILE__ ) ); }
if( ! defined( 'PL_URL' ) ) { define( 'PL_URL', plugin_dir_url( __FILE__ ) ); }
if( ! defined( 'PL_CORE' ) ) { define( 'PL_CORE', PL_DIR . 'core/' ); }
if( ! defined( 'PL_ASSEST' ) ) { define( 'PL_ASSEST', PL_URL . 'assets/' ); }

/*
 * lets boot this scrap
 *
 */
class Playouts_Bootstrap {

	/*
	 * holds all active plugins
	 *
	 */
	static $active_plugins = array();

	/*
	 * the slug of the plugin
	 *
	 */
	static $plugin_slug;

	/*
	 * initiates the plugin
	 *
	 */
	static function init() {

		self::$plugin_slug = plugin_basename( __FILE__ );

		# after active plugins and pluggable functions are loaded
        add_action( 'plugins_loaded', array( 'Playouts_Bootstrap', 'components' ) );

		# make the plguin translatable
        add_action( 'init', array( 'Playouts_Bootstrap', 'translatable' ) );

		if( is_admin() ) {
        	# trigger on plugin activation
        	register_activation_hook( self::$plugin_slug, array( 'Playouts_Bootstrap', 'on_plugin_activation' ) );
		}

    }

	/*
	 * after active plugins and pluggable functions are loaded
	 * we can load the required components
	 *
	 */
    static function components() {

		self::set_globals();

        include PL_CORE . 'class.Playouts-Functions.php';
        include PL_CORE . 'class.Playouts-Option-Type.php';
        include PL_CORE . 'class.Playouts-Shortcode-Parser.php';

        if( is_admin() ) {

            include PL_CORE . 'admin/class.Playouts-Admin.php';
            include PL_CORE . 'admin/class.Playouts-Admin-Settings.php';
			include PL_CORE . 'admin/class.Playouts-Admin-Map.php';
			include PL_CORE . 'admin/class.Playouts-Admin-Modal.php';
            include PL_CORE . 'admin/class.Playouts-Admin-Ajax.php';
            include PL_CORE . 'admin/class.Playouts-Admin-Template-Hooks.php';
            include PL_CORE . 'admin/class.Playouts-Admin-Template-Functions.php';

        }else{

			include PL_CORE . 'class.Playouts-Public.php';
			include PL_CORE . 'class.Playouts-Public-Fonts.php';
		    include PL_CORE . 'class.Playouts-Public-Map.php';
            include PL_CORE . 'class.Playouts-Template-Hooks.php';
            include PL_CORE . 'class.Playouts-Template-Functions.php';

        }

    }

	/*
	 * set global plugin configuration
	 *
	 */
	static function set_globals() {

		self::$active_plugins = get_option( 'active_plugins' );

	}

	/*
	 * make the plguin translatable
	 * loading the plugin translations should be done in the init action
	 *
	 */
    static function translatable() {

		load_plugin_textdomain( 'peenapo-layouts-txd', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

	}



    static function on_plugin_activation() {

        #delete_option( 'pl_layouts_activation' );
        #delete_option( 'pl_layouts_options' );return;

        if( ! get_option( 'pl_layouts_activation' ) ) {

            self::inport_default_options();

            update_option( 'pl_layouts_activation', true );

        }

    }

    static function inport_default_options() {

        $default_options = include PL_DIR . 'inc/default_options.php';
        update_option( 'pl_layouts_options', $default_options );

    }

}
Playouts_Bootstrap::init();
