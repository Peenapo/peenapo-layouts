<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * main admin class to initiate the plugin
 *
 *
 */
class Playouts_Admin {

    /*
     * is plugin enabled for the current post
     *
     */
    static $status = false;

    /*
     * holds the plugin settings options
     *
     */
    static $options = array();

	/*
	 * initiates the admin functions
	 *
	 */
    static function init() {

        # main container classes
        add_action( 'bwpb_main_class', array( 'Playouts_Admin', 'main_class' ) );

        # switch button classes
        add_action( 'bwpb_switch_class', array( 'Playouts_Admin', 'switch_class' ) );

        # the init actions
        add_action( 'init', array( 'Playouts_Admin', 'actions' ) );

        # enqueue scripts
        add_action( 'admin_enqueue_scripts', array( 'Playouts_Admin', 'enqueue_scripts' ) );

        # on custom post type pl_layout save / update
        add_action( 'save_post', array( 'Playouts_Admin', 'on_custom_layout_save' ) );

    }

    /*
     * main container classes
     *
     */
    static function main_class( $classes ) {

        // TODO: add this into an option
        //$classes[] = 'bwpb-editor-hidden';

        return $classes;

    }

    /*
     * switch button classes
     *
     */
    static function switch_class( $classes ) {

        if( ! Playouts_Admin::$status ) {
            $classes[] = 'bw-switch-active';
        }

        return $classes;

    }

    /*
     * the init action hooks
     *
     */
    static function actions() {

        # check if the user have enough permissions
        if( ! current_user_can( 'manage_options' ) ) { return; }

        # on edit page
        add_action( 'load-post.php', array( 'Playouts_Admin', 'on_load_post' ) );
        add_action( 'load-post-new.php', array( 'Playouts_Admin', 'on_load_post' ) );

        # register the custom post type for custom layouts
        self::register_layouts_post_type();

    }

    /*
     * register post type layouts
     *
     */
    static function register_layouts_post_type() {

        # register the taxonomy
    	$taxonomy_labels = array(
    		'name'                       => _x( 'Categories', 'taxonomy general name', 'AAA' ),
    		'singular_name'              => _x( 'Category', 'taxonomy singular name', 'AAA' ),
    		'search_items'               => __( 'Search Categories', 'AAA' ),
    		'popular_items'              => __( 'Popular Categories', 'AAA' ),
    		'all_items'                  => __( 'All Categories', 'AAA' ),
    		'parent_item'                => null,
    		'parent_item_colon'          => null,
    		'edit_item'                  => __( 'Edit Category', 'AAA' ),
    		'update_item'                => __( 'Update Category', 'AAA' ),
    		'add_new_item'               => __( 'Add New Category', 'AAA' ),
    		'new_item_name'              => __( 'New Category Name', 'AAA' ),
    		'separate_items_with_commas' => __( 'Separate categories with commas', 'AAA' ),
    		'add_or_remove_items'        => __( 'Add or remove categories', 'AAA' ),
    		'choose_from_most_used'      => __( 'Choose from the most used categories', 'AAA' ),
    		'not_found'                  => __( 'No categories found.', 'AAA' ),
    		'menu_name'                  => __( 'Categories', 'AAA' ),
    	);
    	$taxonomy_args = array(
    		'hierarchical'          => false,
            'public'                => false,
    		'labels'                => $taxonomy_labels,
    		'show_ui'               => true,
    		'show_admin_column'     => true,
    		'update_count_callback' => '_update_post_term_count',
    		'query_var'             => true,
    		'rewrite'               => array( 'slug' => 'layout_category' ),
    	);
    	register_taxonomy( 'pl_layout_category', 'pl_layout', $taxonomy_args );

        # tagonomies as params
        $labels = array(
            'name'                  => esc_html__( 'Layout View', 'AAA' )
        );
    	$args = array(
            'hierarchical'          => false,
            'labels'                => $labels,
            'show_ui'               => false,
            'show_admin_column'     => true,
            'query_var'             => true,
            'show_in_nav_menus'     => false,
        );
        register_taxonomy( 'layout_view', 'pl_layout', $args );

        # register the post type
        $post_type_labels = array(
            'name'                  => _x( 'Layouts', 'post type general name', 'AAA' ),
            'singular_name'         => _x( 'Layout', 'post type singular name', 'AAA' ),
            'menu_name'             => _x( 'Layouts', 'admin menu', 'AAA' ),
            'name_admin_bar'        => _x( 'Layout', 'add new on admin bar', 'AAA' ),
            'add_new'               => _x( 'Add New', 'layout', 'AAA' ),
            'add_new_item'          => __( 'Add New Layout', 'AAA' ),
            'new_item'              => __( 'New Layout', 'AAA' ),
            'edit_item'             => __( 'Edit Layout', 'AAA' ),
            'view_item'             => __( 'View Layout', 'AAA' ),
            'all_items'             => __( 'All Layouts', 'AAA' ),
            'search_items'          => __( 'Search Layouts', 'AAA' ),
            'parent_item_colon'     => __( 'Parent Layouts:', 'AAA' ),
            'not_found'             => __( 'No books found.', 'AAA' ),
            'not_found_in_trash'    => __( 'No books found in Trash.', 'AAA' )
    	);
    	$post_type_args = array(
            'labels'                => $post_type_labels,
            'description'           => __( 'Layouts', 'AAA' ),
            'taxonomies'            => array( 'pl_layout_category' ),
            'public'                => false,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'show_in_menu'          => false,
            'query_var'             => true,
            'rewrite'               => array( 'slug' => 'layout' ),
            'capability_type'       => 'post',
            'has_archive'           => false,
            'hierarchical'          => false,
            'menu_position'         => null,
            'supports'              => array( 'title', 'editor', 'revisions' ),
    	);
    	register_post_type( 'pl_layout', $post_type_args );

    }

