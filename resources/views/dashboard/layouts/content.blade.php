@extends('dashboard.layouts.app')

@section('main')
    <body>
    <section class="main-dashboard-container">
        <nav class="sidebar-container" id="app-sidebar-container">
            <div class="logo justify-content-center align-items-center position-relative d-flex">
                {{--                    @include('svg.color-logo')--}}
                <img class="logo" src="{{ asset('images/logo.png') }}">
            </div>
            <ul class="sidebar-list p-0 d-flex flex-column">
                @if(\App\Access::is([\App\Models\Datasets\UserRole::ADMIN]))
                    @if(config('app.debug'))

                    @endif

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.user.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.user.') ? ' is-active' : '' }}"><div class="img"></div>User</a>
                        <hr>
                    </li>

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.territory.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.territory.') ? ' is-active' : '' }}"><div class="img"></div>Territory</a>
                    </li>

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.country.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.country.') ? ' is-active' : '' }}"><div class="img"></div>Country</a>
                    </li>

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.language.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.language.') ? ' is-active' : '' }}"><div class="img"></div>Language</a>
                    </li>

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.localization.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.localization.') ? ' is-active' : '' }}"><div class="img"></div>Localization</a>
                        <hr>
                    </li>

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.clinic.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.clinic.') ? ' is-active' : '' }}"><div class="img"></div>Clinic</a>
                    </li>

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.hair-loss-type.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.hair-loss-type.') ? ' is-active' : '' }}"><div class="img"></div>Hair loss type</a>
                    </li>

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.hair-type.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.hair-type.') ? ' is-active' : '' }}"><div class="img"></div>Hair type</a>
                        <hr>
                    </li>

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.procedure.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.procedure.') ? ' is-active' : '' }}"><div class="img"></div>Procedure</a>
                    </li>

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.procedure-result.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.procedure-result.') ? ' is-active' : '' }}"><div class="img"></div>Procedure result</a>
                        <hr>
                    </li>

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.article.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.article.') ? ' is-active' : '' }}"><div class="img"></div>Article</a>
                    </li>

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.article-category.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.article-category.') ? ' is-active' : '' }}"><div class="img"></div>Article category</a>
                        <hr>
                    </li>

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.book.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.book.') ? ' is-active' : '' }}"><div class="img"></div>Book</a>
                        <hr>
                    </li>

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.product.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.product.') ? ' is-active' : '' }}"><div class="img"></div>Product</a>
                    </li>

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.product-category.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.product-category.') ? ' is-active' : '' }}"><div class="img"></div>Product category</a>
                        <hr>
                    </li>

