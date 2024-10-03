<?php
$title = !empty($title) ? ($title != -1 ? $title : '') : (str_replace('_', ' ', ucfirst($id)));
$cell = !is_array($cell) ? [$cell, $cell, $cell] : $cell;
$value = isset($value) ? $value : old((isset($isPrice) ? \App\Repositories\BaseRepository::price($id) : $id), (!empty($model) ? (isset($isPrice) ? \App\Repositories\BaseRepository::price($model->{$id}) : $model->{$id}) : ''));
?>
<div class="form-group {{ !empty($cell) ? 'col-xl-'.$cell[0].' col-md-'.$cell[1].' col-'.$cell[2] : ''}} {{ !empty($cssClass) ? $cssClass : '' }}">
    <label>{{ $title }}</label>
    <div class='input-group date' id='time_picker_{{ $id }}'>
        <input
                class="form-control {{ $errors->has($id) ? 'is-invalid' : '' }} {{ !empty($inputClass) ? $inputClass : '' }}" name="{{ $id }}" id="{{ $id }}"
                maxlength="{{ !empty($maxlength) ? $maxlength : '' }}"
                max="{{ !empty($maxNumber) ? $maxNumber : '' }}"
                min="{{ !empty($minNumber) ? $minNumber : '' }}"
                step="{{ !empty($stepNumber) ? $stepNumber : '' }}"
                type="{{ !empty($typeInput) ? $typeInput : 'text' }}"
                pattern="{{ !empty($isPrice) ? '[-+]?[0-9]*\.?[0-9]{1,2}' : (!empty($isFloat) ? '[-+]?[0-9]*\.?[0-9]+' : (!empty($inputPattern) ? $inputPattern : '.*')) }}"
                name="{{ $id }}"
                placeholder="{{ !empty($placeholder) ? $placeholder : '' }}"
                id="{{ $id }}"
                value="{{ !empty($typeInput) ? $typeInput != 'password' ? $value : '' : $value }}"
                {{ $inputAttr ?? '' }}
        />
        @include('widget.bootstrap.form.error-validation')
    </div>
</div>
@once
    @push('js')
        <script src="{{ asset('js/plugins/bootstrap-datetimepicker.min.js') }}"></script>
    @endpush
    @push('css_1')
        <link rel="stylesheet" href="{{ asset('css/plugins/bootstrap-datetimepicker.min.css') }}"/>
    @endpush
@endonce
@push('js')
    <script type="text/javascript">
        $(function () {
            $('#{{ $id }}').datetimepicker({
                format: '{{ !empty($format) ? $format : 'HH:mm' }}'
            });
        });
    </script>
@endpush