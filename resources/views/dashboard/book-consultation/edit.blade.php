@extends('dashboard.layouts.content')
@include('widget.bootstrap.datatable-form-push-resources')

@section('head_title') {{ $model->id ? 'Edit ID:'.$model->id : 'Add book consultation' }} @endsection
@section('title')
    <a href="{{ route('dashboard.book-consultation.index') }}" class="arrow-back"><i class="material-icons">arrow_back</i></a>
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
        <form action="{{ route($model->id ? 'dashboard.book-consultation.update' : 'dashboard.book-consultation.store', ['model' => $model->id]) }}" autocomplete="off"  method="post" enctype="multipart/form-data" class="row">
            @csrf
            <input type="hidden" name="id" value="{{ !empty($model->id) ? $model->id : '' }}">


            @include('widget.bootstrap.form.wide-card-image', ['id' => 'hair_front_image', 'cell' => [3, 3, 12],
                'showClearButton' => true,
            ])

            @include('widget.bootstrap.form.wide-card-image', ['id' => 'hair_side_image', 'cell' => [3, 3, 12],
                'showClearButton' => true,
            ])

            @include('widget.bootstrap.form.wide-card-image', ['id' => 'hair_back_image', 'cell' => [3, 3, 12],
                'showClearButton' => true,
            ])

            @include('widget.bootstrap.form.wide-card-image', ['id' => 'hair_top_image', 'cell' => [3, 3, 12],
                'showClearButton' => true,
            ])

            <div class="col-12 pt-5"></div>

            @include('widget.bootstrap.form.text-input', ['id' => 'full_name', 'title' => 'Full name', 'cell' => [6, 6, 12], 'maxlength' => 100, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.text-input', ['id' => 'email', 'title' => 'Email', 'typeInput' => 'email', 'cell' => [6, 6, 12], 'maxlength' => 100, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.text-input', ['id' => 'password', 'typeInput' => 'password', 'title' => 'Password', 'cell' => [6, 6, 12], 'maxlength' => 255, 'inputAttr' => 'autocomplete = new-password '])
            @include('widget.bootstrap.form.text-input', ['id' => 'password_confirmation', 'typeInput' => 'password', 'title' => 'Password Confirmation', 'cell' => [6, 6, 12], 'maxlength' => 255, 'inputAttr' => 'autocomplete = off'])

            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'gender_id', 'title' => 'Gender', 'cell' => [6, 6, 12],
                   'options' => collect(\App\Models\Datasets\Gender::all())
            ])

            @include('widget.bootstrap.form.text-input', ['id' => 'age', 'title' => 'Age', 'cell' => [6, 6, 12], 'maxlength' => 3, 'inputAttr' => 'autocomplete = off'])
            @include('widget.bootstrap.form.text-input', ['id' => 'phone_number', 'title' => 'Phone number', 'cell' => [6, 6, 12], 'maxlength' => 16, 'inputAttr' => 'autocomplete = off'])

            <div class="col-12 pt-5"></div>


            @include('widget.bootstrap.form.chosen-select-multiple', ['id' => 'procedures', 'title' => 'Procedures', 'cell' => [6, 6, 12],
                   'options' => \App\Models\Procedure::all()
            ])

            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'clinic_id', 'title' => 'Clinic', 'cell' => [6, 6, 12],
                   'options' => \App\Models\Clinic::all()
            ])

            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'hair_loss_type_id', 'title' => 'Hair loss type', 'cell' => [6, 6, 12],
                   'options' => \App\Models\HairLossType::all()
            ])

            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'hair_type_id', 'title' => 'Hair type', 'cell' => [6, 6, 12],
                   'options' => \App\Models\HairType::all()
            ])

            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'country_id', 'title' => 'Country', 'cell' => [6, 6, 12],
                   'options' => \App\Models\Country::all()
            ])

            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'language_key', 'title' => 'Language key', 'cell' => [6, 6, 12],
                   'options' => \App\Models\Language::all(),
                   'optionName' => 'key',
                   'targetId' => 'key'
            ])

            <div class="col-12 pt-5"></div>
            @include('widget.bootstrap.form.text-input', ['id' => 'how_long_have_you_experienced_hair_loss_for', 'cell' => [6, 6, 12], 'maxlength' => 100, 'inputAttr' => 'autocomplete = off required'])
            <div class="col-12 pt-4"></div>

            @include('widget.bootstrap.form.switch', ['id' => 'does_your_family_suffer_from_hereditary_hair_loss', 'cell' => [6, 6, 12]])
            @include('widget.bootstrap.form.switch', ['id' => 'would_you_like_to_get_in_touch_with_you', 'cell' => [6, 6, 12]])
            @include('widget.bootstrap.form.switch', ['id' => 'is_show_news_and_updates_notification', 'cell' => [6, 6, 12]])
            @include('widget.bootstrap.form.switch', ['id' => 'is_show_promotions_and_offers_notification', 'cell' => [6, 6, 12]])
            @include('widget.bootstrap.form.switch', ['id' => 'is_show_insights_and_tips_notification', 'cell' => [6, 6, 12]])
            @include('widget.bootstrap.form.switch', ['id' => 'is_show_new_articles_notification', 'cell' => [6, 6, 12]])
            @include('widget.bootstrap.form.switch', ['id' => 'is_show_requests_and_tickets_notification', 'cell' => [6, 6, 12]])

            <div class="col-12 row pt-4 mt-4 mx-0 px-0"></div>

            <div class="col-12 footer">
                @include('widget.bootstrap.form.action-buttons', [
                    'cancelUrl' => route('dashboard.book-consultation.index'),
                    'saveBtn' => false,
                    'hideDelete' => true,
                ])
                @include('widget.bootstrap.form.time-log')
            </div>
        </form>

    </div>
