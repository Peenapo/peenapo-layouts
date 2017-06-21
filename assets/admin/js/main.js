

//(function($) {
window.jQuery = window.$ = jQuery;


/*
 * panel to add custom styles to the current page
 *
 */
var Bwpb_custom_css_panel = {

    $panel: $('#bwpb-custom-css'),

    start: function() {

        this.bind();

    },

    bind: function() {

        // open custom css panel
        $('.bwpb-open-custom-css-panel').on('click', Bwpb_custom_css_panel.edit);

        // close panel
        Bwpb_custom_css_panel.$panel.on('click', '.bwpb-button-close', Bwpb_custom_css_panel.close);

        // save custom css
        Bwpb_custom_css_panel.$panel.on('click', '.bwpb-button-save', Bwpb_custom_css_panel.save);

    },

    edit: function() {

        // close any settings panel
        Bwpb_settings_panel.close();
        // open the panel
        Bwpb_custom_css_panel.open();

    },

    open: function() {

        Bwpb_custom_css_panel.$panel.addClass('bwpb-open bwpb-done');
        // overlay
        $('#bwpb-overlay').css({'visibility':'visible', 'opacity':1});

    },

    close: function() {

        Bwpb_custom_css_panel.$panel.removeClass( 'bwpb-open bwpb-done' );
        $('#bwpb-overlay').css({'visibility':'hidden', 'opacity':0});

    },

    /*
     * save will only close the panel
     *
     */
    save: function() {

        Bwpb_custom_css_panel.close();

    },

}

/*
 * panel to edit element's options
 *
 */
var Bwpb_settings_panel = {

    $panel: $('#bwpb-panel-settings'),

    /*
     * the id of the element we are currently editing
     *
     */
    panel_edit_id: 0,

    /*
     * the id of the insider element we are currently editing
     *
     */
    panel_edit_insider_id: 0,

    /*
     * the parent last selected tab
     * this will help us to bring back the last tab when you hit "Back to parent" button
     *
     */
    panel_edit_insider_tab: false,

    /*
     * init some panel settings
     *
     */
    start: function() {

        // TODO: move panel variables like $panel in this object
        // and other init options, if any..

        Bwpb_settings_panel.bind();

    },

    /*
     * bind the panel events
     *
     */
    bind: function() {

        // edit options
        $('#bwpb-main').on('click', '.bwpb-edit', Bwpb_settings_panel.edit);
        $('#bwpb-main').on('click', '.bwpb-edit-columns', Bwpb_settings_panel.edit_columns);

        // close panel
        Bwpb_settings_panel.$panel.on('click', '.bwpb-button-close', Bwpb_settings_panel.close);

        // save settings panel
        Bwpb_settings_panel.$panel.on('click', '.bwpb-button-save', Bwpb_settings_panel.save);
    },

    /*
     * on click edit
     *
     */
    edit: function() {
        Bwpb_settings_panel.open( $(this).closest('.bwpb-block').attr('data-id') );
    },

    /*
     * on column settings
     *
     */
    edit_columns: function() {
        Bwpb_settings_panel.open( $(this).closest('.bwpb-block').attr('data-id'), 'row_columns' );
    },

    edit_by_id: function( id ) {
        Bwpb_settings_panel.open( id );
    },

    edit_inside: function( id ) {
        Bwpb_settings_panel.open_inside( id );
    },

    /*
     * open settings panel
     *
     */
    open: function( id, tab = false ) {

        // this is the element we are currently editing
        var data = BwpbMapper.__mapper_data[ id ];

            // set the title of the panel
            Bwpb_settings_panel.set_title( data.name );
            // close any other settings panels
            Bwpb_settings_panel.close();
            // set the overlay
            $('#bwpb-overlay').css({'visibility':'visible', 'opacity':1});

        // get and generate all the settings via js
        Bwpb_settings_panel.get_settings( id, data, tab );
        // display the panel
        Bwpb_settings_panel.show();
        // bind the info icon to expand description area
        Bwpb_settings_panel.$panel.on('click.bwpb_panel_info_click', '.bwpb-icon-info', Bwpb_settings_panel.on_click_info_icon);

    },

    open_inside: function( id ) {

        Bwpb_settings_panel.tabs.set_insider_last_tab();

        // lets set the insider id before we do anything
        Bwpb_settings_panel.panel_edit_insider_id = Bwpb_settings_panel.panel_edit_id;

        // this is the element we are currently editing
        var data = BwpbMapper.__mapper_data[ id ];

        // add class to save button
        Bwpb_settings_panel.$panel.find('.bwpb-button-save').addClass('bwpb-button-save-insider');
        Bwpb_settings_panel.$panel.find('.bwpb-button-close').addClass('bwpb-button-close-insider');

        // bind the insider buttons
        Bwpb_settings_panel.$panel.on('click.bwpb_insider_back_button', '.bwpb-tabs-back', Bwpb_settings_panel.on_click_insider_back_to_parent);
        Bwpb_settings_panel.$panel.on('click.bwpb_insider_save', '.bwpb-button-save-insider', Bwpb_settings_panel.on_click_insider_save);
        Bwpb_settings_panel.$panel.on('click.bwpb_insider_close', '.bwpb-button-close-insider', Bwpb_settings_panel.on_click_insider_back_to_parent);

        // unbind the info icon
        Bwpb_settings_panel.$panel.off('click.bwpb_panel_info_click');
        // get and generate all the settings via js
        Bwpb_settings_panel.get_settings( id, data, false, true );
        // display the panel
        Bwpb_settings_panel.show();
        // bind the info icon to expand description area
        Bwpb_settings_panel.$panel.on('click.bwpb_panel_info_click', '.bwpb-icon-info', Bwpb_settings_panel.on_click_info_icon);

    },

    on_click_insider_back_to_parent: function() {

        $('.bwpb-panel-tabs .bwpb-tabs-back').remove();

        if( Bwpb_settings_panel.$panel.hasClass('bwpb-ajaxing') ) { return; }

        // edit the insider parent by its id
        Bwpb_settings_panel.edit_by_id( Bwpb_settings_panel.panel_edit_insider_id );
        // close insider
        Bwpb_settings_panel.close_insider();

    },

    on_click_insider_save: function() {

        if( Bwpb_settings_panel.$panel.hasClass('bwpb-ajaxing') ) { return; }
        // lets save the panel options without closing the panel
        Bwpb_settings_panel.save( false );
        // edit the insider parent by its id
        Bwpb_settings_panel.edit_by_id( Bwpb_settings_panel.panel_edit_insider_id );
        // close insider
        Bwpb_settings_panel.close_insider();

    },

    /*
     * perform some actions on insider close
     * clean up classes, binding and insider ids
     *
     */
    close_insider: function() {

        Bwpb_settings_panel.$panel.find('.bwpb-button-save').removeClass('bwpb-button-save-insider');
        Bwpb_settings_panel.$panel.find('.bwpb-button-close').removeClass('bwpb-button-close-insider');

        Bwpb_settings_panel.$panel.off('click.bwpb_insider_save');
        Bwpb_settings_panel.$panel.off('click.bwpb_insider_close');

        Bwpb_settings_panel.panel_edit_insider_id = 0;

    },

    /*
     * close settings panel
     *
     */
    close: function() {

        if( $(this).hasClass('bwpb-button-close-insider') ) { return; }

        // close the panel
        Bwpb_settings_panel.$panel.removeClass( 'bwpb-open' );
        // hide overlay
        $('#bwpb-overlay').css({ 'visibility': 'hidden', 'opacity': 0 });
        // release option types dependencies
        Bwpb_dependencies.release_deps();
        // call option types onclose callbacks
        Bwpb_settings_panel.on_close_options_panel_callbacks();
        // remove tabs
        Bwpb_settings_panel.tabs.end();
        // unbind the info icon
        Bwpb_settings_panel.$panel.off('click.bwpb_panel_info_click');
        // clear the current settings panel id
        Bwpb_settings_panel.panel_edit_id = 0;

    },

    /*
     * this will be called when closing the option panel
     * the current_panel_option_types holds all the displayed option types
     *
     */
    on_close_options_panel_callbacks: function() {

        // if any
        var option_types_length = Object.keys( Playouts_Option_Type.current_panel_option_types ).length;
        if( option_types_length ) {
            // loop the option types and call the onclose callbacks
            for( var option_type in Playouts_Option_Type.current_panel_option_types ) {
                if( typeof Playouts_Option_Type.option_types[ option_type ] !== 'undefined' ) {
                    if( typeof Playouts_Option_Type.option_types[ option_type ].option_onclose_callback == 'function' ) {
                        Playouts_Option_Type.option_types[ option_type ].option_onclose_callback();
                    }
                }
            }
            // empty the option type callbacks after we are done here
            Playouts_Option_Type.current_panel_option_types = [];
        }

    },

    show: function() {
        Bwpb_settings_panel.$panel.addClass( 'bwpb-open' );
    },

    tabs: {

        start: function( panel_tabs, tab_active = false ) {

            if( Object.keys( panel_tabs ).length <= 1 ) { return; }

            Bwpb_settings_panel.tabs.get_html( panel_tabs );
            Bwpb_settings_panel.tabs.bind();
            Bwpb_settings_panel.tabs.set_tab_general();
            Bwpb_settings_panel.tabs.active( tab_active );

        },

        get_html: function( panel_tabs ) {

            var tab_id = 0;

            var tabs_html = '<ul>';
            for( var panel_tab in panel_tabs ) {
                tabs_html += '<li data-tab-id="' + panel_tab + '"' + ( tab_id == 0 ? ' class="bwpb-active"' : '' ) + '>' + panel_tabs[ panel_tab ] + '</li>';
                tab_id++;
            }
            tabs_html += '</ul>';

            var $tabs = $('.bwpb-panel-tabs', Bwpb_settings_panel.$panel);

            $tabs.find('ul').remove();
            $tabs.append( tabs_html );
            $('.bwpb-panel-tabs li').css({ 'top': 0, 'opacity': 1 });

            $('.bwpb-panel-tabs').removeClass('bwpb-loading');

        },

        bind: function() {

            $('.bwpb-panel-tabs li').on('click', Bwpb_settings_panel.tabs.switch_tab);

        },

        set_tab_general: function() {

            $('.bwpb-panel-row', Bwpb_settings_panel.$panel).addClass('bwpb-tab-hidden');
            $('.bwpb-row-tab-general', Bwpb_settings_panel.$panel).removeClass('bwpb-tab-hidden');

        },

        switch_tab: function() {

            var self = $(this);
            var tab_id = self.attr('data-tab-id');

            $('.bwpb-panel-tabs li').removeClass('bwpb-active');
            self.addClass('bwpb-active');

            $('.bwpb-panel-row', Bwpb_settings_panel.$panel).addClass('bwpb-tab-hidden');
            $('.bwpb-row-tab-' + tab_id, Bwpb_settings_panel.$panel).removeClass('bwpb-tab-hidden');

            var $panel_content = $('.bwpb-panel-form');

            Bwpb_dependencies.set_deps();

        },

        tabs_inside: function() {

            // hide tabs and add back button
            var $tabs = $('.bwpb-panel-tabs ul li'),
                $back = $('<span class="bwpb-tabs-back">' + window.bwpb_data.i18n.back_to_parent + '</span>');

            $('.bwpb-panel-tabs').append( $back );

            $('#bwpb-panel-settings .bwpb-panel-title').html( BwpbMapper.__mapper_data[ Bwpb_settings_panel.panel_edit_id ].name + ' ' + window.bwpb_data.i18n.option );

        },

        set_insider_last_tab: function() {

            Bwpb_settings_panel.panel_edit_insider_tab = $('.bwpb-panel-tabs li.bwpb-active').attr('data-tab-id');

        },

        trigger_insider_last_tab: function() {

            if( ! Bwpb_settings_panel.panel_edit_insider_id && Bwpb_settings_panel.panel_edit_insider_tab ) {
                $('.bwpb-panel-tabs li[data-tab-id="' + Bwpb_settings_panel.panel_edit_insider_tab + '"]').trigger('click');
                Bwpb_settings_panel.panel_edit_insider_tab = false;
            }
        },

        active: function( tab_active ) {

            if( tab_active ) {
                $('.bwpb-panel-tabs li[data-tab-id="' + tab_active + '"]').trigger('click');
            }

        },

        loading: function() {

            $('.bwpb-panel-tabs').addClass('bwpb-loading');

        },

        end: function() {

            $('.bwpb-panel-tabs ul', Bwpb_settings_panel.$panel).remove();

        }

    },

    set_title: function( title ) {
        $('.bwpb-panel-title', Bwpb_settings_panel.$panel).html( title + ' ' + window.bwpb_data.i18n.options );
    },

    after_open: function() {

        // set parent tab on insiders
        Bwpb_settings_panel.tabs.trigger_insider_last_tab();

    },

    on_click_info_icon: function(e) {

        e.preventDefault();

        var self = $(this);

        self.toggleClass('bwpb-info-active');

        var $info = self.closest('.bwpb-panel-row').find('.bwpb-header-info');
        if( self.hasClass('bwpb-info-active') ) {
            TweenLite.to( $info, .3, { height: $info.find('p').outerHeight() } );
        }else{
            TweenLite.to( $info, .3, { height: 0 } );
        }

    },

    get_settings: function( uid, data, tab = false, inside = false ) {

        var self = this;

        // use js to display the options output
        Bwpb_settings_panel.panel_edit_id = uid;

        Bwpb_settings_panel.tabs.loading();

        var settings_html = '';
        var $panel_content = $( '.bwpb-panel-content', Bwpb_settings_panel.$panel );
        var option_type_callbacks = {}; // holds the option type callbacks, we will call them after at the end when all options were loaded.
        var panel_tabs = { 'general' : bwpb_data.panel_general_tab };

        $panel_content.empty();
        Bwpb_settings_panel.$panel.removeClass('bwpb-done').addClass('bwpb-ajaxing');

        $.ajax({
            type: 'POST',
            url: bwpb_admin_root.ajax,
            data: {
                'action'        : '__panel_get_options',
                'security'      : window.bwpb_data.security.panel_get_options,
                'options'       : JSON.stringify( data.params )
            },
            success: function( response ) {

                // append the options html
                $panel_content.append( response );

                // loop the params and store all option types into the variable "option_type_callbacks"
                for( var param in data.params ) {

                    var _option_type = data.params[ param ].type;

                    // check if the option has tab
                    if( typeof data.params[ param ].tab == 'object' ) {
                        for( var tab_id in data.params[ param ].tab ) {
                            panel_tabs[ tab_id ] = data.params[ param ].tab[ tab_id ];
                        }
                    }

                    var options = $.extend( {}, data.params[ param ], { 'name' : param } );

                    option_type_callbacks[ _option_type ] = options;

                    // run the option callback
                    if( typeof Playouts_Option_Type.option_types[ _option_type ] !== 'undefined' ) {
                        var $template = Bwpb_settings_panel.$panel.find('.bwpb-panel-row[data-id="' + options.name + '"]');
                        Playouts_Option_Type.option_types[ _option_type ].option_onopen_callback( $template, options );
                    }

                }

                setTimeout(function() {
                    // manage panel classes after we are done
                    Bwpb_settings_panel.$panel.removeClass('bwpb-ajaxing').addClass('bwpb-done');
                }, 50);

                // lets keep the option_types to call the onclose callback when closing the options panel
                Playouts_Option_Type.current_panel_option_types = option_type_callbacks;


                /*
                 * if opening an inside element, call tabs inside
                 *
                 */
                if( inside ) {
                    Bwpb_settings_panel.tabs.tabs_inside();
                }

                /*
                 * handle panel tabs
                 *
                 */
                Bwpb_settings_panel.tabs.start( panel_tabs, tab );

                /*
                 * run dependencies.
                 * create dependency when one option depends on other option's value,
                 *
                 */
                Bwpb_dependencies.create_deps();

                /*
                 * final retouch
                 *
                 */
                Bwpb_settings_panel.after_open();

            }
        });


    },

    /*
     * run before we save the new option values
     * here we can encode or decode any value
     *
     */
    before_save: function() {

        /*
         * base 64 encode
         * we want to store this value as base64 code and not as plain text
         *
         */
        $('.bwpb-panel-row[data-type="base64"]', Bwpb_settings_panel.$panel).each(function() {
            var $row = $(this);
            var $input = $('input', $row);
            var value = $('textarea', $row).val();
            $input.val( str_replace( '=', '_', base64_encode( value ) ) );

        });

    },

    /*
     * get the new option values from the panel form
     * and return it
     *
     * clear empty values and get tinymce contect
     *
     */
    parse_saved_fields: function() {

        var new_values = {};

        var form_data = $('#bwpb-panel-form').serializeArray();

        for( var i = 0; i < form_data.length; i++ ) {

            if( typeof new_values[ form_data[i].name ] !== 'undefined' ) { // add comma separated values for multiple choices
                new_values[ form_data[i].name ] = new_values[ form_data[i].name ] + ',' + form_data[i].value;
            }else{ // do not pass empty values
                if( form_data[i].value !== 'undefined' && form_data[i].value !== '' && form_data[i].value !== '0' ) {
                    new_values[ form_data[i].name ] = form_data[i].value;
                }
            }
        }

        // get tinymce content
        $( '.bwpb-panel-content .bwpb-tinymce-container.tmce-active', Bwpb_settings_panel.$panel ).each(function() {
            if( typeof $('textarea:first', this).attr('name') !== 'undefined' ) {
                new_values[ $('textarea:first', this).attr('name') ] = tinymce.get( $(this).attr('data-editor-id') ).getContent();
            }
        });

        return new_values;
    },

    /*
     * responsibe for getting the new options values
     * and updating the __mapper_data element's object
     *
     */
    save: function( close = true ) {

        if( Bwpb_settings_panel.$panel.hasClass('bwpb-ajaxing') ) { return; }

        // do some stuff before we get the new values and save
        Bwpb_settings_panel.before_save();
        // get the new option values from the panel
        var new_option_values = Bwpb_settings_panel.parse_saved_fields();
        // now update the element with the new option values
        var uid = Bwpb_settings_panel.update_map_obj_on_fields( new_option_values, close );

    },

    /*
     * on settings form save, get the fields and update
     * the map object
     *
     */
    update_map_obj_on_fields: function( fields, close = true ) {

        var uid = Bwpb_settings_panel.panel_edit_id;

        if( typeof uid === 'string' ) {

            var b = BwpbMapper.__mapper_data[ uid ];

            // empty params ( if public )
            for( var param in b.params ) {
                if( typeof b.params[ param ].value !== 'undefined' ) {
                    if( ! ( typeof b.params[ param ].public !== 'undefined' && b.params[ param ].public === false ) ) {
                        b.params[ param ].value = '';
                    }
                }
            }

            for( var field_name in fields ) {
                if( typeof b === 'object' ) {
                    if( typeof b.params === 'object' ) {
                        if( typeof b.params[ field_name ] === 'object' ) {
                            if( typeof b.params[ field_name ].value === 'undefined' ) {
                                if( fields[ field_name ] !== '' ) {
                                    b.params[ field_name ].value = fields[ field_name ];
                                }
                            }else{
                                b.params[ field_name ].value = fields[ field_name ];
                            }
                        }
                    }
                }
            }

            // get new shortcode and append to content
            BwpbShortcoder.reload_shortcodes_and_push( BwpbMapper.__mapper_tree, true );
            // close the panel settings
            if( close ) {
                Bwpb_settings_panel.close();
            }else{
                Bwpb_settings_panel.on_close_options_panel_callbacks();
            }

        }

        return uid;
    }

}

