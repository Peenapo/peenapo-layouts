<?php

function pl_get_pattern( $text ) {
    $pattern = '\[(\[?)(' . implode( '|', Playouts_Element::get_modules_raw() ) . ')(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
    preg_match_all( "/$pattern/s", $text, $c );
    return $c;
}

function pl_parse_atts( $atts_string ) {

    $atts_string = trim( $atts_string );
    $atts_string = str_replace( '&#8221;', '"', $atts_string );
    $atts_string = str_replace( '&#8243;', '"', $atts_string );

    return shortcode_parse_atts( $atts_string );

}

function pl_do_shortcodes( &$output, $text, $child = false ) {

    $patts = pl_get_pattern( $text );

    $t = array_filter( pl_get_pattern( $text ) );

    if ( ! empty( $t ) ) {
        list( $d, $d, $parents, $atts, $d, $contents ) = $patts;
        $out2 = array();
        $n = 0;
        foreach( $parents as $k => $parent ) {
            ++$n;
            $name = $child ? 'child' . $n : $n;
            $t = array_filter( pl_get_pattern( $contents[ $k ] ) );
            $t_s = pl_do_shortcodes( $out2, $contents[ $k ], true );
            $output[ $name ] = array( 'id' => $parents[ $k ] );
            $output[ $name ]['atts'] = pl_parse_atts( $atts[ $k ] );
            $output[ $name ]['content'] = ! empty( $t ) && ! empty( $t_s ) ? $t_s : $contents[ $k ];
        }
    }

    return array_values( $output );

}
