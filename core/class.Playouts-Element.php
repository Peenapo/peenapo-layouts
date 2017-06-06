<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * declare elements
 *
 *
 */
class Playouts_Element {

    public $module;
    public $name;
    public $class_name;
    public $view;
    public $params = array();
    public $category = array();
    public $priority = 0;
    public $module_color;
    public $public = true;

    private static $modules_standard = array();
    private static $index = 0;

    function __construct() {

        self::$index++;

        $this->category = array( 'general' => __( 'General', 'AAA' ) );
        $this->class_name = get_class( $this );

        $this->init();

        self::$modules_standard[ $this->module ] = $this;

    }

	/*static function retrieve_fields() {
        return array();
    }*/

    static function output( $atts = array(), $content = null ) {
        return '';
    }

    static function get_modules() {
        return self::$modules_standard;
    }

    static function get_modules_arr() {

        $modules = array();
        foreach( self::get_modules() as $module ) {
            $modules[ $module->module ] = array(
                'name' => $module->name,
                'view' => $module->view,
                'category' => key( $module->category ),
                'public' => $module->public,

            );
        }
        return $modules;

    }

    static function get_modules_categories() {

        $categories = array();
        $modules = self::get_modules();
        foreach( $modules as $module ) {
            $category_id = key( $module->category );
            if( isset( $module->category[ $category_id ] ) ) {
                $categories[ $category_id ] = $module->category[ $category_id ];
            }
        }
        return $categories;

    }

    static function get_modules_color() {

        $modules_color = array();
        foreach( self::get_modules() as $module ) {
            $modules_color[ $module->module ] = $module->module_color;
        }
        return $modules_color;

    }

    static function get_modules_raw() {
        $modules = array();
        foreach( self::get_modules() as $module ) {
            $modules[] = $module->module;
        }
        return $modules;
    }

    static function get_module_view( $module ) {
        if( isset( self::$modules_standard[ $module ] ) ) {
            return self::$modules_standard[ $module ]->view;
        }
        return '__not_found';
    }
}

class Playouts_Repeater_Element extends Playouts_Element {

    public $module_item;

    private static $modules_repeater = array();

    function __construct() {

        parent::__construct();

        self::$modules_repeater[ $this->module ] = $this;

    }

    static function get_modules_repeater() {
        return self::$modules_repeater;
    }

    static function get_modules_repeater_raw() {
        $modules = array();
        foreach( self::get_modules_repeater() as $module ) {
            $modules[] = $module->module;
        }
        return $modules;
    }
}

class Playouts_Repeater_Item_Element extends Playouts_Element {

    public $module_parent;

    private static $modules_repeater_item = array();

    function __construct() {

        parent::__construct();

        self::$modules_repeater_item[ $this->module ] = $this;

        $this->public = false;
        $this->category = array();

    }

    static function get_modules_repeater_item() {
        return self::$modules_repeater_item;
    }

    static function get_modules_repeater_item_raw() {
        $modules = array();
        foreach( self::get_modules_repeater_item() as $module ) {
            $modules[] = $module->module;
        }
        return $modules;
    }
}

class Playouts_Element_Row extends Playouts_Element {

    function init() {

        $this->module = 'bw_row';
        $this->name = esc_html__( 'Row', 'AAA' );
        $this->view = 'row';
        $this->params = array(
            'dummy' => array( // do not remove
                'type' => 'dummy',
                'ui_remove' => true
            ),
            'is_hidden' => array(
                'type' => 'textfield',
                'ui_remove' => true
            ),
            'row_columns' => array( // do not remove
                'type' => 'columns',
                'tab' => array( 'row_columns' => esc_html__( 'Columns', 'AAA' ) ),
            ),
            'row_layout' => array(
				'label'             => esc_html__( 'Row Layout', 'AAA' ),
				'type'              => 'radio_image',
				'description'       => esc_html__( 'Select the display version of the row.', 'AAA' ),
				'options'           => array(
                    'standard' => array(
                        'label' => 'Standard', 'image' => PL_ASSEST . 'admin/images/__tmp/row_standard.png'
                    ),
                    'full' => array(
                        'label' => 'Full-Width', 'image' => PL_ASSEST . 'admin/images/__tmp/row_full_width.png'
                    ),
                    'boxed' => array(
                        'label' => 'Boxed', 'image' => PL_ASSEST . 'admin/images/__tmp/row_boxed.png'
                    ),
                ),
                'value'             => 'standard'
			),
            'background' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background', 'AAA' ),
				'description'       => esc_html__( 'Select row background type', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
				'options'           => array(
                    'none' => 'None',
                    'color' => 'Color',
                    'image' => 'Image',
                    'parallax' => 'Parallax',
                    'video' => 'Video',
                ),
                'value'             => 'none'
			),
            'bg_color' => array(
                'type'              => 'colorpicker',
				'label'             => esc_html__( 'Background Color', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'color' ),
			),
            'bg_image' => array(
                'type'              => 'image',
				'label'             => esc_html__( 'Background Image', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
			),
            'bg_image_position' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background Position', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
                'options'           => array(
                    'top left'          => 'Top Left',
                    'top center'        => 'Top Center',
                    'top right'         => 'Top Right',
                    'center left'       => 'Center Left',
                    'center center'     => 'Center Center',
                    'center right'      => 'Center Right',
                    'bottom left'       => 'Bottom Left',
                    'bottom center'     => 'Bottom Center',
                    'bottom right'      => 'Bottom Right',
                ),
                'value'             => 'center center',
                'width'             => 50
			),
            'bg_image_size' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background Size', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
                'options'           => array(
                    'auto'              => 'Auto',
                    'cover'             => 'Cover',
                    'contain'           => 'Contain',
                ),
                'value'             => 'cover',
                'width'             => 50
			),
            'bg_parallax_speed' => array(
                'type'              => 'number_slider',
                'label'             => esc_html__( 'Parallax Speed', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'parallax' ),
                'min'               => 0,
                'max'               => 300,
                'step'              => 10,
                'value'             => '',
            ),
            'bg_video_mp4' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Mp4', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_ogv' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Ogv', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_webm' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Webm', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_poster' => array(
                'type'              => 'image',
                'label'             => esc_html__( 'Video Poster', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'text_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Text Color', 'AAA' ),
                'value'             => '',
                'width'             => 50
            ),
            'text_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Text Alignment', 'AAA' ),
                'options'           => array(
                    'inherit'           => 'Inherit',
                    'left'              => 'Left',
                    'center'            => 'Center',
                    'right'             => 'Right',
                ),
                'value'             => '',
                'width'             => 50
			),
            'enable_static_height' => array(
                'label'             => esc_html__( 'Set Static Row Height', 'AAA' ),
                'type'              => 'true_false',
			),
            'static_height' => array(
                'type'              => 'number_slider',
                'label'             => esc_html__( 'Static Height in Percentage', 'AAA' ),
                'description'       => esc_html__( 'Row height, 100% = full window height', 'AAA' ),
                'depends'           => array( 'element' => 'enable_static_height', 'value' => '1' ),
                'append_after'      => '%',
                'min'               => 30,
                'max'               => 100,
                'step'              => 1,
                'value'             => '',
            ),
            'vertical_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Vertical Alignment', 'AAA' ),
                'options'           => array(
                    'baseline'          => 'Baseline',
                    'top'               => 'Top',
                    'middle'            => 'Middle',
                    'bottom'            => 'Bottom',
                ),
                'value'             => '',
			),
            'margin_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Top', 'AAA' ),
                'description'       => esc_html__( "Don't include 'px' in your string. e.g '40' - For perecent value '%' would be needed at the end e.g '10%'.", 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 50
            ),
            'margin_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Bottom', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 50
            ),
            'padding_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Top', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 25
            ),
            'padding_right' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Right', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 25
            ),
            'padding_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Bottom', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 25
            ),
            'padding_left' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Left', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 25
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'row_layout'        => 'standard',
            'is_hidden'         => false,
            'background'        => 'none',
            'bg_color'          => '',
            'bg_image'          => '',
            'bg_image_position' => 'center center',
            'bg_image_size'     => 'auto',
            'bg_parallax_speed' => 150,
            'bg_video_mp4'      => '',
            'bg_video_ogv'      => '',
            'bg_video_webm'     => '',
            'bg_video_poster'   => '',
            'text_color'        => '',
            'text_alignment'    => '',
            'enable_static_height' => false,
            'static_height'     => '30',
            'vertical_alignment' => 'baseline',
            'margin_top'        => '',
            'margin_bottom'     => '',
            'padding_top'       => '',
            'padding_right'     => '',
            'padding_bottom'    => '',
            'padding_left'      => '',
            'inline_class'  => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        if( $is_hidden ) { return ''; }

        $style = $class = $id = '';

        if( $enable_static_height ) { $style .= 'height:' . (int) $static_height . 'vh;'; }
        if( $text_color ) { $style .= 'color:' . esc_attr( $text_color ) . ';'; }
        if( $text_alignment ) { $style .= 'text-align:' . esc_attr( $text_alignment ) . ';'; }
        if( $vertical_alignment ) { $style .= 'vertical-align:' . esc_attr( $vertical_alignment ) . ';'; }
        if( $margin_top ) { $style .= 'margin-top:' . esc_attr( $margin_top ) . ( is_numeric( $margin_top ) ? 'px' : '' ) . ';'; }
        if( $margin_bottom ) { $style .= 'margin-bottom:' . esc_attr( $margin_bottom ) . ( is_numeric( $margin_bottom ) ? 'px' : '' ) . ';'; }
        if( $padding_top ) { $style .= 'padding-top:' . esc_attr( $padding_top ) . ( is_numeric( $padding_top ) ? 'px' : '' ) . ';'; }
        if( $padding_right ) { $style .= 'padding-right:' . esc_attr( $padding_right ) . ( is_numeric( $padding_right ) ? 'px' : '' ) . ';'; }
        if( $padding_bottom ) { $style .= 'padding-bottom:' . esc_attr( $padding_bottom ) . ( is_numeric( $padding_bottom ) ? 'px' : '' ) . ';'; }
        if( $padding_left ) { $style .= 'padding-left:' . esc_attr( $padding_left ) . ( is_numeric( $padding_left ) ? 'px' : '' ) . ';'; }

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        return '<div class="pl-row-outer pl-row-layout-' . $row_layout . $class . '"' . $id . '>'.
            Playouts_Public::set_background( $background, $assigned_atts ).
            '<div class="pl-row" style="' . $style . '">'.
                $content.
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Row;