var Bwpb_dependencies = {

    /*
     * hold the deps in the current options panel,
     * so we can unbind the deps when closing the options panel
     *
     */
    current_panel_deps: [],

    /*
     * build the dependencies on options panel open
     *
     */
    create_deps: function( group = false ) {

        var $panel_content = $('.bwpb-panel-form');

        $panel_content.find('[data-depends-on]').each(function() {

            var $row = $(this);
            var depends_on_field = $row.attr('data-depends-on'), $depends_on_field;

            if( group ) { // editing grouped options
                $depends_on_field = $('[name="' + group + '[' + depends_on_field + ']"]', $panel_content);
            }else{ // editing a settings panel
                $depends_on_field = $('[name="' + depends_on_field + '"]', $panel_content);
            }

            // check the dependency on panel load
            Bwpb_dependencies.check_row_dependency( $row, $depends_on_field );

            // check dependency on input change
            $depends_on_field.on('change.bwpb_click_depends_change', function() {
                Bwpb_dependencies.check_row_dependency( $row, $depends_on_field );
            });

            Bwpb_dependencies.current_panel_deps.push( depends_on_field );
        });

    },

    set_deps: function() {

        var $panel_content = $('.bwpb-panel-form');

        $panel_content.find('[data-depends-on]').each(function() {

            var $row = $(this);
            var depends_on_field = $row.attr('data-depends-on'), $depends_on_field;

            $depends_on_field = $('[name="' + depends_on_field + '"]', $panel_content);

            Bwpb_dependencies.check_row_dependency( $row, $depends_on_field );

        });

    },

    /*
     * unbind the dependencies on options panel close
     *
     */
    release_deps: function() {

        for( var dep in Bwpb_dependencies.current_panel_deps ) {
            $('[name="' + Bwpb_dependencies.current_panel_deps[dep] + '"]', $('#bwpb-panel-settings .bwpb-panel-content')).off('change.bwpb_click_depends_change');
        }
        // empty the dependency array
        Bwpb_dependencies.current_panel_deps = [];

    },

    /*
     * check if the field value is equal to the dependency value
     * and show the row if equals
     *
     */
    check_row_dependency: function( $row, $depends_on_field ) {

        var depends_value;

        if( $depends_on_field.is(':checkbox') ) {

            depends_value = $depends_on_field.is(':checked');
            Bwpb_dependencies.check_row_dependency_by_val( $row, $depends_on_field, depends_value );

        }else if( $depends_on_field.is(':radio') ) {

            depends_value = $depends_on_field.closest('.bwpb-panel-row').find('input:checked').val();
            Bwpb_dependencies.check_row_dependency_by_val( $row, $depends_on_field, depends_value );

        }else{

            depends_value = $row.attr('data-depends-value');
            Bwpb_dependencies.check_row_dependency_by_val( $row, $depends_on_field, depends_value );

        }

    },

    check_row_dependency_by_val: function( $row, $depends_on_field, depends_value ) {

        if( typeof depends_value == 'string' && depends_value.indexOf( ',' ) !== -1 ) {
            var depends_value_split = depends_value.split(',');
            for( var i = 0; i < depends_value_split.length; i++ ) {
               if( $depends_on_field.val() == depends_value_split[i] ) {
                   Bwpb_dependencies.show_row( $row );
                   break;
               }else{
                   Bwpb_dependencies.hide_row( $row )
               }
            }
        }else{
            $depends_on_field.val() == depends_value ? Bwpb_dependencies.show_row( $row ) : Bwpb_dependencies.hide_row( $row );
        }

    },

    show_row: function( $row ) {
        var height = $row.find('.bwpb-panel-row-inner').outerHeight();
        TweenLite.to( $row, .3, { height: height, borderRightWidth: 1, borderBottomWidth: 1, onComplete: function() {
            TweenLite.set( $row, { height: 'auto' } );
        }});
    },

    hide_row: function( $row ) {
        TweenLite.to( $row, .3, { height: 0, borderRightWidth: 0, borderBottomWidth: 0 } );
    }

}


var Playouts_Option_Type = {

    /*
     * store all option types
     *
     */
    option_types: {},

    /*
     * hold the option types in the current options panel,
     * so we can call the onclose callbacks when closing the panel
     *
     */
    current_panel_option_types: [],

}

Playouts_Option_Type.option_types.colorpicker = {

    option_onopen_callback: function( $template, options ) {

        $('.bwpb-colorpicker').wpColorPickerAlpha();

    },

    option_onclose_callback: function() {}
}

