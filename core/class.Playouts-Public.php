<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * the public part starts here
 *
 *
 */
class Playouts_Public {

    /*
     * get the current post type
     *
     */
    static $current_post_type;

    /*
     * will be set to true if we are in the right place
     * and we can parse and render the content
     *
     */
    static $do_render = false;

    /*
     * holds all the modules
     *
     */
    static $modules = array();

    /*
     * Fire the public scrap
     *
     */
	static function init() {

        self::$modules = Playouts_Element::get_modules();

        # check and set some important global information
        //add_action( 'wp', array( 'Playouts_Public', 'set_globals' ) );
        # enqueue scripts
        add_action( 'wp_enqueue_scripts', array( 'Playouts_Public', 'enqueue_scripts' ) );
        # set custom css
        add_action( 'wp_head', array( 'Playouts_Public', 'custom_css' ) );
        # custom class settings
        add_filter( 'body_class', array( 'Playouts_Public', 'body_class_settings' ) );
        # filter the content of the post
        add_filter( 'the_content', array( 'Playouts_Public', 'the_content' ) );
        # templates
        add_action( 'wp_footer', array( 'Playouts_Public', 'templates' ) );

	}

    /*
     * get the content of the post and render the shotcodes
     *
     */
    static function the_content( $content ) {

        if( ! self::is_builder_used() or ! is_main_query() ) {
            return $content;
        }

        // lets use our own shortcode parser
        include_once PL_DIR . 'inc/shortcode_parser.php';

        $outer_class = apply_filters( 'pl_content_wrap_class', array( 'pl-outer' ) );
        $outer_classes = implode( ' ', $outer_class );

        $outer_id = apply_filters( 'pl_content_id', 'pl-outer' );

        $inner_class = apply_filters( 'pl_content_inner_class', array( 'pl-inner' ) );
        $inner_classes = implode( ' ', $inner_class );

		return $content = sprintf(
			'<div class="%2$s" id="%4$s">
				<div class="%3$s">
					%1$s
				</div>
			</div>
            <span id="pl-overlay" class="pl-overlay"></span>
            <span id="pl-overlay-container" class="pl-overlay pl-overlay-container"></span>',
			self::parse_content( $content ), esc_attr( $outer_classes ), esc_attr( $inner_classes ), esc_attr( $outer_id )
		);

    }

    static function is_builder_used( $post_id = false ) {
        if( ! $post_id ) { $post_id = get_the_ID(); }
        return get_post_meta( $post_id, '__pl_status', true ) == '1';
    }

    /*
     * we will use our own parser
     * parse given content and extract shortcodes into array
     *
     */
    static function parse_content( $content ) {

        $shortcodes_arr = array();
        $shortcodes_arr = pl_do_shortcodes( $shortcodes_arr, $content );

        $rendered = self::loop_shortcodes_and_render( $shortcodes_arr );

        return $rendered;


    }

    /*
     * loop array of shortcodes and render the corresponding templates
     *
     */
    static function loop_shortcodes_and_render( $shortcodes_arr ) {

        $html_output = '';

        foreach( $shortcodes_arr as $shortcode_arr ) {

            $module_id = $shortcode_arr['id'];

            $callable_template = self::$modules[ $module_id ]->class_name . '::output';
            $callable_construct = self::$modules[ $module_id ]->class_name . '::construct';

            if( is_callable( $callable_template ) ) {

                $content = '';

                // will be called before the child elements
                // can be used to pass variables to the child template
                if( is_callable( $callable_construct ) ) {
                    $html_output .= call_user_func_array( $callable_construct, array( $shortcode_arr['atts'], $content ) );
                }

                // render the content
                if( isset( $shortcode_arr['content'] ) ) {
                    $content = is_array( $shortcode_arr['content'] ) ? self::loop_shortcodes_and_render( $shortcode_arr['content'] ) : Playouts_Functions::autop( $shortcode_arr['content'] );
                }

                // call the template
                $html_output .= call_user_func_array( $callable_template, array( $shortcode_arr['atts'], $content ) );

            }

        }

        return $html_output;
    }

    /*
     * global public configuration
     *
     */
    /*static function set_globals() {

        # get the current post type
        self::$current_post_type = get_post_type();

        # the plugin can only be used inside post or page
        if( is_single() or is_page() ) {

            # check if the post type is enabled
            if( in_array( self::$current_post_type, Playouts_Bootstrap::$post_types ) ) {

                # get the status. if true, we are good to go..
                if( get_post_meta( get_the_ID(), '__pl_status', true ) ) {
                    self::$do_render = get_post_meta( get_the_ID(), '__pl_status', true );
                }

            }

        }

    }*/

    /*
     * display the custom css code
     *
     */
    static function custom_css() {

        if( self::is_builder_used() ) {

            // TODO: change via plugin option
            $cont_max = apply_filters( 'bwpb_container_max_with', 1100 );
            // TODO: fix this
            echo "<style>.bwpb-row-holder.bwpb-row-full_width_background > .bwpb-row, .bwpb-row-holder.bwpb-row-in_container, .bwpb-wrapper {max-width:{$cont_max}px;}</style>";

            $post_css = get_post_meta( get_the_ID(), '__pl_custom_css', true );

            echo "<style>" . strip_tags( $post_css ) . "</style>";

        }

    }

    /*
     * control the body classes
     *
     */
    static function body_class_settings( $classes ) {

        if( self::is_builder_used() ) {
            $classes[] = 'pl-is-enabled';
            // TODO: check this and insert into option
            //if( Bwpb::$global['align_tables'] ) {
                $classes[] = 'bwpb-align-tables';
            //}
        }
        return $classes;
    }

