<?php
    $title = !empty($title) ? ($title != -1 ? $title : '') : (str_replace('_', ' ', ucfirst($id)));
    $cell = !is_array($cell) ? [$cell, $cell, $cell] : $cell;
    $value = isset($value) ? $value : old((isset($isPrice) ? \App\Repositories\BaseRepository::price($id) : $id), (!empty($model) ? (isset($isPrice) ? \App\Repositories\BaseRepository::price($model->{$id}) : $model->{$id}) : ''));
?>
<div class="{{ !empty($value) || (!empty($typeInput) && $typeInput == 'date') ? 'is-dirty' : '' }} form-group {{ !empty($cell) ? 'col-xl-'.$cell[0].' col-md-'.$cell[1].' col-'.$cell[2] : ''}} {{ !empty($cssClass) ? $cssClass : '' }}">
    <label>{{ $title }}</label>
    <input
            class="form-control {{ $errors->has($id) ? 'is-invalid' : '' }} {{ !empty($inputClass) ? $inputClass : '' }}"
            maxlength="{{ !empty($maxlength) ? $maxlength : '' }}"
            max="{{ !empty($maxNumber) ? $maxNumber : '' }}"
            min="{{ !empty($minNumber) ? $minNumber : '' }}"
            step="{{ !empty($stepNumber) ? $stepNumber : '' }}"
            type="{{ !empty($typeInput) ? $typeInput : 'text' }}"
            pattern="{{ !empty($isPrice) ? '[-+]?[0-9]*\.?[0-9]{1,2}' : (!empty($isFloat) ? '[-+]?[0-9]*\.?[0-9]+' : (!empty($inputPattern) ? $inputPattern : '.*')) }}"
            name="{{ $id }}"
            placeholder="{{ !empty($placeholder) ? $placeholder : '' }}"
            data-date-format="{{ $format ?? '' }}"
            id="{{ $id }}"
            value="{{ !empty($typeInput) ? $typeInput != 'password' ? $value : '' : $value }}"
            {{ $inputAttr ?? '' }}
    />
    @include('widget.bootstrap.form.error-validation')
</div>