    static function after_title() {

        /*
         * get switch button template
         *
         */
        do_action( 'bwpb_get_template_switch_button' );

    }

	/*
	 * check if the plugins is enabled for specific post
	 * basically if the button was clicked or not
	 *
	 */
    static function check_status( $post_id = false ) {

        if( ! $post_id ) { return; }

        $bwpb_status = get_post_meta( $post_id, '__pl_status', true );
        self::$status = $bwpb_status;

    }

    static function on_load_post() {

        # get current post type
        $screen = get_current_screen();
        $current_post_type = $screen->post_type;

        # if the post type supports wordpress editor
        if( post_type_supports( $current_post_type, 'editor' ) ) {
            # if plugin supports current post type
            if( in_array( $current_post_type, Playouts_Bootstrap::$post_types ) ) {

                # set the options
                self::$options = get_option( 'pl_layouts_options' );

                # set the status of the current post type
                $post_id = isset( $_GET['post'] ) ? (int)$_GET['post'] : false;
                self::check_status( $post_id );
                # add switch button after post title
                add_action( 'edit_form_after_title', array( 'Playouts_Admin', 'after_title' ) );
                # add custom body classes
                add_filter( 'admin_body_class', array( 'Playouts_Admin', 'admin_body_class' ) );
                # register page builder
                add_action( 'add_meta_boxes', array( 'Playouts_Admin', 'add_custom_box' ) );
                # load footer templates
                add_action( 'admin_footer', array( 'Playouts_Admin', 'footer_templates' ) );
                # on save/edit post
                add_action( 'save_post', array( 'Playouts_Admin', 'save' ) );

            }
        }
    }

    /*
     * add custom body classes
     *
     */
    static function admin_body_class( $classes ) {
        if( self::$status ) {
            return "{$classes} bwpb-active";
        }
    }

