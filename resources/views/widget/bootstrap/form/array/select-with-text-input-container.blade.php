<div class="form-group row mx-0 col-12" id="add-new-text-input-{{ $name }}-container">
    @foreach( old($name) ?? $model->{$name ?? $id} as $i => $item)
        <div class="col-12 row p-0 m-0 position-relative">
            @include('widget.bootstrap.form.array.chosen-select-single', ['id' => $firstName, 'name' => $firstName, 'selectedValue' => ((array)$item)[$selectId ?? 'select'] ?? null, 'title' => $selectTitle, 'cell' => [6, 6, 12],
                'options' => $selectOptions,
            ])
            @include('widget.bootstrap.form.array.text-input', ['arrayName' => $secondName, 'name' => $secondName, 'title' => $inputTitle, 'value' => ((array)$item)[$inputId ?? 'input'] ?? null, 'cell' => [6, 6, 12], 'close' => false])
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
    <button class="btn btn-self-primary" type="button" onclick="addNew{{ $name }}Form()">{{ $buttonLabel ?? 'Add'.$name }}</button>
</div>
@endif
<template id="add-new-text-input-{{$name}}-template">
    <div class="col-12 row p-0 m-0 position-relative">
        @include('widget.bootstrap.form.array.chosen-select-single', ['id' => $name, 'name' => $name, 'property' => $selectId ?? 'select', 'i' => '${i}', 'title' => $selectTitle, 'cell' => [6, 6, 12],
            'options' => $selectOptions,
            'cssClass' => 'need-init',
        ])
        @include('widget.bootstrap.form.array.text-input', ['arrayName' => $name, 'name' => $name, 'property' => $inputId ?? 'input', 'title' => $inputTitle, 'i' => '${i}', 'cell' => [6, 6, 12], 'close' => false])

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
        function addNew{{ $name }}Form() {
            let i = Date.now() + '_';
            $('#add-new-text-input-{{ $name }}-template').tmpl({
                i: i,
            }).appendTo('#add-new-text-input-{{ $name }}-container');
            defaultChosenInit('#add-new-text-input-{{ $name }}-container .need-init select');
            $('#add-new-text-input-{{ $name }}-container .need-init').removeClass('need-init');
        }
    </script>
@endpush