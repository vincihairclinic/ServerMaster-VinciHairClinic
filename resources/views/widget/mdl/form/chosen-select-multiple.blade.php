@php
    $optionName = !empty($optionName) ? $optionName : 'name';
@endphp
<div class="is-dirty mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ !empty($mdlCell) ? 'mdl-cell mdl-cell--'.$mdlCell[0].'-col mdl-cell--'.$mdlCell[1].'-col-tablet mdl-cell--'.$mdlCell[2].'-col-phone' : ''}} capitalize-all chosen-select-multiple-div {{ !empty($cssClass) ? $cssClass : '' }}" style="{{ !empty($style) ? $style : '' }}">
    <select class="mdl-textfield__input chosen-select" name="{{ $id }}[]" id="{{ $id }}" multiple>
        @foreach($options as $option)
            @if(!empty($group))
                <optgroup label="{{ $option->{$optionName} }}">
                @foreach($option->{$id} as $item)
                    @php
                        $isSelected = isset($model) ?
                        (collect(old($id, $model->{$id}->pluck('id')))->contains($item->id) ? 'selected' : '') :
                        (!empty($selectedValue) && $selectedValue->contains($item->id) ? 'selected' : '')
                    @endphp
                        <option value="{{ $item->id }}" {{ $isSelected }}>{{ (!empty($nameConcatWithRelation) && !empty($item->{$nameConcatWithRelation}) && !empty($item->{$nameConcatWithRelation}->{$optionName}) ? $item->{$nameConcatWithRelation}->{$optionName}.' ' : '') . $item->{$optionName} }}</option>
                @endforeach
                </optgroup>
            @else
                @php
                    $isSelected = isset($model) ?
                    (collect(old($id, $model->{$id}->pluck('id')))->contains($option->id)  ? 'selected' : '') :
                    (!empty($selectedValue) && $selectedValue->contains($option->id) ? 'selected' : '')
                @endphp
                <option value="{{ $option->id }}" {{ $isSelected }}>{{ $option->{$optionName} }}</option>
            @endif
        @endforeach
    </select>
    <label class="mdl-textfield__label">{{ $title }}</label>
</div>

@if(!empty($addNewItems) && \App\Application::pushLoad('chosen_addNewItems'))
    @push('js')
        <script src="{{ asset('js/plugins/chosen.addNewItems.js') }}"></script>
    @endpush
@endif