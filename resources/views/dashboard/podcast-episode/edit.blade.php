@extends('dashboard.layouts.content')
@include('widget.bootstrap.datatable-form-push-resources')

@section('head_title') {{ $model->id ? 'Edit ID:'.$model->id : 'Add podcast-episode' }} @endsection
@section('title')
    <a href="{{ route('dashboard.podcast-episode.index') }}" class="arrow-back"><i class="material-icons">arrow_back</i></a>
    {{ $model->id ? 'Edit ID:'.$model->id : 'Add product' }}
@endsection

@php
    $country = \App\Models\Country::all();
@endphp

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
        <form action="{{ route($model->id ? 'dashboard.podcast-episode.update' : 'dashboard.podcast-episode.store', ['model' => $model->id]) }}" autocomplete="off"  method="post" enctype="multipart/form-data" class="row">
            @csrf
            <input type="hidden" name="id" value="{{ !empty($model->id) ? $model->id : '' }}">

            <div class="col-12">&nbsp</div>
{{--{{ dd($model->toArray()) }}--}}
            <h3 class="col-4 pb-4">Images</h3>
            <h3 class="col-4 pb-4">English file</h3>
            <h3 class="col-4 pb-4">Portugal file</h3>

            @include('widget.bootstrap.form.wide-card-image', ['id' => 'image', 'cell' => [4, 4, 12],
                'showClearButton' => true,
                'title' => -1
            ])
            @include('widget.bootstrap.form.wide-card-media', ['id' => 'file_en', 'title' => 'English file', 'cell' => [4, 4, 12],
                'mediaType' => 'audio',
                'showClearButton' => true,
                'removeContainer' => true,
                'title' => -1
            ])
            @include('widget.bootstrap.form.wide-card-media', ['id' => 'file_pt', 'title' => 'Portugal file', 'cell' => [4, 4, 12],
                'mediaType' => 'audio',
                'showClearButton' => true,
                'removeContainer' => true,
                'title' => -1
            ])


            <div class="col-12 pt-5"></div>


            @include('widget.bootstrap.form.text-input', ['id' => 'name_en', 'title' => 'English name', 'cell' => [6, 6, 12], 'maxlength' => 500, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.text-input', ['id' => 'name_pt', 'title' => 'Portuguese name', 'cell' => [6, 6, 12], 'maxlength' => 500, 'inputAttr' => 'autocomplete = off required'])

            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'content_en', 'title' => 'English content', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'content_pt', 'title' => 'Portugal content', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])

            @include('widget.bootstrap.form.chosen-select-multiple', ['id' => 'tag_procedures', 'title' => 'Tag procedures', 'cell' => [4, 6, 12],
                   'options' => \App\Models\Procedure::all(),
            ])


            @include('widget.bootstrap.form.chosen-select-multiple', ['id' => 'tag_hair_types', 'title' => 'Tag hair type', 'cell' => [4, 6, 12],
                   'options' => \App\Models\HairType::all(),
            ])


            @include('widget.bootstrap.form.chosen-select-multiple', ['id' => 'tag_genders', 'title' => 'Tag gender', 'cell' => [4, 6, 12],
                   'options' => \App\Models\Gender::all(),
            ])

            @include('widget.bootstrap.form.text-input', ['id' => 'duration_min', 'title' => 'English name', 'cssClass' => 'd-none', 'cell' => [6, 6, 12]])

            <div class="col-12 row pt-4 mt-4 mx-0 px-0"></div>


            <div class="col-12 footer">
                @include('widget.bootstrap.form.action-buttons', [
                    'cancelUrl' => route('dashboard.podcast-episode.index'),
                ])
                @include('widget.bootstrap.form.time-log')
            </div>
        </form>



        @if($model->id)
            <form id="destroy-form" action="{{ route('dashboard.podcast-episode.destroy', ['model' => $model->id]) }}" method="POST" style="display: none;"> @csrf </form>
        @endif
    </div>
@endsection

@push('js')
    <script>
        $(function () {
            $('#file_wide-card audio').on('loadedmetadata', function () {
                $('#duration_min').val(Math.ceil($('#file_wide-card audio').get(0).duration / 60));
            });
        });
    </script>
@endpush
