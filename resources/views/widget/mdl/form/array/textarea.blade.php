<div class="is-dirty mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ !empty($mdlCell) ? 'mdl-cell mdl-cell--'.$mdlCell[0].'-col mdl-cell--'.$mdlCell[1].'-col-tablet mdl-cell--'.$mdlCell[2].'-col-phone' : ''}} {{ $errors->has($arrayName.'.'.$i.'.'.$id) ? 'is-invalid' : '' }}" style="{{ !empty($style) ? $style : '' }}">
    <textarea class="mdl-textfield__input" rows= "1" type="{{ isset($typeInput) ? $typeInput : 'text' }}" name="{{ $arrayName }}[{{ $i }}][{{ $id }}]">{{ old($arrayName.'.'.$i.'.'.$id, $item->{$id}) }}</textarea>
    <label class="mdl-textfield__label">{{ $title }}</label>
    @include('widget.mdl.form.array.error-validation')
</div>