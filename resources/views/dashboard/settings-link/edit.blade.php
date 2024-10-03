@extends('dashboard.layouts.content')
@include('widget.bootstrap.datatable-form-push-resources')

@section('head_title') {{ \App\Models\Datasets\SettingsLink::fieldById($model->id, 'name') }} @endsection

@section('content')
@include('widget.bootstrap.dialog-edit-image', ['isUpload' => false, 'isRemove' => false])
{{--{{ dd($is_updated) }}--}}
    @if(session('update_success'))
        {{ Session::forget('update_success') }}
        <div class="alert alert-success" role="alert">
            {{ \App\Models\Datasets\SettingsLink::fieldById($model->id, 'name') }} was updated!
        </div>
    @endif
    <div class="base-form pt-0">
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
        <form action="{{ route('dashboard.settings-link.update', ['model' => $model->id]) }}" autocomplete="off"  method="post" enctype="multipart/form-data" class="row">
            @csrf
            <input type="hidden" name="id" value="{{ !empty($model->id) ? $model->id : '' }}">

{{--            <div class="col-12">&nbsp</div>--}}

            <div class="col-12 pt-3"></div>
{{--            <div class="col-12 py-1 pt-5 my-2 border-bottom" style="color: #921B53;"><h3>Account</h3></div>--}}
            @include('widget.bootstrap.form.text-input', ['id' => 'value', 'title' => \App\Models\Datasets\SettingsLink::fieldById($model->id, 'name'), 'cell' => 12, 'maxlength' => 100, 'inputAttr' => 'autocomplete = off required'])


            <div class="col-12 row pt-4 mt-4 mx-0 px-0"></div>
            <div class="col-12 footer">
                @include('widget.bootstrap.form.action-buttons', [
                    'cancelUrl' => '',
                    'hideDelete' => true
                ])
                @include('widget.bootstrap.form.time-log')
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script>
        setTimeout(function (){
            $('.alert').alert('close');
        }, 3000);
    </script>
@endpush

