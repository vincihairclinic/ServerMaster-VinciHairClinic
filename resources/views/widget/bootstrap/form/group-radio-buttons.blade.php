@php
    $option = empty($option) ? 'name' : $option;
    $cell = !is_array($cell) ? [$cell, $cell, $cell] : $cell;
@endphp
<div class="{{ !empty($cell) ? 'col-xl-'.$cell[0].' col-sm-'.$cell[1].' col-'.$cell[2].'-col-phone' : ''}} btn-group d-flex flex-wrap form-group ds-radio-btn-container {{ $cssClass ?? '' }}" @if(!empty($radio_target)) id="{{ $radio_target }}" @endif role="group">
    <div class="w-100"><h5>{{ $title }}</h5></div>
    @if(!empty($boolean))
        @foreach([1, 0] as $item)
            <input type="radio" class="d-none" name="{{ $id }}" {{ !empty(old($id)) ? (old($id) == $item ? 'checked' : '') : (isset($selectedVal) ? ($selectedVal == $item ? 'checked' : '') : isset($model->{$id}) ? ((bool)$model->{$id} == (bool)$item ? 'checked' : '') : '') }} id="{{ $id }}_{{ !empty($option) ? $item->{$option} : $item }}" value='{{ $item }}' autocomplete="off">
            <label class="btn radio-btn rounded mr-3" for="{{ $id }}_{{ !empty($option) ? $item->{$option} : $item }}">{{ $item ? 'Yes' : 'No' }}</label>
        @endforeach

    @else
        @foreach($value as $item)
                @php
                    if (!empty($selectedValue)) {
                      $isSelected = !empty($option) ? $item->id == $selectedValue : $item == $selectedValue;
                      $isSelected = $isSelected ? 'checked' : '';
                    }
                    $item = (object)$item;
                @endphp
                <input type="radio" class="d-none" name="{{ $id }}" {{ $isSelected ?? '' }} id="{{ $id }}_{{ str_replace(' ', '_', !empty($option) ? $item->{$option} : $item) }}" value="{{ !empty($item->id) ? $item->id : $id }}" autocomplete="off">
                <label class="btn radio-btn mr-3" for="{{ $id }}_{{ str_replace(' ', '_', !empty($option) ? $item->{$option} : $item) }}">{{ !empty($option) ? $item->{$option} : $item }}</label>
        @endforeach
    @endif
</div>
@once
    @push('css_2')
        <style>
            .btn-group > label.btn.radio-btn{
                border: 1px solid var(--dashboard-primary-color);
                border-radius: 20px !important;
                color: var(--dashboard-primary-color);
            }
            input[type=radio]:checked + .btn.radio-btn {
                color: #fff;
                background: var(--dashboard-primary-color);
            }
            .btn.radio-btn:hover{
                transition: 0.15s;
                box-shadow: 0 0 10px -3px var(--dashboard-primary-color);
            }
        </style>
    @endpush

    @if($removed)
        @push('js')
            <script>
                $(function () {
                    $('#{{$radio_target}} label.radio-btn').on('click', function () {
                        let input = document.getElementById(this.getAttribute('for'));
                        if (input.checked) {
                            setTimeout(function () {
                                input.checked = false;
                            }, 20);
                        }
                    });
                });
            </script>
        @endpush
    @endif
@endonce