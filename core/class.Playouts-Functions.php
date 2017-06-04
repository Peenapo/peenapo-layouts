<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * general plugin functions
 * usable in both admin and public
 *
 */
class Playouts_Functions {

    /*
	 * fix paragraphs
	 *
	 */
    static function autop( $content ) {
        $output = preg_replace( '/^<\/p><p>/', '<p>', $content );
        $output = preg_replace( '/^<\/p>/', '', $output );
        $output = preg_replace( '/<\/p><p>$/', '</p>', $output );
        $output = preg_replace( '/<p>$/', '', $output );
        return $output;
    }

	/*
	 * de-escape quotes from shortcode params
	 *
	 */
    static function quote_decode( $text ) {
        return str_replace( '`', "'", str_replace( '``', '"', $text ) );
    }

	/*
	 * retrieves the attachment id from url
	 *
	 */
    static function get_image_id_from_url( $url ) {

		$attachment_id = 0;
        $file = basename( $url );
        $query = new WP_Query( array(
            'post_type'   => 'attachment',
            'post_status' => 'inherit',
            'fields'      => 'ids',
            'meta_query'  => array(
                array(
                    'value'   => $file,
                    'compare' => 'LIKE',
                    'key'     => '_wp_attachment_metadata',
                ),
            )
        ));
        if ( $query->have_posts() ) {
            foreach ( $query->posts as $post_id ) {
                $meta = wp_get_attachment_metadata( $post_id );
                $original_file = basename( $meta['file'] );
                $cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
                if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
                    $attachment_id = $post_id;
                    break;
                }
            }
        }
        return $attachment_id;
    }

	/*
	 * encode base64 into parameter friendly base 64 code
	 *
	 */
	static function base64_to_param_encode( $string ) {
		return str_replace( '=', '_', base64_encode( $string ) );
	}

	/*
	 * decode base64 from parameter friendly base 64 code
	 *
	 */
	static function base64_from_param_decode( $string ) {
		return base64_decode( str_replace( '=', '_', $string ) );
	}

}
