{{--@if(!empty($typeInput))--}}
{{--    {{ dd($typeInput=='date' ? dd($value) : '') }}--}}
{{--@endif--}}
<?php
    $value = isset($value) ? $value : old((isset($isPrice) ? \App\Repositories\BaseRepository::price($id) : $id), (!empty($model) ? (isset($isPrice) ? \App\Repositories\BaseRepository::price($model->{$id}) : $model->{$id}) : ''));
?>

<div class="{{ !empty($value) || (!empty($typeInput) && $typeInput == 'date') ? 'is-dirty' : '' }} mdl-textfield text-input mdl-js-textfield mdl-textfield--floating-label {{ !empty($mdlCell) ? 'mdl-cell mdl-cell--'.$mdlCell[0].'-col mdl-cell--'.$mdlCell[1].'-col-tablet mdl-cell--'.$mdlCell[2].'-col-phone' : ''}} {{ $errors->has($id) ? 'is-invalid' : '' }} {{ !empty($cssClass) ? $cssClass : '' }}">
    <input
            class="mdl-textfield__input"
            maxlength="{{ !empty($maxlength) ? $maxlength : '' }}"
            max="{{ !empty($maxNumber) ? $maxNumber : '' }}"
            step="{{ !empty($stepNumber) ? $stepNumber : '' }}"
            type="{{ !empty($typeInput) ? $typeInput : 'text' }}"
            pattern="{{ !empty($isPrice) ? '[-+]?[0-9]*\.?[0-9]{1,2}' : (!empty($isFloat) ? '[-+]?[0-9]*\.?[0-9]+' : (!empty($inputPattern) ? $inputPattern : '.*')) }}"
            name="{{ $id }}"
            id="{{ $id }}"
            value="{{ $value }}"
            {{ isset($inputAttr) ? $inputAttr : '' }}
    >
    <label class="mdl-textfield__label">{{ $title }}</label>
    @include('widget.bootstrap.form.error-validation')
</div>