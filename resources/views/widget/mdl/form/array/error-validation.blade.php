@if(!empty($arrayProperty))
    @if ($errors->has($arrayName.'.'.$iterator.'.'.$arrayProperty))
        <span class="mdl-textfield__error">{{ $errors->first($arrayName.'.'.$i.'.'.$id) }}</span>
    @endif
@else
    @if ($errors->has($arrayName.'.'.$iterator))
        <span class="mdl-textfield__error">{{ $errors->first($arrayName.'.'.$i.'.'.$id) }}</span>
    @endif
@endif