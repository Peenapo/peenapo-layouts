<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * mapping, load elements and required info to render the public part
 *
 *
 */
class Playouts_Public_Map {

    static $strings;

    static function init() {

        self::set_strings();

        include PL_DIR . 'core/class.Playouts-Element.php';

        add_action( 'wp_footer', array( 'Playouts_Public_Map', 'front_params' ) );

    }

    static function front_params() {

        wp_localize_script( 'bwpb-front', 'bwpb_params', array(

            'is_mobile' => wp_is_mobile(),
            'i18n' => Playouts_Public_Map::$strings,

        ));

    }

    static function set_strings() {

        self::$strings = array(

            'days' => __( 'Days', 'AAA' ),
            'hours' => __( 'Hours', 'AAA' ),
            'minutes' => __( 'Minutes', 'AAA' ),
            'seconds' => __( 'Seconds', 'AAA' ),

        );

    }

}

# define and map all the elements
Playouts_Public_Map::init();