class Playouts_Element_Column extends Playouts_Element {

    function init() {

        $this->module = 'bw_column';
        $this->name = esc_html__( 'Column', 'AAA' );
        $this->view = 'column';
        $this->category = array( 'content' => __( 'Content', 'AAA' ) );
        $this->public = false;
        $this->params = array(
            'dummy' => array( // do not remove
                'type' => 'dummy',
                'ui_remove' => true
            ),
            'col_width' => array( // do not remove
                'type' => 'textfield',
                'ui_hide' => true
            ),
            'background' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background', 'AAA' ),
				'description'       => esc_html__( 'Select row background type', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
				'options'           => array(
                    'none' => 'None',
                    'color' => 'Color',
                    'image' => 'Image',
                    'parallax' => 'Parallax',
                    'video' => 'Video',
                ),
                'value'             => 'none'
			),
            'bg_color' => array(
                'type'              => 'colorpicker',
				'label'             => esc_html__( 'Background Color', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'color' ),
			),
            'bg_image' => array(
                'type'              => 'image',
				'label'             => esc_html__( 'Background Image', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
			),
            'bg_image_position' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background Position', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
                'options'           => array(
                    'top left'          => 'Top Left',
                    'top center'        => 'Top Center',
                    'top right'         => 'Top Right',
                    'center left'       => 'Center Left',
                    'center center'     => 'Center Center',
                    'center right'      => 'Center Right',
                    'bottom left'       => 'Bottom Left',
                    'bottom center'     => 'Bottom Center',
                    'bottom right'      => 'Bottom Right',
                ),
                'value'             => '',
                'width'             => 50
			),
            'bg_image_size' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background Size', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
                'options'           => array(
                    'auto'              => 'Auto',
                    'cover'             => 'Cover',
                    'contain'           => 'Contain',
                ),
                'value'             => '',
                'width'             => 50
			),
            'bg_parallax_speed' => array(
                'type'              => 'number_slider',
                'label'             => esc_html__( 'Parallax Speed', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'parallax' ),
                'min'               => 0,
                'max'               => 300,
                'step'              => 10,
                'value'             => '',
            ),
            'bg_video_mp4' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Mp4', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_ogv' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Ogv', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_webm' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Webm', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_poster' => array(
                'type'              => 'image',
                'label'             => esc_html__( 'Video Poster', 'AAA' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'AAA' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'text_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Text Color', 'AAA' ),
                'value'             => '',
                'width'             => 50
            ),
            'text_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Text Alignment', 'AAA' ),
                'options'           => array(
                    'inherit'           => 'Inherit',
                    'left'              => 'Left',
                    'center'            => 'Center',
                    'right'             => 'Right',
                ),
                'value'             => '',
                'width'             => 50
			),
            'vertical_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Vertical Alignment', 'AAA' ),
                'options'           => array(
                    'baseline'          => 'Baseline',
                    'top'               => 'Top',
                    'middle'            => 'Middle',
                    'bottom'            => 'Bottom',
                ),
                'value'             => '',
			),
            'padding_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Top', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 25
            ),
            'padding_right' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Right', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 25
            ),
            'padding_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Bottom', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 25
            ),
            'padding_left' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Left', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 25
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'col_width'         => '',
            'background'        => 'none',
            'bg_color'          => '',
            'bg_image'          => '',
            'bg_image_position' => 'center center',
            'bg_image_size'     => 'auto',
            'bg_parallax_speed' => 150,
            'bg_video_mp4'      => '',
            'bg_video_ogv'      => '',
            'bg_video_webm'     => '',
            'bg_video_poster'   => '',
            'text_color'        => '',
            'text_alignment'    => '',
            'vertical_alignment' => 'inherit',
            'padding_top'       => '',
            'padding_right'     => '',
            'padding_bottom'    => '',
            'padding_left'      => '',
            'inline_class'  => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $style .= 'width:' . (int) $col_width . '%;';

        if( $text_color ) { $style .= 'color:' . esc_attr( $text_color ) . ';'; }
        if( $text_alignment ) { $style .= 'text-align:' . esc_attr( $text_alignment ) . ';'; }
        if( $vertical_alignment ) { $style .= 'vertical-align:' . esc_attr( $vertical_alignment ) . ';'; }
        if( $padding_top ) { $style .= 'padding-top:' . esc_attr( $padding_top ) . ( is_numeric( $padding_top ) ? 'px' : '' ) . ';'; }
        if( $padding_right ) { $style .= 'padding-right:' . esc_attr( $padding_right ) . ( is_numeric( $padding_right ) ? 'px' : '' ) . ';'; }
        if( $padding_bottom ) { $style .= 'padding-bottom:' . esc_attr( $padding_bottom ) . ( is_numeric( $padding_bottom ) ? 'px' : '' ) . ';'; }
        if( $padding_left ) { $style .= 'padding-left:' . esc_attr( $padding_left ) . ( is_numeric( $padding_left ) ? 'px' : '' ) . ';'; }

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        return '<div class="pl-column-outer' . $class . '" style="' . $style . '"' . $id . '>'.
            Playouts_Public::set_background( $background, $assigned_atts ).
            '<div class="pl-column">'.
                $content.
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Column;

