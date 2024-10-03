@if ($errors->has($id))
    <span class="is-invalid">{{ $errors->first($id) }}</span>
@endif