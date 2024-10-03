<!DOCTYPE html>
<html lang="{{ \App\AppConf::$lang }}">
<head>
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-KWJWQ9Q');
    </script>
    @include('web.widget.meta_head')

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="preload" href="{{ asset('css/fonts/roboto.min.css') }}" as="style">
    <link href="{{ asset('css/fonts/roboto.min.css') }}" rel="stylesheet">

    @include('web.widget.ignore.stat-head-scripts')

    <script>
        var isMobile = {{ !empty(\App\W::$isMobile) ? 'true' : 'false' }};
        var app_host = '{{ config('app.host') }}';
        var app_url = '{{ config('app.url') }}';
        var preloaderAjaxShow = true;
        var storage_url = '{{ \App\AppConf::$storage_url }}';
        var ignoreEventFlag = false;
        var id_adwords_add_product = false;
        @if(!empty(\App\AppConf::$id_adwords_add_product))
            id_adwords_add_product = '{{ \App\AppConf::$id_adwords_add_product }}';
        @endif
    </script>
    @if(false)
        <script src="{{ asset('js/base/func.js') }}"></script>
        <script src="{{ asset('js/plugins/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('js/plugins/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('js/plugins/jquery.ui.touch-punch.min.js') }}"></script>
        <script>
            $(function () {
                $.ajaxSetup({
                    headers: {
                        //'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
            });
        </script>
    @endif
    <script src="{{ asset('js/web/lazysize.min.js') }}" async></script>
    <script src="{{ asset('js/web/func.js') }}"></script>


    @stack('css_base')
    @stack('css_1')
    @stack('css_2')
    @stack('css_3')
    @stack('css_end')
</head>

@yield('layout')

    @stack('js')
    @include('web.widget.json_app')
    @stack('json')

</body>
</html>


