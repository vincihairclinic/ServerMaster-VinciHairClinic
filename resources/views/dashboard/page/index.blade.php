@extends('dashboard.layouts.content')
@include('widget.bootstrap.form.push-resources')

@section('head_title') {{ $model->id ? 'Edit '.$model->name : 'Add '.$model->name }} @endsection
@section('title')
    <a href="javascript:void(0)"  class="arrow-back"></a>
    {{ $model->id ? 'Edit '.$model->name : 'Add '.$model->name }}
@endsection

@section('content')
    <div class="base-form">
        <form action="{{route('dashboard.page.update', ['model' => $model->route]) }}" method="post" enctype="multipart/form-data" class="row">
            @csrf
            @if (\Illuminate\Support\Facades\Request::get('success'))
                <div class="col-12">
                    <div class="alert alert-success w-100" id="policy-success-alert" role="alert">
                        Successfully!
                    </div>
                </div>
                @push('js')
                    <script>
                        setTimeout(function () {
                            $('#policy-success-alert').alert('close');
                        },3000);
                    </script>
                @endpush
            @endif
            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'html', 'title' => '', 'cell' => [12, 12, 12], 'rows' => 5])
            <div class="col-12 footer">
                @include('widget.bootstrap.form.action-buttons', [
                    'hideDelete' => true,
                    'cancelUrl' => false,
                ])
                @include('widget.bootstrap.form.time-log')
            </div>
        </form>
    </div>
@endsection