class Playouts_Element_Row_Inner extends Playouts_Element {

    function init() {

        $this->module = 'bw_row_inner';
        $this->name = esc_html__( 'Row Inner', 'AAA' );
        $this->view = 'row_inner';
        $this->params = array(
            'dummy' => array( // do not remove
                'type' => 'dummy',
                'ui_remove' => true
            ),
            'row_columns' => array( // do not remove
                'type'               => 'columns',
                'tab'                => array( 'row_columns' => esc_html__( 'Columns', 'AAA' ) ),
            ),
            'text_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Text Color', 'AAA' ),
                'value'             => '',
                'width'             => 50
            ),
            'text_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Text Alignment', 'AAA' ),
                'options'           => array(
                    'inherit'           => 'Inherit',
                    'left'              => 'Left',
                    'center'            => 'Center',
                    'right'             => 'Right',
                ),
                'value'             => '',
                'width'             => 50
			),
            'vertical_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Vertical Alignment', 'AAA' ),
                'options'           => array(
                    'baseline'          => 'Baseline',
                    'top'               => 'Top',
                    'middle'            => 'Middle',
                    'bottom'            => 'Bottom',
                ),
                'value'             => '',
			),
            'padding_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Top', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 25
            ),
            'padding_right' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Right', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 25
            ),
            'padding_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Bottom', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 25
            ),
            'padding_left' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Left', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 25
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'is_hidden'         => false,
            'text_color'        => '',
            'text_alignment'    => '',
            'vertical_alignment' => 'baseline',
            'margin_top'        => '',
            'margin_bottom'     => '',
            'padding_top'       => '',
            'padding_right'     => '',
            'padding_bottom'    => '',
            'padding_left'      => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        if( $text_color ) { $style .= 'color:' . esc_attr( $text_color ) . ';'; }
        if( $text_alignment ) { $style .= 'text-align:' . esc_attr( $text_alignment ) . ';'; }
        if( $vertical_alignment ) { $style .= 'vertical-align:' . esc_attr( $vertical_alignment ) . ';'; }
        if( $margin_top ) { $style .= 'margin-top:' . esc_attr( $margin_top ) . ( is_numeric( $margin_top ) ? 'px' : '' ) . ';'; }
        if( $margin_bottom ) { $style .= 'margin-bottom:' . esc_attr( $margin_bottom ) . ( is_numeric( $margin_bottom ) ? 'px' : '' ) . ';'; }
        if( $padding_top ) { $style .= 'padding-top:' . esc_attr( $padding_top ) . ( is_numeric( $padding_top ) ? 'px' : '' ) . ';'; }
        if( $padding_right ) { $style .= 'padding-right:' . esc_attr( $padding_right ) . ( is_numeric( $padding_right ) ? 'px' : '' ) . ';'; }
        if( $padding_bottom ) { $style .= 'padding-bottom:' . esc_attr( $padding_bottom ) . ( is_numeric( $padding_bottom ) ? 'px' : '' ) . ';'; }
        if( $padding_left ) { $style .= 'padding-left:' . esc_attr( $padding_left ) . ( is_numeric( $padding_left ) ? 'px' : '' ) . ';'; }

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        return '<div class="pl-row-inner-outer' . $class . '"' . $id . '>'.
            '<div class="pl-row-inner" style="' . $style . '">'.
                $content.
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Row_Inner;

class Playouts_Element_Column_Inner extends Playouts_Element {

    function init() {

        $this->module = 'bw_column_inner';
        $this->name = esc_html__( 'Column Inner', 'AAA' );
        $this->view = 'column_inner';
        $this->category = array( 'content' => __( 'Content', 'AAA' ) );
        $this->public = false;
        $this->params = array(
            'dummy' => array( // do not remove
                'type' => 'dummy',
                'ui_remove' => true
            ),
            'col_width' => array( // do not remove
                'type' => 'textfield',
                'ui_hide' => true
            ),
            'text_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Text Color', 'AAA' ),
                'value'             => '',
                'width'             => 50
            ),
            'text_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Text Alignment', 'AAA' ),
                'options'           => array(
                    'inherit'           => 'Inherit',
                    'left'              => 'Left',
                    'center'            => 'Center',
                    'right'             => 'Right',
                ),
                'value'             => '',
                'width'             => 50
			),
            'vertical_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Vertical Alignment', 'AAA' ),
                'options'           => array(
                    'inherit'           => 'Inherit',
                    'top'               => 'Top',
                    'middle'            => 'Middle',
                    'bottom'            => 'Bottom',
                ),
                'value'             => '',
			),
            'padding_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Top', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 25
            ),
            'padding_right' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Right', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 25
            ),
            'padding_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Bottom', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 25
            ),
            'padding_left' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Left', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 25
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'col_width'         => '',
            'text_color'        => '',
            'text_alignment'    => '',
            'vertical_alignment' => 'inherit',
            'padding_top'       => '',
            'padding_right'     => '',
            'padding_bottom'    => '',
            'padding_left'      => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $style .= 'width:' . (int) $col_width . '%;';

        if( $text_color ) { $style .= 'color:' . esc_attr( $text_color ) . ';'; }
        if( $text_alignment ) { $style .= 'text-align:' . esc_attr( $text_alignment ) . ';'; }
        if( $vertical_alignment ) { $style .= 'vertical-align:' . esc_attr( $vertical_alignment ) . ';'; }
        if( $padding_top ) { $style .= 'padding-top:' . esc_attr( $padding_top ) . ( is_numeric( $padding_top ) ? 'px' : '' ) . ';'; }
        if( $padding_right ) { $style .= 'padding-right:' . esc_attr( $padding_right ) . ( is_numeric( $padding_right ) ? 'px' : '' ) . ';'; }
        if( $padding_bottom ) { $style .= 'padding-bottom:' . esc_attr( $padding_bottom ) . ( is_numeric( $padding_bottom ) ? 'px' : '' ) . ';'; }
        if( $padding_left ) { $style .= 'padding-left:' . esc_attr( $padding_left ) . ( is_numeric( $padding_left ) ? 'px' : '' ) . ';'; }

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        return '<div class="pl-column-inner-outer' . $class . '" style="' . $style . '"' . $id . '>'.
            '<div class="pl-column-inner">'.
                $content.
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Column_Inner;

