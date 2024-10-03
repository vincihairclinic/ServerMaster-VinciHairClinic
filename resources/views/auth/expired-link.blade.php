@extends('auth.layouts.layout')

@section('metaTitle') Expired! @endsection
@section('content')

    <div class="form">
        <span class="success">
            <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M70 40C70 23.4333 56.5633 10 40 10C23.4367 10 10 23.4333 10 40C10 56.56 23.4367 70 40 70C56.5633 70 70 56.56 70 40Z" stroke="#C6217F" stroke-width="3" stroke-miterlimit="10"/>
                <path d="M50 50L30 30" stroke="#C6217F" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M30 50L50 30" stroke="#C6217F" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
        <div class="msg-container">
            Try again!
        </div>
    </div>

@endsection

@push('css_1')
    <style>
        .g_auth{
            height: 300px;
        }
        .g_auth .header .title{
            font-size: 48px;
            color: #C6217F;
            text-shadow: 2px 2px 2px rgba(198, 33, 127, 0.16);
        }
        .g_content {
            padding: 0;
        }
        .g_auth .form {
            text-align: center;
        }
        .g_auth .form svg {
            width: 150px;
            height: 150px;
            margin-top: 50px;
        }
        .msg-container {
            margin-top: 40px;
            font-size: 18px;
            color: #C6217F;
            text-shadow: 2px 2px 1px rgba(168, 168, 168, 0.2);
            font-weight: bold;
        }
    </style>
@endpush