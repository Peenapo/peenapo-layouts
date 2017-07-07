"use strict";

window.jQuery = window.$ = jQuery;

/*
 * this object is responsible for all prompts functionality
 * we call prompts, all the small modals appearing by an html template
 * the prompt will be called only when needed
 *
 */
var Pl_prompt = {

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

        $('#pl-main').on('click', '.pl-open-prompt', function() {
            Pl_prompt.prompt_open( $(this).attr('data-prompt') );
        });

    },

    before_show: function() {
        $('#pl-overlay').css({ 'visibility': 'visible', 'opacity': 1 });
        $('#pl-overlay').on('click.pl_click_overlay_prompt', Pl_prompt.on_click_prompt_close);
        $('.pl-prompt').on('click.pl_panel_info_click', '.pl-icon-info', Pl_settings_panel.on_click_info_icon);
    },

    before_hide: function() {

        if( ! $('.pl-prompt-confirm--remove-repeater').length ) {
            $('#pl-overlay').css({ 'visibility': 'hidden', 'opacity': 0 });
            $('#pl-overlay').off('click.pl_click_overlay_prompt');
            $('.pl-prompt').on('click.pl_panel_info_click', '.pl-icon-info', Pl_settings_panel.on_click_info_icon);
        }

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
        var $prompt = $( $('#pl-template-prompt-' + prompt_id).html() ).attr('data-prompt-id', prompt_id);

        // append the prompt after the main container
        $('#pl-main').append( $prompt );

        Pl_prompt.before_show();

        // show the prompt
        setTimeout(function() {
            $prompt.addClass('pl-prompted').find('.pl-panel-content').css('opacity', 1);
        }, 50);

        // bind the close button
        $prompt.on('click.pl_prompt_close', '.pl-prompt-close', Pl_prompt.on_click_prompt_close);
        $prompt.on('click.pl_prompt_close', '.pl-prompt-button-save-layout', Pl_prompt.on_click_prompt_button_save_layout);

        if( prompt_id == 'save-layout' ) {
            Pl_prompt.before_prompt_save_layout();
        }

        // bind keys
        $(document).on('keyup.pl_prompt_enter', Pl_prompt.on_prompt_key_enter);
        $(document).on('keyup.pl_prompt_escape', Pl_prompt.on_prompt_key_escape);

    },

    on_prompt_key_enter: function(e) {
        if( e.keyCode == 13 ) {
            $('.pl-prompt-key-enter').trigger('click');
        }
    },

    on_prompt_key_escape: function(e) {
        if( e.keyCode == 27 ) {
            $('.pl-prompt-key-escape').trigger('click');
        }
    },

    close: function() {

        if( ! $('.pl-prompt.pl-prompted').length ) { return; }

        Pl_prompt.before_hide();

        // the prompt container
        var $prompt = $('.pl-prompt').removeClass('pl-prompted');

        // wait for the animation to stop and remove the template
        setTimeout(function() {
            $prompt.remove();
        }, 220 );

        $('.pl-prompt-confirm').off('click.pl_prompt_confirm');

        $(document).off('keyup.pl_prompt_enter');
        $(document).off('keyup.pl_prompt_escape');

    },

    ajaxing_start: function() {

        var $prompt = $('.pl-prompt');
        if( $prompt.length ) {
            $prompt.addClass('pl-prompt-ajaxing');
        }

    },

    ajaxing_end: function() {

        var $prompt = $('.pl-prompt');
        if( $prompt.length ) {
            $prompt.removeClass('pl-prompt-ajaxing');
        }

    },

    before_prompt_save_layout: function() {

        var $categories = $('.bw-save-layout-cats'),
            $template;

        for ( var category in window.playouts_data.map_custom_layout_categories ) {
            $template = $( $('#playouts_template-save_custom_layout_category_item').html() );
            $template.find('input').attr('value', category).attr('id', 'layout-checkbox-' + category );
            $template.find('label').attr('for', 'layout-checkbox-' + category );
            $template.find('span').html( window.playouts_data.map_custom_layout_categories[ category ] );
            $template.appendTo( $('.bw-save-layout-cats') );
        }

    },

    on_click_prompt_button_save_layout: function(e) {

        e.preventDefault();

        if( $('.pl-prompt').hasClass('pl-prompt-ajaxing') ) { return; }

        var layout_name = $('#pl-field-layout-name').val();

        if( layout_name !== '' ) {

            Pl_prompt.ajaxing_start();

            var layout_name = layout_name,
                layout_content = Pl_layouts.custom_layout_content,
                layout_new_category = $('#pl-field-layout-category').val(),
                layout_categories = $('.bw-save-layout-cats input:checked').map(function() {
                    return this.value;
                }).get();

            Pl_layouts.send_layout( layout_name, layout_content, layout_categories, layout_new_category );

        }else{
            $('#pl-field-layout-name').focus();
        }

    },

    on_layout_saving_end: function() {

        $('#pl-field-layout-name').val('');

    },

    /*
     * on click prompt close button
     *
     */
    on_click_prompt_close: function(e) {

        e.preventDefault();

        Pl_prompt.close();

    }
}
Pl_prompt.start();
