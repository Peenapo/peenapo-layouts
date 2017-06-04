window.jQuery = window.$ = jQuery;

var Playouts = {

    // initiation
    start: function() {

        this.bind();
        this.override_styles.start();
        this.elements.start();
        this.on_resize();
        this.on_images_loaded();

    },

    bind: function() {

        // ..

    },

    /*
     * override default css hover styles
     *
     */
    override_styles: {

        start: function() {

            $('.pl-button[data-hover-bg-color-override]')
                .on('mouseover', this.override_hover_bg_color )
                .on('mouseout', this.reset_hover_bg_color );

            $('.pl-button[data-hover-text-color-override]')
                .on('mouseover', this.override_hover_text_color )
                .on('mouseout', this.reset_hover_text_color );

            $('.pl-button[data-hover-shadow-override]')
                .on('mouseover', this.override_hover_shadow_color )
                .on('mouseout', this.reset_hover_shadow_color );

        }

        ,override_hover_bg_color: function() {
            var self = $(this);
            TweenLite.set( self, { backgroundColor: self.attr('data-hover-bg-color-override') });
        }
        ,reset_hover_bg_color: function() {
            $(this).css('background-color', '');
        }

        ,override_hover_text_color: function() {
            var self = $(this);
            TweenLite.set( self, { color: self.attr('data-hover-text-color-override') });
        }
        ,reset_hover_text_color: function() {
            $(this).css('color', '');
        }

        ,override_hover_shadow_color: function() {
            var self = $(this);
            var color = Playouts.hex_to_rgba( self.attr('data-hover-shadow-override') );
            TweenLite.set( self, { boxShadow: '0 20px 38px rgba(' + color + ', 0.15)' });
        }
        ,reset_hover_shadow_color: function() {
            $(this).css('box-shadow', '');
        }

    },

    hex_to_rgba: function( hex ) {
        var c;
        if( /^#([A-Fa-f0-9]{3}){1,2}$/.test( hex ) ) {
            c = hex.substring(1).split('');
            if( c.length == 3 ) {
                c = [c[0], c[0], c[1], c[1], c[2], c[2]];
            }
            c = '0x'+c.join('');
            return ''+[(c>>16)&255, (c>>8)&255, c&255].join(',');
        }
    },

    elements: {

        start: function() {

            this.accordion.start();
            this.tab.start();
            this.progress.start();
            this.auto_type.start();

        }

        ,auto_type: {

            start: function() {

                $('.pl-auto-type-holder').each(function() {

                    var self = $(this),
                        id = self.attr('id'),
                        texts = [];

                    self.find('li').each(function() {
                        texts.push( this.innerHTML );
                    });

                    Typed.new( '#' + id + ' .pl-auto-type', {
                        strings: texts,
                        typeSpeed: 60,
                        backDelay: 1000,
                        loop: true,
                    });

                });



            }

        }

        ,progress: {

            start: function() {

                $('.pl-progress-bar').each(function() {

                    var self = $(this),
                        width = self.attr('data-progress'),
                        $label = self.find('.pl-progress-counter'),
                        $counter = $label.find('em');

                    TweenLite.to( self.find('.pl-the-bar'), 1.2, { width: width + '%', ease: Expo.easeInOut });
                    TweenLite.to( $label, 1.2, { opacity: 1, delay: 0.5 });

                });

            }

        }

        ,accordion: {

            start: function() {

                $('.pl-accordion').on('click', '.pl-accordion-title', Playouts.elements.accordion.on_click_accordion);

            }

            ,on_click_accordion: function() {

                var self = $(this),
                    $item = self.closest('.pl-accirdion-item'),
                    $content = $item.find('.pl-accordion-content'),
                    $inner = $item.find('.pl-accordion-content-inner'),
                    close_other = self.closest('.pl-accordion').hasClass('pl-close-other');

                if( ! $item.hasClass('pl-active') ) { // open

                    if( close_other ) {
                        self.closest('.pl-accordion').find('.pl-accirdion-item.pl-active').find('.pl-accordion-title').trigger('click');
                    }

                    $item.addClass('pl-active');

                    TweenLite.fromTo( $content, .22, { height: 0 }, { height: $inner.outerHeight(), onComplete: function() {
                        $content.css('height', 'auto');
                    }});

                }else{ // close

                    TweenLite.fromTo( $content, .22, { height: $inner.outerHeight() }, { height: 0 });
                    $item.removeClass('pl-active');

                }
            }
        }

        ,tab: {

            start: function() {

                $('.pl-tabs').on('click', '.pl-tab-nav li', Playouts.elements.tab.on_click_tabs);
                $('.pl-tabs').each(function() {

                    var self = $(this),
                        $border = $('.pl-nav-border', self),
                        left = parseInt( self.find('li:first').outerWidth(), 10 );

                    TweenLite.to( $border, .4, { x: 0, scaleX: left, ease: Power4.easeOut } );

                });

            }

            ,on_click_tabs: function(e) {

                e.preventDefault();

                var self = $(this);

                if( self.hasClass('pl-active') ) { return; }

                Playouts.elements.tab.scale_tabs_border( self );

                var $tab = self.closest('.pl-tabs'),
                    $nav = self.closest('.pl-tab-nav'),
                    tab_id = self.children('a').attr('href'),
                    $display_section = $tab.find('.pl-tab-section' + tab_id);

                $nav.find('li').removeClass('pl-active');
                self.addClass('pl-active');

                $tab.find('.pl-tab-section').removeClass('pl-active');
                $display_section.addClass('pl-active');
                TweenLite.fromTo( $display_section, .4, { opacity: 0 }, { opacity: 1 });


            }

            ,scale_tabs_border: function( self ) {

                var left = self.position().left,
                    width =  parseInt( self.outerWidth(), 10 );

                TweenLite.to( $('.pl-nav-border'), .4, { x: left, scaleX: width, ease: Power4.easeOut } );

            }
        }
    },

    on_images_loaded: function() {

        $(document).imagesLoaded(function() {
            Playouts.animations();
        });

    },

    on_resize: function() {

        var self = this;

        $(window).on("debouncedresize", function( event ) {
            // ..
        });

    },

    animations: function() {

        this.background_parallax();

    },

    background_video: function() {

        if( $('.bwpb-video-wrap').length ) {
            $('.bwpb-video-wrap').each(function() {
                $(this).bwpb_core_video_background();
            });
        }

    },

    background_parallax: function() {
        var $parallax = $('.bwpb-parallax');
        if ( $parallax.length ) {
            $parallax.each(function() {
                $(this).bwpb_core_parallax_background();
            });
        }
    },

    on_click_accordion: function() {

    }
}

Playouts.start();
