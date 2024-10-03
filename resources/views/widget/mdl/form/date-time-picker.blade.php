<table class="{{ !empty($mdlCell) ? 'mdl-cell mdl-cell--'.$mdlCell[0].'-col mdl-cell--'.$mdlCell[1].'-col-tablet mdl-cell--'.$mdlCell[2].'-col-phone' : ''}} {{ $errors->has($id) ? 'is-invalid' : '' }}">
    <tr>
        <td style="width: 100%;">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-clear-btn="true" style="width: 100%;">
                <input type="search" class="mdl-textfield__input" id="{{ $id }}" name="title">
                <label class="mdl-textfield__label">{{ $title }}</label>
            </div>
        </td>
        <td>
            <button id="{{ $idBtn }}" class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" style="width: 10%;">Date</button>
        </td>
    </tr>
    <tr>
        <td colspan="2">@include('widget.mdl.form.error-validation')</td>
    </tr>
</table>