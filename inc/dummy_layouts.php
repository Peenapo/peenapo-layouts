<?php

$penapo_premium = array(
    'addon_name' => esc_html__( 'Requires Peenapo Premium', 'peenapo-layouts-txd' ),
    'addon_url' => 'https://www.peenapo.com/addons/peenapo-premium/',
);
$penapo_portfolio = array(
    'addon_name' => esc_html__( 'Requires Peenapo Portfolio', 'peenapo-layouts-txd' ),
    'addon_url' => 'https://www.peenapo.com/addons/peenapo-portfolio/',
);

return array(

    array(
        'name' => esc_html__( 'Portfolio 2 Columns Boxed', 'peenapo-layouts-txd' ),
        'image' => PLAYOUTS_ASSEST . 'admin/images/__layouts/portfolio/2_cols_boxed.png',
        'requires' => array(
            $penapo_portfolio
        ),
    ),

    array(
        'name' => esc_html__( 'Portfolio 3 Columns Wide', 'peenapo-layouts-txd' ),
        'image' => PLAYOUTS_ASSEST . 'admin/images/__layouts/portfolio/3_cols_wide.png',
        'requires' => array(
            $penapo_portfolio
        ),
    ),

    array(
        'name' => esc_html__( 'Portfolio 5 Columns Wide', 'peenapo-layouts-txd' ),
        'image' => PLAYOUTS_ASSEST . 'admin/images/__layouts/portfolio/5_cols_wide.png',
        'requires' => array(
            $penapo_portfolio
        ),
    ),

    array(
        'name' => esc_html__( 'Portfolio 2 Columns Boxed with Autotype Text', 'peenapo-layouts-txd' ),
        'image' => PLAYOUTS_ASSEST . 'admin/images/__layouts/portfolio/2_cols_boxed_autotype.png',
        'requires' => array(
            $penapo_portfolio
        ),
    ),

    array(
        'name' => esc_html__( 'Portfolio 2 Columns Boxed with heading Text', 'peenapo-layouts-txd' ),
        'image' => PLAYOUTS_ASSEST . 'admin/images/__layouts/portfolio/3_cols_boxed_heading.png',
        'requires' => array(
            $penapo_portfolio
        ),
    ),

);
