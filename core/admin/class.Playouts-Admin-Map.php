<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * mapping, load elements definition and output an object with all the info required by the plugin
 * the object will be accessible via js to get modules, status, translations, etc.
 *
 *
 */
class Playouts_Admin_Map {

    static $strings;

    static function init() {

        // don't load if page builder if not enabled.
        // TODO

        self::set_strings();

        include PL_DIR . 'core/class.Playouts-Element.php';
        include PL_DIR . 'core/admin/class.Playouts-Admin-Layouts.php';
        include PL_DIR . 'core/admin/class.Playouts-Admin-Layouts-Custom.php';

        add_action( 'admin_footer', array( 'Playouts_Admin_Map', 'map_data_object' ) );

    }

    static function map_data_object() {

        if( Playouts_Admin::$status_post_type or ( isset( $_GET['page'] ) and $_GET['page'] == 'playouts_options' ) ) {

            $map_modules = $map_modules_repeater = $map_modules_repeater_item = $map_layouts = array();

            foreach( Playouts_Element::get_modules() as $module ) {
                $map_modules[ $module->module ] = $module;
            }

            foreach( Playouts_Repeater_Element::get_modules_repeater() as $module ) {
                $map_modules_repeater[ $module->module ] = $module;
            }

            foreach( Playouts_Repeater_Item_Element::get_modules_repeater_item() as $module ) {
                $map_modules_repeater_item[ $module->module ] = $module;
            }

            foreach( Playouts_Admin_Layout::get_layouts_output() as $layout ) {
                $map_layouts[ $layout['id'] ] = $layout['output'];
            }

            $screen_edit = $screen_layouts_options = false;
            if( function_exists( 'get_current_screen' ) ) {
                $screen = get_current_screen();
                $screen_edit = $screen->parent_base == 'edit';
                //$screen_layouts_options = $screen->parent_base == 'playouts_options';
            }

            $bwpb_data = array(

                'map'                           => json_encode( $map_modules ),
                'map_repeater'                  => json_encode( $map_modules_repeater ),
                'map_repeater_item'             => json_encode( $map_modules_repeater_item ),
                'map_layouts'                   => $map_layouts,
                'map_custom_layouts'            => Playouts_Admin_Layout_Custom::get_layouts_output(),
                'map_custom_layout_categories'  => Playouts_Admin_Layout_Custom::get_categories(),
                'map_favorites'                 => json_encode( Playouts_Admin_Modal::$favorites ),

                'status'                        => Playouts_Admin::$status,
                'post_id'                       => get_the_ID(),
                'screen_edit'                   => $screen_edit,
                'path_assets'                   => PL_ASSEST,

                'i18n'                          => Playouts_Admin_Map::$strings,

                'security' => array(
                    'panel_get_options'         => wp_create_nonce( 'pl-nonce-get-options' ),
                    'panel_get_taxonomies'      => wp_create_nonce( 'pl-nonce-get-taxonomies' ),
                    'save_layout'               => wp_create_nonce( 'pl-nonce-save-layout' ),
                    'save_layout_options'       => wp_create_nonce( 'pl-nonce-save-layout-options' ),
                    'save_favorites'            => wp_create_nonce( 'pl-nonce-save-favorites' ),
                ),

                'module_dependencies'           => Playouts_Admin_Modal::get_dependencies_inverted(),
                'panel_general_tab'             => apply_filters( 'pl_panel_general_tab', __( 'General', 'AAA' ) ),
                'modules'                       => implode( '|', Playouts_Element::get_modules_raw() ),
                'module_colors'                 => Playouts_Element::get_modules_color(),

            );

            //if( $screen_layouts_options ) {
                //$bwpb_data['layouts_options'] = Playouts_Admin_Settings::get_layouts_options();
            //}

            wp_localize_script( 'bwpb-mapper', 'bwpb_data', $bwpb_data );
        }

    }

    static function set_strings() {

        self::$strings = array(

            'empty_all'                         => __( 'Press Ok to delete all the elements, cancel to leave', 'AAA' ),
            'all'                               => __( 'All', 'AAA' ),
            'options'                           => __( 'Options', 'AAA' ),
            'back_to_parent'                    => __( '&larr;&nbsp; Back to parent element', 'AAA' ),
            'option'                            => __( 'Options', 'AAA' ),

            'confirm_empty_title'               => __( 'Empty Content?', 'AAA' ),
            'confirm_empty_description'         => __( 'Are you sure you want to take this action?', 'AAA' ),
            'confirm_delete_title'              => __( 'Remove Module?', 'AAA' ),
            'confirm_delete_description'        => __( 'Are you sure you want to take this action?', 'AAA' ),

            'notifications' => array(
                'module_not_found'              => __( 'The module "{{value}}" was not found!', 'AAA' ),
                'template_not_found'            => __( 'The template "{{value}}" was not found!', 'AAA' ),
                'module_no_template'            => __( 'The module "{{value}}" does not have a template!', 'AAA' ),
                'layout_empty'                  => __( 'Layout cannot be empty!', 'AAA' ),
                'module_not_mapped'             => __( 'The module was not found in the mapping!', 'AAA' ),
                'bad_hex'                       => __( 'Bad hex "{{value}}"!', 'AAA' ),
            ),

        );

    }

}
Playouts_Admin_Map::init();
