"use strict";

window.jQuery = window.$ = jQuery;

var BwpbInterface = {

    /*
     * get current editor's content and send to ajax request
     *
     */
    parse: function() {

        this.send_ajax( BwpbShortcoder.get_editor_content() );

    },

    /*
     * empty the ui modules
     *
     */
    empty_modules: function() {
        $('#bwpb-main .bwpb-blocks').empty();
    },

    /*
     * send the ajax. on success - map the objects and render the shortcodes
     *
     */
    send_ajax: function( content, append__s = false, inherit_defaults = false, is_layout = false ) {

        Bwpb.loading(); // set preloader

        $.ajax({
            type: 'POST',
            url: bwpb_admin_root.ajax,
            data: {
                'action'        : '__parse_shortcode',
                'editor_content': Bwpb.wpautop( content )
            },
            dataType: 'json',
            success: function( response ) {

                // reverse the rows if appending on the top
                // or we will get the wrong order
                response = response.sort().reverse();

                // if we are paring a layout, then set a global
                if( is_layout ) { BwpbLayouts.is_layout = true; }
                // parse the response and do all stuff
                BwpbInterface.parse_ajax_response( response, inherit_defaults );
                // build the tree object and appending the shortcodes
                BwpbMapper.map_tree( append__s );
                // last retouches
                BwpbInterface.on_ajax_finish();
                // clear the layout parent id
                BwpbLayouts.layout_parent_id = 0;

            }
        });

    },

    /*
     * loop the ajax response and parse it
     *
     */
    parse_ajax_response: function( response, inherit_defaults = false ) {

        var self = this;

        for( var i = 0; i < response.length; i++ ) {
            self.parse_ajax_item( response[i], inherit_defaults );
        }

    },

    /*
     * parse an ajax item
     *
     */
    parse_ajax_item: function( item, inherit_defaults = false ) {

        var __all = $.extend( {}, Bwpb.all_modules, Bwpb.all_modules_repeater );

        if( typeof __all[ item.module ] === 'object' ) {

            var uid = item.uid;

            // fix for empty params
            var data = $.extend( true, {}, __all[ item.module ] );
            var module_options = data.params;
            var empty_options = {};

            // flag for content option
            var is_content_param = false;

            // loop the params and check which option is the content
            for ( var option in module_options ) {
                if( typeof __all[ item.module ].params[ option ].is_content !== 'undefined' ) {
                    is_content_param = option;
                }
                if( inherit_defaults || BwpbLayouts.is_layout ) { // inherit the module's default options, always inherit for layouts
                    if( typeof module_options[ option ].value !== 'undefined' ) {
                        empty_options[ option ] = module_options[ option ].value;
                    }
                }else{
                    empty_options[ option ] = ( typeof item.params[ option ] !== 'undefined' ) ? item.params[ option ] : '';
                }
            }

            // is_content
            if( typeof item.is_content !== 'undefined' && typeof item.children !== 'undefined' ) {
                empty_options[ is_content_param ] = Bwpb.wpauton( item.children );
            }

            // now we have the module and the options, so we can map it.
            // it will be stored to our main map object
            BwpbMapper.map_data( uid, data, empty_options );

            // update the new created module with the parsed options
            // the new module comes with the default options and we have to inherit them with the parsed options
            this.sync_new_module_options( uid, item );

            // if we insert a layout and we set parent id, then set the parent id for first level elements
            if( item.parent_id == 0 && BwpbLayouts.layout_parent_id !== 0 ) {
                item.parent_id = BwpbLayouts.layout_parent_id;
            }
            // create the element
            Bwpb.create_element( uid, item.module, item.parent_id, false, item.params );

            // parse childrens, if any..
            if( item.children !== '' && typeof item.children == 'object') {
                this.parse_ajax_response( item.children );
            }

        }else{

            Bwpb.notify( 'module_not_found', item.module );

        }

    },

    /*
     * on ajax finish
     *
     */
    on_ajax_finish: function() {

        Bwpb.loaded(); // remove the preloader
        Bwpb.welcome.check(); // check if there is place for the welcome message
        this.is_non_builder_content(); // check if the content is not one of ours
        Bwpb.reload_ui_functions(); // reload ui like module sorting
        BwpbLayouts.is_layout = false; // reset the layouts flat

    },

    /*
     * check if the content of the editor does not belongs to the plugin
     / then convert it into a text element
     *
     */
    is_non_builder_content: function() {

        //console.log( BwpbShortcoder.__s );
        //console.log( Bwpb.wpautop( BwpbShortcoder.get_editor_content() ) );

        // check if the content of the editor is non ours shortcodes
        if( BwpbShortcoder.__s === '' && Bwpb.wpautop( BwpbShortcoder.get_editor_content() ) !== '' ) {

            /*// add dummy parameter to all the modules out of the custom layouts scope
            // we can't push elements without parents, but dummy will make their lock parents
            var _dummy = window.typenow == 'pl_layout' ? ' dummy="yes"' : '';*/

            // then push template with the current editor content
            BwpbLayouts.push_layout( BwpbShortcoder.get_editor_content() );
            Bwpb.reload(); // reload all

        }

    },

    /*
     * update the new created module with the parsed options
     * the new module comes with the default options and we have to inherit them with the parsed options
     *
     */
    sync_new_module_options: function( uid, tree_obj_item ) {

        if( typeof tree_obj_item.params !== 'undefined' ) {

            var options = tree_obj_item.params;

            if( typeof options !== 'undefined' ) {
                for ( var option in options ) {
                    BwpbMapper.update_mapper_module_options( uid, option, options[ option ] );
                }
            }
        }
    }

}
