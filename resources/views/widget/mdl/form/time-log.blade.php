<div class="col-12 time-log">
    @if(empty($withoutCreated))
        <div>Created: {{ \Carbon\Carbon::parse($model->created_at)->format('d M Y H:i') }} UTC</div>
    @endif
    @if($model->created_at != $model->updated_at || !empty($withoutCreated))
        <div>Updated: {{ \Carbon\Carbon::parse($model->updated_at)->format('d M Y H:i') }} UTC</div>
    @endif
</div>