Playouts_Option_Type.option_types.image = {

    option_onopen_callback: function( $template, options ) {

        $('.bwpb-option-image', $template).each(function() {

            var self = $(this);
            var $input = self.find('input');
            var $thumbnail_img = $('.bwpb-image-preview img', self);

            self.find('.bwpb-image-remove').on('click', function(e) {
                self.removeClass('bwpb-has-image');
                $input.val('');
                $thumbnail_img.attr( 'src', '' );
                return false;
            });

            self.find('.bwpb-upload-button').on('click', function(e) {

                e.preventDefault();

                // extend the wp.media object
                custom_uploader = wp.media.frames.file_frame = wp.media({
                    library: { type: 'image' },
                    multiple: false
                });

                // when a file is selected, grab the url and set it as the text field's value
                custom_uploader.on('select', function() {
                    attachment = custom_uploader.state().get('selection').first().toJSON();

                    $input.val( attachment.url );
                    self.addClass('bwpb-has-image');
                    var image_url = ( typeof attachment.sizes.full !== 'undefined' ) ? attachment.sizes.full.url : attachment.url;
                    $thumbnail_img.attr( 'src', image_url );

                });

                //Open the uploader dialog
                custom_uploader.open();
            });
        });

    },

    option_onclose_callback: function() {}

}

Playouts_Option_Type.option_types.file = {

    option_onopen_callback: function( $template, options ) {

        $('.bwpb-option-file', $template).each(function() {

            var self = $(this);
            var $input = self.find('input[type="text"]');

            self.find('.bwpb-upload-button').on('click', function(e) {
                e.preventDefault();

                //Extend the wp.media object
                custom_uploader = wp.media.frames.file_frame = wp.media({
                    multiple: false
                });

                //When a file is selected, grab the URL and set it as the text field's value
                custom_uploader.on('select', function() {
                    attachment = custom_uploader.state().get('selection').first().toJSON();
                    var url = attachment.url;
                    $input.val( url );
                    custom_uploader.close();
                });

                //Open the uploader dialog
                custom_uploader.open();
            });
        });

    },

    option_onclose_callback: function() {}

}

Playouts_Option_Type.option_types.radio_image = {

    option_onopen_callback: function( $template, options ) {

        $template.find('.bwpb-option-radio-image').each(function() {
            Playouts_Option_Type.option_types.radio_image.check_radio_image( $(this) );
        });

        $template.find('.bwpb-radio-image').on('click', function() {

            var self = $(this);

            self.closest('.bwpb-row-option-radio_image').find('.bwpb-radio-image').removeClass('bwpb-radio-active');
            self.addClass('bwpb-radio-active');

        });

    },

    check_radio_image: function( self ) {
        if( self.find('input').is(':checked') ) {
            self.find('.bwpb-radio-image').addClass('bwpb-radio-active');
        }
    },

    option_onclose_callback: function() {}
}

Playouts_Option_Type.option_types.true_false = {

    option_onopen_callback: function( $template, options ) {

        $template.on('click', '.bwpb-true-false input', function() {

            var self = $(this).closest('.bwpb-true-false');

            $('input', self).trigger('click');

            if( $('input', self).is(':checked') ) {
                self.addClass('bwpb-active');
            }else{
                self.removeClass('bwpb-active');
            }

        });

    },

    option_onclose_callback: function() {}
}

Playouts_Option_Type.option_types.number_slider = {

    option_onopen_callback: function( $template, options ) {

        $('.bwpb-option-number-slider').each(function() {

            var self = $('.bwpb-number-slider', this);
            var min = parseFloat( self.attr('data-min') );
            var max = parseFloat( self.attr('data-max') );
            var step = parseFloat( self.attr('data-step') );
            var value = parseFloat( self.attr('data-value') );

            if( isNaN( min ) || isNaN( max ) || isNaN( step ) || isNaN( value ) ) {
                min = 0; max = 100; step = 1; value = 0;
            }

            self.slider({
                range          : "min",
                min            : min,
                max            : max,
                step           : step,
                value          : value,
                slide: function( event, ui ) {
                    $('input', self).val( ui.value );
                    self.closest('.bwpb-panel-row').find('.bwpb-option-number-slider i').html( ui.value );
                }
            });

        });
    },

    option_onclose_callback: function( options ) {}
}

Playouts_Option_Type.option_types.icon = {

    option_onopen_callback: function( $template, options ) {

        var self = $template.find('.bwpb-option-icon');
        var $container = self.find('.bwpb-icon-container');
        var $expand = self.find('.bwpb-icon-expand');

        // get the icon template and append it into the icon container
        $container.html( $('#bwpb_icons').html() );

        // binds
        $expand.on('click', Playouts_Option_Type.option_types.icon.expand);
        $container.on('click', 'li', Playouts_Option_Type.option_types.icon.select_icon);

        // if no icon is selected, select the first one by default
        if( self.find('.bwpb-icon-label i').attr('class') == '' ) {
            $container.find('li:first-child').trigger('click');
        }

        // add class active for selected icons
        $container.find('li[data-value="' + self.find('.bwpb-icon-label i').attr('class') + '"]').addClass('bwpb-active');

    },

    option_onclose_callback: function( options ) {},

    expand: function() {
        $(this).closest('.bwpb-option-icon').toggleClass('bwpb-expand');
    },

    select_icon: function() {
        var self = $(this);
        var $option = self.closest('.bwpb-option-icon');
        var icon = self.attr('data-value');

        $option.find('.bwpb-icon-container li').removeClass('bwpb-active');
        self.addClass('bwpb-active');
        $option.find('input').val( icon );
        $option.find('.bwpb-icon-label i').attr('class', icon);


    }
}

Playouts_Option_Type.option_types.editor = {

    option_onopen_callback: function( $template, options ) {

        // tinymce
        if( window.tinyMCEPreInit && window.tinyMCEPreInit.mceInit[ wpActiveEditor ] ) {

            var editor_id = 'bwpb_tinymce_' + options.name;

            window.tinyMCEPreInit.mceInit[ editor_id ] = _.extend({}, window.tinyMCEPreInit.mceInit[ wpActiveEditor ], {
                id: editor_id,
                setup: function (ed) {
                   if ( typeof( ed.on ) != 'undefined' ) {
                        ed.on('init', function ( ed ) {
                            ed.target.focus();
                            wpActiveEditor = editor_id;
                        });
                    } else {
                        ed.onInit.add(function ( ed ) {
                            ed.focus();
                            wpActiveEditor = editor_id;
                        });
                    }
                },
            });

            window.tinyMCEPreInit.mceInit[ editor_id ].plugins = window.tinyMCEPreInit.mceInit[ editor_id ].plugins.replace(/,?wpfullscreen/, '').replace(/,?fullscreen/, '');
            window.tinyMCEPreInit.mceInit[ editor_id ].toolbar1 = window.tinyMCEPreInit.mceInit[ editor_id ].toolbar1.replace(/,?dfw/, '');
            window.tinyMCEPreInit.mceInit[ editor_id ].wp_autoresize_on = false;

        }

        if( window.tinymce ) {
            tinymce.execCommand( 'mceAddEditor', true, editor_id );
        }

        // quicktags
        if( window.tinyMCEPreInit ) {
            window.tinyMCEPreInit.qtInit[ editor_id ] = _.extend( {}, window.tinyMCEPreInit.qtInit[ wpActiveEditor ], { id: editor_id } );
            var qt = quicktags( window.tinyMCEPreInit.qtInit[ editor_id ] );
            QTags._buttonsInit();
        }

    },

    option_onclose_callback: function() {
        // clean tinymce
        if( typeof tinymce !== 'undefined' ) {
            tinymce.remove('.bwpb-tinymce-container textarea');
        }

    },

    switch_editor: function(e) {
        var $container = $(e).closest('.bwpb-tinymce-container');
        var tab = $(e).attr('data-switch');
        window.switchEditors.go( $container.attr('data-editor-id'), tab );
        $container.removeClass('tmce-active html-active').addClass( tab + '-active' );
    }
}

Playouts_Option_Type.option_types.repeater = {

    option_onopen_callback: function( $template, options ) {

        Pl_repeater.start( $template, options );

    },

    option_onclose_callback: function( options ) {

        Pl_repeater.before_save();
        Pl_repeater.save();

    }
}

Playouts_Option_Type.option_types.columns = {

    option_onopen_callback: function( $template, options ) {

        Pl_columns.start( $template, options );

    },

    option_onclose_callback: function( options ) {

        Pl_columns.end();

    }
}

Playouts_Option_Type.option_types.google_font = {

    option_onopen_callback: function( $template, options ) {

        Pl_google_font.start( $template, options );

    },

    option_onclose_callback: function( options ) {

        Pl_google_font.end();

    }
}

var Pl_google_font = {

    start: function( $template, options ) {

        var self = this;

        var $field = $('.bwpc-option-google-font', $template),
            $select = $template.find('.bwpc-font-family');

        // selected
        var current_value = $template.find('.bwpc-font-value').val();

        if( current_value.length ) {
            var current_value_json = JSON.parse( current_value );
            if( typeof current_value_json.family !== 'undefined' ) {
                $select.val( current_value_json.family );
                Pl_google_font.family_changed( $select, $template );
            }
        }

        $select.on( 'change', function() {
            Pl_google_font.family_changed( $(this), $template );
        });

        Pl_google_font.output_font( $template );
        $template.find('select').on('change', function() {
            Pl_google_font.output_font( $template );
        });

    }

    ,family_changed: function( select, $template ) {

        var $selected = select.find('option:selected');
        var $variants = $template.find('.bwpc-font-variants');
        var variants = typeof $selected.attr('data-variants') !== 'undefined' ? $selected.attr('data-variants').split(',') : [];
        var $subsets = $template.find('.bwpc-font-subsets');
        var subsets = typeof $selected.attr('data-subsets') !== 'undefined' ? $selected.attr('data-subsets').split(',') : [];
        var data_value = $template.find('.bwpc-font-value').val();

        if( data_value !== '' ) {
            var current_value = JSON.parse( data_value );
        }

        // variants
        $variants.empty().css('display', 'none');

        if( variants.length > 1 ) {
            var out = '';
            out += '<option value="">Select font variant</option>'; // TODO: translate this via i18n
            for ( i = 0; i < variants.length; i++ ) {
                out += '<option value="' + variants[i] + '" ' + ( ( typeof current_value.variants !== 'undefined' && current_value.variants == variants[i] ) ? 'selected="selected"' : '' ) + '>' + variants[i] + '</option>';
            }
            $( out ).appendTo( $variants.css('display', 'block') );
        }

        // subsets
        $subsets.empty().css('display', 'none');

        if( subsets.length > 1 ) {
            var out = '';
            var currentSubset = [];
            if( typeof current_value.subsets !== 'undefined' ) {
                currentSubset = current_value.subsets.split(',');
            }
            for ( i = 0; i < subsets.length; i++ ) {
                out += '<option value="' + subsets[i] + '" ' + ( ( $.inArray( subsets[i], currentSubset ) >= 0 ) ? 'selected="selected"' : '' ) + '>' + subsets[i] + '</option>';
            }
            $( out ).appendTo( $subsets.css('display', 'block') );
        }

    }

    ,output_font: function( $template ) {

        var variants = '';
        var variants_val = $template.find('.bwpc-font-variants').val();
        if( variants_val !== null && variants_val !== '' ) { variants = ',"variants":"' + variants_val + '"'; }

        var subsets = '';
        var subsets_val = $template.find('.bwpc-font-subsets').val();
        if( subsets_val !== null ) { subsets = ',"subsets":"' + subsets_val + '"'; }

        var font_family = $template.find('.bwpc-font-family').val();

        $template.find('.pl-demo-google-font').css('display', font_family == '' ? 'none' : 'block' );

        var font = '{"family":"' + font_family + '"' + variants + subsets + '}';
        $template.find('.bwpc-font-value').val( font ).trigger('change');

        Pl_google_font.font_demo( $template, font_family, variants_val, subsets_val );

    }

    ,font_demo: function( $template, font_family, variants, subsets ) {

        var variants = ( variants !== null && variants !== '' ) ? ':' + variants : '';
        var subsets = ( subsets !== null && subsets !== '' ) ? '&amp;subset=' + subsets : '';
        var enqueue_url = '//fonts.googleapis.com/css?family=' + font_family.replace(/\s+/g, '+') + variants + subsets;
        var id = $template.find('.bwpc-font-value').attr('data-id');
        var $source = $('.pl-demo-google-source[data-id="' + id + '"]');

        if( $source.length ) {
            $source.remove();
        }
        $('head link[rel="stylesheet"]').last().after( $('<link class="pl-demo-google-source" data-id="' + id + '" href="' + enqueue_url + '" rel="stylesheet">') );

        $template.find('.pl-demo-google-font').css('font-family', font_family);

    }

    ,end: function() {}

}

