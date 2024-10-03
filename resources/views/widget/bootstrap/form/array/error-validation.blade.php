@if(!empty($arrayProperty))
    @if ($errors->has($id.'.'.$i.'.'.$property))
        <div class="invalid-feedback">{{ $errorLabel ? str_replace($arrayName.'.'.$i.'.'.$property, $errorLabel, $errors->first($arrayName.'.'.$i.'.'.$property)) : $errors->first($arrayName.'.'.$i.'.'.$property) }}</div>
    @endif
@else
    @if ($errors->has($id.'.'.$i))
        <div class="invalid-feedback">{{ $errorLabel ? str_replace($arrayName.'.'.$i, $errorLabel, $errors->first($arrayName.'.'.$i)) : $errors->first($arrayName.'.'.$i) }}</div>
    @endif
@endif