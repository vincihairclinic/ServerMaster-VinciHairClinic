@extends('editor.layouts.layout_auth')

@section('metaTitle') @lang('auth.create your account') @endsection
@section('headerDiv')  @endsection

@section('content')

    <div class="form">
        <form method="POST" action="{{ route('register') }}" class="captcha">
            @csrf

            @if ($errors->has('name')) <span class="error">{{ $errors->first('name') }}</span> @endif
            <input type="text" name="name" autofocus="true" placeholder="@lang('auth.name')" value="{{ old('name') }}" maxlength="40" class="input_shift">

            @if ($errors->has('email')) <span class="error">{{ $errors->first('email') }}</span> @endif
            <input type="email" name="email" placeholder="@lang('auth.email')" value="{{ old('email') }}" maxlength="80" required class="input_shift">

            @if ($errors->has('password')) <span class="error">{{ $errors->first('password') }}</span>
            @elseif ($errors->has('password_confirmation')) <span class="error">{{ $errors->first('password_confirmation') }}</span> @endif
            <input type="password" name="password" placeholder="@lang('auth.password')" autocomplete="new-password" minlength="6" maxlength="50" required class="input_shift">
            <input type="password" name="password_confirmation" placeholder="@lang('auth.confirm password')" autocomplete="new-password" minlength="6" maxlength="50" required class="input_shift">

            <button class="submit">@lang('auth.sign up')</button>

            <a href="{{ route('login') }}" class="sign_trigger">@lang('auth.login instead')</a>

            <p class="agreement">@lang('auth.register_agreement')</p>
            @include('web.widget.lang_list')
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
        .g_auth .agreement{
            color: rgba(255, 255, 255, 0.61);
            font-size: 13px;
            line-height: 16px;
            position: absolute;
            bottom: 38px;
        }

        .g_auth .agreement a{
            color: #fff;
            -webkit-transition: color .2s;
            transition: color .2s;
        }
        .g_auth .agreement a:hover{
            color: #ff9800;
            -webkit-transition: color .2s;
            transition: color .2s;
        }
    </style>
@endpush