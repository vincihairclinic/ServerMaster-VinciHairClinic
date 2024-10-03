<div class="col-12 rounded {{ !$model->id ? 'center' : '' }}">
    @if($saveBtn ?? true)
        <button class="btn btn-self-success min-width-120" style="{{ isset($createButtonText) && !$model->id ? 'width: 160px;' : 'width: 120px;' }}">{{ $model->id ? 'Save' : (isset($createButtonText) ? $createButtonText : 'Create') }}</button>
    @endif
    @if($cancelUrl)
        <a href="{{ $cancelUrl }}" class="btn btn-self-info min-width-120" style="margin-left: 20px;">Cancel</a>
    @endif
    @if($model->id && empty($hideDelete))
        <a class="btn btn-self-danger min-width-120" style="float: right;" href="#" onclick="event.preventDefault(); if (confirm('Delete the record?')) {document.getElementById('destroy-form').submit();}">Delete</a>
    @endif
</div>
