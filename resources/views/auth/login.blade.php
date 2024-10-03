@extends('editor.layouts.layout_auth')

@section('metaTitle') @lang('auth.sign in') @endsection
@section('content')


<div class="form-container">
    <div class="form">
        <form method="POST" action="{{ route('login') }}" class="captcha">
            @csrf
            <div class="title">
                <div class="head">Sign In</div>
{{--                <div class="sub">Fill up your company details</div>--}}
            </div>
            {{--@if ($errors->has('email')) <span class="error">{{ $errors->first('email') }}</span>
            @elseif($errors->has('verified')) <span class="error">@lang('auth.error.verified')</span> <a class="additional_link" href="{{ route('auth.confirmation-email') }}?email={{ $errors->first('verified') }}">@lang('auth.resend confirmation link')</a>
            @elseif($errors->has('blocked')) <span class="error">@lang('auth.error.blocked')</span>
            @elseif($errors->has('access_denied')) <span class="error">@lang('auth.error.access_denied')</span>
            @elseif($errors->has('error')) <span class="error">@lang('auth.error.other')</span> @endif

            @if(session('register_check_your_email')) <span class="success">@lang('auth.success.register_check_your_email')</span>
            @elseif(session('resend_check_your_email')) <span class="success">@lang('auth.success.resend_check_your_email')</span>
            @elseif(session('check_your_email')) <span class="success">@lang('auth.success.check_your_email')</span>
            @elseif(session('email_confirmed')) <span class="success">@lang('auth.success.email_confirmed')</span>
            @elseif(session('password_was_changed')) <span class="success">@lang('auth.success.password_was_changed')</span> @endif--}}
            <input type="email" name="email" autofocus="true" placeholder="Email" maxlength="80" required class="input_shift" value="{{ old('email', !empty(session('email')) ? session('email') : null) }}">

            @if ($errors->has('password')) <span class="error">{{ $errors->first('password') }}</span> @endif
            <input type="password" name="password" placeholder="@lang('auth.password')" minlength="6" maxlength="50" required class="input_shift">
{{--            <a href="{{ route('auth.confirmation-password-reset') }}?email={{ old('email') }}" class="forgot">@lang('auth.forgot password')?</a>--}}
            <button class="submit">@lang('auth.login')</button>

            {{--<a href="{{ route('register') }}" class="sign_trigger">@lang('auth.create account')</a>
            <a href="{{ route('auth.confirmation-password-reset') }}?email={{ old('email') }}" class="forgot">@lang('auth.forgot password')?</a>--}}
        </form>
    </div>
</div>

@endsection

@push('css_1')
    <style>
        /*@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');*/

        .form-container  .form{
            position: relative;
        }

        .form-container .form form .title {
            margin-bottom: 60px;
        }
        .form-container .form form .title .head {
            font-family: Bebas Neue, sans-serif;
            font-style: normal;
            font-weight: normal;
            font-size: 48px;
            line-height: 109%;
            margin-bottom: 15px;
            color: #fff;
        }

        .form-container .form form .title .sub {
            font-family: var(--dashboard-font-family);
            font-style: normal;
            font-weight: normal;
            font-size: 16px;
            line-height: 150%;
            color: #81828B;
        }
        /*.form-container .form:after {*/
        /*    content: '';*/
        /*    position: absolute;*/
        /*    width: 2px;*/
        /*    height: 150%;*/
        /*    top: -25%;*/
        /*    left: -15px;*/
        /*    background-color: #6e8eaa;*/
        /*}*/

        .g_auth .form a.forgot {
            font-family: Helvetica Neue, sans-serif;
            font-weight: 500;
            font-size: 14px;
            line-height: 150%;
            margin: 16px 0 60px auto;
            text-decoration: none;
            float: right;
            text-align: right;
            color: #fff;
            letter-spacing: 0.5px;
            display: inline-block;
            -webkit-transition: color .2s;
            transition: color .2s;
        }
        .g_auth .form a.forgot:hover{
            color: #abcbd2;
            -webkit-transition: color .2s;
            transition: color .2s;
        }


        .g_auth .form a.additional_link{
            position: absolute;
            right: 0;
            top: 70px;
            color: #fff;
            font-size: 13px;
            -webkit-transition: color .2s;
            transition: color .2s;
            max-width: 200px;
            text-align: right;
        }
        .g_auth .form a.additional_link:hover{
            color: #ff9800;
            -webkit-transition: color .2s;
            transition: color .2s;
        }
    </style>
@endpush
@push('js')
    {{--<script>
        localStorage.clear();
        sessionStorage.clear();
    </script>--}}
@endpush
