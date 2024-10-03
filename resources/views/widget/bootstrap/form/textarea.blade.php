<?php
$title = !empty($title) ? ($title != -1 ? $title : '') : (str_replace('_', ' ', ucfirst($id)));
$cell = !is_array($cell) ? [$cell, $cell, $cell] : $cell;
?>
<div class="{{ !empty($cell) ? 'col-xl-'.$cell[0].' col-md-'.$cell[1].' col-'.$cell[2] : ''}} {{ !empty($cssClass) ? $cssClass : '' }}">
    <div class="form-group w-100">
        <label >{{ $title }}</label>
        <textarea
                maxlength="{{ !empty($maxlength) ? $maxlength : '' }}"
                class="d-block w-100 form-control {{ $inputClass ?? '' }} {{ $errors->has($id) ? 'is-invalid' : '' }}"
                rows= "{{ $rows ?? 1 }}"
                placeholder="{{ $placeholder ?? '' }}"
                name="{{ $id }}"
                id="{{ $id }}"
                {{ !empty($inputAttr) ? $inputAttr : '' }}
        >{!! isset($value) ? $value : old($id, empty($model->{$id}) ? '' : $model->{$id}) !!}</textarea>
        @include('widget.bootstrap.form.error-validation')
    </div>
</div>
