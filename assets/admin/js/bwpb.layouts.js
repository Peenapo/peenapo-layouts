"use strict";

window.jQuery = window.$ = jQuery;

/*
 * this object is responsible for all prompts functionality
 * we call prompts, all the small modals appearing by an html template
 * the prompt will be called only when needed
 *
 */
var BwpbPrompt = {

    /*
     * start here
     *
     */
    start: function() {

        this.bind();

    },

    /*
     * binding
     *
     */
    bind: function() {

        $('#bwpb-main').on('click', '.bwpb-open-prompt', function() {
            BwpbPrompt.prompt_open( $(this).attr('data-prompt') );
        });

    },

    before_show: function() {
        $('#bwpb-overlay').css({ 'visibility': 'visible', 'opacity': 1 });
        $('#bwpb-overlay').on('click.pl_click_overlay_prompt', BwpbPrompt.on_click_prompt_close);
        $('.bwpb-prompt').on('click.bwpb_panel_info_click', '.bwpb-icon-info', Bwpb_settings_panel.on_click_info_icon);
    },

    before_hide: function() {

        $('#bwpb-overlay').css({ 'visibility': 'hidden', 'opacity': 0 });
        $('#bwpb-overlay').off('click.pl_click_overlay_prompt');
        $('.bwpb-prompt').on('click.bwpb_panel_info_click', '.bwpb-icon-info', Bwpb_settings_panel.on_click_info_icon);

    },

    /*
     * on prompt open
     *
     */
    prompt_open: function( prompt_id = false ) {

        if( prompt_id == false ) { return; }

        // do nothing, if the template already exists
        if( $('[data-prompt-id="' + prompt_id + '"]').length ) { return; }

        // get the template html and convert to jquery object
        var $prompt = $( $('#bwpb-template-prompt-' + prompt_id).html() ).attr('data-prompt-id', prompt_id);

        // append the prompt after the main container
        $('#bwpb-main').append( $prompt );

        BwpbPrompt.before_show();

        // show the prompt
        setTimeout(function() {
            $prompt.addClass('bwpb-prompted').find('.bwpb-panel-content').css('opacity', 1);
        }, 50);

        // bind the close button
        $prompt.on('click.bwpb_prompt_close', '.bwpb-prompt-close', BwpbPrompt.on_click_prompt_close);
        $prompt.on('click.bwpb_prompt_close', '.bwpb-prompt-button-save-layout', BwpbPrompt.on_click_prompt_button_save_layout);

        if( prompt_id == 'save-layout' ) {
            BwpbPrompt.before_prompt_save_layout();
        }

        // bind keys
        $(document).on('keyup.pl_prompt_enter', BwpbPrompt.on_prompt_key_enter);
        $(document).on('keyup.pl_prompt_escape', BwpbPrompt.on_prompt_key_escape);

    },

    on_prompt_key_enter: function(e) {
        if( e.keyCode == 13 ) {
            $('.bwpb-prompt-key-enter').trigger('click');
        }
    },

    on_prompt_key_escape: function(e) {
        if( e.keyCode == 27 ) {
            $('.bwpb-prompt-key-escape').trigger('click');
        }
    },

    close: function() {

        if( ! $('.bwpb-prompt.bwpb-prompted').length ) { return; }

        BwpbPrompt.before_hide();

        // the prompt container
        var $prompt = $('.bwpb-prompt').removeClass('bwpb-prompted');

        // wait for the animation to stop and remove the template
        setTimeout(function() {
            $prompt.remove();
        }, 220 );

        $('.bwpb-prompt-confirm').off('click.pl_prompt_confirm');

        $(document).off('keyup.pl_prompt_enter');
        $(document).off('keyup.pl_prompt_escape');

    },

    ajaxing_start: function() {

        var $prompt = $('.bwpb-prompt');
        if( $prompt.length ) {
            $prompt.addClass('bwpb-prompt-ajaxing');
        }

    },

    ajaxing_end: function() {

        var $prompt = $('.bwpb-prompt');
        if( $prompt.length ) {
            $prompt.removeClass('bwpb-prompt-ajaxing');
        }

    },

    before_prompt_save_layout: function() {

        var $categories = $('.bw-save-layout-cats'),
            $template;

        for ( var category in window.bwpb_data.map_custom_layout_categories ) {
            $template = $( $('#bwpb_template-save_custom_layout_category_item').html() );
            $template.find('input').attr('value', category).attr('id', 'layout-checkbox-' + category );
            $template.find('label').attr('for', 'layout-checkbox-' + category );
            $template.find('span').html( window.bwpb_data.map_custom_layout_categories[ category ] );
            $template.appendTo( $('.bw-save-layout-cats') );
        }

    },

    on_click_prompt_button_save_layout: function(e) {

        e.preventDefault();

        if( $('.bwpb-prompt').hasClass('bwpb-prompt-ajaxing') ) { return; }

        var layout_name = $('#bwpb-field-layout-name').val();

        if( layout_name !== '' ) {

            BwpbPrompt.ajaxing_start();

            var layout_name = layout_name,
                layout_content = BwpbLayouts.custom_layout_content,
                layout_new_category = $('#bwpb-field-layout-category').val(),
                layout_categories = $('.bw-save-layout-cats input:checked').map(function() {
                    return this.value;
                }).get();

            BwpbLayouts.send_layout( layout_name, layout_content, layout_categories, layout_new_category );

        }else{
            $('#bwpb-field-layout-name').focus();
        }

    },

    on_layout_saving_end: function() {

        $('#bwpb-field-layout-name').val('');

    },

    /*
     * on click prompt close button
     *
     */
    on_click_prompt_close: function(e) {

        e.preventDefault();

        BwpbPrompt.close();

    }
}
BwpbPrompt.start();

