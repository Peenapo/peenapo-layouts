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



/*class Playouts_Layout_Test extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_test1';
        $this->name = esc_html__( 'Layout Test 1', 'AAA' );
        $this->layout_view = 'row';
        $this->image = PL_ASSEST . 'admin/images/__layouts/test.png';

    }

    static function output() {

        return '[bw_row][bw_column][bw_text]Some test[/bw_text][/bw_column][/bw_row]';

    }
}
new Playouts_Layout_Test;*/



class Playouts_Layout_About extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_about';
        $this->name = esc_html__( 'About', 'AAA' );
        $this->layout_view = 'row';
        $this->image = PL_ASSEST . 'admin/images/__layouts/about.png';

    }

    static function output() {
        return '[bw_row row_layout="full" enable_static_height="1" static_height="100" vertical_alignment="center"][bw_column col_width="50" padding_top="8%" padding_right="8%" padding_bottom="8%" padding_left="8%"][bw_heading title="Cras pharetra semper ex id ornare. Integer elit est" h_tag="h2" text_alignment="inherit" font_size_heading="40" font_size_content="15" font_size_top="15" bold_text="1" speed="450" delay="100"]<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quis porta eros. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget ligula quam. Nunc a dolor vitae enim semper auctor eu vitae neque. Cras pharetra semper ex id ornare. Integer elit est, porta non dui eget, elementum consequat leo.</p> [/bw_heading][bw_progress_bars enable_animation="1" animation_speed="150" animation_delay="80"][bw_progress_bar title="JavaScript" progress="85" bar_color="#23efde" bar_color_secondary="#a423ea" direction="right"][/bw_progress_bar][bw_progress_bar title="WordPress" progress="65" bar_color="#23efde" bar_color_secondary="#a423ea" direction="right"][/bw_progress_bar][bw_progress_bar title="UX Design" progress="75" bar_color="#23efde" bar_color_secondary="#a423ea" direction="right"][/bw_progress_bar][bw_progress_bar title="Photoshop" progress="55" bar_color="#23efde" bar_color_secondary="#a423ea" direction="right"][/bw_progress_bar][/bw_progress_bars][bw_button label="Contact me" link="#" style="small" bg_color="#a423ea" border_radius="60" transform_top="1" shadow="1" margin_top="50"][/bw_button][/bw_column][bw_column col_width="50" background="image" bg_image="http://localhost/pp/demo/wp-content/uploads/2017/03/vadim-sherbakov-277610.jpg" padding_top="50" padding_right="50" padding_bottom="50" padding_left="50"][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_About;



class Playouts_Layout_About_2 extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_about_2';
        $this->name = esc_html__( 'About 2', 'AAA' );
        $this->layout_view = 'row';
        $this->image = PL_ASSEST . 'admin/images/__layouts/about_2.png';

    }

    static function output() {
        return '[bw_row row_layout="full" background="image" overlay_direction="bottom right" overlay_opacity="50" enable_static_height="1" static_height="100" vertical_alignment="center"][bw_column col_width="30.0" background="image" bg_image="http://localhost/pp/demo/wp-content/uploads/2017/03/autumn-goodman-242816.jpg" bg_image_position="center center" bg_image_size="cover" overlay_enable="1" overlay_bg_color="#24e5d8" overlay_bg_second="#9528fc" overlay_direction="top right" overlay_opacity="70"][/bw_column][bw_column col_width="70.0" background="image" bg_image="http://localhost/pp/demo/wp-content/uploads/2017/03/thierry-meier-218997.jpg" overlay_enable="1" overlay_bg_color="#ffffff" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="97"][bw_row_inner text_alignment="inherit" vertical_alignment="stretch" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="none" animation_speed="200" animation_delay="100" inline_css="max-width:45%;margin:0 auto;"][bw_column_inner overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_heading title="Integer elit est, porta non dui eget, elementum consequat leo" h_tag="h3" text_alignment="inherit" font_size_heading="40" font_size_content="15" font_size_top="15" bold_text="1" speed="450" delay="100"]<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quis porta eros. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget ligula quam. Nunc a dolor vitae enim semper auctor eu vitae neque. Cras pharetra semper ex id ornare. Integer elit est, porta non dui eget, elementum consequat leo.</p>[/bw_heading][/bw_column_inner][/bw_row_inner][bw_row_inner text_alignment="inherit" vertical_alignment="stretch" padding_top="25" padding_bottom="35" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="none" animation_speed="200" animation_delay="100" inline_css="max-width:45%;margin:0 auto;"][bw_column_inner col_width="50" overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_icon icon="bwpb-7s-rocket" text="Smarter, stronger, faster" font_size="42" direction="bottom right"][/bw_icon][/bw_column_inner][bw_column_inner col_width="50" overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_icon icon="bwpb-7s-monitor" text="Build a website in minutes" font_size="42" direction="bottom right"][/bw_icon][/bw_column_inner][/bw_row_inner][bw_row_inner text_alignment="inherit" vertical_alignment="stretch" padding_bottom="35" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="none" animation_speed="200" animation_delay="100" inline_css="max-width:45%;margin:0 auto;"][bw_column_inner col_width="50" overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_icon icon="bwpb-7s-timer" text="Optimized for speed" font_size="42" direction="bottom right"][/bw_icon][/bw_column_inner][bw_column_inner col_width="50" overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_icon icon="bwpb-7s-magic-wand" text="Pick any Google Font" font_size="42" direction="bottom right"][/bw_icon][/bw_column_inner][/bw_row_inner][bw_row_inner text_alignment="inherit" vertical_alignment="stretch" padding_bottom="35" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="none" animation_speed="200" animation_delay="100" inline_css="max-width:45%;margin:0 auto;"][bw_column_inner col_width="50" overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_icon icon="bwpb-7s-help2" text="Badass support" font_size="42" direction="bottom right"][/bw_icon][/bw_column_inner][bw_column_inner col_width="50" overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_icon icon="bwpb-7s-help2" text="Badass support" font_size="42" direction="bottom right"][/bw_icon][/bw_column_inner][/bw_row_inner][bw_row_inner text_alignment="inherit" vertical_alignment="stretch" padding_top="25" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="none" animation_speed="200" animation_delay="100" inline_css="max-width:45%;margin:0 auto;"][bw_column_inner overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_button label="Learn more button" link="#" style="large" border_radius="60" transform_top="1" shadow="1"][/bw_button][/bw_column_inner][/bw_row_inner][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_About_2;



class Playouts_Layout_Heading_video extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_heading_video';
        $this->name = esc_html__( 'Heading Text with Video Modal', 'AAA' );
        $this->layout_view = 'row';
        $this->image = PL_ASSEST . 'admin/images/__layouts/heading_video.png';

    }

    static function output() {
        return '[bw_row row_layout="full" background="image" bg_image="http://localhost/pp/demo/wp-content/uploads/2017/03/linas-bam-223729.jpg" overlay_enable="1" overlay_bg_color="#ffffff" overlay_direction="top right" overlay_opacity="90" text_color="#0c0c0c" text_alignment="center" enable_static_height="1" static_height="100" vertical_alignment="center"][bw_column overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_heading title="Awesome WordPress Plugin for Free" h_tag="h2" text_alignment="inherit" font_size_heading="62" font_size_content="15" font_size_top="15" bold_text="1" speed="450" delay="100"]<p>Mauris auctor sapien a quam consectetur rutrum. Nullam bibendum enim et nisi pretium venenatis vitae egestas urna. Vestibulum nec accumsan nulla, id tristique diam</p>[/bw_heading][bw_video_modal size="medium" size_button="medium" autoplay="1" color="#0a0a0a" bg_color="#ffffff" text="Watch the video" inline_css="margin-top:20px;"]https://www.youtube.com/watch?v=bSfshpw3MRo[/bw_video_modal][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_Heading_video;
