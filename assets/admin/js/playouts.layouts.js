"use strict";

window.jQuery = window.$ = jQuery;

var Pl_layouts = {

    /*
     * are we in an post edit page with pl_layout post type
     *
     */
    screen_edit_layout: false,

    /*
     * when adding a lauput, this will holds the holder layout element's id
     * so when the ajax parser finishes, it will append the result into that element
     *
     */
    layout_parent_id: 0,

    /*
     * will return true, when parsing a layout
     *
     */
    is_layout: false,

    /*
     * holds the value of the custom layouts
     * we will store it here, before we send it
     *
     */
    custom_layout_content: '',

    /*
     *
     *
     */
    start: function() {

        // set screen
        this.screen_edit_layout = window.playouts_data.screen_edit && window.typenow == 'pl_layout';

        this.bind();

    },

    bind: function() {

        $('#pl-main').on('click', '.pl-button-save-custom-layout', Pl_layouts.on_click_save_custom_layout);

    },

    on_click_save_custom_layout: function() {

        var self = $(this);
        var save_type = self.attr('data-save-layout');
        var parent_id = self.closest('.pl-block').attr('data-id');

        switch( save_type ) {
            case 'content':
                Pl_layouts.custom_layout_content = Pl_main.wpautop( Pl_shortcoder.get_editor_content() );
                break;
            case 'row':
                Pl_layouts.flush_custom_layout_content_by_module_id( parent_id, false );
                Pl_layouts.custom_layout_content = Pl_layouts.custom_layout_content;
                break;
            case 'column':
                Pl_layouts.flush_custom_layout_content_by_module_id( parent_id, true );
                Pl_layouts.custom_layout_content = '[bw_row dummy="yes"][bw_column dummy="yes"]' + Pl_layouts.custom_layout_content + '[/bw_column][/bw_row]';
                break;
            case 'element':
                Pl_layouts.flush_custom_layout_content_by_module_id( parent_id, false );
                Pl_layouts.custom_layout_content = '[bw_row dummy="yes"][bw_column dummy="yes"]' + Pl_layouts.custom_layout_content + '[/bw_column][/bw_row]';
                break;
        }

    },

    /*on_click_save_layout: function() {

        Pl_prompt.open_prompt( $(this) );

        //Pl_layouts.save_layout();

    },*/

    /*save_layout: function() {

        var layout = Pl_shortcoder.get_editor_content();
        Pl_layouts.send_layout( Pl_main.wpautop( layout ) );

    },*/

    send_layout: function( layout_name, layout_content, layout_categories = false, layout_new_category = false ) {

        $.ajax({
            type: 'POST',
            url: playouts_admin_root.ajax,
            data: {
                action              : '__save_layout',
                nonce               : playouts_data.security.save_layout,
                layout_name         : layout_name,
                layout_content      : layout_content,
                layout_categories   : layout_categories,
                layout_new_category : layout_new_category
            },
            dataType: 'json',
            success: function( response ) {

                Pl_prompt.ajaxing_end(); // remove ajaxing class
                Pl_prompt.on_layout_saving_end(); // callback for prompt layout save

                window.playouts_data.map_custom_layouts = response.custom_layouts; // refresh layouts
                window.playouts_data.map_custom_layout_categories = response.custom_layout_categories; // refresh layout categories

                Pl_modal.reload_custom_layouts();
                Pl_modal.reload_custom_layout_categories();

            }
        });

        Pl_prompt.close(); // close the prompt modal

    },

    push_layout: function( layout, parent_id = 0, layout_view = false ) {

        // replace placeholder image path to assets
        layout = layout.replace( /%PLAYOUTS_PATH_ASSETS%/g, window.playouts_data.path_assets );

        // dummy modules are not needed here
        if( layout_view ) {
            layout = Pl_layouts.remove_dummy_modules( layout, layout_view );
        }

        if( layout == '' ) {
            Pl_main.notify( 'layout_empty', module );
            return;
        }

        this.layout_parent_id = parent_id;

        Pl_interface.send_ajax( layout, true, true, true );

    },

    remove_dummy_modules: function( layout, layout_view ) {

        if( /bw_row dummy="yes"/g.test( layout ) ) {
            layout = layout.replace( '[bw_row dummy="yes"]', '' );
            layout = layout.replace( /\[\/bw_row\]$/, '' );
        }
        if( /bw_column dummy="yes"/g.test( layout ) ) {
            layout = layout.replace( '[bw_column dummy="yes"]', '' );
            layout = layout.replace( /\[\/bw_column\]$/, '');
        }

        return layout;

    },

    /*
     * get tree object part and its children modules by id
     * then set the module's childrens as custom layout content
     *
     */
    flush_custom_layout_content_by_module_id: function( module_id, exclude_parent = false ) {

        Pl_layouts.match_tree_modules_children_by_id( Pl_mapper.__mapper_tree, module_id, exclude_parent );

    },

    match_tree_modules_children_by_id( tree_modules_obj, module_id, exclude_parent = false ) {

        var tree_length = tree_modules_obj.length;

        for ( var i = 0; i < tree_length; i++ ) {

            if( tree_modules_obj[i].id == module_id ) {
                var focus_obj = exclude_parent ? tree_modules_obj[i].children : tree_modules_obj[i];
                var focus_holder = [];
                focus_holder.push( tree_modules_obj[i] );
                Pl_layouts.custom_layout_content = Pl_shortcoder.reload_shortcodes_and_push( focus_holder, false );
                break;
            }

            if( tree_modules_obj[i].children && $.isArray( tree_modules_obj[i].children ) ) {
                Pl_layouts.match_tree_modules_children_by_id( tree_modules_obj[i].children, module_id, exclude_parent );
            }
        }

    },

}
Pl_layouts.start();
