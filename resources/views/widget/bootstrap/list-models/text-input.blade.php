<?php
    $title = !empty($title) ? ($title != -1 ? $title : '') : (str_replace('_', ' ', ucfirst($id)));
    $cell = !is_array($cell) ? [$cell, $cell, $cell] : $cell;
    $value = isset($value) ? $value : old((isset($isPrice) ? \App\Repositories\BaseRepository::price($id) : $id), (!empty($model) ? (isset($isPrice) ? \App\Repositories\BaseRepository::price($model->{$id}) : $model->{$id}) : ''));
?>
<div class="{{ !empty($value) || (!empty($typeInput) && $typeInput == 'date') ? 'is-dirty' : '' }} form-group {{ !empty($cell) ? 'col-xl-'.$cell[0].' col-md-'.$cell[1].' col-'.$cell[2] : ''}} {{ !empty($cssClass) ? $cssClass : '' }}">
    <label>{{ $title }}</label>
    <input
            class="form-control {{ $errors->has($id) ? 'is-invalid' : '' }}"
            maxlength="{{ !empty($maxlength) ? $maxlength : '' }}"
            max="{{ !empty($maxNumber) ? $maxNumber : '' }}"
            step="{{ !empty($stepNumber) ? $stepNumber : '' }}"
            type="{{ !empty($typeInput) ? $typeInput : 'text' }}"
            pattern="{{ !empty($isPrice) ? '[-+]?[0-9]*\.?[0-9]{1,2}' : (!empty($isFloat) ? '[-+]?[0-9]*\.?[0-9]+' : (!empty($inputPattern) ? $inputPattern : '.*')) }}"
            name="{{ $inputName ?? $id }}"
            id="{{ !empty($modalId) ? $modalId.'_'.$id : $id }}"
            value="{{ !empty($typeInput) ? $typeInput != 'password' ? $value : '' : $value }}"
            {{ isset($inputAttr) ? $inputAttr : '' }}
    />
    @if($modalValidation ?? false)
        @include('widget.bootstrap.list-models.error-modal-validation', ['id' => !empty($modalId) ? $modalId.'_'.$id : $id])
    @else
        @include('widget.bootstrap.list-models.error-validation')
    @endif
</div>