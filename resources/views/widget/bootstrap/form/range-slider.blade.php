<?php
    $title = !empty($title) ? ($title != -1 ? $title : '') : (str_replace('_', ' ', ucfirst($id)));
    $cell = !is_array($cell) ? [$cell, $cell, $cell] : $cell;
?>
<div class="form-group pt-2 overflow-hidden {{ !empty($cell) ? 'col-xl-'.$cell[0].' col-md-'.$cell[1].' col-'.$cell[2] : 'col-auto'}} {{ !empty($cssClass) ? $cssClass : '' }}">
    <div class="d-flex flex-wrap justify-content-between px-1">
        <label>{{ $title }}</label>
        <div id="{{ $name ?? $id }}_slider_shower"></div>
    </div>
    <div class="px-1">
        <input id="{{ $id }}" name="{{ $name ?? $id }}" type="text" value="" data-slider-min="{{ $min }}" data-slider-max="{{ $max }}" data-slider-step="{{ $step ?? 1 }}"
               data-slider-value="{{ '['.(!empty(old(($name ?? $id).'_from')) ? old(($name ?? $id).'_from') : (isset($selectedVal) ? $selectedVal : (isset($model->{($name ?? $id).'_from'}) ? $model->{($name ?? $id).'_from'} : $min))).','.(!empty(old(($name ?? $id).'_to')) ? old(($name ?? $id).'_to') : (isset($selectedVal) ? $selectedVal : (isset($model->{($name ?? $id).'_to'}) ? $model->{($name ?? $id).'_to'} : $max))).']' }}"/>
    </div>
</div>
@push('js')
    <script>
        $(function (){
            $('#{{ $id }}').slider({
                @if(!empty($formatterBefore) || !empty($formatterMiddle) || !empty($formatterAfter))
                    formatter: function(value) {
                        if (Array.isArray(value)){
                        document.getElementById('{{ $name ?? $id }}_slider_shower').innerHTML = '{{ $formatterBefore ?? '' }}' + value[0] + '{{ $formatterMiddle ?? ' - ' }}' + value[1] + '{{ $formatterAfter ?? '' }}';
                        }
                        return value;
                    }
                @endif
            });
        });
    </script>
@endpush
