@extends('auth.layouts.layout')

@section('metaTitle') Error! @endsection
@section('content')

    <div class="form">
        <span class="success">{{--Please log in!--}}</span>
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

    </style>
@endpush