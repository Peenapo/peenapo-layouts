window.jQuery = window.$ = jQuery;

var Playouts = {

    // initiation
    start: function() {

        this.bind();
        this.override_styles.start();
        this.on_resize();
        this.on_images_loaded();

    },

    bind: function() {

        // disable empty url\'s
        $(document).on('click', '.pl-outer a[href="#"]', function(e) {
            e.preventDefault();
        });

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
            this.carousel.start();

        }

        ,carousel: {

            start: function() {

                $('.pl-slider').each( Playouts.elements.carousel.build_slider );

            }

            ,build_slider: function() {

                var self = $(this);
                var slide_width = self.attr('data-slide-width');

                var attr = {
                    cellAlign               : 'center',
                    contain                 : true,
                    groupCells              : typeof self.attr('data-group') !== 'undefined' ? parseInt( self.attr('data-group'), 10 ) : false,
                    autoPlay                : typeof self.attr('data-autoplay') !== 'undefined' ? parseInt( self.attr('data-autoplay'), 10 ) : false,
                    wrapAround              : typeof self.attr('data-infinite') !== 'undefined' && self.attr('data-infinite') == 'true' ? true : false,
                    pauseAutoPlayOnHover    : typeof self.attr('data-autoplay-stop') !== 'undefined' ? true : false,
                    adaptiveHeight          : typeof self.attr('data-adaptive-height') !== 'undefined' && self.attr('data-adaptive-height') == 'true' ? true : false,
                    prevNextButtons         : typeof self.attr('data-navigation') !== 'undefined' && self.attr('data-adaptive-height') == 'true' ? true : false,
                    pageDots                : typeof self.attr('data-pagination') !== 'undefined' && self.attr('data-pagination') == 'true' ? true : false,
                    selectedAttraction      : typeof self.attr('data-attraction') !== 'undefined' ? parseFloat( self.attr('data-attraction') ) : 0.025,
                    friction                : typeof self.attr('data-friction') !== 'undefined' ? parseFloat( self.attr('data-friction') ) : 0.28,
                };

                self.find(' > * ').css('width', slide_width + '%');

                self.flickity( attr );

            }

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

            Playouts.elements.start();
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

        this.appearance();
        this.background_parallax();
        this.sequence();
        this.video_button();

    },

    background_video: function() {

        if( $('.bwpb-video-wrap').length ) {
            $('.bwpb-video-wrap').each(function() {
                $(this).bwpb_core_video_background();
            });
        }

    },

    video_button: function() {

        $('.pl-video-button').on('mouseenter', function() {

            var self = $(this);

            if( self.hasClass('pl-animated') ) { return; }

            self.addClass('pl-animated');

            setTimeout(function() {
                self.removeClass('pl-animated');
            }, 500);

        });

    },

    appearance: function() {

        $('.pl-animation').waypoint({
            handler: function() {

                var self = $( this.element ).addClass('pl-animated');

                var animation  = typeof self.attr('data-animation') !== 'undefined' ? self.attr('data-animation') : 'scale';
                var animation_speed = typeof self.attr('data-animation-speed') !== 'undefined' ? parseInt( self.attr('data-animation-speed'), 10 ) * 0.001 : .4;
                var animation_delay = typeof self.attr('data-animation-delay') !== 'undefined' ? parseInt( self.attr('data-animation-delay'), 10 ) * 0.001 : 0;

                switch( animation ) {
                    case 'scale':
                        TweenMax.fromTo( self, animation_speed, { scale: 0.8 }, { opacity:1, scale: 1, delay: animation_delay } );
                        break;
                    case 'top':
                        TweenMax.fromTo( self, animation_speed, { y: '-10%' }, { opacity:1, y: '0%', delay: animation_delay } );
                        break;
                    case 'right':
                        TweenMax.fromTo( self, animation_speed, { x: '10%' }, { opacity:1, x: '0%', delay: animation_delay } );
                        break;
                    case 'bottom':
                        TweenMax.fromTo( self, animation_speed, { y: '10%' }, { opacity:1, y: '0%', delay: animation_delay } );
                        break;
                    case 'left':
                        TweenMax.fromTo( self, animation_speed, { x: '-10%' }, { opacity:1, x: '0%', delay: animation_delay } );
                        break;
                }

                this.destroy();

            },
            offset: '80%'
        });

    },

    sequence: function() {

        $('.pl-animated-appearance').waypoint({
            handler: function() {

                var self = $( this.element ).addClass('pl-animated');

                var $to_animate = $(' > *', self);
                var animation_speed = parseInt( self.attr('data-animation-speed'), 10 ) * 0.001;
                var animation_delay = parseInt( self.attr('data-animation-delay'), 10 ) * 0.001;

                TweenMax.staggerTo( $to_animate, animation_speed, { opacity:1 }, animation_delay );

                this.destroy();

            },
            offset: '80%'
        });

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

$(document).ready(function() {
    Playouts.start();
});
