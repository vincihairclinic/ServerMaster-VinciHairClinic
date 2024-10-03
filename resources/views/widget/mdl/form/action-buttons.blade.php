<div class="mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--4-col-phone action-buttons-div {{ !$model->id ? 'center' : '' }}">
    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" style="{{ isset($createButtonText) && !$model->id ? 'width: 160px;' : 'width: 120px;' }} background-color: #8bc34a; color: #fff">{{ $model->id ? 'Save' : (isset($createButtonText) ? $createButtonText : 'Create') }}</button>
    @if($cancelUrl)
        <a href="{{ $cancelUrl }}" class="mdl-button mdl-js-button" style="margin-left: 20px;">Cancel</a>
    @endif
    @if($model->id && empty($hideDelete))
        <a class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" style="float: right;" href="#" onclick="event.preventDefault(); if (confirm('Delete the record?')) {document.getElementById('destroy-form').submit();}">Delete</a>
    @endif
</div>