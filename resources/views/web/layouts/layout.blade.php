@extends('web.layouts.app'.(\App\W::$isAmp ? '_amp' : ''))

@section('layout')
    <body id="app-body" class="mdl-js {{ \App\W::$isAmp ? 'isAmp' : '' }} {{ \App\W::$isMobile ? 'isMobile' : 'isDesktop' }} {{ !empty(Auth::user()) ? 'auth' : 'guest' }}">
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KWJWQ9Q" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    @include('web.widget.ignore.stat-body-scripts')

    <div id="top"></div>

    <div class="layout grid">
        <div class="content_layer">
            @include('web.widget.header')
            @yield('content')
            @include('web.widget.footer')
        </div>
        @include('web.widget.menu')
    </div>
@endsection

@if((\App\W::$isAmp || \App\AppConf::$is_dev_mode) && \App\Application::pushLoad('layout_css'))
@push('css_base')
    @if(!\App\W::$isAmp) <style> @endif
        @include('web.layouts.style_base')
        @include('web.layouts.style_web')

        p, div, h1, h2, h3, h4 {
            cursor: default;
        }

        body.disable_scroll{
            overflow-y: auto;
            position: static;
        }
        @media screen and (max-width: {{ \App\AppConf::$width_content[2] + \App\AppConf::$width_menu[1] + 62 }}px) {
            body.disable_scroll{
                overflow-y: scroll;
                position: fixed;
            }
        }


    @if(!\App\W::$isAmp) </style> @endif
@endpush
@endif