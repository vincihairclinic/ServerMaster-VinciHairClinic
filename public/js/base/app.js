var app = {
    preloaderAjaxShow: false,
    ignoreEventFlag: false,
    isScrolling: false,
    windowScrollTop: 0,
    isMdlLayoutScrollRun: false,

    windowHeight: 5000,
    windowWidth: 500,

    animateShowContent: {
        start: true,
        speed: 0,

        enable: function (speed) {
            if(speed){
                app.animateShowContent.start = false;
                app.animateShowContent.speed = speed;
                document.getElementById('globalWhiteContentMask').style.opacity = 1;
            }
        },

        init: function () {
            setTimeout(function () {
                if(app.animateShowContent.start){
                    if(!app.animateShowContent.speed){
                        $('#globalWhiteContentMask').hide();
                    }else {
                        $('#globalWhiteContentMask').animate({opacity: 0}, app.animateShowContent.speed, function() {
                            $('#globalWhiteContentMask').hide();
                        });
                    }
                }else {
                    app.animateShowContent.init();
                }
            }, 10);
        }
    },

    event: {
        resize: function (isFirst) {
            app.windowHeight = $(window).height();
            app.windowWidth = $(window).width();

            $('.show_all_btn_block').each(function () {
                if($(this).parent().find('.overflow_hidden_block').prop('scrollHeight') > $(this).parent().find('.overflow_hidden_block').height()){
                    $(this).show();
                }else {
                    $(this).hide();
                }
            });
        }
    },

    initWindowScrollTop: function (additionally, removeAfter, callback) {
        if(localStorage.getItem('windowScrollTop') !== null) {
            additionally = additionally === undefined ? 0 : additionally;
            if(!app.windowScrollTop){
                app.windowScrollTop = parseInt(localStorage.getItem('windowScrollTop'));
            }
            document.getElementsByClassName('mdl-layout')[0].scrollTop = app.windowScrollTop+additionally;

            if (callback && typeof (callback) === 'function') {
                callback();
            }

            if(removeAfter){
                localStorage.removeItem('windowScrollTop')
            }
        }
    },

    saveWindowScrollTop: function () {
        if($('.mdl-layout').get(0)){
            localStorage.setItem('windowScrollTop', $('.mdl-layout').scrollTop());
        }
    }
};

$(function () {
    $('.mdl-layout__content').css({'height': 'auto'});
    app.event.resize(true);
    $('.footer').show();
    setTimeout(function () {
        app.event.resize();
    }, 1000);
    setTimeout(function () {
        app.event.resize();
    }, 15000);

    var flagIsResize = false;
    $(window).on('resize', function () {
        if (!flagIsResize) {
            flagIsResize = true;
            setTimeout(function () {
                app.event.resize();
                setTimeout(function () {
                    app.event.resize();
                    flagIsResize = false;
                }, 1000);
            }, 500);
        }
    });

    $('.mdl-layout').on('scroll', function() {
        if(!app.isScrolling){
            app.isScrolling = true;
            setTimeout(function () {
                app.isScrolling = false;
            }, 10);
        }
    });

    if(app.preloaderAjaxShow){
        $(window).ajaxStart(function() {
            $('#preloader').show();
        });
        $(window).ajaxStop(function() {
            $('#preloader').hide();
        });
        window.onbeforeunload = function() {
            $('#preloader').show();
            setTimeout(function () {
                $('#preloader').hide();
            }, 2000);
        };
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.base_style_select2, .base_style_input').on('click', function () {
        $(this).removeClass('is_invalid');
    });

});

function mdl(f){/in/.test(document.readyState)?setTimeout('mdl('+f+')',9):f()}




