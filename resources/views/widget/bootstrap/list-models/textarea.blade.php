<?php
$title = !empty($title) ? ($title != -1 ? $title : '') : (str_replace('_', ' ', ucfirst($id)));
$cell = !is_array($cell) ? [$cell, $cell, $cell] : $cell;
?>
<div class="col {{ !empty($cell) ? 'col-xl-'.$cell[0].' col-md-'.$cell[1].' col-'.$cell[2] : ''}} {{ !empty($cssClass) ? $cssClass : '' }}">
    <div class="form-group w-100 {{ $errors->has($id) ? 'is-invalid' : '' }}">
        <label >{{ $title }}</label>
        <textarea
                maxlength="{{ !empty($maxlength) ? $maxlength : '' }}"
                class="d-block w-100 form-control"
                rows= "1"
                name="{{ !empty($modalId) ? $modalId.'_'.$id : $id }}"
                {{ !empty($inputAttr) ? $inputAttr : '' }}
        >{!! isset($value) ? $value : old($id, $model->{$id}) !!}</textarea>
        @include('widget.mdl.form.error-validation')
    </div>
</div>
