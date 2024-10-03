<div class="{{ !empty($cell) ? 'col-xl-'.$cell[0].' col-sm-'.$cell[1].' col-'.$cell[2].'-col-phone' : ''}} btn-group d-flex flex-wrap form-group" role="group" aria-label="Basic radio toggle button group">
    <div class="w-100"><h5>{{ $title }}</h5></div>
        @foreach($value as $item)
                <input type="checkbox" class="d-none" name="{{ $id }}[]" {{ !empty(old($id)) ? (in_array($item->id, old($id)) ? 'checked' : ''):  (isset($selectedVal) ? (in_array($item->id, $selectedVal) ? 'checked' : '') : (isset($model->{$id}) ? (in_array($item->id, $model->{$id}) ? 'checked' : '') : ''))}} id="{{ $id }}_{{ !empty($option) ? $item->{$option} : $item }}" value="{{ $item->id }}" autocomplete="off">
                <label class="btn checkbox-btn width-120 mr-3" for="{{ $id }}_{{ !empty($option) ? $item->{$option} : $item }}">{{ !empty($option) ? $item->{$option} : $item }}</label>
        @endforeach
</div>
@once
    @push('css_2')
        <style>
            .btn-group > label.btn.checkbox-btn{
                border: 1px solid #942056;
                border-radius: 20px !important;
                color: #942056;
            }
            input[type=checkbox]:checked + .btn.checkbox-btn {
                color: #fff;
                background: #942056;
            }
            .btn.checkbox-btn:hover{
                transition: 0.15s;
                box-shadow: 0 0 4px 3px rgba(148, 32, 86, 0.3);
            }
        </style>
    @endpush
@endonce