var Pl_columns = {

    start: function( $template, options ) {

        Pl_columns.bind();

        var col_obj = $.extend( {}, BwpbMapper.get_tree_modules_children_by_id( BwpbMapper.__mapper_tree, Bwpb_settings_panel.panel_edit_id ) );
        var $col_template, col_width;

        // loop the col object and add param values
        for( col in col_obj ) {

            col_obj[col]['col_data'] = BwpbMapper.__mapper_data[ col_obj[col].id ];
            col_width = col_obj[col]['col_data']['params']['col_width']['value'];
            if( ! col_width ) { col_width = 100; }

            $col_template = $( $('#bwpb_template-panel_columns').html() );
            $col_template.css( 'width', col_width + '%' );

            $col_template.attr( 'data-column-width', col_width ).attr( 'data-id', col_obj[col].id );
            $col_template.find('.bwpb-column-label').html( col_width );

            $template.find('.bwpb-option-columns').append( $col_template );

        }

        Pl_columns.draggable();

    },

    bind: function() {

        $('#bwpb-panel-settings').on('click.pl_panel_add_column', '.bwpb-option-add-column', Pl_columns.on_click_add_column);
        $('#bwpb-panel-settings').on('click.pl_panel_remove_column', '.bwpb-option-remove-column', Pl_columns.on_click_remove_column);
        $('#bwpb-panel-settings').on('click.pl_click_column', '.bwpb-option-column', Pl_columns.on_click_column);

    },

    unbind: function() {

        $('#bwpb-panel-settings').off('click.pl_panel_add_column');
        $('#bwpb-panel-settings').off('click.pl_panel_remove_column');
        $('#bwpb-panel-settings').off('click.pl_click_column');

    },

    on_click_column: function() {

        Bwpb_settings_panel.save( false );

        Bwpb_settings_panel.$panel.removeClass('bwpb-done').addClass('bwpb-ajaxing');

        Bwpb_settings_panel.edit_inside( $(this).attr('data-id') );

    },

    on_click_add_column: function(e) {

        e.preventDefault();

        var cols = Pl_columns.crop_column( $('#bwpb-main .bwpb-block[data-id="' + Bwpb_settings_panel.panel_edit_id + '"]'), true );

        Pl_columns.set_panel_columns( cols );

    },

    on_click_remove_column: function(e) {

        e.preventDefault();

        var cols = Pl_columns.crop_column( $('#bwpb-main .bwpb-block[data-id="' + Bwpb_settings_panel.panel_edit_id + '"]'), false );

        Pl_columns.set_panel_columns( cols );

    },

    set_panel_columns: function( cols ) {

        if( typeof cols !== 'undefined' ) {

            var $cols = $('.bwpb-option-columns'), template = $('#bwpb_template-panel_columns').html();

            $('.bwpb-option-columns').empty();

            for( var i = 0; i < cols.length; i++ ) {

                var $template = $( template );
                $template.attr('data-column-width', cols[i]).css('width', cols[i] + '%').find('.bwpb-column-label').html( cols[i] );

                // get the new ids of the columns using the parent id
                var tree_obj_columns = BwpbMapper.get_tree_modules_children_by_id( BwpbMapper.__mapper_tree, Bwpb_settings_panel.panel_edit_id );
                // and set the new ids as data param
                $template.attr('data-id', tree_obj_columns[i].id );

                $cols.append( $template );

            }

            Pl_columns.draggable();
        }

    },

    /*
     * crop column
     *
     */
    crop_column: function( $module, sum ) {

        var column_length = $(' > .bwpb-block-container > .bwpb-content > .bwpb-block', $module).length + ( sum ? +1 : -1 );
        if( column_length > 8 || column_length < 1 ) { return; }
        var col_values = [];
        var col_push = 100 / column_length;

        for( var i = 0; i < column_length; i++ ) {
            col_values.push( Math.floor( col_push * 10 ) / 10 );
        }

        Pl_columns.add_column( col_values.join(','), $module.attr('data-id') );

        return col_values;

    },

    /*
     * add new column
     *
     */
    add_column: function( cols, parent_id ) {

        var new_cols = [];
        var current_column_modules = [];
        var current_column_ids = [];
        var merge_data = {};

        var $row = $('#bwpb-main *[data-id="' + parent_id + '"]');
        var $cols = $row.hasClass('bwpb-module-bw_row') ? $row.find('.bwpb-module-bw_column') : $row.find('.bwpb-module-bw_column_inner');
        cols = cols.split(',');

        // get current cols data
        $cols.each(function() {

            var $oldCol = $(this);
            var current_col_html = $oldCol.hasClass('bwpb-isnt-empty') ? $( '.bwpb-content', $oldCol ).html() : '';
            current_column_modules.push( current_col_html );
            current_column_ids.push( $oldCol.attr('data-id') );

        });
        $cols.remove(); // remove the cols

        for( var i = 0; i < cols.length; i++ ) {
            merge_data = { 'col_width': cols[i] };

            // get current col data and push it to the new col
            // this will save the changes on column switch
            if( typeof current_column_ids[i] !== 'undefined' ) {
                if( typeof BwpbMapper.__mapper_data[ current_column_ids[i] ] !== 'undefined' ) {

                    var col_params = BwpbMapper.__mapper_data[ current_column_ids[i] ].params;
                    for( var param in col_params ) {

                        var col_param_value = col_params[ param ].value;
                        if( typeof col_param_value !== 'undefined' && col_param_value !== '' && param !== 'col_width' ) {
                            merge_data[ param ] = col_param_value;
                        }
                    }
                    // remove the previous column data
                    delete BwpbMapper.__mapper_data[ current_column_ids[i] ];
                }
            }
            new_cols.push( Bwpb.add_module( $cols.attr('data-module'), parent_id, false, merge_data, current_column_ids[i] ) );
        }

        // loop the column modules and place them in the new columns
        for( var i = 0; i < current_column_modules.length; i++ ) {

            if( current_column_modules[i] !== '' ) {

                if( typeof new_cols[i] !== 'undefined' ) {

                    var $module = $('#bwpb-main .bwpb-block[data-id="' + new_cols[i] + '"]').removeClass('bwpb-is-empty').addClass('bwpb-isnt-empty');
                    $(' > .bwpb-block-container > .bwpb-content', $module).html( current_column_modules[i] );

                }else{

                    for( var j = i; j < current_column_modules.length; j++ ) {

                        if( current_column_modules[j] !== '' ) {
                            $('#bwpb-main .bwpb-block[data-id="' + new_cols[i-1] + '"]').removeClass('bwpb-is-empty').addClass('bwpb-isnt-empty').find('.bwpb-content:first').append( current_column_modules[j] );
                        }else{
                            $('#bwpb-main .bwpb-block[data-id="' + new_cols[i-1] + '"]').removeClass('bwpb-isnt-empty').addClass('bwpb-is-empty');
                        }

                    }
                }
            }
        }

        BwpbMapper.map_tree( true ); // re-map tree object
        Bwpb.reload_ui_functions(); // reload  ui

    },

    draggable: function() {

        $('.bwpb-option-column-drag').draggable({
            axis            : 'x',
            handle          : '.bwpb-option-column-dragger',
            containment     : '.bwpb-option-columns',
            stop            : Pl_columns.on_column_stop,
            drag            : Pl_columns.on_column_drag
        });

    },

    /*
     * on change column size
     *
     */
    on_column_stop: function( e, ui ) {

        var $drag_separator = ui.helper;
        var col_values = [];

        $drag_separator.closest('.bwpb-option-columns').find('.bwpb-option-column').each(function() {
            col_values.push( $(this).attr('data-column-width') );
        });

        Pl_columns.add_column( col_values.join(','), Bwpb_settings_panel.panel_edit_id );

        $drag_separator.removeAttr('style');

    },

    /*
     * on drag column separator - change widths percentage
     *
     */
    on_column_drag: function( e, ui ) {

        var $drag_separator = ui.helper,
            $col_left = $drag_separator.closest('.bwpb-option-column'),
            $col_right = $col_left.next();

        var left = parseFloat( ( ui.position.left / $drag_separator.closest('.bwpb-option-columns').width() ) * 100 ).toFixed(1),
            numChange = parseFloat( $col_left.attr('data-column-width') ) - left,
            right = parseFloat( $col_right.attr('data-column-width') ) + numChange,
            change_widths = true;

        //$drag_separator.closest('.bwpb-option-columns').addClass('bwpb-columns-dragging');

        // column widths limits
        if( left < 12.5 || right < 12.5 ) {
            ui.position.left = false;
            change_widths = false;
        }

        if( change_widths ) {

            $('.bwpb-column-label', $col_left).html( left );
            $('.bwpb-column-label', $col_right).html( right.toFixed(1) );

            // change column width
            $col_left.css('width', left + '%');
            $col_left.attr('data-column-width', left);
            $col_right.css('width', right.toFixed(1) + '%');
            $col_right.attr('data-column-width', right.toFixed(1));

        }
    },

    end: function() {

        //$('.bwpb-option-column-drag').draggable('destroy');
        Pl_columns.unbind();

    }

}

/*
 * responsible for the repeater elements
 * repeater elements are used to add multiple elements inside a module
 *
 */
