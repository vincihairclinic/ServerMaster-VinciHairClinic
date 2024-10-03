
    <div class="{{ !empty($mdlCell) ? 'mdl-cell mdl-cell--'.$mdlCell[0].'-col mdl-cell--'.$mdlCell[1].'-col-tablet mdl-cell--'.$mdlCell[2].'-col-phone' : ''}} {{ !empty($cssClass) ? $cssClass : '' }}">
        <div style="width: 100%;" class="is-dirty mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has($id) ? 'is-invalid' : '' }}">
            <textarea
                    maxlength="{{ !empty($maxlength) ? $maxlength : '' }}"
                    class="mdl-textfield__input"
                    rows= "1"
                    name="{{ $id }}"
                    {{ !empty($inputAttr) ? $inputAttr : '' }}
            >{!! isset($value) ? $value : old($id, $model->{$id}) !!}</textarea>
            <label class="mdl-textfield__label">{{ $title }}</label>
            @include('widget.mdl.form.error-validation')
        </div>
    </div>