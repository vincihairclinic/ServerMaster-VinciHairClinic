@php
    $title = !empty($title) ? ($title != -1 ? $title : '') : (str_replace('_', ' ', ucfirst($id)));
    $cell = !is_array($cell) ? [$cell, $cell, $cell] : $cell;
    $optionName = !empty($optionName) ? $optionName : 'name';
@endphp
<div class="form-group {{ !empty($cell) ? 'col-xl-'.$cell[0].' col-sm-'.$cell[1].' col-'.$cell[2] : ''}}  {{ !empty($cssClass) ? $cssClass : '' }}" style="{{ !empty($style) ? $style : '' }}">
    <label>{{ $title }}</label>
    <select class="{{ $targetName ?? 'chosen-select' }}" name="{{ $id }}[]" id="{{ $id }}" multiple {{ $inputAttr ?? '' }}>
        @foreach($options as $option)
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
                <option value="{{  $option->id }}" {{ $isSelected }}>{{ $option->{$optionName} }}</option>
            @endif
        @endforeach
    </select>
</div>

@if(!empty($addNewItems) && \App\Application::pushLoad('chosen_addNewItems'))
    @push('js')
        <script src="{{ asset('js/plugins/chosen.addNewItems.js') }}"></script>
    @endpush
    @push('css_2')
        <style>
            .create-option a:not([href]):not([class]){
                color: #bd0052;
            }
        </style>
    @endpush
@endif