var Pl_repeater = {

    /*
     * the repeater module found in the current panel settings
     *
     */
    current_repeater_module: 0,

    /*
     * the repeater module item found in the current panel settings
     *
     */
    current_repeater_module_item: 0,

    /*
     * start the repeater
     *
     */
    start: function( $template, options ) {

        Pl_repeater.current_repeater_module = BwpbMapper.__mapper_data[ Bwpb_settings_panel.panel_edit_id ].module;
        Pl_repeater.current_repeater_module_item = BwpbMapper.__mapper_data[ Bwpb_settings_panel.panel_edit_id ].module_item;

        Pl_repeater.build_items();

        Pl_repeater.sort(); // make the items sortable

        $template.on('click', '.bwpb-repeater-plus', Pl_repeater.on_click_plus);
        $template.on('click', '.bwpb-item-delete', Pl_repeater.on_click_delete);
        $template.on('click', '.bwpb-item-duplicate', Pl_repeater.on_click_duplicate);
        $template.on('click', '.bwpb-item-edit', Pl_repeater.on_click_edit);

    },

    sort: function() {

        $('.bwpb-repeater-content').sortable({
            axis            : 'y',
            items           : '> .bwpb-item',
            handle          : '.bwpb-item-drag-handle',
            placeholder     : 'bwpb-placeholder-repeater',
            stop            : Pl_repeater.sort_on_stop,
            drag            : Pl_repeater.sort_on_drag
        });

    },

    sort_on_drag: function( e, ui ) {

        ui.item.addClass('bwpb-drag');

    },

    sort_on_stop: function( e, ui ) {

        ui.item.removeClass('bwpb-drag');
        Pl_repeater.re_order_ui_repeaters(e, ui);

    },

    re_order_ui_repeaters: function( e, ui ) {

        var $repeater = ui.item.closest('.bwpb-repeater-content');
        var $repeater_items = $repeater.find(' > .bwpb-item');
        var $repeater_ui = $('.bwpb-block[data-id="' + Bwpb_settings_panel.panel_edit_id + '"]').find('.bwpb-content');
        var $repeater_ui_items = $repeater_ui.find(' > .bwpb-block-repeater-item').detach();

        $repeater_items.each(function() {
            var self = $(this);
            var id = self.attr('data-id');

            var $new_ui_item = $repeater_ui_items.filter(function() {
                return $( this ).attr( 'data-id' ) == id;
            });

            $new_ui_item.appendTo( $repeater_ui );
        });

    },

    /*
     * lets loop the blocks and get the repeater items
     * so we can build the settings panel list of items
     *
     */
    build_items: function() {

        var $items = $('#bwpb-main').find('.bwpb-block-repeater[data-id="' + Bwpb_settings_panel.panel_edit_id + '"] .bwpb-content > .bwpb-block-repeater-item');

        $items.each(function() {

            var self = $(this),
                id = self.attr('data-id');

            Pl_repeater.add_repeater_item( id );

        });

    },

    /*
     * callback on plugin hit
     *
     */
    on_click_plus: function() {

        // create the repeater item
        Pl_repeater.add_repeater_item();

        // this will save the repeater items on every plus click
        //Bwpb_settings_panel.save( false );

    },

    on_click_delete: function() {

        var self = $(this), $item = self.closest('.bwpb-item');

        //$item.remove();
        Bwpb.remove_module( $('#bwpb-main .bwpb-block-repeater-item[data-id="' + $item.attr('data-id') + '"]') );

    },

    on_click_duplicate: function(e) {

        e.preventDefault();

        var self = $(this);
        var $repeater = self.closest('.bwpb-item')
        var id = $repeater.attr('data-id');

        // duplicate ui block
        var $to_duplicate = $('#bwpb-main .bwpb-block-repeater .bwpb-block-repeater-item[data-id="' + id + '"]');
        var $clone = $to_duplicate.clone(); // duplicate as block in html
        var new_id = Bwpb.clone_module_parameters( $clone ); // closed data object for new elements

        $clone.insertAfter( $to_duplicate ); // insert closed module in html

        // duplicate repeater
        var $repeater_clone = $repeater.clone().attr('data-id', new_id);
        $repeater_clone.insertAfter( $repeater );

        //Bwpb.reload(); // reinit items based on editor content
        //Bwpb.reload_ui_functions(); // refresh blocks

    },

    /*module_dusplicate: function(e) {

        e.preventDefault();

        var $to_duplicate = e.closest('.bwpb-block'); // module to duplicate
        var $clone = $to_duplicate.clone(); // duplicate as block in html
        this.clone_module_parameters( $clone ); // closed data object for new elements
        $clone.insertAfter( $to_duplicate ); // insert closed module in html
        this.reload(); // reinit items based on editor content
        this.reload_ui_functions(); // refresh blocks

    },*/

    on_click_edit: function() {

        Bwpb_settings_panel.save( false );

        Bwpb_settings_panel.$panel.removeClass('bwpb-done').addClass('bwpb-ajaxing');

        Bwpb_settings_panel.edit_inside( $(this).closest('.bwpb-item').attr('data-id') );

    },

    /*
     * this will add a new repeater item in the setting panel
     * if we pass id, it wount append it again as shortcode
     *
     */
    add_repeater_item: function( id = false ) {

        var uid;

        var template = $('#bwpb_template-panel_repeater_item').html();
        var $item = $( template );

        if( id ) { // if module exists and we have its id
            uid = id;
            $item.addClass('bwpb-item-exists');
        }else{ // create a new item
            uid = Bwpb.get_unique_id();
            var data = $.extend( {}, Bwpb.all_modules_repeater_item[ Pl_repeater.current_repeater_module_item ] );
            BwpbMapper.map_data( uid, data, {} );
        }

        $item.attr( 'data-id', uid );

        $('.bwpb-repeater-content').append( $item );

        $('.bwpb-option-repeater').removeClass('bwpb-is-empty').addClass('bwpb-isnt-empty');

    },

    /*
     * do something before we save the repeater items
     *
     */
    before_save: function() {

        $('.bwpb-repeater-content').sortable('destroy'); // kill sorting

    },

    /*
     * let process repeater items on hit save button
     *
     */
    save: function() {

        $('.bwpb-repeater-content .bwpb-item').each(function() {

            var self = $(this);
            if( ! self.hasClass('bwpb-item-exists') ) {

                var id = self.attr('data-id');
                Bwpb.add_module( Pl_repeater.current_repeater_module_item, Bwpb_settings_panel.panel_edit_id, false, {}, id );

            }

        });

        // map tree and build shortcodes
        BwpbMapper.map_tree( true );
        // reload ui
        Bwpb.reload_ui_functions();

    }

}

/*
 * all modal functions
 *
 */
var Pl_modal = {

    /*
     * the id of the parent module when expanding the modal
     * so we can know where to push the new element
     *
     */
    module_parent_id: 0,

    /*
     * shall we append the new element on the top
     *
     */
    placement: 'insert_bottom',

    /*
     * jquery object of the modal
     *
     */
    $modal: $('#bwpb-modal'),

    /*
     * set to true if we add new favorite elements
     *
     */
    favorites_has_changed: false,

    /*
     * initiate the modal
     *
     */
    start: function() {

        this.bind();
        this.prepare_tabs();

    },

    prepare_tabs: function() {

        $('.bwpb-modal-tabs li:first-child').trigger('click');
        $('.bwpb-modal-categories li:first-child').trigger('click');


    },

    /*
     * all bindings
     *
     */
    bind: function() {

        $(document).on('click', '#bwpb-main .bwpb-open-modal', Pl_modal.open);
        Pl_modal.$modal.on('click', '.bwpb-button-close', Pl_modal.close);
        Pl_modal.$modal.on('click', '.bwpb-modal-tabs li', Pl_modal.tab_click);
        Pl_modal.$modal.on('click', '.bwpb-modal-categories li', Pl_modal.category_click);
        Pl_modal.$modal.on('click', '.bwpb-button-add', Pl_modal.on_click_favorites_button);
        Pl_modal.bind_elements();
        Pl_modal.bind_favorites();

    },

    bind_elements: function() {

        Pl_modal.$modal.on('click.bwpb_module_click', '.bwpb-modal-modules li', Pl_modal.module_click);
        Pl_modal.$modal.on('click.bwpb_layout_click', '.bwpb-modal-layouts li', Pl_modal.layout_click);

    },

    bind_favorites: function() {

        Pl_modal.$modal.on('click.bwpb_favorite_click', '.bwpb-favorite-list li', Pl_modal.on_favorite_click);

        // run list sortable
        var $list = Pl_modal.$modal.find('.bwpb-favorite-list');
        if( $list.hasClass('ui-sortable') ) {
            $list.sortable('destroy');
        }

    },

    unbind_elements: function() {

        Pl_modal.$modal.off('click.bwpb_module_click');
        Pl_modal.$modal.off('click.bwpb_layout_click');

    },

    unbind_favorites: function() {

        Pl_modal.$modal.off('click.bwpb_favorite_click');

        // remove list sortable
        Pl_modal.$modal.find('.bwpb-favorite-list').sortable({

            items                   : ' > li',
            cursor                  : 'move',
            placeholder             : 'bwpb-placeholder-favorite',
            axis                    : 'y',
            start                   : Pl_modal.on_drag_start,
            stop                    : Pl_modal.on_drag_stop,

        });

    },

    on_drag_start: function( e, ui ) {

        ui.item.addClass('bwpb-drag');

    },

    on_drag_stop: function( e, ui ) {

        Pl_modal.favorites_has_changed = true;

        ui.item.removeClass('bwpb-drag');

    },

    on_click_favorites_button: function() {

        var $button = Pl_modal.$modal.find('.bwpb-button-add'),
            class_active = 'bwpb-favorite-active';

        Pl_modal.$modal.toggleClass( class_active );

        if( Pl_modal.$modal.hasClass( class_active ) ) {
            $button.html( $button.attr('data-label-save') );
            Pl_modal.unbind_elements();
            Pl_modal.$modal.on('click.bwpb_add_favorite', '.bwpb-modal-elements li', Pl_modal.on_click_add_favorite);
            Pl_modal.unbind_favorites();
        }else{
            $button.html( $button.attr('data-label-manage') );
            Pl_modal.bind_elements();
            Pl_modal.$modal.off('click.bwpb_add_favorite');
            Pl_modal.favorites_save();
            Pl_modal.bind_favorites();
        }

    },

    favorites_save: function() {

        if( Pl_modal.favorites_has_changed ) {

            var data = [];

            $('#bwpb-favorites .bwpb-favorite-list li').each(function() {
                var self = $(this);
                data.push( {'id': self.attr('data-id'), 'label': self.html()} );
            });

            $.ajax({
                type: 'POST',
                url: bwpb_admin_root.ajax,
                dataType: 'json',
                data: {
                    action        : '__save_favorites',
                    security      : window.bwpb_data.security.save_favorites,
                    favorites     : data
                },
                beforeSend: function () {

                },
                success: function( response ) {

                }
            });

            Pl_modal.favorites_has_changed = false;

        }

    },

    on_click_add_favorite: function() {

        var self = $(this),
            $favorites = $('#bwpb-favorites .bwpb-favorite-list'),
            id = self.attr('data-id');

        Pl_modal.favorites_has_changed = true;

        self.toggleClass('bwpb-is-favorite');

        if( self.hasClass('bwpb-is-favorite') ) {

            var $fav = $('<li></li>');
            $fav.attr('data-id', id).html( self.find('span').html() );
            $favorites.prepend( $fav );
            $favorites.removeClass('bwpb-empty');

        }else{

            $favorites.find('li[data-id="' + id + '"]').remove();
            if( $favorites.find('li').length <= 0 ) {
                $favorites.addClass('bwpb-empty');
            }

        }

    },

    tab_click: function() {

        var self = $(this);

        $('.bwpb-modal-tabs li').removeClass('bwpb-tab-active');
        self.addClass('bwpb-tab-active');

        $('.bwpb-tab-content').removeClass('bwpb-tab-active');
        $('.bwpb-tab-content.bwpb-tab-content-' + self.attr('data-tab')).addClass('bwpb-tab-active');

    },

    category_click: function() {

        var self = $(this),
            $tab_list = self.closest('ul'),
            $tab_content = self.closest('.bwpb-tab-content');

        $('li', $tab_list).removeClass('bwpb-category-active');
        self.addClass('bwpb-category-active');

        $('.bwpb-modal-elements li', $tab_content).css('display', 'none');
        if( self.attr('data-category') == '*' ) {
            $('.bwpb-modal-elements li', $tab_content).css('display', 'inline-block');
        }else{
            if( $tab_content.find('.bwpb-modal-categories').hasClass('bwpb-modal-multiple-categories') ) {
                $('.bwpb-modal-elements li[data-category*="%' + self.attr('data-category') + '%"]', $tab_content).css('display', 'inline-block');
            }else{
                $('.bwpb-modal-elements li[data-category="' + self.attr('data-category') + '"]', $tab_content).css('display', 'inline-block');
            }
        }

    },

    /*
     * reload the categories, in case that someone added
     * a new custom category
     *
     */
    reload_custom_layout_categories: function() {

        var _list = '<li data-category="*">' + window.bwpb_data.i18n.all + '</li>';

        for ( var id in window.bwpb_data.map_custom_layout_categories ) {
            _list += '<li data-category="' + id + '">' + window.bwpb_data.map_custom_layout_categories[ id ] + '</li>';
        }

        $('.bwpb-tab-content-custom_layouts .bwpb-modal-categories').html( _list ).find('li:first').trigger('click');

    },

    reload_custom_layouts: function() {

        var _list = '';

        for ( var id in window.bwpb_data.map_custom_layouts ) {
            _list = '<li data-layout-id="' + id + '"' +
                'data-view="' + window.bwpb_data.map_custom_layouts[id].view + '"' +
                'data-category="' + window.bwpb_data.map_custom_layouts[id].category + '"' +
                'data-id="custom-layout-' + id + '">' +
                    '<div class="bwpb-element">' +
                        '<div class="bwpb-element-image">' +
                            '<img src="' + window.bwpb_data.path_assets + 'admin/images/default-layout.png" alt="">' +
                        '</div>' +
                        '<span>' + window.bwpb_data.map_custom_layouts[id].name + '</span>' +
                    '</div>' +
            '</li>' + _list;
        }

        $('.bwpb-tab-content-custom_layouts .bwpb-modal-elements').html( _list );

    },

    /*
     * here we can add some logics before opening the modal
     * like re-arranging the modules
     *
     */
    check_dependencies: function( self ) {

        if( typeof self.attr('data-view') == 'undefined' ) { return; }

        var view_request = self.attr('data-view');
        var visible_views = window.bwpb_data.module_dependencies[ view_request ];
        var $modules = $('#bwpb-modal .bwpb-modal-modules');

        if( view_request == 'row' ) { view_request = '__solo'; }

        if( view_request == '__solo' ) { // display all the modules if __solo

            $modules.find('li').removeClass('bwpb-module-hidden');

        }else{ // display modules with the currect view

            $modules.find('li').addClass('bwpb-module-hidden');

            for( var i = 0; i < visible_views.length; i++ ) {

                $modules.find('li[data-view="' + visible_views[i] + '"]').removeClass('bwpb-module-hidden');

            }

        }

    },

    /*
     * filter the layouts, depending on the place to append
     *
     */
    check_dependencies_layouts: function( self ) {

        if( typeof self.attr('data-view') == 'undefined' ) { return; }

        var view_request = self.attr('data-view');
        var visible_views = window.bwpb_data.module_dependencies[ view_request ];
        var $layouts = $('#bwpb-modal .bwpb-modal-layouts');

        if( view_request == 'row' ) { view_request = '__solo'; }

        if( view_request == '__solo' ) { // display all the layouts if __solo

            $layouts.find('li').addClass('bwpb-layout-hidden');
            $layouts.find('li[data-view="row"]').removeClass('bwpb-layout-hidden');

        }else{ // display layouts with matching view

            $layouts.find('li').addClass('bwpb-layout-hidden');

            for( var i = 0; i < visible_views.length; i++ ) {

                $layouts.find('li[data-view="' + visible_views[i] + '"]').removeClass('bwpb-layout-hidden');

            }

        }

    },

    /*
     * expand the modal
     *
     */
    open: function(e) {

        e.preventDefault();

        var self = $(this);
        var $parentBlock = self.closest('.bwpb-block');

        Pl_modal.check_dependencies( self );
        Pl_modal.check_dependencies_layouts( self );

        // set the placement on modal open
        if( typeof self.attr('data-placement') !== 'undefined' ) {
            Pl_modal.placement = self.attr('data-placement');
        }

        // close any panel
        Bwpb_settings_panel.close();

        // set modal id
        Pl_modal.module_parent_id = $parentBlock.length ? $parentBlock.attr('data-id') : 0;

        $('#bwpb-overlay').css({'visibility':'visible', 'opacity':1});
        Pl_modal.$modal.addClass('bwpb-modal-open');

        // bind esc
        $(document).on('keyup', Pl_modal.on_modal_escape);


    },

    on_modal_escape: function(e) {
        if( e.keyCode == 27 ) {
            Pl_modal.close();
        }
    },

    /*
     * close the modal
     *
     */
    close: function() {

        Pl_modal.$modal.removeClass('bwpb-modal-open');
        $('#bwpb-overlay').css({'visibility':'hidden', 'opacity':0});

        // bind esc
        $(document).off('keyup.pl_modal_escape');

    },

    /*
     * here we will append the new element to the ui, on module click
     *
     */
    module_click: function() {

        var self = $(this);

        // close modal
        Pl_modal.close();

        Bwpb.added_manually = true;

        var module = self.attr('data-module');

        var uid = Bwpb.add_module( module, Pl_modal.module_parent_id, true, false );

        // map tree
        BwpbMapper.map_tree( true );

        // open settings on create
        var openOnCreate = BwpbMapper.__mapper_data[ uid ].open_settings_on_create;
        if( openOnCreate !== 'undefined' && openOnCreate === true ) {
            Bwpb_settings_panel.open( uid );
        }

        Bwpb.reload_ui_functions();

        Bwpb.added_manually = false;

    },

    layout_click: function() {

        var self = $(this);

        if( self.hasClass('bwpb-layout-hidden') ) { return; }

        // close modal
        Pl_modal.close();

        var layout = self.attr('data-layout');

        // built-in layouts
        if( typeof layout !== 'undefined' ) {
            BwpbLayouts.push_layout( window.bwpb_data.map_layouts[ layout ], Pl_modal.module_parent_id );
        }
        // custom layouts
        else{
            var custom_layout_id = self.attr('data-layout-id');
            BwpbLayouts.push_layout( window.bwpb_data.map_custom_layouts[ custom_layout_id ].content, Pl_modal.module_parent_id, self.attr('data-view') );
        }

    },

    on_favorite_click: function() {

        var id = $(this).attr('data-id');

        $('#bwpb-modal .bwpb-modal-elements li[data-id="' + id + '"]').trigger('click');

    }

}

