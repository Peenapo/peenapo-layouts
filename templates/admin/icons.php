<?php
if( false === ( $icons = get_transient( 'bwpb_icons' ) ) ) {

    global $wp_filesystem;
    $subject = file_get_contents( PL_DIR . 'assets/fonts/bwpb-7-stroke/pe-icon-7-stroke.css' );

    $pattern = '/\.(bwpb-7s-(.*)):before\s*{\s*content/';
    preg_match_all( $pattern, $subject, $matches, PREG_SET_ORDER );

    $icons = array();
    foreach( $matches as $match ) {
        $icons[] = $match[1];
    }
    set_transient( 'bwpb_icons', $icons, 60 * 60 * 24 );
}

echo '<script type="text/template" id="bwpb_icons">';
foreach( $icons as $icon ) {
    echo "<li data-value='{$icon}'><i class='{$icon}'></i></li>";
}
echo '</script>';
