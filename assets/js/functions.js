var $ = jQuery.noConflict();

var SARGILLA = SARGILLA || {};
(function($){
    "use strict";
    SARGILLA.initialize = {
        init: function(){
            SARGILLA.initialize.contactForm();
            SARGILLA.initialize.smoothScroll();
            SARGILLA.initialize.stickyHeader();
            SARGILLA.initialize.scrollToTop();
        },
        contactForm: function(){
            if( !$().validate ) {
                console.log('contactForm: Form Validate not Defined.');
                return true;
            }

            if( !$().ajaxSubmit ) {
                console.log('contactForm: jQuery Form not Defined.');
                return true;
            }
            var $contactForm = $('.contact-widget:not(.customjs)');
            if( $contactForm.length < 1 ){ return true; }

            $contactForm.each( function(){
                var element = $(this),
                    elementAlert = element.attr('data-alert-type'),
                    elementLoader = element.attr('data-loader'),
                    elementResult = element.find('.contact-form-result'),
                    elementRedirect = element.attr('data-redirect');
                element.find('form').validate({
                    submitHandler: function(form) {
                        elementResult.hide();
                        if( elementLoader == 'button' ) {
                            var defButton = $(form).find('button'),
                                defButtonText = defButton.html();
                            defButton.html('<i class="icon-line-loader icon-spin nomargin"></i>');
                        } else {
                            $(form).find('.form-process').fadeIn();
                        }
                        $(form).ajaxSubmit({
                            target: elementResult,
                            dataType: 'json',
                            success: function( data ) {
                                if( elementLoader == 'button' ) {
                                    defButton.html( defButtonText );
                                } else {
                                    $(form).find('.form-process').fadeOut();
                                }
                                if( data.alert != 'error' && elementRedirect ){
                                    window.location.replace( elementRedirect );
                                    return true;
                                }
                                if( elementAlert == 'inline' ) {
                                    if( data.alert == 'error' ) {
                                        var alertType = 'alert-danger';
                                    } else {
                                        var alertType = 'alert-success';
                                    }
                                    elementResult.removeClass( 'alert-danger alert-success' ).addClass( 'alert ' + alertType ).html( data.message ).slideDown( 400 );
                                } else {
                                    if (data.alert == 'error') {
                                        toastr.error(data.message)
                                    } else {
                                        toastr.success(data.message)
                                    }
                         
                                    // console.log(data)
                                }
                                if( $(form).find('.g-recaptcha').children('div').length > 0 ) { grecaptcha.reset(); }
                                if( data.alert != 'error' ) { $(form).clearForm(); }
                            }
                        });
                    }
                });
            });
        },
        scrollToTop: function() {
            $(window).scroll(function () {
                if ($(this).scrollTop() > 50) {
                    $('#back-to-top').fadeIn();
                } else {
                    $('#back-to-top').fadeOut()
                }
            });
            $('#back-to-top').click(function () {
                $('body,html').animate({
                    scrollTop: 0
                }, 800);
                return false;
            });
        },
        smoothScroll: function() {
            new SmoothScroll('a[href*="#"]', {
                offset: function (anchor, toggle) {
                    if ($('#logo-menu').hasClass('fixed')) {
                        return 65;
                    } else {
                        return 231;
                    }
                },
            });
        },
        stickyHeader: function(){
            var header = document.querySelector(".menu");
            let logoMenuNav = document.querySelector("#logo-menu");
            var sticky = header.offsetTop;
           
            $(window).scroll(function () {
                if (window.pageYOffset > sticky) {
                    header.classList.add("is-fixed");
                    logoMenuNav.classList.add("fixed")
                } else {
                    header.classList.remove("is-fixed");
                    logoMenuNav.classList.remove("fixed");
                }
            });
        }
    };
    $(document).ready( SARGILLA.initialize.init() );
})(jQuery);
