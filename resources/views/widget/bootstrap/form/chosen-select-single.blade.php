<?php
    $title = !empty($title) ? ($title != -1 ? $title : '') : (str_replace('_', ' ', ucfirst($id)));
    $cell = !is_array($cell) ? [$cell, $cell, $cell] : $cell;
    $optionName = !empty($optionName) ? $optionName : 'name';
    $targetId = empty($targetId) ? 'id' : $targetId;
?>

<div class="form-group {{ !empty($selectedValue) || (!empty($model) && !empty($model->{$id})) ? 'is-dirty' : '' }} {{ !empty($cell) ? 'col-xl-'.$cell[0].' col-md-'.$cell[1].' col-'.$cell[2] : ''}} {{ !empty($cssClass) ? $cssClass : '' }}" style="{{ !empty($style) ? $style : '' }}">
    <label>{{ $title }}</label>
    <select class="{{ $targetName ?? 'chosen-select' }}" name="{{ isset($name) ? $name : $id }}" id="{{ !empty($id_) ? $id_ : (isset($name) ? $name : $id)  }}" {{ isset($inputAttr) ? $inputAttr : '' }} data-placeholder="{{ isset($placeholder) ? $placeholder : '' }}" {!! isset($onchange) ? 'onchange="'.$onchange.'"' : '' !!}>
        @foreach($options as $option)
            @if(!empty($group))
                @if(!isset($option->{$id}) || empty($option->{$id}) || !isset($option->{$id}[0]))
                    @if($createEmptyGroup ?? true)
                        <option value="{{ $option->{$targetId} }}">{{ $option->{$optionName} }}</option>
                    @endif
                @else
                    <optgroup label="{{ $option->{$optionName} }}">
                        @foreach($option->{$id} as $item)
                            @php
                                $isSelected = isset($selectedValue) ?
                                (!empty($selectedValue) && $selectedValue == $item->{$targetId} ? 'selected' : '') :
                                (isset($model) ? (old($id, $model->{isset($name) ? $name : $id}) == $item->{$targetId} ? 'selected' : '') : '');
                            @endphp
                            <option value="{{ $item->{$targetId} }}" {{ $isSelected }}>{{ $item->{$optionName} }}</option>
                        @endforeach
                    </optgroup>
                @endif
            @else
                @php
                    $isSelected = isset($selectedValue) ?
                    (!empty($selectedValue) && $selectedValue == ((object)$option)->{$targetId} ? 'selected' : '') :
                    (isset($model) ? (old($id, $model->{$id}) == ((object)$option)->{$targetId} ? 'selected' : '') : '');
                @endphp
                <option style="{{ !empty($option->color) ? 'color:'.$option->color : '' }}"
                        value="{{ ((object)$option)->{$targetId} }}" {{ $isSelected }} {{ isset($optionAttr) ? $optionAttr : '' }}>
                    {{ isset($idInOptionName) && $option->{$targetId} ? $option->{$targetId}.' | ' : '' }}
                    {{ ((object)$option)->{$optionName} }}</option>
            @endif
        @endforeach
    </select>
</div>
