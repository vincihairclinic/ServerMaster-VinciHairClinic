@php
    $title = !empty($title) ? ($title != -1 ? $title : '') : (str_replace('_', ' ', ucfirst($id)));
    $cell = !is_array($cell) ? [$cell, $cell, $cell] : $cell;
    $optionName = !empty($optionName) ? $optionName : 'name';
@endphp
<div class="form-group {{ !empty($cell) ? 'col-xl-'.$cell[0].' col-sm-'.$cell[1].' col-'.$cell[2] : ''}}  {{ !empty($cssClass) ? $cssClass : '' }}" style="{{ !empty($style) ? $style : '' }}">
    <label>{{ $title }}</label>
    <select class="{{ $targetName ?? 'bootstrap-multiple' }}" name="{{ $id }}[]" id="{{ $id }}" multiple data-placeholder="{{ $placeholder ?? 'None selected' }}" {{ $inputAttr ?? '' }}>
        @foreach($options as $option)
            @php $option = (object)$option @endphp
            @if(!empty($group))
                <optgroup label="{{ $option->{$optionName} }}">
                    @foreach($option->{ $optionValue ?? $id } as $item)
                        @php
                            $isSelected = isset($model) && !empty($model->{$id}) ?
                            (collect(old($id, (is_array($model->{$id}) ? $model->{$id} : $model->{$id}->pluck('id'))))->contains($item->id) ? 'selected' : '') :
                            (!empty($selectedValue) && $selectedValue->contains($item->id) ? 'selected' : '');

                        @endphp
                        <option value="{{ $item->id }}" {{ $isSelected }}>{{ (!empty($nameConcatWithRelation) && !empty($item->{$nameConcatWithRelation}) && !empty($item->{$nameConcatWithRelation}->{$optionName}) ? $item->{$nameConcatWithRelation}->{$optionName}.' ' : '') . $item->{$optionName} }}</option>
                    @endforeach
                </optgroup>
            @else
                @php
                    $isSelected = isset($model) && !empty($model->{$id}) ?
                    (collect(old($id, (is_array($model->{$id}) ? $model->{$id} : $model->{$id}->pluck('id'))))->contains($option->id)  ? 'selected' : '') :
                    (!empty($selectedValue) && $selectedValue->contains($option->id) ? 'selected' : '')
                @endphp
            @php
            @endphp
                <option value="{{ $option->id }}" {{ $isSelected }} {{ isset($disabled)  ? (!empty($option->{$disabled}) ? 'disabled' : '') :'' }}>{{ $option->{$optionName} }}</option>
            @endif
        @endforeach
    </select>
</div>
@once
    @push('css_1')
        <link href="{{ asset('/css/plugins/bootstrap-multiselect.min.css') }}" rel="stylesheet">
    @endpush
    @push('js')
        <script src="{{ asset('js/plugins/bootstrap-multiselect.min.js') }}"></script>
        <script>
            $(function () {
                $('select[multiple].bootstrap-multiple').multiselect({
                    maxHeight: 300,
                    allSelectedText: '{{ !empty($selectedText) ? $selectedText : '' }}',
                    includeSelectAllOption: {{ !empty($selectedText) ? 1 : 0 }},
                });
            });
        </script>
    @endpush
@endonce