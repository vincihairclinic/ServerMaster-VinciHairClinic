<div class="{{ !empty($mdlCell) ? 'mdl-cell mdl-cell--'.$mdlCell[0].'-col mdl-cell--'.$mdlCell[1].'-col-tablet mdl-cell--'.$mdlCell[2].'-col-phone' : ''}} {{ !empty($cssClass) ? $cssClass : '' }}" style="{{ !empty($style) ? $style : '' }}">
    <label class="mdl-switch mdl-js-switch" style="{{ !empty($styleLabel) ? $styleLabel : '' }}">
        @php
            $isChecked = isset($value) ? ($value ? 'checked' : '') : (isset($model) ?
            (old($id, $model->{$id}) ? 'checked' : '') :
            (isset($isChecked) && $isChecked ? 'checked' : ''))
        @endphp
        <input type="checkbox" {{ isset($inputAttr) ? $inputAttr : '' }} class="mdl-switch__input" name="{{ $id }}" id="{{ $id }}" {{ $isChecked }}>
        <span class="mdl-switch__label uppercase nowrap">{{ $title }}</span>
    </label>
</div>
