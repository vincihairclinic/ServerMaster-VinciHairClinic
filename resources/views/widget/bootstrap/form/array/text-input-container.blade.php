@php
    $array = empty($array) ? old($id, $model->{$id}) : $array;
@endphp

<div class="form-group row mx-0 col-12" id="add-new-text-input-{{ $id }}-container">
    <h3 class="col-12">{{ $title }}</h3>
    @foreach($array as $i => $item)
        @include('widget.bootstrap.form.array.text-input', ['arrayName' => $id, 'title' => $title, 'value' => $item, 'cell' => [6, 6, 12]])
    @endforeach
</div>
<div class="col-12 text-right">
    <button class="btn btn-self-primary" type="button" onclick="addNew{{ $id }}Form()">{{ $buttonLabel ?? 'Add '.$title }}</button>
</div>

<template id="add-new-text-input-{{$id}}-template">
    @include('widget.bootstrap.form.array.text-input', ['arrayName' => $id, 'title' => $title, 'i' => '${i}', 'cell' => [6, 6, 12]])
</template>

@push('js')
    @if(!App\AppConf::$is_tmpl_exist)
        <script src="{{ asset('js/plugins/jquery.tmpl.min.js') }}"></script>
    @endif
    <script>
        function addNew{{ $id }}Form() {
            let i = Date.now() + '_';
            $('#add-new-text-input-{{ $id }}-template').tmpl({
                i: i,
            }).appendTo('#add-new-text-input-{{ $id }}-container');
        }
    </script>
@endpush