@extends('dashboard.layouts.content')
@include('widget.bootstrap.form.push-resources')
@section('head_title') {{ $model->id ? 'Edit '.$settingsListModels['name'].' ID:'.$model->id : 'Add '.$settingsListModels['name'] }} @endsection
@section('title')
    <a href="{{ route('dashboard.list-models.index',['settingsListModels' => $settingsListModels['id']]) }}"  class="arrow-back"><i class="material-icons">arrow_back</i></a>
    {{ $model->id ? 'Edit '.$settingsListModels['name'].' ID:'.$model->id.' '.$model->name : 'Add '.$settingsListModels['name'] }}
@endsection

@section('content')
{{--{{dd( $errors->all() )}}--}}
    <div class="base-form">
        <form action="{{ route($model->id ? 'dashboard.list-models.update' : 'dashboard.list-models.store', ['model' => $model, 'settingsListModels' => $settingsListModels['id']]) }}" method="post" enctype="multipart/form-data" class="row">
            @csrf
            <input type="hidden" name="id" value="{{ !empty($model->id) ? $model->id : '' }}">

            @include('widget.bootstrap.form.text-input', ['id' => 'name', 'title' => 'Name', 'cell' => [12, 12, 12], 'maxlength' => 255])

            <div class="col-12 footer">
                @include('widget.bootstrap.form.action-buttons', [
                    'cancelUrl' => route('dashboard.list-models.index',['settingsListModels' => $settingsListModels['id']]),
                ])
                @include('widget.bootstrap.form.time-log')
            </div>
        </form>

        @if($model->id)
            <form id="destroy-form" action="{{ route('dashboard.list-models.destroy',['model' => $model,'settingsListModels' => $settingsListModels['id']]) }}" method="POST" style="display: none;"> @csrf </form>
        @endif
    </div>
@endsection