var BwpbLayouts = {

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
        this.screen_edit_layout = window.bwpb_data.screen_edit && window.typenow == 'pl_layout';

        this.bind();

    },

    bind: function() {

        $('#bwpb-main').on('click', '.bwpb-button-save-custom-layout', BwpbLayouts.on_click_save_custom_layout);

    },

    on_click_save_custom_layout: function() {

        var self = $(this);
        var save_type = self.attr('data-save-layout');
        var parent_id = self.closest('.bwpb-block').attr('data-id');

        switch( save_type ) {
            case 'content':
                BwpbLayouts.custom_layout_content = Bwpb.wpautop( BwpbShortcoder.get_editor_content() );
                break;
            case 'row':
                BwpbLayouts.flush_custom_layout_content_by_module_id( parent_id, false );
                BwpbLayouts.custom_layout_content = BwpbLayouts.custom_layout_content;
                break;
            case 'column':
                BwpbLayouts.flush_custom_layout_content_by_module_id( parent_id, true );
                BwpbLayouts.custom_layout_content = '[bw_row dummy="yes"][bw_column dummy="yes"]' + BwpbLayouts.custom_layout_content + '[/bw_column][/bw_row]';
                break;
            case 'element':
                BwpbLayouts.flush_custom_layout_content_by_module_id( parent_id, false );
                BwpbLayouts.custom_layout_content = '[bw_row dummy="yes"][bw_column dummy="yes"]' + BwpbLayouts.custom_layout_content + '[/bw_column][/bw_row]';
                break;
        }

    },

    /*on_click_save_layout: function() {

        BwpbPrompt.open_prompt( $(this) );

        //BwpbLayouts.save_layout();

    },*/

    /*save_layout: function() {

        var layout = BwpbShortcoder.get_editor_content();
        BwpbLayouts.send_layout( Bwpb.wpautop( layout ) );

    },*/

    send_layout: function( layout_name, layout_content, layout_categories = false, layout_new_category = false ) {

        $.ajax({
            type: 'POST',
            url: bwpb_admin_root.ajax,
            data: {
                action              : '__save_layout',
                nonce               : bwpb_data.security.save_layout,
                layout_name         : layout_name,
                layout_content      : layout_content,
                layout_categories   : layout_categories,
                layout_new_category : layout_new_category
            },
            dataType: 'json',
            success: function( response ) {

                BwpbPrompt.ajaxing_end(); // remove ajaxing class
                BwpbPrompt.on_layout_saving_end(); // callback for prompt layout save

                window.bwpb_data.map_custom_layouts = response.custom_layouts; // refresh layouts
                window.bwpb_data.map_custom_layout_categories = response.custom_layout_categories; // refresh layout categories

                Pl_modal.reload_custom_layouts();
                Pl_modal.reload_custom_layout_categories();

            }
        });

        BwpbPrompt.close(); // close the prompt modal

    },

    push_layout: function( layout, parent_id = 0, layout_view = false ) {

        if( layout_view ) {
            layout = BwpbLayouts.remove_dummy_modules( layout, layout_view );
        }

        if( layout == '' ) {
            Bwpb.notify( 'layout_empty', module );
            return;
        }

        this.layout_parent_id = parent_id;

        BwpbInterface.send_ajax( layout, true, true, true );

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

        BwpbLayouts.match_tree_modules_children_by_id( BwpbMapper.__mapper_tree, module_id, exclude_parent );

    },

    match_tree_modules_children_by_id( tree_modules_obj, module_id, exclude_parent = false ) {

        var tree_length = tree_modules_obj.length;

        for ( var i = 0; i < tree_length; i++ ) {

            if( tree_modules_obj[i].id == module_id ) {
                var focus_obj = exclude_parent ? tree_modules_obj[i].children : tree_modules_obj[i];
                var focus_holder = [];
                focus_holder.push( tree_modules_obj[i] );
                BwpbLayouts.custom_layout_content = BwpbShortcoder.reload_shortcodes_and_push( focus_holder, false );
                break;
            }

            if( tree_modules_obj[i].children && $.isArray( tree_modules_obj[i].children ) ) {
                BwpbLayouts.match_tree_modules_children_by_id( tree_modules_obj[i].children, module_id, exclude_parent );
            }
        }

    },

}
BwpbLayouts.start();
