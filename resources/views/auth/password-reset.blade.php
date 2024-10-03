@extends('editor.layouts.layout_auth')

@section('metaTitle') @lang('auth.password reset') @endsection
@section('content')

    <div class="form">
        <form method="POST" action="{{ route('auth.password-reset') }}" class="captcha">
            @csrf
            <div class="title">Reset password</div>
            {{--@if ($errors->has('email')) <span class="error">{{ $errors->first('email') }}</span> @endif
            <input type="{{ empty($email) || $errors->has('email') ? 'email' : 'hidden' }}" name="email" placeholder="@lang('auth.email')" value="{{ old('email', !empty($email) ? $email : null) }}" maxlength="80" required>--}}

            @if(!empty($oobCode))
                <input type="hidden" name="oobCode" value="{{ $oobCode }}">
            @endif


            @if ($errors->has('password')) <span class="error">{{ $errors->first('password') }}</span>
            @elseif ($errors->has('password_confirmation')) <span class="error">{{ $errors->first('password_confirmation') }}</span> @endif
            <input type="password" name="password" placeholder="@lang('auth.new password')" autocomplete="new-password" minlength="6" maxlength="50" required class="input_shift">
            <input type="password" name="password_confirmation" placeholder="@lang('auth.confirm new password')" autocomplete="new-password" minlength="6" maxlength="50" required class="input_shift">

            <button class="submit">@lang('web.send')</button>

            {{--<a href="{{ route('login') }}" class="sign_trigger">@lang('auth.login instead')</a>--}}
        </form>
    </div>

@endsection

@push('css_1')
    <style>
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
        .g_auth .form .title {
            font-size: 26px;
            text-align: center;
            padding-bottom: 20px;
        }
    </style>
@endpush