@endsection

@if(!empty($model->id))
    @push('css_2')
        <link href="{{ asset('css/plugins/rowReorder.bootstrap.min.css') }}" rel="stylesheet">
        <style>
            .dt-rowReorder-moving {
                position: relative;
                opacity: 0.2;
            }
            .dt-rowReorder-moving:after {
                content: '';
                inset: 0;
                background-color: var(--dashboard-primary-color);
                position: absolute;
            }

            div.ds-circle-progress {
                position: absolute!important;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 999999;
            }
        </style>
    @endpush

    @push('css_1')
        <style>
            .modal-bg {
                position: fixed;
                width: 100%;
                height: auto;
                overflow: auto;
                min-height: 100%;
                inset: 0;
                z-index: 20;
                padding-top: 50px;
                padding-bottom: 50px;
                background: rgba(0, 0, 0, 0.3);
                opacity: 0;
                visibility: hidden;
                transition: 0.3s ease;
                display: flex;
                align-items: self-start;
                justify-content: center;
            }

            .modal-bg.show {
                opacity: 1;
                visibility: visible;
            }

            .modal-bg .modal {
                background: #fff;
                position: relative;
                display: block;
                min-height: 400px;
                min-width: 500px;
                max-width: 95%;
                width: 95%;
                height: auto;
                border-radius: 0.25em;
                padding: 24px;
            }

            .modal-bg .modal .close-modal {
                width: 24px;
                height: 24px;
                position: relative;
                cursor: pointer;
            }

            .modal-bg .modal .close-modal:hover {
                opacity: 0.75;
                transition: 0.2s ease;
            }

            .modal-bg .modal .close-modal span {
                width: 24px;
                height: 2px;
                background: #333;
                border-radius: 20%;
                position: absolute;
                top: 50%;
            }

            .modal-bg .modal .close-modal span:first-of-type {
                transform: rotate(45deg);
            }

            .modal-bg .modal .close-modal span:last-of-type {
                transform: rotate(-45deg);
            }

        </style>
    @endpush
@endif