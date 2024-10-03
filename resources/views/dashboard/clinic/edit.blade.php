@extends('dashboard.layouts.content')
@include('widget.bootstrap.datatable-form-push-resources')

@section('head_title') {{ $model->id ? 'Edit ID:'.$model->id : 'Add clinic' }} @endsection
@section('title')
    <a href="{{ route('dashboard.clinic.index') }}" class="arrow-back"><i class="material-icons">arrow_back</i></a>
    {{ $model->id ? 'Edit ID:'.$model->id : 'Add clinic' }}
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
        <form action="{{ route($model->id ? 'dashboard.clinic.update' : 'dashboard.clinic.store', ['model' => $model->id]) }}" autocomplete="off"  method="post" enctype="multipart/form-data" class="row">
            @csrf
            <input type="hidden" name="id" value="{{ !empty($model->id) ? $model->id : '' }}">


            @include('widget.bootstrap.form.wide-card-media', ['id' => 'image', 'title' => 'Main image', 'cell' => [4, 4, 12],
                'withoutWideCardDelete' => false,
            ])

            <h3 class="col-12 mb-4">Images</h3>
            @include('widget.bootstrap.form.medias', ['id' => 'images', 'title' => -1, 'cell' => [4, 4, 12],
                'showClearButton' => true,
            ])

            <div class="col-12 pb-5"></div>

            @include('widget.bootstrap.form.text-input', ['id' => 'name_en', 'title' => 'English name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.text-input', ['id' => 'name_pt', 'title' => 'Portuguese name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])

            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'about_en', 'title' => 'English about', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'about_pt', 'title' => 'Portugal about', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])

            @include('widget.bootstrap.form.array.text-input-container', ['id' => 'benefits_en', 'title' => 'English benefits', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.array.text-input-container', ['id' => 'benefits_pt', 'title' => 'Portugal benefits', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])

            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'lat', 'title' => 'Lat', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'lng', 'title' => 'Lng', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])

            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'about_clinic_location_en', 'title' => 'English Text under Location tag', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'about_clinic_location_pt', 'title' => 'Portuguese Text under Location tag', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])


            <div class="col-12 pb-5"></div>
            @include('widget.bootstrap.form.text-input', ['id' => 'postcode', 'title' => 'Postcode', 'cell' => [6, 6, 12], 'maxlength' => 20, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'address', 'title' => 'Address', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])

            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'country_id', 'title' => 'Country', 'cell' => [6, 6, 12],
                   'options' => \App\Models\Country::all()
                   ])

            <div class="col-12 pb-5"></div>
            @include('widget.bootstrap.form.text-input', ['id' => 'email', 'title' => 'Email', 'cell' => [6, 6, 12], 'maxlength' => 100, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.text-input', ['id' => 'phone_number', 'title' => 'Phone number', 'cell' => [6, 6, 12], 'maxlength' => 16, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.text-input', ['id' => 'whatsapp', 'title' => 'Whatsapp', 'cell' => [6, 6, 12], 'maxlength' => 100, 'inputAttr' => 'autocomplete = off required'])



            <div class="col-12 row pt-4 mt-4 mx-0 px-0"></div>
            <div class="col-12 footer">
                @include('widget.bootstrap.form.action-buttons', [
                    'cancelUrl' => route('dashboard.clinic.index'),
                ])
                @include('widget.bootstrap.form.time-log')
            </div>
        </form>

        @if($model->id)
            <form id="destroy-form" action="{{ route('dashboard.clinic.destroy', ['model' => $model->id]) }}" method="POST" style="display: none;"> @csrf </form>
        @endif
    </div>
@endsection

@push('css_1')
    <style>
        #items-card-imagess-div .base-img-fit-contain {
            display: block !important;
        }
        #image_image {
            display: block !important;
        }
    </style>
@endpush

