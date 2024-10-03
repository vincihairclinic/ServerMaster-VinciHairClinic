@extends('editor.layouts.app')

@section('layout')
    <body id="app-body" class="{{ \App\W::$isMobile ? 'isMobile' : 'isDesktop' }}">
    <div class="g_content">
        {{--        <div class="preview">--}}
        {{--            <img class="logo" src="{{ asset('/images/logo.svg') }}">--}}
        {{--        </div>--}}
        <ul class="bg-bubbles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
        <div class="g_auth">
            {{--            <a href="{{ route('home') }}" class="home noselect">Wekode<span class="sub">co.uk</span></a>--}}
            {{--            <div class="header">--}}
            {{--                @yield('headerDiv')--}}
            {{--                @include('web.widget.captcha')--}}
            {{--                <h1 class="title noselect">@yield('metaTitle')</h1>--}}
            {{--            </div>--}}

            @yield('content')

            {{--<div class="links">
                <a href="{{ route('web.about') }}">@lang('web.about')</a>
                <a href="{{ route('web.help') }}">@lang('web.help')</a>
                <a href="{{ route('web.faq') }}">FAQ</a>
                <a href="{{ route('web.privacy') }}">Privacy</a>
                <a href="{{ route('web.terms') }}">Terms</a>
            </div>--}}
        </div>
    </div>
    {{--    <div style="text-align: right; margin-top: 56px; position: relative;">--}}
    {{--        <img src="{{ asset('images/code_bg.png') }}" style="position: absolute; right: 0;">--}}
    {{--    </div>--}}
    @endsection

    @if(\App\AppConf::$is_dev_mode && \App\Application::pushLoad('layout_css'))
        @push('css_base')
            <style>
                @include('web.layouts.style_base')
            @include('web.layouts.style_web')

            body{
                {{--background: url({{ asset('images/404_bg.png') }}) repeat-y right #bcc5ca;--}}
            }
                .g_content{
                    padding: 0;
                    height: 100vh;
                    align-items: center;
                    background-color: var(--dashboard-login-background);
                    display: flex;
                    justify-content: center;
                    /*background-color: #202125;*/
                    box-shadow: 0 13px 7px -5px rgba(26,38,49,.09), 6px 15px 34px -6px rgba(33,48,73,.29);
                }
                .g_content .home{
                    color: #fff;
                    text-transform: uppercase;
                    font-size: 18px;
                    padding: 12px 0 12px 12px;
                    font-weight: bold;
                    text-decoration: none;
                    display: inline-block;
                    float: right;
                }
                .g_content .home .sub{
                    background-color: #6e8eaa;
                    line-height: 28px;
                    display: inline-block;
                    border-radius: 2px;
                    -webkit-transition: background-color .3s;
                    transition: background-color .3s;
                }
                .g_content .home:hover .sub{
                    background-color: #ff3f00;
                    -webkit-transition: background-color .3s;
                    transition: background-color .3s;
                }
                .g_content .preview {
                    width: 100%;
                    background-image: url({{ asset('/images/login-bg.png') }});
                    background-repeat: no-repeat;
                    background-size: cover;
                    background-position: center;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    overflow: hidden;
                }
                .g_content .preview .logo {
                    width: 20%;
                    min-width: 230px;
                }
                .g_auth{
                    width: 492px;
                    min-width: 492px;
                    border-radius: 6px;
                    max-width: 100%;
                    z-index: 30;
                    background-color: var(--dashboard-login-form);
                    padding: 60px 76px;
                    display: flex;
                    align-items: center;
                    /*box-shadow: 0 13px 7px -5px rgba(26,38,49,.09), 6px 15px 34px -6px rgba(33,48,73,.29);*/
                    /*box-shadow: 0 13px 7px -5px rgba(26,38,49,.09), 6px 15px 34px -6px rgba(33,48,73,.29), -17px 13px 56px 0 rgba(50,154,127,.35);*/
                    box-shadow: var(--dashboard-login-form-shadow);
                    font-size: 14px;
                    position: relative;
                }
                @media (max-width: 600px) {
                    .g_auth{
                        width: 100%;
                        min-width: auto;
                        padding: 0 20px;
                    }
                    .g_content .preview {
                        display: none;
                    }
                }

                .g_auth .header{
                    /*width: 300px;*/
                    overflow: hidden;
                }

                .g_auth .header .title{
                    color: #fff;
                    margin-top: 30px;
                    margin-bottom: 30px;
                    cursor: default;
                    text-align: center;
                    font-size: 40px;
                    line-height: 110%;
                }
                .bg-bubbles {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    overflow: hidden;
                    z-index: 1;
                }
                .bg-bubbles li {
                    position: absolute;
                    list-style: none;
                    display: block;
                    width: 40px;
                    height: 40px;
                    background-color: rgba(255, 255, 255, 0.15);
                    bottom: -160px;
                    -webkit-animation: square 25s infinite;
                    animation: square 25s infinite;
                    -webkit-transition-timing-function: linear;
                    transition-timing-function: linear;
                }
                .bg-bubbles li:nth-child(1) {
                    left: 10%;
                }
                .bg-bubbles li:nth-child(2) {
                    left: 20%;
                    width: 80px;
                    height: 80px;
                    animation-delay: 2s;
                    animation-duration: -17s;
                }
                .bg-bubbles li:nth-child(3) {
                    left: 25%;
                    animation-delay: -4s;
                }
                .bg-bubbles li:nth-child(4) {
                    left: 40%;
                    width: 60px;
                    height: 60px;
                    animation-duration: -22s;
                    background-color: rgba(255, 255, 255, 0.25);
                }
                .bg-bubbles li:nth-child(5) {
                    left: 70%;
                }
                .bg-bubbles li:nth-child(6) {
                    left: 80%;
                    width: 120px;
                    height: 120px;
                    animation-delay: 3s;
                    background-color: rgba(255, 255, 255, 0.2);
                }
                .bg-bubbles li:nth-child(7) {
                    left: 32%;
                    width: 160px;
                    height: 160px;
                    animation-delay: -7s;
                }
                .bg-bubbles li:nth-child(8) {
                    left: 55%;
                    width: 20px;
                    height: 20px;
                    animation-delay: 15s;
                    animation-duration: 40s;
                }
                .bg-bubbles li:nth-child(9) {
                    left: 25%;
                    width: 10px;
                    height: 10px;
                    animation-delay: 2s;
                    animation-duration: 30s;
                    background-color: rgba(255, 255, 255, 0.3);
                }
                .bg-bubbles li:nth-child(10) {
                    left: 90%;
                    width: 160px;
                    height: 160px;
                    animation-delay: 11s;
                }
                @-webkit-keyframes square {
                    0% {
                        transform: translateY(0);
                    }
                    100% {
                        transform: translateY(calc(-100vh - 200px)) rotate(600deg);
                    }
                }


                /*--------------------*/

                .g_auth .form input{
                    /*background-color: #e8f0fe;*/
                    margin: 0;
                    margin-bottom: 12px;
                    box-shadow: 0 0 0 0 black inset;
                    width: 100%;
                    padding: 0 8px;
                    border: 1px solid #fff;
                    box-sizing: border-box;
                    font-weight: 500;
                    font-size: 14px;
                    line-height: 20px;
                    color: #000000;
                    opacity: 0.9;
                    border-radius: 5px;
                    height: 38px;
                }
                .g_auth .form input::placeholder {
                    font-family: Helvetica Neue, sans-serif;
                    font-weight: 500;
                    padding-left: 12px;
                    margin-left: 0;
                    font-size: 14px;
                    line-height: 20px;
                    color: #81828B;
                    opacity: 0.7;
                }

                .g_auth .form .success{
                    color: #8bc34a;
                    font-size: 13px;
                    line-height: 14px;
                    display: inline-block;
                }
                .g_auth .form .success i{
                    color: #fff;
                    font-style: normal;
                    line-height: 14px;
                }

                .g_auth .form .error{
                    color: #c32d22;
                    font-weight: 600;
                    font-size: 13px;
                    line-height: 14px;
                    display: inline-block;
                }
                .g_auth .form .error i{
                    color: rgba(255, 255, 255, 0.61);
                    font-style: normal;
                    line-height: 14px;
                }
                .g_auth .form .error a{
                    color: #fff;
                    -webkit-transition: color .2s;
                    transition: color .2s;
                    line-height: 18px;
                    padding-bottom: 4px;
                    display: inline-block;
                }
                .g_auth .form .error a:hover{
                    color: #ff9800;
                    -webkit-transition: color .2s;
                    transition: color .2s;
                }

                .g_auth .form .submit{
                    border: none;
                    width: 100%;
                    margin:  80px auto 0;
                    padding: 16px 0;
                    background: #212529;
                    box-shadow: 0px 16px 40px rgba(142, 146, 167, 0.13);
                    border-radius: 5px;
                    text-transform: uppercase;
                    cursor: pointer;
                    -webkit-transition: background-color .3s;
                    transition: background-color .3s;
                    font-style: normal;
                    font-weight: 500;
                    font-size: 14px;
                    line-height: 150%;
                    text-align: center;
                    color: #FFFFFF;
                }
                .g_auth .form .submit:hover{
                    opacity: 0.9;
                    -webkit-transition: opacity .3s;
                    transition: opacity .3s;
                }

                /*---------------------------------*/

                .g_auth .links{
                    position: absolute;
                    bottom: -56px;
                    width: 300px;
                    display: -webkit-flex;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding-top: 36px;
                }

                .g_auth .links a{
                    color: #374250;
                    -webkit-transition: color .2s;
                    transition: color .2s;
                }
                .g_auth .links a:hover{
                    color: #ff3f00;
                    -webkit-transition: color .2s;
                    transition: color .2s;
                }

                .g_auth .form a.sign_trigger{
                    position: absolute;
                    right: 0;
                    color: #ff9800;
                    -webkit-transition: color .2s;
                    transition: color .2s;
                }
                .g_auth .form a.sign_trigger:hover{
                    color: #fff;
                    -webkit-transition: color .2s;
                    transition: color .2s;
                }

                .g_auth .error_captcha{
                    position: absolute;
                    top: 12px;
                    color: #f44336;
                    font-size: 13px;
                    line-height: 14px;
                }

                /*-------------------*/

                .g_auth .g_lang_list{
                    position: absolute;
                    bottom: 0;
                    margin: 0;
                }
                .g_auth .g_lang_list > span,
                .g_auth .g_lang_list > a{
                    border-color: rgba(197, 197, 197, 0.61);
                    background-color: transparent;
                    font-weight: normal;
                    font-size: 13px;
                }
                .g_auth .g_lang_list > a{
                    border: none;
                }
                .g_auth .g_lang_list > span{
                    color: #fff;
                }
            </style>
    @endpush
    @endif

