<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * class for build-in layouts
 *
 *
 */
class Playouts_Admin_Layout {

    public $id;
    public $name;
    public $image;
    public $class_name;
    public $category = array();
    public $public = true;
    public $layout_view;
    //public $first_module;

    //static $regex_extract_first_module = '/[^ []+/';

    private static $layouts = array();
    private static $index = 0;

    function __construct() {

        self::$index++;

        $this->category = array( 'general' => __( 'General', 'AAA' ) );
        $this->image = PL_ASSEST . 'admin/images/default-layout.png';
        $this->class_name = get_class( $this );

        $this->init();

        self::$layouts[ $this->id ] = $this;

        // get the first module of the layout
        //$this->first_module = self::extract_first_module( call_user_func( $this->class_name . '::output' ) );

    }

    static function output() {
        return '';
    }

    static function get_layouts() {
        return self::$layouts;
    }

    static function get_modules_arr() {

        $layouts = array();
        foreach( self::get_layouts() as $layout ) {

            $layouts[ $layout->id ] = array(
                'name' => $layout->name,
                'category' => key( $layout->category ),
                'public' => $layout->public,
                'layout_view' => $layout->layout_view,
                'image' => $layout->image,
                //'first_module' => $layout->first_module
            );

        }
        return $layouts;

    }

    static function get_layouts_output() {

        $layouts = array();
        foreach( self::get_layouts() as $layout ) {

            $output = call_user_func( $layout->class_name . '::output' );

            $layouts[ $layout->id ] = array(
                'id' => $layout->id,
                'output' => $output,
            );

        }
        return $layouts;

    }

    static function get_layout_categories() {

        $categories = array();
        $layouts = self::get_layouts();
        foreach( $layouts as $layout ) {
            $category_id = key( $layout->category );
            $categories[ $category_id ] = $layout->category[ $category_id ];
        }
        return $categories;

    }

}

class Playouts_Layout_Test1 extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_test1';
        $this->name = esc_html__( 'Layout Test 1', 'AAA' );
        $this->layout_view = 'row';
        $this->image = PL_ASSEST . 'admin/images/__layouts/test.png';

    }

    static function output() {

        return '[bw_row bg_color="#157"][bw_column col_width="50"][bw_text title="this is l" title2="sdfsf"]LAYOUT PART 1[/bw_text][/bw_column][bw_column col_width="50"][/bw_column][/bw_row]';

    }

}
new Playouts_Layout_Test1;

class Playouts_Layout_Test2 extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_test2';
        $this->name = esc_html__( 'Layout Test 2', 'AAA' );
        $this->category = array( 'blog' => __( 'Blog', 'AAA' ) );
        $this->layout_view = 'row';
        //$this->image = '';

    }

    static function output() {

        return '[bw_row bg_color="#fff"][bw_column col_width="33.3"][/bw_column][bw_column col_width="33.3"][bw_text title="Helloooo!" title2="Helloooo!"]Helloooo! man! How are you?[/bw_text][/bw_column][bw_column col_width="33.3"][/bw_column][/bw_row]';

    }

}
new Playouts_Layout_Test2;

class Playouts_Layout_Test3 extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_test3';
        $this->name = esc_html__( 'Layout TEXT', 'AAA' );
        $this->category = array( 'blog' => __( 'Blog', 'AAA' ) );
        $this->layout_view = 'element';
        //$this->image = '';

    }

    static function output() {

        return '[bw_text title="Helloooo!" title2="Helloooo!"]EEEEEEEEEEEEEEEE![/bw_text]';

    }

}
new Playouts_Layout_Test3;
