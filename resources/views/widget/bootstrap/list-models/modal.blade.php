@php
    $fields = $settingsListModels['fields']
@endphp
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="{{ $modalId }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $title ?? '' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body">
                    <input class="d-none" type="hidden" name="ajax" value="1">
                    @foreach($fields as $key => $item)
                        @switch($item['type'])
                            @case('text')
                                @include('widget.bootstrap.list-models.text-input', ['id' => $item['id'], 'title' => $item['title'], 'inputName' => $item['id'] ,'cell' => $item['cell'] ?? [],'modalValidation' => true ])
                            @break

                            @case('select')
                                @include('widget.bootstrap.list-models.chosen-select-single', ['id' => $item['id'], 'title' => $item['title'], 'inputName' => $item['id'] ,'cell' => $item['cell'] ?? [],
                                    'options' => $item['options']::findAll(),
                                ])
                            @break

                            @case('switch')

                            @break
                            @default
                        @endswitch
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-self-primary" onclick="modal{{ $modalId }}Submit()">Save</button>
                </div>
        </div>
    </div>
</div>

@if($pushJs ?? false)
    @push('js')
@endif

    <script>
        function modal{{ $modalId }}Submit(){
            let arrayInputs = Array.from($('#{{ $modalId }} .form-control'));
            let data = {};
            arrayInputs.forEach(el =>{
                data[el.name] = el.value;
            });
            $.ajax({
                url : '{{ $actionUrl }}',
                method: 'POST',
                data: data,
                success: function (data){
                    arrayInputs.forEach(el => {
                        el.value = '';
                        el.classList.remove('is-invalid');
                    })
                    dataTable.ajax.reload();
                    $('#{{ $modalId }}').modal('hide');
                },
                error: function (data){
                    if (data.status === 422) {
                        arrayInputs.forEach(el =>{
                            el.classList.remove('is-invalid');
                        })
                        let errors = data.responseJSON.errors;
                        Object.getOwnPropertyNames(errors).forEach(el =>{
                            $('#error_{{ $modalId.'_' }}' + el).text(errors[el]);
                            $('#{{ $modalId.'_' }}' + el).addClass('is-invalid');
                            $('#{{ $modalId.'_' }}' + el).attr('onkeydown','this.');
                        });

                    }
                    else {
                        dataTable.ajax.reload();
                    }
                }
            });
        }

        defaultChosenInit();
    </script>

@if($pushJs ?? false)
    @endpush
@endif