@extends('dashboard.layouts.content')
@include('widget.bootstrap.datatable-form-push-resources')

@section('head_title') {{ $model->id ? 'Edit ID:'.$model->id : 'Add localization' }} @endsection
@section('title')
    <a href="{{ route('dashboard.localization.index') }}" class="arrow-back"><i class="material-icons">arrow_back</i></a>
    {{ $model->id ? 'Edit ID:'.$model->id : 'Add localization' }}
@endsection


@section('content')
    @include('widget.bootstrap.dialog-edit-image', ['isUpload' => false, 'isRemove' => false])
    <div class="base-form">
        @if($errors->count())
            <div class="modal fade" id="error_modal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-danger">Please make sure all fields are filled in correctly!</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-light m-0 p-0 text-center" style="font-weight: bold; font-size: 17px" role="alert">
                                @foreach($errors->all() as $i => $error)
                                    <span class="w-100 px-2 d-block pt-1 text-left text-dark">{{ $i + 1 }}. {{ $error }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            @push('js')
                <script>
                    $(function () {
                        $('#error_modal').modal('show');
                    });
                </script>
            @endpush
        @endif
        <form action="{{ route($model->id ? 'dashboard.localization.update' : 'dashboard.localization.store', ['model' => $model->id]) }}" autocomplete="off"  method="post" enctype="multipart/form-data" class="row">
            @csrf
            <input type="hidden" name="id" value="{{ !empty($model->id) ? $model->id : '' }}">


            @include('widget.bootstrap.form.text-input', ['id' => 'key', 'title' => 'Key', 'cell' => [6, 6, 12], 'maxlength' => 255, 'inputAttr' => 'autocomplete = off required'])
            <div class="col-12"></div>

            @include('widget.bootstrap.form.text-input', ['id' => 'value_en', 'title' => 'English value', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.text-input', ['id' => 'value_pt', 'title' => 'Portugal value', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])

            <div class="col-12 row pt-4 mt-4 mx-0 px-0"></div>
            <div class="col-12 footer">
                @include('widget.bootstrap.form.action-buttons', [
                    'cancelUrl' => route('dashboard.localization.index'),
                ])
                @include('widget.bootstrap.form.time-log')
            </div>
        </form>

        @if($model->id)
            <form id="destroy-form" action="{{ route('dashboard.localization.destroy', ['model' => $model->id]) }}" method="POST" style="display: none;"> @csrf </form>
        @endif
    </div>
@endsection