    /*static function get_current_post_type() {
        global $post, $typenow, $current_screen;
        if ( $post && $post->post_type ) {
            return $post->post_type;
        }elseif( $typenow ) {
            return $typenow;
        }elseif( $current_screen && $current_screen->post_type ) {
            return $current_screen->post_type;
        }elseif( isset( $_REQUEST['post_type'] ) ) {
            return sanitize_key( $_REQUEST['post_type'] );
        }
        return null;
    }*/

    static function add_custom_box() {

        $currnet_post_type = get_post_type();

        if( in_array( $currnet_post_type, Playouts_Bootstrap::$post_types ) ) {

            add_meta_box(
                'bw_page_builder_section',
                __( 'Peenapo Page Builder', 'peenapo-layouts-txd' ),
                array( 'Playouts_Admin', 'bw_page_builder_custom_box' ),
                $currnet_post_type,
                'normal',
                'high'
            );

        }

        /*foreach ( Bwpb::$post_types as $post_type ) {

            add_meta_box(
                'bw_page_builder_section',
                __( 'Peenapo Page Builder', 'peenapo-layouts-txd' ),
                array( 'Playouts_Admin', 'bw_page_builder_custom_box' ),
                $post_type,
                'normal',
                'high'
            );
        }*/

    }

    static function get_children( $children ) {
        if( is_array( $children ) ) {
            return implode(',', $children );
        }
        return $children;
    }

    static function get_custom_css() {

        $bwpb_custom_css = get_post_meta( get_the_ID(), '__pl_custom_css', true );
        if ( ! isset( $bwpb_custom_css ) or $bwpb_custom_css == '' ) {
            $bwpb_custom_css = '';
        }
        return $bwpb_custom_css;

    }

    static function bw_page_builder_custom_box( $post ) {

        /*
         * get main template
         *
         */
        do_action( 'bwpb_get_template_main' );

    }

    static function get_free_id() {

        $last_id = get_option( 'bwpb_last_block_id', 0 );

        if ( $last_id <= 2 ) { $last_id = 2; }
        $last_id++;

        update_option( 'bwpb_last_block_id', $last_id );

        return $last_id;

    }

    static function update_builder_data( $post_id, $value, $data ) {

        array_walk_recursive( $data, array( 'Playouts_Admin', 'sanitize_array' ) );
        update_post_meta( $post_id, $value, $data );

    }

    static function sanitize_array( &$item, $key ) {

        $item = htmlentities( $item, ENT_QUOTES );

    }

    static function post_param( $param, $default = null ) {
        return isset( $_POST[$param] ) ? $_POST[$param] : $default;
    }

    static function footer_templates() {

        /*
         * generate all the element's templates
         *
         */
        do_action( 'bwpb_get_template_elements' );

        /*
         * other partials
         *
         */
        do_action( 'bwpb_get_template_partials' );

        /*
         * get template for settings panel
         *
         */
        do_action( 'bwpb_get_template_settings_panel' );

        /*
         * generate a template for our icons
         *
         */
        do_action( 'bwpb_get_template_icons' );

    }

    static function save( $post_id ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }

        if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

        $status = self::get_post_param( 'bwpb_status' );
        $custom_css = isset( $_POST['bw_custom_css'] ) ? strip_tags( $_POST['bw_custom_css'] ) : '';

