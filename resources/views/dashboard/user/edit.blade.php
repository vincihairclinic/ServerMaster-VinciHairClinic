@extends('dashboard.layouts.content')
@include('widget.bootstrap.datatable-form-push-resources')

@section('head_title') {{ $model->id ? 'Edit '.\App\Models\Datasets\UserRole::nameById($model->role_id).' ID:'.$model->id : 'Add '.\App\Models\Datasets\UserRole::nameById($model->role_id) }} @endsection
@section('title')
    <a href="{{ route('dashboard.user.index') }}" class="arrow-back"><i class="material-icons">arrow_back</i></a>
    {{ $model->id ? 'Edit '.\App\Models\Datasets\UserRole::nameById($model->role_id).' ID:'.$model->id.' '.$model->email : 'Add '.\App\Models\Datasets\UserRole::nameById($model->role_id) }}
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
        <form action="{{ route($model->id ? 'dashboard.user.update' : 'dashboard.user.store', ['model' => $model->id]) }}" autocomplete="off"  method="post" enctype="multipart/form-data" class="row">
            @csrf
            <input type="hidden" name="id" value="{{ !empty($model->id) ? $model->id : '' }}">

            <div class="col-12">&nbsp</div>


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
                    'cancelUrl' => route('dashboard.user.index'),
                ])
                @include('widget.bootstrap.form.time-log')
            </div>
        </form>

        @if($model->id)
            <form id="destroy-form" action="{{ route('dashboard.user.destroy', ['model' => $model->id]) }}" method="POST" style="display: none;"> @csrf </form>
        @endif
    </div>
@endsection

