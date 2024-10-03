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
    {{--<meta name="csrf-token" content="{{ csrf_token() }}">--}}
    <link rel="preload" href="{{ asset('css/fonts/roboto.min.css') }}" as="style">
    <link href="{{ asset('css/fonts/roboto.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/base/variable.css') }}" rel="stylesheet">
    {{--<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">--}}

    @if (!empty(\App\AppConf::$id_google_analytics))
        <script>
            setTimeout(function () {
                var script = document.createElement('script');
                script.setAttribute('src', "https://www.googletagmanager.com/gtag/js?id={{ \App\AppConf::$id_google_analytics }}");
                script.setAttribute('async', true);
                document.body.appendChild(script);
            }, 1500);
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ \App\AppConf::$id_google_analytics }}');
        </script>
    @endif

    @if (!empty(\App\AppConf::$id_rambler))
        <!-- Top100 (Kraken) Counter -->
        <script>
            (function (w, d, c) {
                (w[c] = w[c] || []).push(function() {
                    var options = {
                        project: {{ \App\AppConf::$id_rambler }},
                    };
                    try {
                        w.top100Counter = new top100(options);
                    } catch(e) { }
                });
                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
                s.type = "text/javascript";
                s.async = true;
                s.src =
                    (d.location.protocol == "https:" ? "https:" : "http:") +
                    "//st.top100.ru/top100/top100.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else { f(); }
            })(window, document, "_top100q");
        </script>
        <noscript>
            <img src="//counter.rambler.ru/top100.cnt?pid={{ \App\AppConf::$id_rambler }}" alt="Топ-100" />
        </noscript>
        <!-- END Top100 (Kraken) Counter -->
    @endif

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
        var isOnIOS = navigator.userAgent.match(/iPad/i)|| navigator.userAgent.match(/iPhone/i);
        var ignoreEventFlag = false;

        $(function () {
            /*$('#preloader').show();
            setTimeout(function () {
                $('#preloader').hide();
            }, 2000);

            if(preloaderAjaxShow){
                $(window).ajaxStart(function() {
                    $('#preloader').show();
                });
                $(window).ajaxStop(function() {
                    $('#preloader').hide();
                });
                window.onbeforeunload = function() {
                    $('#preloader').show();
                };
            }*/

            $.ajaxSetup({
                headers: {
                    //'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
        #preloader.hide{
            opacity: 0;
        }
        .md-preloader{font-size:0;display:inline-block;-webkit-animation:outer 6600ms linear infinite;animation:outer 6600ms linear infinite}.md-preloader svg{-webkit-animation:inner 990ms linear infinite;animation:inner 990ms linear infinite}.md-preloader svg circle{fill:none;stroke:#ffffff;stroke-linecap:square;-webkit-animation:arc 990ms cubic-bezier(.8, 0, .4, .8) infinite;animation:arc 990ms cubic-bezier(.8, 0, .4, .8) infinite}@-webkit-keyframes outer{0%{-webkit-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes outer{0%{-webkit-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@-webkit-keyframes inner{0%{-webkit-transform:rotate(-100.8deg);transform:rotate(-100.8deg)}100%{-webkit-transform:rotate(0);transform:rotate(0)}}@keyframes inner{0%{-webkit-transform:rotate(-100.8deg);transform:rotate(-100.8deg)}100%{-webkit-transform:rotate(0);transform:rotate(0)}}@-webkit-keyframes arc{0%{stroke-dasharray:1 210.48670779px;stroke-dashoffset:0}40%{stroke-dasharray:151.55042961px,210.48670779px;stroke-dashoffset:0}100%{stroke-dasharray:1 210.48670779px;stroke-dashoffset:-151.55042961px}}@keyframes arc{0%{stroke-dasharray:1 210.48670779px;stroke-dashoffset:0}40%{stroke-dasharray:151.55042961px,210.48670779px;stroke-dashoffset:0}100%{stroke-dasharray:1 210.48670779px;stroke-dashoffset:-151.55042961px}}
    </style>

    <style>
        @font-face {
            font-family: 'Helvetica Neue';
            src: url('/fonts/helvetica_neue/HelveticaNeueBoldCondensed.eot');
            src: local('Helvetica Neue Condensed Bold'), local('HelveticaNeueBoldCondensed'),
            url('/fonts/helvetica_neue/HelveticaNeueBoldCondensed.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueBoldCondensed.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueBoldCondensed.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueBoldCondensed.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        @font-face {
            font-family: 'Helvetica Neue';
            src: url('/fonts/helvetica_neue/HelveticaNeueBlackCondensed.eot');
            src: local('Helvetica Neue Condensed Black'), local('HelveticaNeueBlackCondensed'),
            url('/fonts/helvetica_neue/HelveticaNeueBlackCondensed.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueBlackCondensed.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueBlackCondensed.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueBlackCondensed.ttf') format('truetype');
            font-weight: 900;
            font-style: normal;
        }

        @font-face {
            font-family: 'HelveticaNeueCyr';
            src: url('/fonts/helvetica_neue/HelveticaNeueCyr-Bold.eot');
            src: local('HelveticaNeueCyr-Bold'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Bold.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Bold.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Bold.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Bold.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        @font-face {
            font-family: 'HelveticaNeueCyr';
            src: url('/fonts/helvetica_neue/HelveticaNeueCyr-Light.eot');
            src: local('HelveticaNeueCyr-Light'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Light.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Light.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Light.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Light.ttf') format('truetype');
            font-weight: 300;
            font-style: normal;
        }

        @font-face {
            font-family: 'HelveticaNeueCyr';
            src: url('/fonts/helvetica_neue/HelveticaNeueCyr-Medium.eot');
            src: local('HelveticaNeueCyr-Medium'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Medium.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Medium.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Medium.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Medium.ttf') format('truetype');
            font-weight: 500;
            font-style: normal;
        }

        @font-face {
            font-family: 'HelveticaNeueCyr';
            src: url('/fonts/helvetica_neue/HelveticaNeueCyr-MediumItalic.eot');
            src: local('HelveticaNeueCyr-MediumItalic'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-MediumItalic.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-MediumItalic.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-MediumItalic.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-MediumItalic.ttf') format('truetype');
            font-weight: 500;
            font-style: italic;
        }

        @font-face {
            font-family: 'HelveticaNeueCyr';
            src: url('/fonts/helvetica_neue/HelveticaNeueCyr-LightItalic.eot');
            src: local('HelveticaNeueCyr-LightItalic'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-LightItalic.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-LightItalic.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-LightItalic.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-LightItalic.ttf') format('truetype');
            font-weight: 300;
            font-style: italic;
        }

        @font-face {
            font-family: 'HelveticaNeueCyr';
            src: url('/fonts/helvetica_neue/HelveticaNeueCyr-HeavyItalic.eot');
            src: local('HelveticaNeueCyr-HeavyItalic'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-HeavyItalic.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-HeavyItalic.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-HeavyItalic.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-HeavyItalic.ttf') format('truetype');
            font-weight: 900;
            font-style: italic;
        }

        @font-face {
            font-family: 'HelveticaNeueCyr';
            src: url('/fonts/helvetica_neue/HelveticaNeueCyr-Black.eot');
            src: local('HelveticaNeueCyr-Black'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Black.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Black.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Black.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Black.ttf') format('truetype');
            font-weight: 900;
            font-style: normal;
        }

        @font-face {
            font-family: 'HelveticaNeueCyr';
            src: url('/fonts/helvetica_neue/HelveticaNeueCyr-Heavy.eot');
            src: local('HelveticaNeueCyr-Heavy'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Heavy.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Heavy.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Heavy.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Heavy.ttf') format('truetype');
            font-weight: 900;
            font-style: normal;
        }

        @font-face {
            font-family: 'HelveticaNeueCyr';
            src: url('/fonts/helvetica_neue/HelveticaNeueCyr-Italic.eot');
            src: local('HelveticaNeueCyr-Italic'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Italic.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Italic.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Italic.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Italic.ttf') format('truetype');
            font-weight: 500;
            font-style: italic;
        }

        @font-face {
            font-family: 'HelveticaNeueCyr';
            src: url('/fonts/helvetica_neue/HelveticaNeueCyr-Roman.eot');
            src: local('HelveticaNeueCyr-Roman'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Roman.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Roman.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Roman.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Roman.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'HelveticaNeueCyr';
            src: url('/fonts/helvetica_neue/HelveticaNeueCyr-BlackItalic.eot');
            src: local('HelveticaNeueCyr-BlackItalic'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-BlackItalic.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-BlackItalic.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-BlackItalic.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-BlackItalic.ttf') format('truetype');
            font-weight: 900;
            font-style: italic;
        }

        @font-face {
            font-family: 'HelveticaNeueCyr';
            src: url('/fonts/helvetica_neue/HelveticaNeueCyr-UltraLightItalic.eot');
            src: local('HelveticaNeueCyr-UltraLightItalic'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-UltraLightItalic.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-UltraLightItalic.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-UltraLightItalic.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-UltraLightItalic.ttf') format('truetype');
            font-weight: 200;
            font-style: italic;
        }

        @font-face {
            font-family: 'HelveticaNeueCyr';
            src: url('/fonts/helvetica_neue/HelveticaNeueCyr-BoldItalic.eot');
            src: local('HelveticaNeueCyr-BoldItalic'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-BoldItalic.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-BoldItalic.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-BoldItalic.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-BoldItalic.ttf') format('truetype');
            font-weight: bold;
            font-style: italic;
        }

        @font-face {
            font-family: 'HelveticaNeueCyr';
            src: url('/fonts/helvetica_neue/HelveticaNeueCyr-ThinItalic.eot');
            src: local('HelveticaNeueCyr-ThinItalic'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-ThinItalic.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-ThinItalic.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-ThinItalic.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-ThinItalic.ttf') format('truetype');
            font-weight: 100;
            font-style: italic;
        }

        @font-face {
            font-family: 'HelveticaNeueCyr';
            src: url('/fonts/helvetica_neue/HelveticaNeueCyr-UltraLight.eot');
            src: local('HelveticaNeueCyr-UltraLight'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-UltraLight.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-UltraLight.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-UltraLight.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-UltraLight.ttf') format('truetype');
            font-weight: 200;
            font-style: normal;
        }

        @font-face {
            font-family: 'HelveticaNeueCyr';
            src: url('/fonts/helvetica_neue/HelveticaNeueCyr-Thin.eot');
            src: local('HelveticaNeueCyr-Thin'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Thin.eot?#iefix') format('embedded-opentype'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Thin.woff2') format('woff2'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Thin.woff') format('woff'),
            url('/fonts/helvetica_neue/HelveticaNeueCyr-Thin.ttf') format('truetype');
            font-weight: 100;
            font-style: normal;
        }


    </style>
</head>

@yield('layout')

    <div id="preloader">
        <div style="margin: auto; width: 75px; height: 75px; position: relative; top: 50%; -webkit-transform: translateY(-50%); -ms-transform: translateY(-50%); transform: translateY(-50%);"><div class="md-preloader"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="75" width="75" viewbox="0 0 75 75"><circle cx="37.5" cy="37.5" r="33.5" stroke-width="8"/></svg></div></div>
    </div>

    @stack('js')
</body>
</html>


