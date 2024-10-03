$(function () {
    $('#main-right-side-menu').show();

    (function($) {
        var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;
        $.fn.attrchange = function(callback) {
            if (MutationObserver) {
                var options = {
                    subtree: false,
                    attributes: true
                };
                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(e) {
                        callback.call(e.target, e.attributeName);
                    });
                });
                return this.each(function() {
                    observer.observe(this, options);
                });
            }
        }
    })(jQuery);

    var scrollTopContentIsRun = false;
    function scrollTopContentRun(e) {
        if(scrollTopContentIsRun){
            if($(e).hasClass('is-animating') && $(e).hasClass('is-compact')){
                var layoutContentScrollTop = $('.mdl-layout__content').scrollTop();
                if(layoutContentScrollTop < 10 && layoutContentScrollTop > 1){
                    $('.mdl-layout__content').scrollTop(1);
                }else {
                    scrollTopContentIsRun = false;
                }
                setTimeout(function () {
                    scrollTopContentRun(e);
                }, 1);
            }else {
                scrollTopContentIsRun = false;
            }
        }
    }

    $('.mdl-layout__header--waterfall').attrchange(function(attrName){
        if(attrName == 'class'){
            if(!scrollTopContentIsRun){
                if($(this).hasClass('is-animating') && $(this).hasClass('is-compact')){
                    $('.mdl-layout__content').scrollTop(1);
                    scrollTopContentIsRun = true;
                    scrollTopContentRun(this);
                }
            }
        }
    });

    $('.mdl-textfield--floating-label input[type="date"]').on('focus', function () {
        $(this).parent().addClass('is-dirty');
    });
    $('.mdl-textfield--floating-label input[type="date"]').on('focusout', function () {
        $(this).parent().addClass('is-dirty');
    });
    mdl(function(){
        $('.mdl-textfield--floating-label input[type="date"]').parent().addClass('is-dirty');
    });
});

window.onerror = function (errorMsg, url, lineNumber) {
    if(!base_repository.getQueryVariable('onerror') && errorMsg.namespace == 'dt') {
        var locationSearch = window.location.search + (window.location.search ? "&" : "?");
        locationSearch += "onerror=1";
        //localStorage.clear();
        sessionStorage.clear();
        window.location.href = window.location.protocol + "//" + window.location.host + window.location.pathname + locationSearch;
    }else {
        $(function () {
            $('#preloader').hide();
        });
    }
};