var Bwpb_sort = {

    start: function() {

        this.sort.rows.start();
        this.sort.blocks.start();
        this.sort.columns.start();

    },

    /*
     *
     *
     */
    update: function( e, ui ) {

        Bwpb.reload(); // re-build the modules based on editor's content

    },

    receive: function( e, ui ) {

        var self = $(this);
        self.closest('.bwpb-block').removeClass('bwpb-is-empty').addClass('bwpb-isnt-empty');

    },

    remove: function( e, ui ) {

        var self = $(this);
        if( ! self.find('.bwpb-block').length ) {
            self.closest('.bwpb-block').removeClass('bwpb-isnt-empty').addClass('bwpb-is-empty');
        }

    },

    sort: {

        rows: {

            start: function() {

                $( "#bwpb-main .bwpb-blocks" ).sortable({

                    items                   : ' > .bwpb-block',
                    connectWith             : '.bwpb-row-content',
                    cursor                  : 'move',
                    cursorAt                : { left: 15, top: 17 },
                    handle                  : '.bwpb-drag:first',
                    placeholder             : 'bwpb-placeholder-row',
                    distance                : 15,
                    update                  : Bwpb_sort.update,
                    start                   : function( e, ui ) { ui.item.toggleClass('bwpb-drag'); },
                    stop                    : function( e, ui ) { ui.item.toggleClass('bwpb-drag'); }

                });

            },

        },

        blocks: {

            blocks_element: '#bwpb-main .block-column > .bwpb-block-container > .bwpb-content',

            start: function() {

                var __modules = $( Bwpb_sort.sort.blocks.blocks_element );

                __modules.sortable({

                    items                   : '> .bwpb-separator-block, > .bwpb-block-draggable, > .bwpb-module-bw_row_inner',
                    connectWith             : Bwpb_sort.sort.blocks.blocks_element,
                    cursor                  : 'move',
                    cursorAt                : { left: 15, top: 17 },
                    forcePlaceholderSize    : true,
                    placeholder             : 'bwpb-placeholder-block',
                    distance                : 15,
                    update                  : Bwpb_sort.update,
                    receive                 : Bwpb_sort.receive,
                    remove                  : Bwpb_sort.remove,
                    tolerance               : 'pointer',
                    start                   : Bwpb_sort.sort.blocks.on_blocks_start,
                    stop                    : Bwpb_sort.sort.blocks.on_blocks_stop,

                });
            },

            on_blocks_start: function( e, ui ) {

                var __modules = $( Bwpb_sort.sort.blocks.blocks_element );

                ui.item.addClass('bwpb-drag'); // convert to edit block
                ui.item.closest('.bwpb-block.block-row').addClass('bwpb-block-dragging');

                if ( ui.item.hasClass('bwpb-module-bw_row_inner') ) { // inner row
                    __modules.sortable('option', 'connectWith', '.bwpb-module-bw_column > .bwpb-block-container > .bwpb-content');
                    __modules.sortable('refresh');
                }
            },

            on_blocks_stop: function( e, ui ) {

                var __modules = $( Bwpb_sort.sort.blocks.blocks_element );

                ui.item.removeClass('bwpb-drag'); // removes the edit block style
                ui.item.closest('.bwpb-block.block-row').removeClass('bwpb-block-dragging');

                if ( ui.item.hasClass('bwpb-module-bw_row_inner') ) { // inner row
                    __modules.sortable('option', 'connectWith', connections);
                    __modules.sortable('refresh');
                }
            }
        },

        columns: {

            start: function() {

                $('.bwpb-column-drag').draggable({
                    axis: 'x',
                    handle: '.bwpb-col-drag-handle',
                    containment: '.bwpb-content',
                    start: Bwpb_sort.sort.columns.on_column_start,
                    stop: Bwpb_sort.sort.columns.on_column_stop,
                    drag: Bwpb_sort.sort.columns.on_column_drag
                });

            },

            on_column_start: function( e, ui ) {
                ui.helper.closest('.block-row').addClass('bwpb-column-dragging');
            },

            /*
             * on change column size
             *
             */
            on_column_stop: function( e, ui ) {

                var $drag_separator = ui.helper,
                    $row = $drag_separator.closest('.block-row');

                $row.removeClass('bwpb-column-dragging');

                var col_values = [];
                $(' > .bwpb-block-container > .bwpb-content > .bwpb-block', $row).each(function() {
                    col_values.push( $(' > .bwpb-column-width > .bwpb-col-width-label em', this).html() );
                });
                Pl_columns.add_column( col_values.join(','), $row.attr('data-id') );

            },

            /*
             * on drag column separator - change widths percentage
             *
             */
            on_column_drag: function( e, ui ) {

                var $drag_separator = ui.helper,
                    $col_left = $drag_separator.closest('.block-column'),
                    $col_right = $col_left.next();

                var left = parseFloat( ( ui.position.left / $drag_separator.closest('.bwpb-content').width() ) * 100 ).toFixed(1),
                    numChange = parseFloat( $col_left.attr('data-col-width') ) - left,
                    right = parseFloat( $col_right.attr('data-col-width') ) + numChange,
                    change_widths = true;

                // column widths limits
                if( left < 12.5 || right < 12.5 ) {
                    ui.position.left = false;
                    change_widths = false;
                }

                if( change_widths ) {

                    $('> .bwpb-column-width > .bwpb-col-width-label em', $col_left).html( left );
                    $('> .bwpb-column-width > .bwpb-col-width-label em', $col_right).html( right.toFixed(1) );

                    // change column width
                    $col_left.css('width', left + '%');
                    $col_right.css('width', right.toFixed(1) + '%');

                }
            }
        }
    }
}

var Pl_guide = {

    start: function() {

        if( ! $('#pl-guide').length ) { return; }

        Pl_guide.bind();
        //Pl_guide.hash_active();

        Pl_guide.section_layout_settings();

    },

    section_layout_settings: function() {

        if( $('.bwpb-panel-form').length ) {
            Pl_guide.render_options();
        }

    },

    render_options: function() {

        var _option_type;

        $('.plg-layouts-options .bwpb-panel-row').each(function() {

            _option_type = $(this).attr('data-type');

            if( typeof Playouts_Option_Type.option_types[ _option_type ] !== 'undefined' ) {

                Playouts_Option_Type.option_types[ _option_type ].option_onopen_callback( $(this), [] );

            }

        });

        //var layouts_options_arr = $.parseJSON( window.bwpb_data.layouts_options ), param;
        /*var option_type_callbacks = {}; // holds the option type callbacks, we will call them after at the end when all options were loaded.

        for( param in layouts_options_arr ) {

            //var data = BwpbMapper.__mapper_data[ uid ];
            var _option_type = layouts_options_arr[ param ].type;
            var _template = $( '#bwpb-template-option-' + _option_type ).html();

            if( typeof _template !== 'undefined' ) {

                var options = $.extend( {}, layouts_options_arr[ param ], { 'name' : param } );
                var handlebars_template = Handlebars.compile( _template );
                var $template = $( handlebars_template( options ) );

                option_type_callbacks[ _option_type ] = options;

                $('.plg-layouts-options').append( $template );

                // check if the option type has a callback
                if( typeof Playouts_Option_Type.option_types[ _option_type ] !== 'undefined' ) {

                    // now we can run the option type callbacks
                    Playouts_Option_Type.option_types[ _option_type ].option_onopen_callback( $template, options );

                }
            }
        }*/

        // bind info icon
        $('.plg-layouts-options').on('click.bwpb_panel_info_click', '.bwpb-icon-info', Bwpb_settings_panel.on_click_info_icon);

        /*
         * run dependencies.
         * create dependency when one option depends on other option's value,
         *
         */
        Bwpb_dependencies.create_deps( 'playouts_options' );

    },

    bind: function() {

        $('#pl-guide').on( 'click', '.plg-tabs-list li', Pl_guide.on_tab_click );
        $(document).on( 'click', '#plg-do-layouts-settings-save', Pl_guide.layout_settings_save );

    },

    layout_settings_save: function(e) {

        e.preventDefault();

        var $form = $( '#plg-layouts-options-general, #plg-layouts-options-fonts' ), data = '';

        data += $form.serialize();
        data += '&action=__save_layout_options';
        data += '&security=' + bwpb_data.security.save_layout_options;

        $.ajax({
            type: 'POST',
            url: bwpb_admin_root.ajax,
            dataType: 'json',
            data: data,
            beforeSend: function () {
                $('#pl-guide').addClass('plg-ajaxing');
            },
            success: function( response ) {
                $('#pl-guide').removeClass('plg-ajaxing');
            }
        });

    },

    /*tab_message_in: function() {
        $('#pl-guide-message-codes').addClass('plg-visible');
    },

    tab_message_out: function() {
        $('#pl-guide-message-codes').removeClass('plg-visible');
    },*/

    /*hash_active: function() {
        var hash = window.location.hash;
        if( hash ) {
            $('#pl-guide .plg-tabs-list > li[data-hash="' + hash.replace('#', '') + '"]').trigger('click');
        }
    },*/

    on_tab_click: function() {

        var self = $(this);

        $('#pl-guide .plg-tabs-list > li').removeClass('plg-active');
        self.addClass('plg-active');

        var index = $('#pl-guide .plg-tabs-list > li').index(self);
        $('#pl-guide .plg-tabs-content > li').removeClass('plg-active').eq(index).addClass('plg-active');

        /*var hash = self.attr('data-hash');
        if( typeof hash !== 'undefined' ) {
            window.location.hash = hash;
        }*/

    }
}

