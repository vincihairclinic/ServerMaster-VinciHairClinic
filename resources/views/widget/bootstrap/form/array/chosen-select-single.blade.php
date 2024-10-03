<?php
    $title = !empty($title) ? ($title != -1 ? $title : '') : (str_replace('_', ' ', ucfirst($id)));
    $cell = !is_array($cell) ? [$cell, $cell, $cell] : $cell;
    $optionName = !empty($optionName) ? $optionName : 'name';
?>

<div class="form-group {{ !empty($selectedValue) || (!empty($model) && !empty($model->{$id})) ? 'is-dirty' : '' }} {{ !empty($cell) ? 'col-xl-'.$cell[0].' col-md-'.$cell[1].' col-'.$cell[2] : ''}} {{ !empty($cssClass) ? $cssClass : '' }}" style="{{ !empty($style) ? $style : '' }}">
    <label>{{ $title }}</label>
    <select class="{{ $targetName ?? 'chosen-select' }}" name="{{ isset($name) ? $name : $id }}[{{ $i }}]{{ isset($property) ? '['.$property.']' : '' }}" id="{{ !empty($id_) ? $id_ : (isset($name) ? $name : $id)  }}[{{ $i }}]" {{ isset($inputAttr) ? $inputAttr : '' }} {!! isset($onchange) ? 'onchange="'.$onchange.'"' : '' !!}>
        @foreach($options as $option)
            @if(!empty($group))
                @if(!isset($option->{$id}) || empty($option->{$id}) || !isset($option->{$id}[0]))
                    @if($createEmptyGroup ?? true)
                        <option value="{{ $option->id }}">{{ $option->{$optionName} }}</option>
                    @endif
                @else
                    <optgroup label="{{ $option->{$optionName} }}">
                        @foreach($option->{$id} as $item)
                            @php
                                $isSelected = isset($selectedValue) ?
                                (!empty($selectedValue) && $selectedValue == $item->id ? 'selected' : '') :
                                (isset($model) ? (old($id, $model->{isset($name) ? $name : $id}) == $item->id ? 'selected' : '') : '');
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
</div>
