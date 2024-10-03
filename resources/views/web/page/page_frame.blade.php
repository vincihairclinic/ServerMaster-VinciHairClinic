@extends('web.layouts.layout')

@section('content')
    <div class="g_content">
        <div class="g_frame cell g_page_frame">
            @yield('frame')
        </div>
    </div>
@endsection


@if((\App\W::$isAmp || \App\AppConf::$is_dev_mode) && \App\Application::pushLoad('style_page_css'))
    @push('css_1')
        @if(!\App\W::$isAmp) <style> @endif

            .g_content {
                padding-top: 0;
            }
            .g_page_frame{
                padding-bottom: 200px;
            }
            .g_page_frame h1{
                padding-bottom: 18px;
                font-weight: bold;
            }
            .g_page_frame h2{
                padding: 48px 0 18px;
            }
            .g_page_frame p{
                padding-bottom: 14px;
            }

            .g_page_frame ol,
            .g_page_frame ul{
                padding: 8px 0 8px 32px;
            }

            .g_page_frame .image img{
                max-width: 100%;
                object-fit: cover;
                display: block;
                margin: auto;
            }

        @if(!\App\W::$isAmp) </style> @endif
    @endpush
@endif