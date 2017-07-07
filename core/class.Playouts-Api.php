<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * do api stuff
 *
 *
 */
class Playouts_Api {

    private static $api_feedback_url = 'https://www.peenapo.com/api/v1/feedback/';

    static function send_feedback( $feedback_key, $feedback_text ) {
		return wp_remote_post( self::$api_feedback_url, array(
			'timeout' => 30,
			'body' => array(
				'api_version' => '1.0',
				'feedback_key' => $feedback_key,
				'feedback' => $feedback_text,
			),
		));
	}

}
Playouts_Api::init();
