@extends('editor.layouts.layout_auth')

@section('metaTitle') @lang('auth.password recovery') @endsection
@section('content')

    <div class="form">
        <form method="POST" action="{{ route('auth.confirmation-password-reset') }}" class="captcha">
            @csrf
            <div class="title">
                <div class="head">Forgot Your Password?</div>
                <div class="sub">No problem! Just tell us your email and we’ll send a password reset link right away</div>
            </div>
            @if ($errors->has('email')) <span class="error">{{ $errors->first('email') }}</span> @endif
            <input type="email" name="email" placeholder="@lang('auth.email')" value="{{ old('email', $email) }}" maxlength="80" required class="input_shift">

            <button class="submit">Reset Password</button>
            <a href="{{ route('login') }}" class="back">Back</a>

{{--            <a href="{{ route('login') }}" class="sign_trigger">@lang('auth.login instead')</a>--}}
        </form>
    </div>

@endsection

@push('css_1')
    <style>
        /*@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');*/
        .g_auth{
            height: 100%;
        }
        .g_auth .header .title{
            font-size: 38px;
        }
        .g_auth .agree_text{
            color: rgba(255, 255, 255, 0.61);
            font-size: 13px;
            line-height: 16px;
            position: absolute;
            bottom: 0;
        }

        .g_auth .agree_text a{
            color: #fff;
            -webkit-transition: color .2s;
            transition: color .2s;
        }
        .g_auth .agree_text a:hover{
            color: #ff9800;
            -webkit-transition: color .2s;
            transition: color .2s;
        }
        .form form .title {
            margin-bottom: 60px;
        }
        .form form .title .head {
            font-family: Bebas Neue, sans-serif;
            font-style: normal;
            font-weight: normal;
            font-size: 56px;
            line-height: 109%;
            margin-bottom: 15px;
            color: #000000;
        }

        .form form .title .sub {
            font-family: var(--dashboard-font-family);
            font-style: normal;
            font-weight: normal;
            font-size: 16px;
            line-height: 150%;
            color: #81828B;
        }
        .g_auth .form input {
            margin-bottom: 60px;
        }
        .g_auth .form .back{
            border: none;
            width: 100%;
            margin:  0 auto;
            padding: 16px 0;
            background: transparent;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            -webkit-transition: background-color .3s;
            transition: background-color .3s;
            font-style: normal;
            font-weight: 500;
            font-size: 14px;
            line-height: 150%;
            text-align: center;
            display: block;
            margin-top: 12px;
            color: #C6217F;
        }
        .g_auth .form .back:hover {
            opacity: 0.9;
            -webkit-transition: opacity .3s;
            transition: opacity .3s;
        }
    </style>
@endpush