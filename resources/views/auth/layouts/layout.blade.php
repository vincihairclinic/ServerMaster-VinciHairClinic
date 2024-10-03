@extends('auth.layouts.app')

@section('layout')
    <body id="app-body" class="{{ \App\W::$isMobile ? 'isMobile' : 'isDesktop' }}">
    <div class="g_content">
        <div class="g_auth">
            <div class="header">
                <h1 class="title noselect">@yield('metaTitle')</h1>
            </div>

            @yield('content')
        </div>
    </div>
    <div style="text-align: right; margin-top: 56px; position: relative;">
    </div>
    @endsection

    @if(\App\AppConf::$is_dev_mode && \App\Application::pushLoad('layout_css'))
        @push('css_base')
            <style>

                body{
                {{--                    background: url({{ asset('images/404_bg.png') }}) repeat-y right #bcc5ca;--}}
}
                .g_content{
                    padding: 0 8px 8px 0;
                    /*background-color: #202125;*/
                    /*box-shadow: 0 13px 7px -5px rgba(26,38,49,.09), 6px 15px 34px -6px rgba(33,48,73,.29);*/
                }
                .g_auth{
                    width: 300px;
                    margin: auto;
                    /*background-color: #374250;*/
                    /*box-shadow: 0 13px 7px -5px rgba(26,38,49,.09), 6px 15px 34px -6px rgba(33,48,73,.29);*/
                    height: 320px;
                    font-size: 14px;
                    position: relative;
                }

                .g_auth .header{
                    width: 300px;
                    overflow: hidden;
                }

                .g_auth .header .title{
                    color: #E82C68;
                    text-align: center;
                    text-shadow: 2px 2px 3px rgba(232, 44, 104, 0.3);
                    margin-top: 62px;
                    margin-bottom: 12px;
                    cursor: default;
                    font-size: 40px;
                    line-height: 110%;
                }

                .g_auth .header .home{
                    color: #fff;
                    text-transform: uppercase;
                    font-size: 18px;
                    padding: 12px 0 12px 12px;
                    font-weight: bold;
                    text-decoration: none;
                    display: inline-block;
                    float: right;
                }
                .g_auth .header .home .sub{
                    background-color: #6e8eaa;
                    line-height: 28px;
                    display: inline-block;
                    border-radius: 2px;
                    -webkit-transition: background-color .3s;
                    transition: background-color .3s;
                }
                .g_auth .header .home:hover .sub{
                    /*background-color: #ff3f00;*/
                    -webkit-transition: background-color .3s;
                    transition: background-color .3s;
                }

                /*--------------------*/

                .g_auth .form input{
                    background-color: #e8f0fe;
                    margin: 0;
                    margin-bottom: 8px;
                    width: 100%;
                    padding: 0 8px;
                    border-radius: 2px;
                    height: 38px;
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
                    color: #f44336;
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
                    background-color: #6e8eaa;
                    border: none;
                    padding: 0;
                    margin: 0;
                    color: #fff;
                    width: 50%;
                    height: 32px;
                    font-size: 13px;
                    line-height: 32px;
                    border-radius: 2px;
                    text-transform: uppercase;
                    cursor: pointer;
                    -webkit-transition: background-color .3s;
                    transition: background-color .3s;
                }
                .g_auth .form .submit:hover{
                    background-color: #ff9800;
                    -webkit-transition: background-color .3s;
                    transition: background-color .3s;
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
            </style>
    @endpush
    @endif

