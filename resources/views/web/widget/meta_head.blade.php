<title>{!! \App\W::$metaTitle !!}@yield('metaTitle')</title>
<meta name="description" content="{!! \App\W::$metaDescription !!}" />
<meta name="keywords" content="" />
{{--<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />--}}
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">

<meta property="og:type" content="company">
<meta property="og:title" content="{{ \App\W::$metaTitle }}">
<meta property="og:url" content="{{ request()->fullUrl() }}">
<meta property="og:site_name" content="{{ config('app.name') }}" />
<meta property="og:description" content="{{ \App\W::$metaDescription }}" />
<meta property="og:locale" content="{{ \App\AppConf::$lang }}" />

@if(!empty(\App\AppConf::$id_google_verification))
    <meta name="google-site-verification" content="{{ \App\AppConf::$id_google_verification }}" />
@endif

@if(!\App\W::$isAmp)
    @if(!empty(\App\AppConf::$id_yandex_verification))
        <meta name="yandex-verification" content="{{ \App\AppConf::$id_yandex_verification }}" />
    @endif
    @if(!empty(\App\AppConf::$id_mailru_verification))
        <meta name='wmail-verification' content='{{ \App\AppConf::$id_mailru_verification }}' />
    @endif
    @if(!empty(\App\AppConf::$id_google_ads) && Route::currentRouteName() != 'editor.index')
        <script data-ad-client="ca-pub-{{ \App\AppConf::$id_google_ads }}" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    @endif

    @if (!empty(\App\AppConf::$id_google_analytics))
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ \App\AppConf::$id_google_analytics }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ \App\AppConf::$id_google_analytics }}');
        </script>
    @endif

    @if (!empty(\App\AppConf::$id_adwords))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ \App\AppConf::$id_adwords }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '{{ \App\AppConf::$id_adwords }}');
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
@else
    @if (!empty(\App\AppConf::$id_google_analytics))
        <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    @endif
    @if(!empty(\App\AppConf::$is_adsense))
        <script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>
    @endif
@endif