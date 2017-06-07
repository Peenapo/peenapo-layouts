<?php

return array(

    'select1' => array(
        'label'             => esc_html__( 'Select', 'AAA' ),
        'type'              => 'select',
        'description'       => esc_html__( 'Some description', 'AAA' ),
        'options'           => array(
            'some1' => 'Some 1',
            'some2' => 'Some 2',
            'some3' => 'Some 3',
        ),
        'value'             => 'some2'
    ),

    'icon2' => array(
        'label'             => esc_html__( 'Checkbox 2', 'AAA' ),
        'type'              => 'icon',
    ),

    'radio12' => array(
        'label'             => esc_html__( 'Radio Image', 'AAA' ),
        'type'              => 'radio_image',
        'description'       => esc_html__( 'Some description', 'AAA' ),
        'options'           => array(
            'some1' => array(
                'label' => 'Some 1', 'image' => PL_ASSEST . 'img/__tmp/row_full_width_background.png'
            ),
            'some2' => array(
                'label' => 'Some 2', 'image' => PL_ASSEST . 'img/__tmp/row_in_container.png'
            ),
        ),
        'value'             => 'some1'
    ),

    'radio11' => array(
        'label'             => esc_html__( 'Some Radio Button', 'AAA' ),
        'type'              => 'radio',
        'description'       => esc_html__( 'Some description', 'AAA' ),
        'options'           => array(
            'some1' => 'Some 1',
            'some2' => 'Some 2',
            'some3' => 'Some 3',
        ),
        'value'             => 'some1'
    ),

    'option1' => array(
        'label' => __( 'Some name 1', 'AAA' ),
        'type' => 'textfield',
        'value' => 'Some value 1',
        'description' => __( 'Some description 1', 'AAA' ),
        'width' => 50
    ),

    'option2' => array(
        'label' => __( 'Some name 2', 'AAA' ),
        'type' => 'textfield',
        'value' => 'Some value 2',
        'description' => __( 'Some description 2', 'AAA' ),
        'width' => 50
    ),

    'select1' => array(
        'label'             => esc_html__( 'Select', 'AAA' ),
        'type'              => 'sidebars',
        'description'       => esc_html__( 'Some description', 'AAA' ),
    ),

    'number_slider1' => array(
        'label'             => esc_html__( 'Number Slider', 'AAA' ),
        'type'              => 'number_slider',
        'description'       => esc_html__( 'Some description', 'AAA' ),
        'append_before'     => 'before..',
        'append_after'      => 'after..',
        'min'               => 10,
        'max'               => 100,
        'step'              => 5,
        'value'             => 55,
    ),

    'image3' => array(
        'label'             => esc_html__( 'Image Depends', 'AAA' ),
        'type'              => 'image',
        'value'             => '',
        'depends'           => array( 'element' => 'checkbox11', 'value' => '1' ),
    ),
    
    'checkbox11' => array(
        'label'             => esc_html__( 'Checkbox', 'AAA' ),
        'type'              => 'true_false',
    ),

);
