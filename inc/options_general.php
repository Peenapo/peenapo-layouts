<?php

$pl_options = array(

    'google_map_api_key' => array(
        'type'              => 'textfield',
        'label'             => esc_html__( 'Google Map Api Key', 'AAA' ),
        'description'       => sprintf( esc_html__( 'Add Google Map Api Key to display your maps correctly. You can get the key from %s.', 'AAA' ), '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank" rel="nofollow">' . esc_attr__( 'here', 'AAA' ) . '</a>' ),
    )

    ,'show_editor' => array(
        'type'              => 'true_false',
        'label'             => esc_html__( 'Show Content Editor', 'AAA' ),
        'description'       => esc_html__( 'Show the WordPress Content Editor while Peenapo Layouts is active', 'AAA' ),
    )

    ,'post_types_heading' => array(
        'type'              => 'heading',
        'label'             => esc_html__( 'Select Post Types', 'AAA' ),
        'description'       => esc_html__( 'Select the post types where to render the plugin', 'AAA' ),
    )

);

foreach( get_post_types( array( 'public'   => true ), 'objects' ) as $post_type ) {

    if( $post_type->name == 'attachment' ) { continue; }

    $pl_options['post_types'][ $post_type->name ] = array(
        'type'              => 'true_false',
        'label'             => $post_type->label,
    );

}

return $pl_options;
