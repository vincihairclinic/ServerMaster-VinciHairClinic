@extends('editor.layouts.layout_auth')

@section('metaTitle') @lang('auth.confirmation email') @endsection
@section('content')

    <div class="form">
        <form method="POST" action="{{ route('auth.confirmation-email') }}" class="captcha">
            @csrf

            @if ($errors->has('email')) <span class="error">{{ $errors->first('email') }}</span> @endif
            <input type="email" name="email" placeholder="@lang('auth.email')" value="{{ old('email', $email) }}" maxlength="80" required class="input_shift">

            <button class="submit">@lang('web.send')</button>

            <a href="{{ route('login') }}" class="sign_trigger">@lang('auth.login instead')</a>
        </form>
    </div>

@endsection

@push('css_1')
    <style>
        .g_auth{
            height: 300px;
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
    </style>
@endpush