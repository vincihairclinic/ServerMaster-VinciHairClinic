@php
    $optionName = !empty($optionName) ? $optionName : 'name';
@endphp
<div class="{{ !empty($selectedValue) || (!empty($model) && !empty($model->{$id})) ? 'is-dirty' : '' }} mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ !empty($mdlCell) ? 'mdl-cell mdl-cell--'.$mdlCell[0].'-col mdl-cell--'.$mdlCell[1].'-col-tablet mdl-cell--'.$mdlCell[2].'-col-phone' : ''}} capitalize-all chosen-select-single-div {{ !empty($cssClass) ? $cssClass : '' }}" style="{{ !empty($style) ? $style : '' }}">
    <select class="mdl-textfield__input chosen-select" name="{{ isset($name) ? $name : $id }}" id="{{ !empty($id_) ? $id_ : (isset($name) ? $name : $id)  }}" {{ isset($inputAttr) ? $inputAttr : '' }} {!! isset($onchange) ? 'onchange="'.$onchange.'"' : '' !!}>
        @foreach($options as $option)
            @if(!empty($group))
                @if(!isset($option->{$id}) || empty($option->{$id}) || !isset($option->{$id}[0]))
                    <option value="{{ $option->id }}">{{ $option->{$optionName} }}</option>
                @else
                    <optgroup label="{{ $option->{$optionName} }}">
                        @foreach($option->{$id} as $item)
                            @php
                                $isSelected = isset($selectedValue) ?
                                (!empty($selectedValue) && $selectedValue == $item->id ? 'selected' : '') :
                                (isset($model) ? (old($id, $model->{$id}) == $item->id ? 'selected' : '') : '');
                            @endphp
                            <option value="{{ $item->id }}" {{ $isSelected }}>{{ $item->{$optionName} }}</option>
                        @endforeach
                    </optgroup>
                @endif
            @else
                @php
                    $isSelected = isset($selectedValue) ?
                    (!empty($selectedValue) && $selectedValue == $option->id ? 'selected' : '') :
                    (isset($model) ? (old($id, $model->{$id}) == $option->id ? 'selected' : '') : '');
                @endphp
                <option style="{{ !empty($option->color) ? 'color:'.$option->color : '' }}" value="{{ $option->id }}" {{ $isSelected }} {{ isset($optionAttr) ? $optionAttr : '' }}>{{ isset($idInOptionName) && $option->id ? $option->id.' | ' : '' }}{{ $option->{$optionName} }}</option>
            @endif
        @endforeach
    </select>
    <label class="mdl-textfield__label">{{ $title }}</label>
</div>