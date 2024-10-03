@extends('editor.layouts.app')

@section('layout')
    <body id="app-body" class="{{ \App\W::$isMobile ? 'isMobile' : 'isDesktop' }}">
    @if(\App\AppConf::$is_adsense)
        <script>
            setTimeout(function () {
                var script = document.createElement('script');
                script.setAttribute('src', "https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js");
                script.setAttribute('async', true);
                @if(!\App\W::$isMobile)
                    script.onload = function () {
                    setTimeout(function () {
                        document.querySelectorAll('.product_item').forEach(function(e){
                            e.style.removeProperty('height');
                        });
                    }, 2000);
                };
                @endif
                document.body.appendChild(script);
            }, 2000);
        </script>
    @endif

    <div id="top"></div>

    <div class="layout grid">
        <div class="content_layer">
            @include('web.widget.header')
            @yield('content')
        </div>
        @include('web.widget.menu')
    </div>
@endsection

@if(\App\AppConf::$is_dev_mode && \App\Application::pushLoad('layout_css'))
@push('css_base')
    <style>
        @include('web.layouts.style_base')
        @include('web.layouts.style_web')

    </style>
@endpush
@endif
