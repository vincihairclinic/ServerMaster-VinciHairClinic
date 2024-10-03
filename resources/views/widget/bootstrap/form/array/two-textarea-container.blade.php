<div class="form-group row mx-0 col-12" id="add-new-text-input-{{ $id }}-container">
    @foreach( old($id, $model->{$id}) as $i => $item)
        <div class="col-12 row p-0 m-0 position-relative">
            @include('widget.bootstrap.form.array.textarea', ['arrayName' => $id, 'name' => $id, 'property' => $firstProperty ?? 'input', 'title' => $firstTitle, 'value' => ((array)$item)[$firstProperty ?? 'input'] ?? null, 'cell' => [6, 6, 12], 'close' => false])
            @include('widget.bootstrap.form.array.textarea', ['arrayName' => $id, 'name' => $id, 'property' => $secondProperty ?? 'input', 'title' => $secondTitle, 'value' => ((array)$item)[$secondProperty ?? 'input'] ?? null, 'cell' => [6, 6, 12], 'close' => false])
            @if($close ?? true)
                <button type="button" class="close position-absolute top-0" onclick="this.parentElement.remove()" style=" top: 5px; right: 15px;" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            @endif
        </div>
    @endforeach
</div>
@if($showAdd ?? true)
<div class="col-12 text-right">
    <button class="btn btn-self-primary" type="button" onclick="addNew{{ $id }}Form()">{{ $buttonLabel ?? 'Add'.$id }}</button>
</div>
@endif
<template id="add-new-text-input-{{$id}}-template">
    <div class="col-12 row p-0 m-0 position-relative">
        @include('widget.bootstrap.form.array.textarea', ['arrayName' => $id, 'name' => $id, 'property' => $firstProperty ?? 'input', 'title' => $firstTitle, 'i' => '${i}', 'cell' => [6, 6, 12], 'close' => false])
        @include('widget.bootstrap.form.array.textarea', ['arrayName' => $id, 'name' => $id, 'property' => $secondProperty ?? 'input', 'title' => $secondTitle, 'i' => '${i}', 'cell' => [6, 6, 12], 'close' => false])

        @if($close ?? true)
            <button type="button" class="close position-absolute top-0" onclick="this.parentElement.remove()" style=" top: 5px; right: 15px;" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        @endif
    </div>
</template>

@push('js')
    @once
        <script src="{{ asset('js/plugins/jquery.tmpl.min.js') }}"></script>
    @endonce
    <script>
        function addNew{{ $id }}Form() {
            let i = Date.now() + '_';
            $('#add-new-text-input-{{ $id }}-template').tmpl({
                i: i,
            }).appendTo('#add-new-text-input-{{ $id }}-container');
            defaultChosenInit('#add-new-text-input-{{ $id }}-container .need-init select');
            $('#add-new-text-input-{{ $id }}-container .need-init').removeClass('need-init');
        }
    </script>
@endpush