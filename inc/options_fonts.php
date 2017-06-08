<?php
// TODO: use one file for all the options
return array(

    'font_container' => array(
        'label'             => esc_html__( 'Container Font', 'AAA' ),
        'type'              => 'google_font',
        'description'       => esc_html__( 'Select the main font of the container. The rest of elements will inherit it.', 'AAA' ),
        'value'             => '',
        'preview'           => esc_html__( 'It was going to be a lonely trip back. Almost before we knew it, we had left the ground.', 'AAA' ),
        'font_size'         => '15px'
    ),

    'font_headings' => array(
        'label'             => esc_html__( 'Headings Font', 'AAA' ),
        'type'              => 'google_font',
        'description'       => esc_html__( 'Select the font for all the titles.', 'AAA' ),
        'value'             => '',
        'preview'           => esc_html__( 'I watched the storm, so beautiful yet terrific.', 'AAA' )
    ),

);
