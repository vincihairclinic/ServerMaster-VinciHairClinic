<div class="{{ !empty($mdlCell) ? 'mdl-cell mdl-cell--'.$mdlCell[0].'-col mdl-cell--'.$mdlCell[1].'-col-tablet mdl-cell--'.$mdlCell[2].'-col-phone' : ''}}">
    <label style="font-size: 12px; {{ !empty($style) ? $style : '' }}" class="md-color-text-base {{ !empty($cssClass) ? $cssClass : '' }} {{ $errors->has($id) ? 'is-invalid' : '' }}">{{ $title }}</label>
    <textarea
            class="jqte_textarea"
            name="{{ $id }}"
    >{{ isset($value) ? $value : old($id, $model->{$id}) }}</textarea>
    @include('widget.mdl.form.error-validation')
</div>