    static function set_background( $background, $atts ) {

        extract( $atts );

        if( $background !== 'none' ) {

            $style = $class = $data_attr = $inner = '';

            switch( $background ) {
                case 'color':
                    $style = 'background-color:' . esc_attr( $bg_color ) . ';';
                    break;
                case 'image':
                    $style  = 'background-image:url(' . esc_url( $bg_image ) . ');';
                    $style .= 'background-position:' . esc_attr( $bg_image_position ) . ';';
                    $style .= 'background-size:' . esc_attr( $bg_image_size ) . ';';
                    break;
                case 'parallax':
                    $class  = ' bwpb-parallax';
                    $style  = 'background-image:url(' . esc_url( $bg_image ) . ');';
                    $style .= 'background-position:' . esc_attr( $bg_image_position ) . ';';
                    $style .= 'background-size:' . esc_attr( $bg_image_size ) . ';';
                    $data_attr = ' data-parallax-speed="' . (int) $bg_parallax_speed . '"';
                    $inner = '<div class="bwpb-background-parallax"></div>';
                    break;
                case 'video':
                    //$class = ' bwpb-parallax';
                    //$data_attr = ' data-parallax-speed="300"';
                    if( ! empty( $bg_video_mp4 ) or ! empty( $bg_video_ogv ) or ! empty( $bg_video_webm ) ) {
                        $source = '';
                        if( ! empty( $bg_video_mp4 ) ) {
                            $source .= '<source src="' . esc_url( $bg_video_mp4 ) . '" type=\'video/webm; codecs="vp8.0, vorbis"\'>';
                        }
                        if( ! empty( $bg_video_ogv ) ) {
                            $source .= '<source src="' . esc_url( $bg_video_ogv ) . '" type=\'video/ogg; codecs="theora, vorbis"\'>';
                        }
                        if( ! empty( $bg_video_webm ) ) {
                            $source .= '<source src="' . esc_url( $bg_video_webm ) . '" type=\'video/mp4; codecs="avc1.4D401E, mp4a.40.2"\'>';
                        }
                        $inner = '<video poster="' . esc_url( $bg_video_poster ) . '" autobuffer autoplay loop muted>' . $source .
                        	'<p>' . esc_html__( 'Video not supported!', 'AAA' ) . '</p>'.
                        '</video>';
                    }
                    break;
            }

            return '<div class="bw-background-outer">'.
                '<div class="bw-background bw-row-background-' . esc_attr( $background ) . $class . '" style="' . $style . '"' . $data_attr . '>' . $inner . '</div>'.
            '</div>';

        }

        return '';

    }

    static function enqueue_scripts() {

        if( self::is_builder_used() ) {

            # css
            wp_enqueue_style( 'pl-style', PL_ASSEST . 'css/style.css' );
            # icons
            wp_enqueue_style( 'pl-stroke-7', PL_ASSEST . 'fonts/bwpb-7-stroke/pe-icon-7-stroke.css' );
            # dynamic google fonts
            wp_enqueue_style( 'pl-google-fonts', Playouts_Public_Fonts::output_google_font(), array('pl-style') );
            wp_add_inline_style( 'pl-google-fonts', Playouts_Public_Fonts::$font_declarations );

            # js
            wp_enqueue_script( 'jquery' );

    		# google maps
            /*if( Playouts_Public::check_shortcode( Bwpb::$global['load_scripts_on_shortcode']['google_api'] ) ) {
    			$google_api_key = '';
    			if( class_exists('Bw') ) {
    				$google_api_key = esc_attr( get_option( Bw::$theme_prefix . '_google_api_key', '' ) );
    			}
                wp_enqueue_script( 'bwpb-google-maps', '//maps.google.com/maps/api/js?key=' . $google_api_key );
            }*/

    		# fonts
    		/*if( Playouts_Public::check_shortcode( Bwpb::$global['load_scripts_on_shortcode']['fonts'] ) ) {
    			if( Playouts_Public::check_shortcode_string( 'font-awesome' ) ) {
    				wp_enqueue_style( 'bw-font-awesome', PL_ASSEST . 'fonts/font-awesome/font-awesome.min.css' );
    			}
    			if( Playouts_Public::check_shortcode_string( 'lineicons' ) ) {
    				wp_enqueue_style( 'bw-font-lineicons', PL_ASSEST . 'fonts/bwpb-lineicons/lineicons.css' );
    			}
    			if( Playouts_Public::check_shortcode_string( '7s' ) ) {
    				wp_enqueue_style( 'bw-font-stroke-7', PL_ASSEST . 'fonts/bwpb-7-stroke/pe-icon-7-stroke.css' );
    			}
    		}*/

            wp_enqueue_script( 'bwpb-front-plugins', PL_ASSEST . 'js/bwpb-front-plugins.js', array('jquery'), '1.0', true );
            # owl carousel
            /*if( Playouts_Public::check_shortcode( Bwpb::$global['load_scripts_on_shortcode']['owl_carousel'] ) ) {
                wp_enqueue_style( 'bwpb-owl-carousel', PL_ASSEST . 'css/vendors/jquery.owl-carousel.min.css' );
                wp_enqueue_script( 'bwpb-owl-carousel', PL_ASSEST . 'js/vendors/jquery.owl-carousel/owl.carousel.min.js', array('jquery'), '1.0', true );
            }*/
            wp_enqueue_script( 'bwpb-front', PL_ASSEST . 'js/bwpb-front.js', array('jquery'), '1.0', true );
        }
    }

    static function templates() {

        do_action( 'pl_get_public_templates' );

    }

}

function pl_init_plugin() {
	Playouts_Public::init();
}
add_action( 'init', 'pl_init_plugin' );
