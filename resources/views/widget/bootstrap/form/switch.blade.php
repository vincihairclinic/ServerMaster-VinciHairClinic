<?php
$title = !empty($title) ? ($title != -1 ? $title : '') : (str_replace('_', ' ', ucfirst($id)));
$cell = !is_array($cell) ? [$cell, $cell, $cell] : $cell;
?>
<div class="form-group {{ !empty($cell) ? 'col-xl-'.$cell[0].' col-sm-'.$cell[1].' col-'.$cell[2] : ''}} {{ ($bottom ?? true) ? 'align-self-end pb-2' : '' }} {{ !empty($cssClass) ? $cssClass : '' }}">
    <div class="custom-control custom-switch {{ !empty($baseCss) ? $baseCss : '' }}" style="{{ !empty($style) ? $style : '' }}">
            @php
                $isChecked = isset($value) ? ($value ? 'checked' : '') : (isset($model) ?
                (old($id, $model->{$id}) ? 'checked' : '') :
                (isset($isChecked) && $isChecked ? 'checked' : ''))
            @endphp
        <input type="checkbox" class="custom-control-input {{ !empty($cssInputClass) ? $cssInputClass : '' }}" {{ isset($inputAttr) ? $inputAttr : '' }} name="{{ $id }}" id="{{ $id }}" {{ $isChecked }}>
        <label class="custom-control-label" for="{{ $id }}">{!! $title !!}</label>
    </div>
</div>