@once
    @push('css_2')
        <link href="{{ asset('/css/plugins/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('/css/plugins/bootstrap/select2-bootstrap4.min.css') }}" rel="stylesheet" />

        <style>
            .select-template-image{
                max-width: 60px;
                max-height: 40px;
            }
            .select2-container .select2-selection--single{
                min-height: 28px;
                height: auto;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                top: 50%;
                transform: translateY(-50%);
            }
            .select2-container--default .select2-selection--single .select2-selection__clear {
                top: 50%;
                position: absolute;
                right: 20px;
                line-height: 0;
                font-size: 21px;
                margin-right: 0;
                z-index: 20;
                transform: translateY(-50%);
            }
            .select2-container .select2-selection--single {
                min-height: 28px;
                height: auto;
            }
            .select2-template .select2-container .select2-selection--single {
                min-height: 60px;
                height: auto;
            }
            .select2-container--bootstrap4.select2-container--focus .select2-selection {
                 border-color: #7a1831;
                 -webkit-box-shadow: 0 0 0 0.2rem rgba(122, 24, 49, 0.25);
                 box-shadow: 0 0 0 0.2rem rgba(122, 24, 49, 0.25);
             }
            .select2-container--bootstrap4 .select2-results__option--highlighted, .select2-container--bootstrap4 .select2-results__option--highlighted.select2-results__option[aria-selected="true"] {
                color: #fff;
                background-color: #7a1831;
            }
        </style>
    @endpush
    @push('js')
        <script src="{{ asset('/js/plugins/select2.min.js') }}"></script>
    @endpush
@endonce

<div class="form-group {{ !empty($selectTemplates) ? 'select2-template' : '' }} {{ !empty($value) || (!empty($typeInput) && $typeInput == 'date') ? 'is-dirty' : '' }} {{ !empty($cell) ? 'col-xl-'.$cell[0].' col-md-'.$cell[1].' col-'.$cell[2] : 'col-12'}} {{ !empty($cssClass) ? $cssClass : '' }}">
    <label>{{ $title }}</label>
    <select class="{{ $cssClass ?? '' }} @if($errors->has($name ?? $id))is-invalid @endif w-100" id="{{ $id }}" name="{{ $name ?? $id }}" onchange="this.classList.remove('is-invalid');this.removeAttribute('onclick');this.nextElementSibling.nextElementSibling.classList.remove('is-invalid');">
    </select>
    <input type="hidden" class="@if($errors->has($name ?? $id))is-invalid @endif">
    @include('widget.bootstrap.form.error-validation')
</div>

@push('js')
    <script>
        $(function (){
            @php
                if (!isset($selectedItem)) {
                    $selectedItem = old($name ?? $id) ?? $model->{$name ?? $id};
                }
            @endphp
            @if(!empty($selectTemplates))
                function select2ProcessResults{{ str_replace(['-','_'], '', $id) }} (item) {
                    let img = $('<img>').attr('src', item.url_image ?? defaultImageUrl).attr('class','select-template-image border').attr('onerror','this.src = "{{ asset('images/base/upload-image-default.png') }}"');
                    let container = $('<div>').attr('class','row align-items-center py-2 mx-0')
                    .append(img)
                    .append($('<div>').append(item.text ?? '').attr('class','col'));
                    return $(container);
                }
                function select2ProcessSelection{{ str_replace(['-','_'], '', $id) }} (item) {
                    let img = $('<img>').attr('src', item.url_image ?? defaultImageUrl).attr('class','select-template-image border').attr('onerror','this.src = "{{ asset('images/base/upload-image-default.png') }}"');
                    let container = $('<div>').attr('class','row align-items-center py-2 mx-0')
                        .append(img)
                        .append($('<div>').append(item.text ?? '').attr('class','col'));
                    return $(container);
                }
            @endif
            $('#{{ $id }}').select2({
                // theme: 'bootstrap4',
                allowClear: true,
                @if($placeholder ?? true)
                    placeholder: {
                        id: '',
                        text: '{{ $selectPlaceholder ?? 'Select..' }}',
                    },
                @endif
                ajax: {
                    url: '{{ $route }}',
                    dataType: 'json',
                    method: 'POST',
                    delay: 250,
                    selectOnClose: true,
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data,params) {
                        params.page = params.page || 1;
                        return data;
                    },
                },
                @if(!empty($selectTemplates))
                    templateResult: select2ProcessResults{{ str_replace(['-','_'], '', $id) }},
                    templateSelection: select2ProcessSelection{{ str_replace(['-','_'], '', $id) }},
                @endif
            })@if(!empty($selectTemplates))
                .on('select2:select', function (e) {
                    let data = e.params.data;
                    let select = $('#select2-' + this.id + '-container');
                    let img = $('<img>').attr('src', data.url_image ?? defaultImageUrl).attr('class', 'select-template-image align-self-center border').attr('onerror', 'this.src = "' + defaultImageUrl + '"');
                    let container = $('<div>').attr('class', 'row align-items-center py-2 mx-0')
                        .append(img)
                        .append($('<div>').append(data.text ?? '').attr('class', 'col'));
                    setTimeout(function (){
                        $(select).empty();
                        $(select).append(container);
                    },10);
            })@endif;
                @if (!empty($selectedItem))
                $.ajax({
                    url: '{{ $route }}' + '?selectedValue=' + '{{ $selectedItem }}',
                    method: 'POST',
                    success: function (data) {
                        let select = $('#{{ $id }}')[0];
                        let option = new Option(data.text, data.id, true, true);
                        $(select).append(option).trigger('change');
                        $(select).trigger({
                            type: 'select2:select',
                            params: {
                                data: data,
                            }
                        });
                    }
                });
                @endif
{{--            {{ dd($selectedItem) }}--}}
        });
    </script>
@endpush
