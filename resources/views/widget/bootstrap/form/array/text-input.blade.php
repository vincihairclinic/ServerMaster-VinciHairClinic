<?php
    $title = !empty($title) ? ($title != -1 ? $title : '') : (str_replace('_', ' ', ucfirst($id)));
    $cell = !is_array($cell) ? [$cell, $cell, $cell] : $cell;
?>
<div class="form-group position-relative {{ !empty($cell) ? 'col-xl-'.$cell[0].' col-md-'.$cell[1].' col-'.$cell[2] : ''}} {{ $errors->has($id) ? 'is-invalid' : '' }} {{ !empty($cssClass) ? $cssClass : '' }}">
    <label>{{ $title }}</label>
    <input {{ !empty($inputAttr) ? $inputAttr : '' }}
           class="form-control mr-5 {{ $errors->has($id.'.'.$i) ? 'is-invalid' : '' }}" type="{{ isset($typeInput) ? $typeInput : 'text' }}"
           name="{{ $id }}[{{ $i }}]{{ !empty($property) ? '['.$property.']' : '' }}" value="{{ !empty($value) ? $value : '' }}">
    @include('widget.bootstrap.form.array.error-validation')
    @if($close ?? true)
        <button type="button" class="close position-absolute top-0" onclick="this.parentElement.remove()" style=" top: 5px; right: 15px;" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    @endif
</div>