/*
 * main object to start the main functions
 *
 */
var Bwpb = {

    /*
     *
     *
     */
    all_modules: $.parseJSON( window.bwpb_data.map ),

    /*
     *
     *
     */
    all_modules_repeater: $.parseJSON( window.bwpb_data.map_repeater ),

    /*
     *
     *
     */
    all_modules_repeater_item: $.parseJSON( window.bwpb_data.map_repeater_item ),

    /*
     * the id of the last module added
     *
     */
    latest_element_id: 0,

    /*
     * the id of the last row added
     *
     */
    latest_row_id: 0,

    /*
     * the id of the last column added
     *
     */
    latest_col_id: 0,

    /*
     * if the element was added by the modal or generated by other function
     *
     */
    added_manually: false,

    /*
     * start the plugin
     *
     */
    start: function() {

        this.on_ready();
        this.bind();

        // fire on edit screen only
        if( window.bwpb_data.screen_edit ) {
            this.status_check();
        }

        Bwpb_settings_panel.start();
        Bwpb_custom_css_panel.start();
        Pl_modal.start();

    },

    /*
     * take action on status
     *
     */
    status_check: function() {
        // custom layouts edit screen
        if( typeof window.typenow !== 'undefined' && window.typenow == 'pl_layout' ) {
            this.status_enable();
        }
        // standard post type edit screen
        else{
            bwpb_data.status ? this.status_enable() : this.status_disable();
        }
    },

    /*
     * enable
     *
     */
    status_enable: function() {

        Bwpb.welcome.hide();

        // toggle the switch button
        $('#bwpb-switch-button').removeClass('bw-switch-active');

        // hide the editor if enabled
        if( $('#bwpb-main').hasClass('bwpb-editor-hidden') ) {
            $('#postdivrich').css('display', 'none');
        }

        // show the plugin postbox
        $('#peenapo_layouts_section_ui').css('display', 'block');

        // set the hidden field value to true, so we can save it on post update
        $('#bwpb_status').val(1);

        Bwpb.reload_module_ui();

    },

    /*
     * disable
     *
     */
    status_disable: function() {

        // toggle the switch button
        $('#bwpb-switch-button').addClass('bw-switch-active');

        // show the editor if enabled
        if( $('#bwpb-main').hasClass('bwpb-editor-hidden') ) {
            $('#postdivrich').css('display', 'block');
        }

        // hide the plugin postbox
        $('#peenapo_layouts_section_ui').css('display', 'none');

        // set the hidden field value to none, so we can save it on post update
        $('#bwpb_status').val('');
    },

    /*
     * manage the welcome message
     *
     */
    welcome: {

        // check if there are any elements in our module ui
        check: function() {
            BwpbMapper.__mapper_tree.length === 0 ? Bwpb.welcome.show() : Bwpb.welcome.hide();
        },

        // display the welcome message
        show: function() {
            $('#bwpb-welcome-add').css('display', 'block');
        },

        // hide the welcome message
        hide: function() {
            $('#bwpb-welcome-add').css('display', 'none');
        }

    },

    /*
     * empty objects, get the current editors content and re-build the ui
     *
     */
    reload_module_ui: function() {

        // empty latest ids
        Bwpb.clear_ids();
        // empty the mapping objects
        BwpbMapper.__clear_mapper();
        // empty the ui modules
        BwpbInterface.empty_modules();
        // parse the editors content and get the new info
        BwpbInterface.parse();

    },

    /*
     * reload all
     *
     */
    reload: function() {

        // clear the mapped tree data
        BwpbMapper.__clear_mapper_tree();
        // and build it again based on the blocks
        BwpbMapper.parse_modules_and_build_tree( $('#bwpb-main .bwpb-blocks'), false );
        // get the new shortcode and insert it to the editor
        BwpbShortcoder.append_shortcodes( BwpbShortcoder.reload_shortcodes_and_push( BwpbMapper.__mapper_tree, false ) );

    },

    /*
     * reload all the ui stuff
     *
     */
    reload_ui_functions: function() {

        Bwpb_sort.start();
        Bwpb.block_hovers();

    },

    block_hovers: function() {

        $('.block-row').off('mouseenter')
        .on('mouseenter', function(e) {

            var $block = $(this);

            if( $block.hasClass('block-row-inner') ) {
                //$block.closest('.bwpb-module-bw_row').trigger('mouseleave');
                $block.closest('.bwpb-module-bw_row').removeClass('bwpb-hover');
            }

            $block.addClass('bwpb-hover');

            return;

        }).off('mouseleave').on('mouseleave', function() {

            var $block = $(this);

            if( $block.hasClass('block-row-inner') ) {
                $block.closest('.bwpb-module-bw_row').addClass('bwpb-hover');
            }

            $block.removeClass('bwpb-hover');

            return;

        });

    },

    /*
     * the HTML document has been loaded
     *
     */
    on_ready: function() {

        $(document).ready(function() {

            Pl_guide.start();

        });

    },

    /*
     * clear the latest modules ids
     *
     */
    clear_ids: function() {
        Bwpb.latest_element_id = 0;
        Bwpb.latest_row_id = 0;
        Bwpb.latest_col_id = 0;
    },

    escape_param: function( value ) {
        if( typeof value == 'string') {
            return value.replace(/"/g, '``');
        }
        return value;
    },

    unescape_param: function( value ) {
        return value.replace(/(\`{2})/g, '"');
    },

    /*
     * set binds
     *
     */
    bind: function() {

        var $main = $('#bwpb-main');

        $main.on('click', 'a[href="#"]',                    Bwpb.on_click_empty_url); // disable empty urls
        $main.on('click', '.bwpb-blocks .bwpb-cut',         Bwpb.on_click_crop_column); // crop column
        $main.on('click', '.bwpb-blocks .bwpb-trash',       Bwpb.on_click_remove_module); // remove module
        $main.on('click', '.bwpb-blocks .bwpb-visibility',  Bwpb.on_click_module_visibility); // toggle module visibility
        $main.on('click', '.bwpb-blocks .bwpb-duplicate',   Bwpb.on_click_module_duplicate); // duplicate module
        $main.on('click', '.bwpb-empty-content',            Bwpb.on_click_empty_all_content); // remove all the modules and empty the page
        $('#bwpb-switch-button').on('click',                Bwpb.on_click_switch_button); // toggle the switch button
        $('#bwpb-overlay').on('click',                      Bwpb.on_click_overlay); // close any modals

    },

    /*
     * on overlay click - close any modals
     *
     */
    on_click_overlay: function() {
        Pl_modal.close();
        Bwpb_settings_panel.close();
        Bwpb_custom_css_panel.close();
    },

    /*
     * disable empty urls
     *
     */
    on_click_empty_url: function(e) {
        e.preventDefault();
    },

    /*
     * remove column
     *
     */
    on_click_remove_column: function() {
        Pl_columns.crop_column( $(this).closest('.block-row'), false );
    },

    /*
     * crop column
     *
     */
    on_click_crop_column: function() {
        Pl_columns.crop_column( $(this).closest('.bwpb-block'), true );
    },

    /*
     * remove module
     *
     */
    on_click_remove_module: function() {
        if( $(this).closest('.bwpb-block').hasClass('block-column') ) {
            return;
        }
        Bwpb.remove_module( $(this) );
    },

    /*
     * toggle module visibility
     *
     */
    on_click_module_visibility: function() {
        Bwpb.module_visibility( $(this) );
    },

    /*
     * duplicate module
     *
     */
    on_click_module_duplicate: function() {
        Bwpb.module_dusplicate( $(this) );
    },

    /*
     * confirm actions amd remove all the modules
     *
     */
    on_click_empty_all_content: function() {

        Bwpb.confirm({
            title: window.bwpb_data.i18n.confirm_empty_title,
            description: window.bwpb_data.i18n.confirm_empty_description,
            callback: Bwpb.empty_ui
        });

        //console.log( 111 );

        //$(document).on('keyup.pl_confirm_enter', Bwpb.on_confirm_enter);
        //$(document).on('keyup.pl_confirm_escape', Bwpb.on_confirm_escape);

    },

    /*on_confirm_enter: function(e) {
        if( e.keyCode == 13 ) {
            $('.bwpb-prompt-button-confirm').trigger('click');
        }
    },

    on_confirm_escape: function(e) {
        if( e.keyCode == 27 ) {
            $('.bwpb-prompt-close').trigger('click');
        }
    },*/

    /*
     * custom confirm dialog
     *
     */
    confirm: function( args ) {

        BwpbPrompt.prompt_open( 'confirm' );

        var $confirm = $('.bwpb-prompt-confirm');

        $confirm.find('.bwpb-panel-title').html( args.title );
        $confirm.find('.bwpb-panel-row-inner').html( args.description );

        $('.bwpb-prompt-confirm').on('click.pl_prompt_confirm', '.bwpb-prompt-button-confirm', args.callback);

    },

    /*
     * remove all modules and empty objects
     *
     */
    empty_ui: function() {

        BwpbInterface.empty_modules(); // empty ui html
        BwpbMapper.__clear_mapper(); // clear mapper objects
        BwpbShortcoder.set_editor_content(''); // empty the editor
        Bwpb.welcome.show(); // show welcome message
        BwpbPrompt.close(); // close the prompt if any

    },

    /*
     * toggle the switch button
     *
     */
    on_click_switch_button: function() {
        $(this).hasClass('bw-switch-active') ? Bwpb.status_enable() : Bwpb.status_disable();
    },

    /*
     * enable ui preloader
     *
     */
    loading: function() {
        $('#bwpb-main').addClass('bwpb-main-ajaxing');
    },

    /*
     * remove ui preloader
     *
     */
    loaded: function() {
        $('#bwpb-main').removeClass('bwpb-main-ajaxing');
    },

    /*
     * change the is_hidden parameter of a row
     * so we can hide it in the front-end
     *
     */
    module_visibility: function(e) {

        var $row = e.closest('.block-row');
        $row.toggleClass('bwpb-row-hidden');

        // update the visibility param of the clicked row
        BwpbMapper.update_mapper_module_options( $row.attr('data-id'), 'is_hidden', $row.hasClass('bwpb-row-hidden') ? 'true' : '' );

        // flush the shortcodes
        BwpbShortcoder.reload_shortcodes_and_push( BwpbMapper.__mapper_tree, true );

    },

    /*
     * duplicate modules
     *
     */
    module_dusplicate: function( self ) {

        var $to_duplicate = self.closest('.bwpb-block'); // module to duplicate
        var $clone = $to_duplicate.clone(); // duplicate as block in html
        this.clone_module_parameters( $clone ); // closed data object for new elements
        $clone.insertAfter( $to_duplicate ); // insert closed module in html
        this.reload(); // reinit items based on editor content
        this.reload_ui_functions(); // refresh blocks

    },

    /*
     * clone the mapped data from the cloned module so we can get the same options
     * note: the $close param must have the same id as the cloned element
     *
     * $clone: ui clone jquery object
     *
     */
    clone_module_parameters: function( $clone ) {

        var self = this;

        var owner_id = $clone.attr( 'data-id' ); // the id of the cloned module
        var new_id = this.get_unique_id(); // get a new id for the new module

        // we will clone the owner object options, so we can get the same option values
        var cloned_map_object = $.extend( true, {}, BwpbMapper.__mapper_data[ owner_id ] );

        BwpbMapper.__mapper_data[ new_id ] = cloned_map_object;
        $clone.attr( 'data-id', new_id );

        if( $( '*[data-id]', $clone ) ) {
            $( '*[data-id]', $clone ).each(function() {
                self.clone_module_parameters( $(this) );
            });
        }

        return new_id;

    },

    /*
     * remove module from the ui
     *
     */
    remove_module: function( self ) {

        Bwpb.confirm({
            title: window.bwpb_data.i18n.confirm_delete_title,
            description: window.bwpb_data.i18n.confirm_delete_description,
            callback: function() {
                Bwpb.remove_module_callback( self );
            }
        });

        //$(document).on('keyup.pl_confirm_enter', Bwpb.on_confirm_enter);
        //$(document).on('keyup.pl_confirm_escape', Bwpb.on_confirm_escape);

    },

    remove_module_callback: function( self ) {

        var item_id = self.attr('data-id');

        var $column = self.closest('.block-column');

        // remove repeater item, if any
        if( $('#bwpb-panel-settings .bwpb-repeater-content').length ) {
            $('#bwpb-panel-settings .bwpb-repeater-content .bwpb-item[data-id="' + item_id + '"]').remove();
        }

        self.closest('.bwpb-block').remove(); // remove as block in ui
        this.reload(); // reload all
        this.reload_ui_functions(); // reload ui functions as sorting
        this.welcome.check(); // check if welcome message is needed
        BwpbPrompt.close(); // close the prompt if any

        // set empty class
        if( $column.find(' > .bwpb-block-container > .bwpb-content > .bwpb-block').length == 0 ) {
            $column.removeClass('bwpb-isnt-empty').addClass('bwpb-is-empty');
        }

    },

    /*
     * check if specific module exists
     *
     */
    module_exists: function( module ) {
        return typeof Bwpb.all_modules[ module ] === 'object';
    },

    /*
     * add new module
     *
     * module: module id
     * parent_id: add parent id to place the new module inside another
     * auto_place_modules:
     * merge_data: pass parameters to inherit
     *
     */
    add_module: function( module, parent_id, auto_place_modules, merge_data, id = false ) {

        if( Bwpb.module_exists( module ) ) { // check if the module exists
            var uid = id ? id : Bwpb.get_unique_id(); // the of the module id
            var data = $.extend( true, {}, Bwpb.all_modules[ module ] );
            BwpbMapper.map_data( uid, data, merge_data ); // add the new object to the map
            Bwpb.create_element( uid, module, parent_id, auto_place_modules, merge_data ); // create the element

            return uid;

        }else{ Bwpb.notify( 'module_no_template', module ); }

    },

    /*
     * creates element
     *
     * uid: unique id
     * module: module id
     * parent_id: the id of the parent module
     * auto_place_modules: in same cases a module can't go solo, like the columns,
     * if auto_place_modules is set to true, it will automatically add the parent module before
     * merge_data: parameters to inherit
     *
     */
    create_element: function( uid, module_id, parent_id, auto_place_modules, merge_data ) {

        Bwpb.before_create_element(); // do something before creating the element

        var data, view, _module;

        // if repeater
        /*if( typeof Pl_repeater.all_repeaters[ module_id ] !== 'undefined' ) {
            data = $.extend( true, {}, Pl_repeater.all_repeaters[ module_id ] );
            view = 'repeater_item';
            _module = data.repeater_module;
        }else{ // not repeater
            data = $.extend( true, {}, Bwpb.all_modules[ module_id ] ); // clone module data
            view = ( typeof data.view !== 'undefined' ) ? data.view : 'element'; // the view of the current module
            _module = data.module;
        }*/

        data = $.extend( true, {}, Bwpb.all_modules[ module_id ] ); // clone module data
        view = ( typeof data.view !== 'undefined' ) ? data.view : 'element'; // the view of the current module
        _module = data.module;

        // if template does not exists
        if( ! $( '#bwpb_template-' + view ).length ) {
            Bwpb.notify( 'template_not_found', view );
            return;
        }

        // merge the extra parameters
        if( merge_data ) {
            BwpbMapper.sync_object_data( data, merge_data );
        }

        Bwpb.set_latest_ids( uid, view ); // set the latest ids

        // get module template and convert to jquery obj
        var __module = $( $( '#bwpb_template-' + view ).html() );

        // add params, classes, set labels
        __module.attr( 'data-id', uid ).find('.just-edit .bwpb-label').html( data.name );
        __module.attr( 'data-module', _module );
        __module.addClass('bwpb-module-' + _module);

        /*
         * do stuff based on module_id or view
         *
         */
        if( view == 'row' || view == 'row_inner' ) {
            if( typeof merge_data.is_hidden !== 'undefined' && Boolean( merge_data.is_hidden ) == true ) {
                __module.addClass('bwpb-row-hidden');
            }
        }
        if( view == 'column' || view == 'column_inner' ) {

            var col_width_value = data.params.col_width.value;

            __module.css( 'width', col_width_value + '%' );
            __module.attr( 'data-col-width', col_width_value );

            // add percent
            $('> .bwpb-column-width em', __module).html( col_width_value );
        }

        if( ( Pl_modal.placement == 'before' || Pl_modal.placement == 'after' ) && module_id !== 'bw_row' ) { // content elements without parent
            if( ! this.added_manually ) { // manually added
                parent_id = this.latest_element_id;
            }else{ // not manually added element without row
                var auto_id = Bwpb.get_unique_id();
                this.add_module( 'bw_row', parent_id, auto_place_modules, false, auto_id );
                parent_id = this.latest_col_id;
            }
        }

        // place the element in the ui
        Bwpb.place_element( module_id, __module, parent_id );

        // if row, call some column inside, except when auto_place_modules
        // is not requested ( the col will be pushed after )
        if( auto_place_modules ) {
            if( module_id == 'bw_row' ) {
                Bwpb.add_module( 'bw_column', uid, auto_place_modules, false );
            }
            // same for inner row
            if( module_id == 'bw_row_inner' ) {
                Bwpb.add_module( 'bw_column_inner', uid, auto_place_modules, false );
            }
        }

        // modules coloring
        Bwpb.element_colors( __module, data );

    },

    before_create_element: function() {

        Bwpb.welcome.hide();

    },

    /*
     * set last added ids for elements, rows, columns
     *
     */
    set_latest_ids: function( uid, view ) {

        this.latest_element_id = uid;

        if( view == 'row' ) {
            this.latest_row_id = uid;
        }

        if( view == 'column' ) {
            this.latest_col_id = uid;
        }

    },

    hex_to_rgb: function( hex, opacity = 1 ) {

        var c;

        if( hex == null ) { return; }

        if( /^#([A-Fa-f0-9]{3}){1,2}$/.test( hex ) ) {

            c = hex.substring(1).split('');

            if( c.length == 3 ) {
                c = [ c[0], c[0], c[1], c[1], c[2], c[2] ];
            }

            c = '0x' + c.join('');

            return 'rgba( ' + [ ( c >> 16 )&255, ( c >> 8 )&255, c&255 ].join(',') + ', ' + opacity + ' )';

        }else{

            Bwpb.notify( 'bad_hex', hex );

        }

    },

    /*
     * add some styling, colors
     *
     */
    element_colors: function( __module, data ) {

        if( $('.bwpb-label', __module).length && typeof window.bwpb_data.module_colors[ data.module ] !== 'undefined' ) {
            var rgb_color = Bwpb.hex_to_rgb( window.bwpb_data.module_colors[ data.module ], .25 );
            $('.bwpb-label, .bwpb-option-holder, .bwpb-plus', __module).css( 'background-color', window.bwpb_data.module_colors[ data.module ] );
            $('.bwpb-label, .bwpb-option-holder, .bwpb-plus', __module).css( 'box-shadow', '0px 3px 30px 0px ' + rgb_color );
        }

    },

    /*
     * place the element on top or bottom
     *
     */
    place_element: function( module_id, __module, parent_id ) {

        //console.log( 'module: ' + module_id + ', place: ' + Pl_modal.placement + ', parent_id: ' + parent_id );

        if( ! parent_id && Pl_modal.placement !== 'bottom' ) { Pl_modal.placement = 'top'; }

        switch( Pl_modal.placement ) {

            case 'top': // on top of all elements
                $('#bwpb-main .bwpb-blocks').prepend( __module );
                break;

            case 'bottom': // on bottom of all elements
                $('#bwpb-main .bwpb-blocks').append( __module );
                break;

            case 'before':
                $('#bwpb-main .bwpb-block[data-id="' + parent_id + '"]').before( __module );
                break;

            case 'after':
                $('#bwpb-main .bwpb-block[data-id="' + parent_id + '"]').after( __module );
                break;

            case 'manually_after': // same as after, but this won't auto insert row and column
                $('#bwpb-main .bwpb-block[data-id="' + parent_id + '"]').after( __module );
                break;

            case 'insert_top':
                $('#bwpb-main .bwpb-block[data-id="' + parent_id + '"] .bwpb-content:first').prepend( __module );
                break;

            case 'insert_bottom': // default
                $('#bwpb-main .bwpb-block[data-id="' + parent_id + '"] .bwpb-content:first').append( __module );
                break;

        }

        if( module_id !== 'bw_column' ) {
            $('#bwpb-main .bwpb-block[data-id="' + parent_id + '"]').closest('.bwpb-block').removeClass('bwpb-is-empty').addClass('bwpb-isnt-empty');
        }

        Pl_modal.placement = 'insert_bottom'; // reset to default element placement

        return;

        if( parent_id ) { // has parent

            $destination = $('#bwpb-main .bwpb-block[data-id="' + parent_id + '"] .bwpb-content:first');

            Pl_modal.placement ? $destination.prepend( __module ) : $destination.append( __module );
            //$destination.append( __module ); // TODO: fix this, column cropping not working if removed

            if( module_id !== 'bw_column' ) {
                $('#bwpb-main .bwpb-block[data-id="' + parent_id + '"]').closest('.bwpb-block').removeClass('bwpb-is-empty').addClass('bwpb-isnt-empty');
            }

        }else{ // has no parent

            var $main_blocks = $('#bwpb-main .bwpb-blocks');

            Pl_modal.placement ? $main_blocks.prepend( __module ) : $main_blocks.append( __module );

        }

    },

    /*
     * get some unique identifier id
     *
     */
    get_unique_id: function() {

        return '4xxxxx-yxxxxx'.replace(/[xy]/g,
            function(c) {
                var r = Math.random() * 16 | 0, v = c == 'x' ? r : ( r & 0x3 | 0x8 );
                return v.toString(16);
            }).toLowerCase();

    },

    /*
     * fix wp paragraphs
     *
     */
    wpautop: function( content ) {

        if( typeof content == 'undefined' ) { return; }

        if( content.substring(0, 7) == "</p><p>" ) {
            content = content.substring(7);
        }else if( content.substring(0, 3) == "<p>" ) {
            content = content.substring(3);
        }
        var regex = /^.*<\/p><p>$/;
        var regex2 = /^.*<\/p>$/;
        if ( regex.test( content ) ) {
            content = content.slice(0, -7);
        }else if( regex2.test( content ) ) {
            content = content.slice(0, -4);
        }

        return content;

    },

    /*
     * fix line breaks
     *
     */
    wpauton: function( content ) {

        if( typeof content == 'undefined' ) { return; }

        content = content.replace(/\n([ \t]*\n)+/g, '</p><p>');
        if( content.substring(0, 4) == "</p>" ) {
            content = content.substring(4);
        }
        var regex = /^.*<p>$/;
        if ( regex.test( content ) ) {
            content = content.slice(0, -3);
        }

        return content;

    },

    /*
     * get notified via console for debugging
     * TODO: expand notifications
     * TODO: add options to disable / enable debugging
     *
     */
    notify: function( type, value ) {

        var output = '';
        var i18n_errors_strings = window.bwpb_data.i18n.notifications;

        if( typeof i18n_errors_strings[ type ] !== 'undefined' ) {
            console.log( i18n_errors_strings[ type ].replace( '{{value}}', value ) );
        }

    }

};

/*
 * start the js on document ready state
 *
 */
$(document).ready(function() {
    Bwpb.start();
});

//})( jQuery );
