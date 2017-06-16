<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * All template functions
 *
 *
 */
class Playouts_Template_Functions {

    static function get_templates() {

        do_action( 'pl_get_template_video_modal' );

    }

    static function get_template_video_modal() {
        self::get_template( 'video-modal-screen' );
    }

    static function get_template( $template ) {
        include PL_DIR . 'templates/' . $template . '.php';
    }

}