class Playouts_Element_Text extends Playouts_Element {

    function init() {

        $this->module = 'bw_text';
        $this->name = esc_html__( 'Text', 'AAA' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'AAA' ) );
        $this->module_color = '#77e08a';
        $this->params = array(
            'text_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Text Color', 'AAA' ),
                'value'             => '',
                'width'             => 50
            ),
            'text_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Text Alignment', 'AAA' ),
                'options'           => array(
                    'inherit'           => 'Inherit',
                    'left'              => 'Left',
                    'center'            => 'Center',
                    'right'             => 'Right',
                ),
                'value'             => '',
                'width'             => 50
			),
            'content' => array(
				'label'             => esc_html__( 'Content', 'AAA' ),
				'type'              => 'editor',
				'is_content'        => true,
                'value'             => 'Text element. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.',
			),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'text_color'        => '',
            'text_alignment'    => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $style .= ! empty( $text_color ) ? 'color:' . esc_attr( $text_color ) . ';' : '';
        $style .= ! empty( $text_alignment ) ? 'text-align:' . esc_attr( $text_alignment ) . ';' : '';

        return '<div class="pl-text' . $class . '" style="' . $style . '"' . $id . '>'.
            do_shortcode( $content ) .
        '</div>';

    }
}
new Playouts_Element_Text;

class Playouts_Element_Audio extends Playouts_Element {

