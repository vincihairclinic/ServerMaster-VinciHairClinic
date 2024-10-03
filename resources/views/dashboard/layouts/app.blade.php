<!DOCTYPE html>
<html lang="{{ \App\AppConf::$lang }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <title>{{ \App\AppConf::$site_name }} - @yield('head_title')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('css/fonts/roboto.min.css') }}">
    <link href="{{ asset('css/plugins/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    @stack('css_1')
    <link rel="stylesheet" href="{{ asset('css/plugins/dialog-polyfill.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/content.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/bootstrap-glyphicons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/button.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/variable.css') }}">
    <script src="{{ asset('js/plugins/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap/bootstrap.min.js') }}"></script>
    @stack('css_2')
    @stack('head_js')
    @stack('css_3')
    @stack('css_end')
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

@yield('main')

{{--    <div id="preloader">--}}
{{--        <div style="margin: auto; width: 75px; height: 75px; position: relative; top: 50%; -webkit-transform: translateY(-50%); -ms-transform: translateY(-50%); transform: translateY(-50%);"><div class="md-preloader"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="75" width="75" viewbox="0 0 75 75"><circle cx="37.5" cy="37.5" r="33.5" stroke-width="8"/></svg></div></div>--}}
{{--    </div>--}}

    <script src="{{ asset('js/base/func.js') }}"></script>
    <script src="{{ asset('js/base/repository.js') }}"></script>
    @include('dashboard.js-datasets')
    <script src="{{ asset('js/base/app.js') }}"></script>

    @stack('js')
</body>
</html>