{{--                    <li class="sidebar-list-item">--}}
{{--                        <a href="{{ route('dashboard.podcast.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.podcast.') ? ' is-active' : '' }}"><div class="img"></div>Podcast</a>--}}
{{--                    </li>--}}


                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.podcast-episode.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.podcast-episode.') ? ' is-active' : '' }}"><div class="img"></div>Podcast episodes</a>
                    </li>

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.user-simulation-request.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.user-simulation-request.') ? ' is-active' : '' }}"><div class="img"></div>User simulation request</a>
                    </li>

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.book-consultation.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.book-consultation.') ? ' is-active' : '' }}"><div class="img"></div>Book consultation</a>
                    </li>


                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.request-contact-from-clinic.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.request-contact-from-clinic.') ? ' is-active' : '' }}"><div class="img"></div>Request contact from clinic</a>
                    </li>

                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.simulation-request-type.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.simulation-request-type.') ? ' is-active' : '' }}"><div class="img"></div>Simulation request type</a>
                        <hr>
                    </li>

                    @foreach(\App\Models\Datasets\SettingsLink::$data as $item)
                        <li class="sidebar-list-item">
                            <a href="{{ route('dashboard.settings-link.edit', ['model' => $item['id']]) }}" class="w-100{{ request()->url() == route('dashboard.settings-link.edit', ['model' => $item['id']]) || mb_strpos(request()->url(), route('dashboard.settings-link.edit', ['model' => $item['id']]).'/') !== false ? ' is-active' : '' }}"><div class="img"></div>{{ $item['name'] }}</a>
                        </li>
                    @endforeach
                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.file.edit') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.file.') ? ' is-active' : '' }}"><div class="img"></div>Video</a>
                    </li>
                    <li class="sidebar-list-item">
                        <a href="{{ route('dashboard.video.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.video.') ? ' is-active' : '' }}"><div class="img"></div>Social Video</a>
                    </li>

                    {{--                        <li class="sidebar-list-item">--}}
                    {{--                            <a href="{{ route('dashboard.user.index') }}" class="w-100{{ str_contains(Route::currentRouteName(), 'dashboard.user' ? ' is-active' : '' }}"><div class="img">@include('svg.serial_codes')</div>Serial Codes</a>--}}
                    {{--                            <hr>--}}
                    {{--                        </li>--}}

                    {{--                        <li class="sidebar-list-item">--}}
                    {{--                            <a href="{{ route('dashboard.user.index') }}" class="w-100{{ request()->url() == route('dashboard.user.index') || mb_strpos(request()->url(), route('dashboard.user.index').'/') !== false ? ' is-active' : '' }}">User</a>--}}
                    {{--                        </li>--}}


                    {{--                        @foreach(\App\Models\Page::all() as $item)--}}
                    {{--                            <li class="sidebar-list-item">--}}
                    {{--                                <a href="{{ route('dashboard.page.index', ['model' => $item->route]) }}" class="d-block w-100{{ request()->url() == route('dashboard.page.index', ['model' => $item->route]) ? ' is-active' : '' }}">{{ $item->name }}</a>--}}
                    {{--                            </li>--}}
                    {{--                        @endforeach--}}
                    {{--                        <li class="sidebar-list-item">--}}
                    {{--                            <button class="sidebar-list-btn position-relative text-left d-block w-100 border-0 bg-transparent {{ mb_strpos(request()->url(), route('dashboard.user.index').'/') !== false ? '' : 'sidebar-hidden' }}">User</button>--}}
                    {{--                            <ul class="sidebar-list-attachments pl-0 position-relative">--}}
                    {{--                                <li class="attachments-list">--}}
                    {{--                                    <a href="{{ route('dashboard.user.index') }}" class="py-2 d-block w-100{{ request()->url() == route('dashboard.user.list') || mb_strpos(request()->url(), route('dashboard.user.list').'/') !== false ? ' is-active' : '' }}">All</a>--}}
                    {{--                                </li>--}}
                    {{--                                <li class="attachments-list">--}}
                    {{--                                    <a href="{{ route('dashboard.user.approve.identity.index') }}" class="py-2 d-block w-100{{ request()->url() == route('dashboard.user.approve.identity.index') || mb_strpos(request()->url(), route('dashboard.user.approve.identity.index').'/') !== false ? ' is-active' : '' }}">Identity Approve</a>--}}
                    {{--                                </li>--}}
                    {{--                                <li class="attachments-list">--}}
                    {{--                                    <a href="{{ route('dashboard.user.approve.employment.index') }}" class="py-2 d-block w-100{{ request()->url() == route('dashboard.user.approve.employment.index') || mb_strpos(request()->url(), route('dashboard.user.approve.employment.index').'/') !== false ? ' is-active' : '' }}">Employment Approve</a>--}}
                    {{--                                </li>--}}
                    {{--                            </ul>--}}
                    {{--                        </li>--}}

                    {{--                        @foreach(\App\Models\Datasets\ListModels::$data as $item)--}}
                    {{--                            <li class="sidebar-list-item">--}}
                    {{--                                <a href="{{ route('dashboard.list-models.index', ['settingsListModels' => $item['id']]) }}" class="d-block w-100{{ request()->url() == route('dashboard.list-models.index', ['settingsListModels' => $item['id']]) || mb_strpos(request()->url(), route('dashboard.list-models.index', ['settingsListModels' => $item['id']]).'/') !== false ? ' is-active' : '' }}">{{ $item['name'] }}</a>--}}
                    {{--                            </li>--}}
                    {{--                        @endforeach--}}

                    {{--                        <li class="sidebar-list-item">--}}
                    {{--                            <a href="{{ route('dashboard.privacy.index') }}" class="d-block w-100{{ request()->url() == route('dashboard.privacy.index') || mb_strpos(request()->url(), route('dashboard.privacy.index').'/') !== false ? ' is-active' : '' }}">Privacy policy</a>--}}
                    {{--                        </li>--}}
                    <li class="sidebar-list-item flex-grow-1"></li>
                    <li class="sidebar-list-item">
                        <hr>
                        <a href="{{ route('logout') }}" class="d-block w-100"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><div class="img">@include('svg.logout')</div>{{ __('Logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              style="display: none;">@csrf</form>
                    </li>
                @endif
            </ul>
        </nav>
        <div class="sidebar-canvas" onclick="toggleSidebar()"></div>
        <section class="dashboard-content-container">
            <div class="dashboard-content">
                @include('dashboard.alert.alert-container')
                <header class="mb-4">
                    <div class="header container-fluid py-3 position-relative">
                        <div class="container-fluid">
                            <div class="row no-gutters justify-content-between position-relative pb-2">
                                <div class="row no-gutters col pl-0">
                                    <div class="sidebar-toggle-btn align-self-center pr-3">
                                        <div class="nav-tab" onclick="toggleSidebar()">
                                            <a href="javascript:void(0);">
                                                <span class="first-child">&nbsp;</span>
                                                <span class="second-child">&nbsp;</span>
                                                <span class="third-child">&nbsp;</span>
                                                <span class="fourth-child">&nbsp;</span>
                                            </a>
                                        </div>
                                    </div>
                                    <h3 class="text-white m-0">@yield('title')</h3>
                                </div>
                                @guest
                                    <div class="col align-items-end mt-auto">
                                        <nav class="navigation-list text-right">
                                            <a class="navigation-link px-2"
                                               href="{{ route('login') }}">{{ __('Login') }}</a>
                                            <a class="navigation-link px-2"
                                               href="{{ route('register') }}">{{ __('Register') }}</a>
                                        </nav>
                                    </div>
                                @else
                                    @yield('global-search-input')
                                @endguest
                            </div>
                        </div>
                    </div>
                    @yield('tab_bar')
                </header>
                <div class="dashboard-content">
                    <div class="container-fluid px-3">
                        @yield('content')
                    </div>
                </div>
            </div>
        </section>
    </section>
    @push('js')
        <script>
            function toggleSidebar(){
                $('#app-sidebar-container').toggleClass('show');
            }
        </script>
    @endpush

    @push('css_2')
        @if(!\App\W::$isAmp) <style> @endif
                @import url('https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&display=swap');
            @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css");

            .sidebar-container {
                position: fixed;
                font-family: var(--dashboard-sidebar-font-famaly);
                height: 100vh;
                width: 300px;
                z-index: 10;
                background: #FBFAFA;
                box-shadow: 0 2px 30px rgba(51, 51, 51, 0.1);
            }
            .sidebar-container .logo {
                margin: auto;
                padding: 15px;
                height: 150px;
                background: var(--dashboard-sidebar-logo-color);
                /*box-shadow: 0 2px 30px rgba(0, 0, 0, 0.1);*/
            }
            .sidebar-container .logo img {
                max-width: 100%;
                max-height: 100%;
                width: fit-content;
                height: fit-content;
            }
            .sidebar-toggle{
                display: none;
            }
            .sidebar-toggle-btn{
                display: none;
            }
            .sidebar-list ul {
                list-style: none;
            }
            .sidebar-list {
                height: calc(100vh - 150px);
                overflow: auto;
                overflow: overlay;
                background: var(--dashboard-sidebar-navbar-color);
                list-style: none;
            }
            .sidebar-list::-webkit-scrollbar-track
            {
                background-color: #fff;
            }

            .sidebar-list::-webkit-scrollbar
            {
                width: 6px;
                background-color: #fff;
            }
            .sidebar-list hr{
                margin: 0;
            }
            .sidebar-list::-webkit-scrollbar-thumb
            {
                background-color: #c2c2c2;
            }
            .sidebar-list li {
                cursor: pointer;
            }
            .sidebar-list a {
                border-radius: 0 3px 3px 0;
                color: #212529;
                text-decoration: none;
                padding: 20px 0 20px 40px;
                position: relative;
                font-style: normal;
                font-weight: 500;
                font-size: 16px;
                display: flex;
                align-items: center;
                line-height: 100%;
            }
            .sidebar-list a .img {
                display: inline-block;
                margin-right: 21px;
            }
            .sidebar-list a .img svg{
                width: 21px;
                height: 21px;
            }
            .sidebar-list a.is-active::after {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 1px;
                height: 100%;
                background: radial-gradient(185.44% 100.68% at 98.86% -0.68%, #66E8CF 0%, #0A73FF 100%);
            }
            .sidebar-list .sidebar-list-btn {
                color: #333;
                position: relative;
                font-size: 17px;
            }
            .sidebar-list a.is-active {
                background-color: var(--dashboard-sidebar-active-color);
            }
            .sidebar-list a path {
                fill: #503629;
            }
            .sidebar-list a.is-active path, .sidebar-list a:hover path {
                fill: #503629;
            }
            .sidebar-list a.is-active .personalifit path:nth-of-type(1), .sidebar-list a:hover .personalifit path:nth-of-type(1), .sidebar-list a.is-active .personalifit path:nth-of-type(2), .sidebar-list a:hover .personalifit path:nth-of-type(2) {
                fill: #503629;
            }

            .sidebar-list a:hover {
                background-color: var(--dashboard-sidebar-active-color);
                filter: brightness(0.9);
            }
            .sidebar-list a i {
                color: #0c2317;
                font-size: 20px;
            }

            .dashboard-content-container {
                width: calc(100% - 300px);
                margin-left: 300px;
                min-height: 100vh;
                position: relative;
            }

            .sidebar-list .sidebar-list-btn:after {
                position: absolute;
                top: 50%;
                width: 24px;
                height: 24px;
                transform: translateY(-50%) rotate(90deg);
                right: 10px;
                transition: 0.15s;
                content: url('{{asset('/images/icon/chevron_right.svg')}}');
            }

            .sidebar-list .sidebar-list-btn.sidebar-hidden:after {
                transform: translateY(-50%) rotate(0deg);
            }
            .sidebar-list-btn + .sidebar-list-attachments {
                /*transform: scaleY(1);*/
                max-height: 230px;
                overflow: hidden;
                /*background: linear-gradient(340deg, rgba(71, 161, 136, 0.15), transparent 40%);*/
                transition: max-height .3s ease-out .1s, background .2s;
            }

            .sidebar-list-btn.sidebar-hidden + .sidebar-list-attachments {
                max-height: 0!important;
                transition: max-height .3s ease-out;
            }

            .sidebar-list-btn + .sidebar-list-attachments:has(.is-active) {
                background-color: #0a6aa1!important;
            }
            .sidebar-list-btn.sidebar-active + .sidebar-list-attachments {
                transition: max-height 0s;
            }
            .sidebar-list .attachments-list a{
                font-size: 15px;
                padding-left: 2em;
            }
            /*.sidebar-list-btn.open + .sidebar-list-attachments, .sidebar-list-btn.open + .sidebar-list-attachments * {*/
            /*    font-size: 0;*/
            /*    margin: 0;*/
            /*    opacity: 0 !important;*/
            /*    padding: 0 !important;*/
            /*}*/

            @media (max-width: 1024px) {
                .sidebar-container {
                    width: 0;
                    transition: 0.3s;
                }

                .sidebar-container.show {
                    width: 300px;
                }

                .sidebar-container.show ~ .sidebar-canvas {
                    width: 100vw;
                    height: 100vh;
                    background-color: black;
                    position: fixed;
                    z-index: 9;
                    top: 0;
                    left: 0;
                    opacity: 0.3;
                }

                .dashboard-content-container {
                    width: 100%;
                    margin-left: 0;
                }
                .sidebar-toggle-btn{
                    display: block;
                }
            }
            /*.header:after {*/
            /*    content: '';*/
            /*    width: 100%;*/
            /*    height: 35px;*/
            /*    background-color: #64C5B1;*/
            /*    left: 0;*/
            /*    position: absolute;*/
            /*    z-index: -1;*/
            /*    top: 100%;*/
            /*}*/
            header .header {
                background: var(--dashboard-container-navbar-color);
                height: 75px;
                z-index: 10;
                box-shadow: 0px 58px 103px rgba(181, 181, 181, 0.11), 0px 29.3625px 44.9016px rgba(181, 181, 181, 0.07425), 0px 11.6px 16.7375px rgba(181, 181, 181, 0.055), 0px 2.5375px 5.95469px rgba(181, 181, 181, 0.03575);
                clip-path: inset(0 0 -155px 0);
                /*box-shadow: 0 12px 30px rgba(80, 143, 244, 0.1);*/
            }

            .sidebar-toggle-btn .nav-tab a {
                cursor: pointer;
                display: block;
                color: transparent;
                width: 100%;
            }

            .sidebar-toggle-btn .nav-tab span {
                background: #e1e7f4;
                display: block;
                width: 30px;
                height: 4px;
                margin-bottom: 3px;
                transition: all ease-in-out .4s;
                -webkit-transition: all ease-in-out .4s;
            }
            .nav.nav-tabs {
                background: var(--dashboard-container-navbar-color);
                font-weight: bold;
            }
            .nav.nav-tabs .nav-link{
                min-width: 80px;
                text-align: center;
                color: #fff;
            }
            .nav.nav-tabs .nav-link.active{
                color: #333;
            }
            .nav-tabs .nav-link:focus, .nav-tabs .nav-link:hover {
                border-color: #ffffff45;
            }
            .nav.nav-tabs .nav-link {
                padding: 0.3em 1em;
            }
            .ds-container > .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 42px;
            }
            .ds-container > .header .title {
                font-family: Bebas Neue, sans-serif;
                font-style: normal;
                font-weight: normal;
                font-size: 48px;
                line-height: 150%;
                color: #000000;
            }
            .ds-container > .header .user-info {
                display: flex;
                align-items: center;
            }
            .ds-container > .header .user-info .info-container {
                margin-right: 16px;
            }

            .ds-container > .header .user-info .info-container .name {
                font-family: var(--dashboard-sidebar-font-famaly);
                font-style: normal;
                font-weight: 500;
                font-size: 14px;
                line-height: 150%;
                text-align: right;
                color: #000000;
            }
            .ds-container > .header .user-info .info-container .role {
                font-family: var(--dashboard-sidebar-font-famaly);
                font-style: normal;
                font-weight: normal;
                font-size: 14px;
                line-height: 150%;
                text-align: right;
                color: #81828B;
            }
            .ds-container > .header .user-info img {
                width: 48px;
                height: 48px;
                object-fit: cover;
                border-radius: 50%;
            }


            #preloader {
                display: none !important;
            }
            @if(!\App\W::$isAmp) </style> @endif
    @endpush


    @endsection

    @push('js')
        <script>
            var dataTable;
            var tempDataTable = {};

            var defaultImageUrl = '{{ asset('images/base/upload-image-default.png') }}';
            var storageUrl = '{{ asset('storage') }}';
            var pathImage = '{{ \App\Application::storageImageAsset() }}';
            var baseAdminUrl = '{{ url('').'/dashboard/' }}';
            var baseUrl = '{{ url('').'/' }}';
            var userLanguageId = '{{ app()->getLocale() }}';
            var expandAllFlag = 0;
            $(function () {
                $('.sidebar-container button.sidebar-list-btn').on('click', function () {
                    $(this).toggleClass('sidebar-hidden');
                });
                $('.is-invalid').on('change', function () {
                    $(this).removeClass('is-invalid');
                });
            });
        </script>
        <script src="{{ asset('js/base/dashboard.js') }}"></script>
    @endpush