        // TODO: fix this
        if ( $status !== false ) {
            // Add status
            if ( get_post_meta( $post_id, '__pl_status' ) == '' ) {
                add_post_meta( $post_id, '__pl_status', $status, true );
            }
            // Update status
            elseif ( $status !== get_post_meta( $post_id, '__pl_status', true ) ) {
                update_post_meta( $post_id, '__pl_status', $status );
            }
            // Delete status
            elseif ( $status == '' ) {
                delete_post_meta( $post_id, '__pl_status', get_post_meta( $post_id, '__pl_status', true ) );
            }
        }
        if ( $custom_css !== false ) {
            // add custom css
            if ( get_post_meta( $post_id, '__pl_custom_css' ) == '' ) {
                add_post_meta( $post_id, '__pl_custom_css', $custom_css, true );
            }
            // update custom css
            elseif ( $custom_css != get_post_meta( $post_id, '__pl_custom_css', true ) ) {
                update_post_meta( $post_id, '__pl_custom_css', $custom_css );
            }
            // delete custom css
            elseif ( $custom_css == '' ) {
                delete_post_meta( $post_id, '__pl_custom_css', get_post_meta( $post_id, '__pl_custom_css', true ) );
            }
        }
    }

    static function get_post_param( $param, $default = null ) {
        return isset( $_POST[ $param ] ) ? $_POST[ $param ] : $default;
    }

    static function on_custom_layout_save( $layout_id ) {

        if ( wp_is_post_revision( $layout_id ) ) { return; } // do nothing, if this is just a revision

        if( get_post_type( $layout_id ) == 'pl_layout' ) {

            // extract the first module of the layout
            $first_module = Playouts_Admin_Layout_Custom::extract_first_module( get_post_field( 'post_content', $layout_id ) );

            // get layout view by the first extracted module
            $layout_view = Playouts_Element::get_module_view( $first_module );

            // save the layout view
            wp_set_post_terms( $layout_id, $layout_view, 'layout_view' );

        }

    }

    static function enqueue_scripts() {
        //TODO: fix this
        # css
        wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'bwpb', PL_ASSEST . 'admin/css/style.css' );
		wp_enqueue_style( 'bwpb-jquery-ui', PL_ASSEST . 'admin/css/vendors/jquery-ui.css' );

        # google fonts
        $query_args = array(
            'family' => 'Palanquin+Dark:400,600|Oxygen:400',
            'subset' => 'latin',
        );
        wp_enqueue_style( 'bwpb-google-fonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ), array(), null );

		# js
        if( isset( $_GET['page'] ) and $_GET['page'] == 'playouts_options' ) {
            wp_enqueue_media();
        }
		wp_enqueue_script( array( "jquery", "jquery-ui-core", "jquery-ui-dialog", "jquery-ui-sortable", "wp-color-picker", "jquery-ui-slider" ) );
        wp_register_script( 'bwpb', PL_ASSEST . 'admin/js/main.js', array('jquery-ui-sortable'), '1.0', true );
		wp_localize_script( 'bwpb', 'bwpb_admin_root', array( 'ajax' => admin_url( 'admin-ajax.php' ) ) );
        wp_enqueue_script( 'bwpb-smart-resize', PL_ASSEST . 'admin/js/vendors/jquery-smartresize-master/jquery.debouncedresize.js', array(), '1.0', true );
        wp_enqueue_script( 'bwpb-vendors', PL_ASSEST . 'admin/js/vendors.js', array(), '1.0', true );
        wp_enqueue_script( 'bwpb-php-default', PL_ASSEST . 'admin/js/vendors/php.default/php.default.min.js', array(), '1.0', true );
        wp_enqueue_script( 'bwpb-colorpicker', PL_ASSEST . 'admin/js/vendors/wpcolorpicker/wp-colorpicker.min.js', array(), '1.0', true );
        wp_enqueue_script( 'bwpb-blocker', PL_ASSEST . 'admin/js/bwpb.blocker.js', array(), '1.0', true );
        wp_enqueue_script( 'bwpb-shortcoder', PL_ASSEST . 'admin/js/bwpb.shortcoder.js', array(), '1.0', true );
        wp_enqueue_script( 'bwpb-layouts', PL_ASSEST . 'admin/js/bwpb.layouts.js', array(), '1.0', true );
        wp_enqueue_script( 'bwpb-mapper', PL_ASSEST . 'admin/js/bwpb.mapper.js', array(), '1.0', true );

		wp_enqueue_script( 'bwpb' );

    }

}
Playouts_Admin::init()

?>
