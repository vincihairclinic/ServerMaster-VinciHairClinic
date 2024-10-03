<!DOCTYPE html>
<html lang="{{ \App\AppConf::$lang }}">
<head>
    <title>{!! \App\W::$metaTitle !!}@yield('metaTitle')</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preload" href="{{ asset('css/fonts/roboto.min.css') }}" as="style">
    <link href="{{ asset('css/fonts/roboto.min.css') }}" rel="stylesheet">
    {{--<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">--}}


    <script src="{{ asset('js/base/func.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery.ui.touch-punch.min.js') }}"></script>
    {{--<script src="{{ asset('js/plugins/html2canvas.min.js') }}"></script>--}}
    <script src="{{ asset('js/web/lazysize.min.js') }}" async></script>

    <script>
        var isMobile = {{ !empty(\App\W::$isMobile) ? 'true' : 'false' }};
        var app_host = '{{ config('app.host') }}';
        var app_url = '{{ config('app.url') }}';
        var preloaderAjaxShow = true;
        var storage_url = '{{ \App\AppConf::$storage_url }}';
        //var request_id = Math.floor(Math.random() * (9 - 1) + 1)+''+(Date.now()+'').slice(6)+(Math.floor(Math.random() * (99999 - 10000)) + 10000);

        var ignoreEventFlag = false;

        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        });
    </script>

    @stack('css_base')
    @stack('css_1')
    @stack('css_2')
    @stack('css_3')
    @stack('css_end')

    <style>
        .hide-scroll::-webkit-scrollbar {width: 0}
        .hide-scroll{
            overflow: -moz-scrollbars-none;
            -ms-overflow-style: none;
            scrollbar-width: none;
            overscroll-behavior: none;
        }

        .ico{
            display: -webkit-flex;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .v_align{
            display: -webkit-flex;
            display: flex;
            align-items: center;
        }
        .flex{
            display: -webkit-flex;
            display: flex;
            -webkit-flex-flow: row wrap;
        }
        .clear{
            font-size: inherit;
            font-weight: inherit;
            line-height: inherit;
            padding: 0;
            margin: 0;
        }


        img,
        button,
        .btn,
        .noselect *,
        .noselect {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        *:focus {outline:none}

        /*--------------------------------------------*/

        * {
            line-height: 160%;
            padding: 0;
            margin: 0;
            /*font-family: "Arial", sans-serif;*/
            font-family: 'Roboto', sans-serif;
            /*font-family: 'Open Sans', sans-serif;*/
            color: inherit;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
            -webkit-user-drag: none;
            -khtml-user-drag: none;
            -moz-user-drag: none;
            -o-user-drag: none;
            user-drag: none;
        }

        /*p{
            font-size: 13px !important;
            line-height: 140% !important;
        }*/

        ::selection {
            background-color: #d4ecff;
        }

        select,
        textarea,
        input {
            -webkit-appearance: none;
            border: none;
            overflow: auto;
            outline: none;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
            padding: 0;
            margin: 0;
        }

        h1, h2, h3, h4 {
            color: #374250;
        }

        h1{
            font-size: 40px;
            line-height: 110%;
        }

        h2{
            font-size: 30px;
            line-height: 120%;
        }

        h3{
            font-size: 24px;
            line-height: 130%;
        }

        h4{
            font-size: 18px;
            line-height: 140%;
        }
        body {
            width: 100%;
            color: #374250;
            font-size: 14px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            /*background-image: url({{ asset('images/web_body_bg.svg') }});*/
            overflow-x: hidden;
            overflow-y: scroll;
            background-color: #f6f7f9;
        }

        a{
            color: #1a73e8;
            text-decoration: underline;
        }



        #preloader{
            width: 100%;
            height: 100%;
            background-color: #202125;
            opacity: 0.3;
            position: fixed;
            top: 0px;
            left: 0px;
            z-index: 99999999999999999;
            display: none;
}
        .md-preloader{font-size:0;display:inline-block;-webkit-animation:outer 6600ms linear infinite;animation:outer 6600ms linear infinite}.md-preloader svg{-webkit-animation:inner 990ms linear infinite;animation:inner 990ms linear infinite}.md-preloader svg circle{fill:none;stroke:#ffffff;stroke-linecap:square;-webkit-animation:arc 990ms cubic-bezier(.8, 0, .4, .8) infinite;animation:arc 990ms cubic-bezier(.8, 0, .4, .8) infinite}@-webkit-keyframes outer{0%{-webkit-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes outer{0%{-webkit-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@-webkit-keyframes inner{0%{-webkit-transform:rotate(-100.8deg);transform:rotate(-100.8deg)}100%{-webkit-transform:rotate(0);transform:rotate(0)}}@keyframes inner{0%{-webkit-transform:rotate(-100.8deg);transform:rotate(-100.8deg)}100%{-webkit-transform:rotate(0);transform:rotate(0)}}@-webkit-keyframes arc{0%{stroke-dasharray:1 210.48670779px;stroke-dashoffset:0}40%{stroke-dasharray:151.55042961px,210.48670779px;stroke-dashoffset:0}100%{stroke-dasharray:1 210.48670779px;stroke-dashoffset:-151.55042961px}}@keyframes arc{0%{stroke-dasharray:1 210.48670779px;stroke-dashoffset:0}40%{stroke-dasharray:151.55042961px,210.48670779px;stroke-dashoffset:0}100%{stroke-dasharray:1 210.48670779px;stroke-dashoffset:-151.55042961px}}
    </style>
</head>

@yield('layout')
    <div id="preloader">
        <div style="margin: auto; width: 75px; height: 75px; position: relative; top: 50%; -webkit-transform: translateY(-50%); -ms-transform: translateY(-50%); transform: translateY(-50%);"><div class="md-preloader"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="75" width="75" viewbox="0 0 75 75"><circle cx="37.5" cy="37.5" r="33.5" stroke-width="8"/></svg></div></div>
    </div>

    @stack('js')
</body>
</html>


