@extends('dashboard.layouts.content')
@include('widget.bootstrap.datatable-form-push-resources')

@section('head_title') {{ $model->id ? 'Edit ID:'.$model->id : 'Add video' }} @endsection
@section('title')
    <a href="{{ route('dashboard.video.index') }}" class="arrow-back"><i class="material-icons">arrow_back</i></a>
    {{ $model->id ? 'Edit ID:'.$model->id : 'Add video' }}
@endsection

{{--{{ dd($model->toArray()) }}--}}

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
        <form action="{{ route($model->id ? 'dashboard.video.update' : 'dashboard.video.store', ['model' => $model->id]) }}" autocomplete="off"  method="post" enctype="multipart/form-data" class="row">
            @csrf
            <input type="hidden" name="id" value="{{ !empty($model->id) ? $model->id : '' }}">

            <div class="col-12">&nbsp</div>

            <div class="col-12">
                <iframe width="600" height="400" src="https://www.youtube.com/embed/{{ $model->source }}"></iframe>
            </div>

            <div class="col-12"></div>

            @include('widget.bootstrap.form.text-input', ['id' => 'title', 'title' => 'Title', 'cell' => 12, 'maxlength' => 100, 'inputAttr' => 'autocomplete = off required'])

            @include('widget.bootstrap.form.chosen-select-multiple', ['id' => 'tag_procedures', 'title' => 'Tag procedures', 'cell' => [4, 6, 12],
                   'options' => \App\Models\Procedure::all(),
            ])


            @include('widget.bootstrap.form.chosen-select-multiple', ['id' => 'tag_hair_types', 'title' => 'Tag hair type', 'cell' => [4, 6, 12],
                   'options' => \App\Models\HairType::all(),
            ])


            @include('widget.bootstrap.form.chosen-select-multiple', ['id' => 'tag_genders', 'title' => 'Tag gender', 'cell' => [4, 6, 12],
                   'options' => \App\Models\Gender::all(),
            ])
            <div class="col-12 row pt-4 mt-4 mx-0 px-0"></div>
            <div class="col-12 footer">
                @include('widget.bootstrap.form.action-buttons', [
                    'cancelUrl' => route('dashboard.video.index'),
                ])
                @include('widget.bootstrap.form.time-log')
            </div>
        </form>

        @if($model->id)
            <form id="destroy-form" action="{{ route('dashboard.video.destroy', ['model' => $model->id]) }}" method="POST" style="display: none;"> @csrf </form>
        @endif
    </div>
@endsection

