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
        <input id="{{ $id }}" name="{{ $name ?? $id }}" type="text" value="" data-slider-value="{{ $model->{ $id } ?? $default }}" data-slider-ticks="{{ $ticks }}" data-slider-lock-to-ticks="true">
     </div>
</div>
@push('js')
    <script>
        $(function (){
            $('#{{ $id }}').slider({
                @if(!empty($formatterBefore) || !empty($formatterAfter))
                formatter: function(value) {
                    if (value  >= {{ $default }}){
                        document.getElementById('{{ $name ?? $id }}_slider_shower').innerHTML = '{{ '> ' ?? '' }}' + value + '{{ $formatterAfter ?? '' }}';
                    }else {
                        document.getElementById('{{ $name ?? $id }}_slider_shower').innerHTML = '{{ $formatterBefore ?? '' }}' + value + '{{ $formatterAfter ?? '' }}';
                    }
                    return value;
                }
                @endif
            });
        });
    </script>
@endpush
