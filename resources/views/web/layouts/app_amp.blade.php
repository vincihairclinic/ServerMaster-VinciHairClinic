<!doctype html>
<html amp lang="{{ \App\AppConf::$lang }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">

    @include('web.widget.meta_head')

    <link rel="canonical" href="{{ str_replace('://amp.', '://', request()->fullUrl()) }}">

    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    <style amp-custom>
        @stack('css_base')
        @stack('css_1')
        @stack('css_2')
        @stack('css_3')
        @stack('css_end')
    </style>

    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-lightbox-gallery" src="https://cdn.ampproject.org/v0/amp-lightbox-gallery-0.1.js"></script>
    <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
    <script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>

    @include('web.widget.ignore.stat-head-scripts')

    @include('web.widget.json_app')
    @stack('json')
</head>

@yield('layout')
    <amp-analytics type="gtag" data-credentials="include">
        <script type="application/json">
                    {
                      "vars" : {
                        "gtag_id": "{{ \App\AppConf::$id_google_analytics }}",
                        "config" : {
                          "{{ \App\AppConf::$id_google_analytics }}": { "groups": "default" }
                        }
                      }
                    }
                </script>
    </amp-analytics>

</body>
</html>