    function init() {

        $this->module = 'bw_audio';
        $this->name = esc_html__( 'Audio Player', 'AAA' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'AAA' ) );
        $this->module_color = '#62cdef';
        $this->params = array(
            'audio_file' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Audio File', 'AAA' ),
            ),
            'cover_top' => array(
                'label'             => esc_html__( 'Display Full Cover', 'AAA' ),
                'type'              => 'true_false',
            ),
            'title' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Title', 'AAA' ),
            ),
            'artist' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Artist', 'AAA' ),
            ),
            'cover_image' => array(
                'type'              => 'image',
                'label'             => esc_html__( 'Cover Image', 'AAA' ),
            ),
            'background_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Background Color', 'AAA' ),
                'width'             => 50
            ),
            'text_color' => array(
                'label'             => esc_html__( 'Text Color', 'AAA' ),
                'type'              => 'select',
                'options'           => array(
                    '#000'  => 'Dark',
                    '#fff'  => 'Light',
                ),
                'width'             => 50
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'audio_file'        => '',
            'cover_top'         => false,
            'title'             => '',
            'artist'            => '',
            'cover_image'       => '',
            'background_color'  => '',
            'text_color'        => '#000',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $cover = $heading = '';

        if( $cover_image ) {
            $cover = '<div class="pl-audio-cover" style="background-image:url(' . esc_url( $cover_image ) . ');"></div>';
        }
        if( $title ) {
            $heading .= '<h3>' . esc_html( $title ) . '</h3>';
        }
        if( $artist ) {
            $heading .= '<p>' . esc_html__( 'By: ', 'AAA' ) . '<strong>' . esc_html( $artist ) . '</strong></p>';
        }
        if( $text_color ) {
            $style .= 'color:' . esc_attr( $text_color ) . ';';
        }
        if( $background_color ) {
            $style .= 'background-color:' . esc_attr( $background_color ) . ';';
        }
        if( $cover_top ) {
            $class .= ' pl-cover-full';
        }

        $class .= ' pl-audio-text-color-' . ( $text_color == '#000' ? 'dark' : 'light' );

        return '<div class="pl-audio' . $class . '" style="' . $style . '"' . $id . '>'.
            $cover.
            '<div class="pl-audio-content">'.
            '<div class="pl-audio-heading">' . $heading . '</div>'.
            do_shortcode( '[audio src="' . esc_url( $audio_file ) . '"]' ).
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Audio;

class Playouts_Element_Accordion extends Playouts_Repeater_Element {

    function init() {

        $this->module = 'bw_accordion';
        $this->module_item = 'bw_accordion_item';
        $this->name = esc_html__( 'Accordion', 'AAA' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'AAA' ) );
        $this->module_color = '#be6ef6';
        $this->params = array(
            'items' => array(
                'type'               => 'repeater',
                'label'              => esc_html__( 'Tab items', 'AAA' ),
                'description'        => esc_html__( 'You can add as many tabs as you need, just click the plus icon.', 'AAA' ),
            ),
            'close_other' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Close Other Items?', 'AAA' ),
                'description'        => esc_html__( 'Enable this option if you want to close the rest of accordion items on mouse click.', 'AAA' ),
            ),
            'line_height' => array(
                'label'             => esc_html__( 'Titles Height', 'AAA' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels',
                'min'               => 40,
                'max'               => 150,
                'step'              => 1,
                'value'             => 90,
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'close_other'       => false,
            'line_height'       => 90,
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        if( $close_other ) { $class .= ' pl-close-other'; }
        $style .= 'line-height:' . (int) $line_height . 'px;';

        return '<div class="pl-accordion' . $class . '" style="' . $style . '"' . $id . '>' . $content . '</div>';

    }
}
new Playouts_Element_Accordion;

class Playouts_Element_Accordion_Item extends Playouts_Repeater_Item_Element {

    function init() {

        $this->module = 'bw_accordion_item';
        $this->module_parent = 'bw_accordion';
        $this->name = esc_html__( 'Accordion Item', 'AAA' );
        $this->view = 'repeater_item';

        $this->params = array(
            'title' => array(
				'label'              => esc_html__( 'Title', 'AAA' ),
				'type'               => 'textfield',
				'description'        => esc_html__( '', 'AAA' ),
			),
            'active' => array(
                'label'             => esc_html__( 'Active by Default?', 'AAA' ),
                'type'              => 'true_false',
            ),
            'content' => array(
				'label'             => esc_html__( 'Content', 'AAA' ),
				'type'              => 'editor',
				'is_content'        => true,
                'value'             => 'Accordion item. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.',
			),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'title'             => '',
            'active'            => false,
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        if( $active ) { $class .= ' pl-active'; }

        return '<div class="pl-accirdion-item' . $class . '" style="' . $style . '"' . $id . '>'.
            '<div class="pl-accordion-title pl-no-select"><strong><div class="pl-accordion-toggle"><i></i></div>' . esc_html( $title ) . '</strong></div>'.
            '<div class="pl-accordion-content"' . ( $active ? ' style="height:auto;"' : '' ) . '>'.
                '<div class="pl-accordion-content-inner">'.
                    do_shortcode( $content ).
                '</div>'.
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Accordion_Item;

class Playouts_Element_Tabs extends Playouts_Repeater_Element {

    function init() {

        $this->module = 'bw_tabs';
        $this->module_item = 'bw_tab_item';
        $this->name = esc_html__( 'Tabs', 'AAA' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'AAA' ) );
        $this->module_color = '#be6ef6';
        $this->params = array(
            'items' => array(
                'type'               => 'repeater',
                'label'              => esc_html__( 'Tab items', 'AAA' ),
                'description'        => esc_html__( 'You can add as many tabs as you need, just click the plus icon.', 'AAA' ),
            ),
            'nav_border' => array(
                'label'             => esc_html__( 'Enable Tabs Bottom Border', 'AAA' ),
                'type'              => 'true_false',
            ),
            'line_height' => array(
                'label'             => esc_html__( 'Tab Line Height', 'AAA' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels',
                'min'               => 40,
                'max'               => 100,
                'step'              => 1,
                'value'             => 65,
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'nav_border'        => false,
            'line_height'       => 65,
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        if( $nav_border ) { $class .= ' pl-bottom-border'; }

        $tabs_output = '<ul class="pl-tab-nav" style="line-height:' . (int) $line_height . 'px;">';
        $c = 0;
        foreach( Playouts_Element_Tab_Item::$tabs as $tab_id => $tab ) {
            $tabs_output .= '<li' . ( $c == 0 ? ' class="pl-active"' : '' ) . '><a href="#tab-' . $tab_id . '">' . esc_attr( $tab ) . '</a></li>';
            $c++;
        }
        $tabs_output .= '<li class="pl-nav-border"></li>';
        $tabs_output .= '</ul>';

        return '<div class="pl-tabs' . $class . '" style="' . $style . '"' . $id . '>' . $tabs_output . $content . '</div>';

    }
}
new Playouts_Element_Tabs;

class Playouts_Element_Tab_Item extends Playouts_Repeater_Item_Element {

    static $tabs = array();

    function init() {

        $this->module = 'bw_tab_item';
        $this->module_parent = 'bw_tabs';
        $this->name = esc_html__( 'Tab', 'AAA' );
        $this->view = 'repeater_item';

        $this->params = array(
            'title' => array(
				'label'              => esc_html__( 'Title', 'AAA' ),
				'type'               => 'textfield',
				'description'        => esc_html__( '', 'AAA' ),
			),
            'content' => array(
				'label'             => esc_html__( 'Content', 'AAA' ),
				'type'              => 'editor',
				'is_content'        => true,
                'value'             => 'Accordion item. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.',
			),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'title'             => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $tab_id = Playouts_Shortcode_Parser::get_unique_id();

        $class .= empty( self::$tabs ) ? ' pl-active' : '';

        self::$tabs[ $tab_id ] = $title;

        return '<div id="tab-' . $tab_id . '" class="pl-tab-section' . $class . '" style="' . $style . '"' . $id . '>'.
            do_shortcode( $content ).
        '</div>';

    }
}
new Playouts_Element_Tab_Item;

class Playouts_Element_Progress_Bar extends Playouts_Element {

    function init() {

        $this->module = 'bw_progress_bar';
        $this->name = esc_html__( 'Progress Bar', 'AAA' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'AAA' ) );
        $this->module_color = '#72cdf3';

        $this->params = array(
            'title' => array(
				'label'              => esc_html__( 'Title', 'AAA' ),
				'type'               => 'textfield',
			),
            'value' => array(
				'label'              => esc_html__( 'Value ( Optional )', 'AAA' ),
				'type'               => 'textfield',
			),
            'progress' => array(
                'label'             => esc_html__( 'Progress', 'AAA' ),
                'type'              => 'number_slider',
                'description'       => esc_html__( 'Some description', 'AAA' ),
                'append_after'      => '%',
                'min'               => 0,
                'max'               => 100,
                'step'              => 1,
                'value'             => 50,
            ),
            'bar_color' => array(
                'label'             => esc_html__( 'Bar Color', 'AAA' ),
                'type'              => 'colorpicker',
                'width'             => 50
            ),
            'bar_bg_color' => array(
                'label'             => esc_html__( 'Bar Background Color', 'AAA' ),
                'type'              => 'colorpicker',
                'width'             => 50
            ),
            'text_color' => array(
                'label'             => esc_html__( 'Text Color', 'AAA' ),
                'type'              => 'colorpicker',
                'width'             => 50
            ),
            'counter_color' => array(
                'label'             => esc_html__( 'Counter Text Color', 'AAA' ),
                'type'              => 'colorpicker',
                'width'             => 50
            ),
            'padding_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Top', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 50
            ),
            'padding_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Bottom', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 50
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'title'             => '',
            'value'             => '',
            'progress'          => 0,
            'bar_color'         => '',
            'bar_bg_color'      => '',
            'text_color'        => '',
            'counter_color'     => '',
            'padding_top'       => '',
            'padding_bottom'    => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        if( $padding_top ) { $style .= 'padding-top:' . esc_attr( $padding_top ) . ( is_numeric( $padding_top ) ? 'px' : '' ) . ';'; }
        if( $padding_bottom ) { $style .= 'padding-bottom:' . esc_attr( $padding_bottom ) . ( is_numeric( $padding_bottom ) ? 'px' : '' ) . ';'; }

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        return '<div data-progress="' . (int) $progress . '" class="pl-progress-bar' . $class . '" style="' . $style . '"' . $id . '>'.
            '<div class="pl-progress-label"' . ( $text_color ? ' style="color:' . esc_attr( $text_color ) . '"' : '' ) . '>' . esc_attr( $title ) . '</div>'.
            '<div class="pl-the-progress"' . ( $bar_bg_color ? ' style="background-color:' . esc_attr( $bar_bg_color ) . '"' : '' ) . '>'.
                '<span class="pl-the-bar"' . ( $bar_color ? ' style="background-color:' . esc_attr( $bar_color ) . '"' : '' ) . '>'.
                    '<span class="pl-progress-counter"' . ( $counter_color ? ' style="color:' . esc_attr( $counter_color ) . '"' : '' ) . '><em>' . (int) $progress . '</em>' . esc_attr( $value ) . '</span>'.
                '</span>'.
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Progress_Bar;

class Playouts_Element_Button extends Playouts_Element {

    function init() {

        $this->module = 'bw_button';
        $this->name = esc_html__( 'Button', 'AAA' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'AAA' ) );
        $this->module_color = '#8d61f2';

        $this->params = array(
            'label' => array(
				'label'              => esc_html__( 'Label', 'AAA' ),
				'type'               => 'textfield',
				'value'              => esc_html__( 'This is a button', 'AAA' ),
			),
            'link' => array(
				'label'              => esc_html__( 'Link', 'AAA' ),
				'type'               => 'textfield',
				'placeholder'        => 'http://',
			),
            'target' => array(
                'label'             => esc_html__( 'Open in a New Tab?', 'AAA' ),
                'type'              => 'true_false',
            ),
            'style' => array(
                'label'             => esc_html__( 'Style', 'AAA' ),
                'type'              => 'select',
                'options'           => array(
                    'extra_small'       => 'Extra Small',
                    'small'             => 'Small',
                    'medium'            => 'Medium',
                    'large'             => 'Large',
                    'extra_large'       => 'Extra Large',
                ),
                'value'             => 'medium'
            ),
            'bold' => array(
                'label'             => esc_html__( 'Strong Text?', 'AAA' ),
                'type'              => 'true_false',
            ),
            'bg_color' => array(
                'label'             => esc_html__( 'Background Color', 'AAA' ),
                'type'              => 'colorpicker',
            ),
            'text_color' => array(
                'label'             => esc_html__( 'Text Color', 'AAA' ),
                'type'              => 'colorpicker',
            ),
            'border_radius' => array(
                'label'             => esc_html__( 'Border Radius', 'AAA' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels',
                'min'               => 0,
                'max'               => 60,
                'step'              => 1,
                'value'             => 0,
            ),
            'transform_top' => array(
                'label'             => esc_html__( 'Transform Top Position', 'AAA' ),
                'type'              => 'true_false',
                'tab'               => array( 'hover' => esc_html__( 'Hover Styles', 'AAA' ) ),
            ),
            'shadow' => array(
                'label'             => esc_html__( 'Add Shadow', 'AAA' ),
                'type'              => 'true_false',
                'tab'               => array( 'hover' => esc_html__( 'Hover Styles', 'AAA' ) ),
            ),
            'hover_bg_color' => array(
                'label'             => esc_html__( 'Background Color', 'AAA' ),
                'type'              => 'colorpicker',
                'tab'               => array( 'hover' => esc_html__( 'Hover Styles', 'AAA' ) ),
            ),
            'hover_text_color' => array(
                'label'             => esc_html__( 'Text Color', 'AAA' ),
                'type'              => 'colorpicker',
                'tab'               => array( 'hover' => esc_html__( 'Hover Styles', 'AAA' ) ),
            ),
            'margin_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Top', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 50
            ),
            'margin_right' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Right', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 50
            ),
            'margin_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Bottom', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 50
            ),
            'margin_left' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Left', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 50
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'label'             => '',
            'link'              => '',
            'target'            => false,
            'style'             => 'medium',
            'bold'              => false,
            'bg_color'          => '#f93d66',
            'text_color'        => '',
            'border_radius'     => 0,
            'transform_top'     => false,
            'shadow'            => false,
            'hover_bg_color'    => '',
            'hover_text_color'  => '',
            'margin_top'        => '',
            'margin_right'      => '',
            'margin_bottom'     => '',
            'margin_left'       => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $_style = $class = $id = $attr = '';

        if( $margin_top ) { $_style .= 'margin-top:' . esc_attr( $margin_top ) . ( is_numeric( $margin_top ) ? 'px' : '' ) . ';'; }
        if( $margin_right ) { $_style .= 'margin-right:' . esc_attr( $margin_right ) . ( is_numeric( $margin_right ) ? 'px' : '' ) . ';'; }
        if( $margin_bottom ) { $_style .= 'margin-bottom:' . esc_attr( $margin_bottom ) . ( is_numeric( $margin_bottom ) ? 'px' : '' ) . ';'; }
        if( $margin_left ) { $_style .= 'margin-left:' . esc_attr( $margin_left ) . ( is_numeric( $margin_left ) ? 'px' : '' ) . ';'; }

        $class .= ' pl-button-style-' . esc_attr( $style );
        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $class .= $transform_top ? ' pl-button-transform-top' : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';

        $_style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';
        $_style .= ! empty( $border_radius ) ? 'border-radius:' . (int) $border_radius . 'px;' : '';
        $_style .= ! empty( $bg_color ) ? 'background-color:' . esc_attr( $bg_color ) . ';' : '';
        $_style .= ! empty( $text_color ) ? 'color:' . esc_attr( $text_color ) . ';' : '';
        $_style .= $bold ? 'font-weight:bold;' : '';

        $attr .= $target ? ' target="_blank"' : '';
        $attr .= $hover_bg_color ? ' data-hover-bg-color-override="' . esc_attr( $hover_bg_color ) . '"' : '';
        $attr .= $hover_text_color ? ' data-hover-text-color-override="' . esc_attr( $hover_text_color ) . '"' : '';
        $shadow_color = $hover_bg_color ? $hover_bg_color : $bg_color;
        $attr .= $shadow ? ' data-hover-shadow-override="' . esc_attr( $shadow_color ) . '"' : '';

        return '<a href="' . esc_url( $link ) . '" class="pl-button' . $class . '" style="' . $_style . '"' . $id . $attr . '>'.
            esc_attr( $label ).
        '</a>';

    }
}
new Playouts_Element_Button;

class Playouts_Element_Code extends Playouts_Element {

    function init() {

        $this->module = 'bw_code';
        $this->name = esc_html__( 'Code', 'AAA' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'AAA' ) );
        $this->module_color = '#e2de7d';

        $this->params = array(
            'content' => array(
				'label'             => esc_html__( 'Code', 'AAA' ),
				'type'              => 'textarea',
				'is_content'        => true,
                'value'             => '&lt;div class="example"&gt;
    ' . esc_html__( 'Some code goes here..', 'AAA' ) . '
&lt;/div&gt;',
			),
            'margin_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Top', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 50
            ),
            'margin_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Bottom', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
                'width'             => 50
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'margin_top'        => '',
            'margin_bottom'     => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        if( $margin_top ) { $style .= 'margin-top:' . esc_attr( $margin_top ) . ( is_numeric( $margin_top ) ? 'px' : '' ) . ';'; }
        if( $margin_bottom ) { $style .= 'margin-bottom:' . esc_attr( $margin_bottom ) . ( is_numeric( $margin_bottom ) ? 'px' : '' ) . ';'; }

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';

        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        return '<pre class="pl-code' . $class . '" style="' . $style . '"' . $id . '>'.
            htmlentities( $content ).
        '</pre>';

    }
}
new Playouts_Element_Code;

class Playouts_Element_Sidebar extends Playouts_Element {

    function init() {

        $this->module = 'bw_sidebar';
        $this->name = esc_html__( 'Sidebar', 'AAA' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'AAA' ) );
        $this->module_color = '#3d60f9';

        $this->params = array(
            'sidebar' => array(
                'type'              => 'sidebars',
                'label'             => esc_html__( 'Select Sidebar', 'AAA' ),
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'sidebar'           => 0,
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        ob_start();

        echo '<div class="pl-sidebar' . $class . '" style="' . $style . '"' . $id . '>';
            dynamic_sidebar( $sidebar );
        echo '</div>';

        return ob_get_clean();

    }
}
new Playouts_Element_Sidebar;

if( in_array( 'contact-form-7/wp-contact-form-7.php', Playouts_Bootstrap::$active_plugins ) ) {
    class Playouts_Element_Contact_Form_7 extends Playouts_Element {

        function init() {

            $this->module = 'bw_contact_form_7';
            $this->name = esc_html__( 'Contact Form 7', 'AAA' );
            $this->view = 'element';
            $this->category = array( 'content' => __( 'Content', 'AAA' ) );
            $this->module_color = '#15dcc7';

            global $wpdb;

            $forms = $wpdb->get_results("
                SELECT $wpdb->posts.*
                FROM $wpdb->posts, $wpdb->postmeta
                WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
                AND $wpdb->postmeta.meta_key = '_form'
                AND $wpdb->posts.post_status = 'publish'
                AND $wpdb->posts.post_type = 'wpcf7_contact_form'
                ORDER BY $wpdb->posts.post_name DESC
            ");

            $form_options = array();
            if( $forms ) {
                $form_options[0] = __( 'Select Form', 'AAA' );
                foreach ( $forms as $form ) {
                    $form_options[ (int)$form->ID ] = esc_attr( $form->post_title );
                }
            }else{
                $form_options[0] = __( 'No contact forms found.', 'AAA' );
            }

            $this->params = array(
                'form_id' => array(
                    'type'              => 'select',
                    'label'             => esc_html__( 'Select Contact Form', 'AAA' ),
                    'options'           => $form_options,
                ),
            );

        }

        static function output( $atts = array(), $content = null ) {

            extract( $assigned_atts = shortcode_atts( array(
                'form_id'           => 0,
                'inline_class'      => '',
                'inline_id'         => '',
                'inline_css'        => '',
            ), $atts ) );

            $style = $class = $id = '';

            $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
            $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
            $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

            return '<div class="pl-contact-form-7' . $class . '" style="' . $style . '"' . $id . '>'.
                do_shortcode( '[contact-form-7 id="' . (int) $form_id . '"]' ).
            '</div>';

        }
    }
    new Playouts_Element_Contact_Form_7;
}

class Playouts_Element_Divider extends Playouts_Element {

    function init() {

        $this->module = 'bw_divider';
        $this->name = esc_html__( 'Divider', 'AAA' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'AAA' ) );
        $this->module_color = '#1593dc';

        $this->params = array(
            'height' => array(
                'label'             => esc_html__( 'Separator height', 'AAA' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels.',
                'min'               => 10,
                'max'               => 300,
                'step'              => 5,
                'value'             => 60,
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'height'            => 0,
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';
        $style .= ! empty( $height ) ? 'height:' . (int) $height . 'px;' : '';

        ob_start();

        echo '<div class="pl-divider' . $class . '" style="' . $style . '"' . $id . '></div>';

        return ob_get_clean();

    }
}
new Playouts_Element_Divider;

class Playouts_Element_Image extends Playouts_Element {

    function init() {

        $this->module = 'bw_image';
        $this->name = esc_html__( 'Image', 'AAA' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'AAA' ) );
        $this->module_color = '#c5e375';

        $this->params = array(
            'image' => array(
                'type'              => 'image',
				'label'             => esc_html__( 'Image', 'AAA' ),
			),
            'alt_tag' => array(
                'type'              => 'textfield',
				'label'             => esc_html__( 'Alt Tag ( Optional )', 'AAA' ),
			),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'image'             => '',
            'alt_tag'           => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        return '<div class="pl-image' . $class . '" style="' . $style . '"' . $id . '>'.
            '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $alt_tag ) . '">'.
        '</div>';

    }
}
new Playouts_Element_Image;

class Playouts_Element_Auto_Type extends Playouts_Element {

    function init() {

        $this->module = 'bw_auto_type';
        $this->module_item = 'bw_auto_type_item';
        $this->name = esc_html__( 'Auto Type Heading', 'AAA' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'AAA' ) );
        $this->module_color = '#75a9e3';

        $this->params = array(
            'items' => array(
                'label'              => esc_html__( 'Texts', 'AAA' ),
                'type'               => 'repeater',
                'description'        => esc_html__( 'You can add as many texts as you need, just click the plus icon.', 'AAA' ),
            ),
            'static_heading' => array(
                'label'              => esc_html__( 'Static Heading', 'AAA' ),
                'type'               => 'textfield',
                'value'              =>  esc_html__( 'This is the main title', 'AAA' ),
            ),
            'h_tag' => array(
                'label'             => esc_html__( 'Select Heading Title Tag', 'AAA' ),
                'type'              => 'select',
                'options'           => array(
                    'h1'    => 'H1',
                    'h2'    => 'H2',
                    'h3'    => 'H3',
                    'h4'    => 'H4',
                    'h5'    => 'H5',
                    'h6'    => 'H6',
                ),
                'value'             => 'h2'
            ),
            'font_size' => array(
                'label'             => esc_html__( 'Font Size', 'AAA' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels',
                'min'               => 14,
                'max'               => 150,
                'step'              => 1,
                'value'             => 75,
            ),
            'text_color' => array(
                'type'              => 'colorpicker',
				'label'             => esc_html__( 'Text Color', 'AAA' ),
			),
            'auto_type_color' => array(
                'type'              => 'colorpicker',
				'label'             => esc_html__( 'Auto Type Color', 'AAA' ),
			),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'AAA' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'AAA' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'static_heading'    => '',
            'h_tag'             => 'h2',
            'font_size'         => 75,
            'text_color'        => '',
            'auto_type_color'   => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';
        $style .= ! empty( $font_size ) ? 'font-size:' . (int) $font_size . 'px;' : '';
        $style .= ! empty( $text_color ) ? 'color:' . esc_attr( $text_color ) . ';' : '';

        $id = 'pl-auto-type-' . Playouts_Shortcode_Parser::get_unique_id();

        return '<div class="pl-auto-type-holder' . $class . '" style="' . $style . '" id="' . $id . '">'.
            '<' . esc_attr( $h_tag ) . '>'.
                '<em>' . wp_kses( $static_heading, array(
                    'a' => array(
                        'href' => array(),
                        'title' => array()
                    ),
                    'br' => array(),
                    'em' => array(),
                    'strong' => array(),
                )) . '</em>'.
                '<span class="pl-auto-type" style="' . ( ! empty( $auto_type_color ) ? 'color:' . esc_attr( $auto_type_color ) . ';' : '' ) . '"></span>'.
            '</' . esc_attr( $h_tag ) . '>'.
            '<ul class="pl-auto-type-texts">' . $content . '</ul>'.
        '</div>';

    }
}
new Playouts_Element_Auto_Type;

class Playouts_Element_Auto_Type_Item extends Playouts_Element {

    function init() {

        $this->module = 'bw_auto_type_item';
        $this->module_item = 'bw_auto_type';
        $this->name = esc_html__( 'Auto Type Text', 'AAA' );
        $this->view = 'repeater_item';
        $this->category = array( 'content' => __( 'Content', 'AAA' ) );

        $this->params = array(
            'text' => array(
                'label'             => esc_html__( 'Text', 'AAA' ),
                'type'              => 'textfield',
                'value'             => esc_html__( 'Auto type text here.', 'AAA' ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'text'              => '',
        ), $atts ) );

        return '<li>' . esc_attr( $text ) . '</li>';

    }
}
new Playouts_Element_Auto_Type_Item;

/*class Playouts_Element_Tabs extends Playouts_Repeater_Element {

    function init() {

        $this->module = 'bw_tabs';
        $this->module_item = 'bw_tab_item';
        $this->name = esc_html__( 'Tabs', 'AAA' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'AAA' ) );
        $this->module_color = '#be6ef6';
        $this->params = array(
            'tabs' => array(
                'label'              => esc_html__( 'Tab items', 'AAA' ),
                'type'               => 'repeater',
                'description'        => esc_html__( 'You can add as many tabs as you need, just click the plus icon.', 'AAA' ),
            ),
            'title' => array(
				'label'              => esc_html__( 'Tab title', 'AAA' ),
				'type'               => 'textfield',
				'description'        => esc_html__( 'Here you can add some title', 'AAA' ),
			),
            'title2' => array(
				'label'              => esc_html__( 'Tab title 2', 'AAA' ),
				'type'               => 'textfield',
                'tab'                => array( 'new' => esc_html__( 'New Tab Man', 'AAA' ) ),
				'description'        => esc_html__( 'Here you can add some title', 'AAA' ),
			),
            'image' => array(
				'label'              => esc_html__( 'Tab title 2', 'AAA' ),
				'type'               => 'image',
				'description'        => esc_html__( 'Here you can add some image', 'AAA' ),
			)
        );

    }

    static function output( $atts = array(), $content = null ) {

        return '<div class="pl-tabs">' . $content . '</div>';

    }
}
new Playouts_Element_Tabs;

class Playouts_Element_Tab_Item extends Playouts_Repeater_Item_Element {

    function init() {

        $this->module = 'bw_tab_item';
        $this->module_parent = 'bw_tabs';
        $this->name = esc_html__( 'Tab Item', 'AAA' );
        $this->view = 'repeater_item';

        $this->params = array(
            'title2' => array(
				'label'              => esc_html__( 'Tab item title', 'AAA' ),
				'type'               => 'textfield',
				'description'        => esc_html__( 'Here you can add some title 2', 'AAA' ),
			),
            'image' => array(
				'label'              => esc_html__( 'Tab title 2', 'AAA' ),
				'type'               => 'image',
				'description'        => esc_html__( 'Here you can add some image', 'AAA' ),
			)
        );

    }

    static function output( $atts = array(), $content = null ) {

        return '<div class="pl-tab-item">' . $content . '</div>';

    }
}
new Playouts_Element_Tab_Item;*/

/*'title' => array(
    'label'             => esc_html__( 'Title', 'AAA' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
),
'title2' => array(
    'label'             => esc_html__( 'Title', 'AAA' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
    'width'             => 50
),
'title3' => array(
    'label'             => esc_html__( 'Title', 'AAA' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
    'width'             => 50
),
'title4' => array(
    'label'             => esc_html__( 'Title', 'AAA' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
),
'title7' => array(
    'label'             => esc_html__( 'Title', 'AAA' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
),
'title8' => array(
    'label'             => esc_html__( 'Title', 'AAA' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
),
'title5' => array(
    'label'             => esc_html__( 'Title 2', 'AAA' ),
    'tab'               => array( 'new' => esc_html__( 'New Tab Man', 'AAA' ) ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
),
'title6' => array(
    'label'             => esc_html__( 'Title 3', 'AAA' ),
    'tab'               => array( 'new' => esc_html__( 'New Tab Man', 'AAA' ) ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
),
'content' => array(
    'label'             => esc_html__( 'Content', 'AAA' ),
    'type'              => 'editor',
    'value'             => 'Helloooo! <strong>man</strong>! How are you?',
    'is_content'        => true,
),*/
/*'select5' => array(
    'label'             => esc_html__( 'Select 5 Post Type', 'AAA' ),
    'type'              => 'taxonomy',
    'multiple'          => true,
    'post_type'         => 'category',
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
    //'append_before'     => 'before..',
    'append_after'      => 'pixels',
    'min'               => 10,
    'max'               => 100,
    'step'              => 5,
    'value'             => 55,
),
'number_slider2' => array(
    'label'             => esc_html__( 'Number Slider 2', 'AAA' ),
    'type'              => 'number_slider',
    'description'       => esc_html__( 'Some description', 'AAA' ),
    'append_before'     => 'before..',
    'append_after'      => 'after..',
    'min'               => 0,
    'max'               => 20,
    'step'              => 1,
    'value'             => 0,
),*/
/*'checkbox121' => array(
    'label'             => esc_html__( 'Checkbox', 'AAA' ),
    'type'              => 'true_false',
),
'icon1' => array(
    'label'             => esc_html__( 'Checkbox', 'AAA' ),
    'type'              => 'icon',
),
'icon2' => array(
    'label'             => esc_html__( 'Checkbox 2', 'AAA' ),
    'type'              => 'icon',
),
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
),*/
/*'row_layout' => array(
    'label'             => esc_html__( 'Row Layout', 'AAA' ),
    'type'              => 'radio_image',
    'description'       => esc_html__( 'Select the display version of the row.', 'AAA' ),
    'options'           => array(
        'standard' => array(
            'label' => 'Standard', 'image' => PL_ASSEST . 'admin/images/__tmp/row_standard.png'
        ),
        'full' => array(
            'label' => 'Full-Width', 'image' => PL_ASSEST . 'admin/images/__tmp/row_full_width.png'
        ),
        'boxed' => array(
            'label' => 'Boxed', 'image' => PL_ASSEST . 'admin/images/__tmp/row_boxed.png'
        ),
    ),
    'value'             => 'standard'
),
'file1' => array(
    'label'             => esc_html__( 'File 1', 'AAA' ),
    'type'              => 'image',
),
'image1' => array(
    'label'             => esc_html__( 'Image 1', 'AAA' ),
    'type'              => 'image',
    'value'             => '',
),
'image2' => array(
    'label'             => esc_html__( 'Image 2', 'AAA' ),
    'type'              => 'image',
    'value'             => '',
),*/
/*'checkbox11' => array(
    'label'             => esc_html__( 'Checkbox', 'AAA' ),
    'type'              => 'true_false',
),
'image3' => array(
    'label'             => esc_html__( 'Image Depends', 'AAA' ),
    'type'              => 'image',
    'value'             => '',
    'depends'           => array( 'element' => 'checkbox11', 'value' => '1' ),
),
'title3' => array(
    'label'             => esc_html__( 'Title 3 depends', 'AAA' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
    'depends'        => array( 'element' => 'checkbox11', 'value' => '1' ),
),
'editor1' => array(
    'label'             => esc_html__( 'Editor1', 'AAA' ),
    'type'              => 'editor',
    'value'             => 'Helloooo! <strong>man</strong>! How are you?',
    'is_content'        => true,
),
'title2' => array(
    'label'             => esc_html__( 'Title 2', 'AAA' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
    'width'             => 50
),
'checkbox11' => array(
    'label'             => esc_html__( 'Checkbox', 'AAA' ),
    'type'              => 'true_false',
),
'bg_color22' => array(
    'label'             => esc_html__( 'Background Color', 'AAA' ),
    'type'              => 'colorpicker',
    'description'       => esc_html__( 'Some description', 'AAA' ),
    'width'             => 50
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
'title4' => array(
    'label'             => esc_html__( 'Title 4 depends', 'AAA' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
    'depends'        => array( 'element' => 'radio11', 'value' => 'some2' ),
),
'heading' => array(
    'label'             => esc_html__( 'Heading', 'AAA' ),
    'type'              => 'heading',
    'description'       => esc_html__( 'Some description', 'AAA' ),
),
'textarea' => array(
    'label'             => esc_html__( 'Textarea', 'AAA' ),
    'type'              => 'textarea',
    'value'             => esc_html__( 'Some textarea value here...', 'AAA' ),
    'description'       => esc_html__( 'Some description', 'AAA' ),
),
'base64' => array(
    'label'             => esc_html__( 'Base 64', 'AAA' ),
    'type'              => 'base64',
    'value'             => Playouts_Functions::base64_to_param_encode( 'dfgdfgdf dfg dfgwer456' ),
    'description'       => esc_html__( 'Some description', 'AAA' ),
),*/
