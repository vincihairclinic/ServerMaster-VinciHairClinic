<div class="{{ !empty($mdlCell) ? 'mdl-cell mdl-cell--'.$mdlCell[0].'-col mdl-cell--'.$mdlCell[1].'-col-tablet mdl-cell--'.$mdlCell[2].'-col-phone' : ''}} {{ !empty($cssClass) ? $cssClass : '' }}" style="{{ !empty($style) ? $style : '' }}">
    <label class="mdl-switch mdl-js-switch">
        @php
            $isChecked = isset($model) ?
            (old($arrayName.'.'.$i.'.'.$id, $item->{$id}) ? 'checked' : '') :
            (isset($isChecked) && $isChecked ? 'checked' : '')
        @endphp
        <input {{ !empty($inputAttr) ? $inputAttr : '' }} type="checkbox" class="mdl-switch__input" name="{{ $arrayName }}[{{ $i }}][{{ $id }}]" {{ $isChecked }}>
        <span class="mdl-switch__label uppercase nowrap">{{ $title }}</span>
    </label>
</div>
