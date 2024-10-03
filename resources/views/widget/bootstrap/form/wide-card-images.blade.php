<div class="col-12 no-gutters px-0 row">
    <div class="col-12 row px-0 mx-0 form-group" id="items-card-photos-{{ $arrayName }}-container" style="padding: 0; margin: 0; width: 100%;">
        @foreach($array as $i => $item)
            @include('widget.bootstrap.form.wide-card-image', ['id' => $arrayName, 'cell' => $cell,
                'showDeleteButton' => true,
                'src' => $item,
                'i' => $i,
            ])
        @endforeach


    </div>
    <div class="col-12">
        <button type="button" class="btn btn-self-primary min-width-120 float-right mr-2" onclick="addNewItems{{ $arrayName }}Form()">
            <span class="material-icons align-middle">playlist_add</span>
        </button>
    </div>
</div>
<template id="add-new-card-photo-{{ $arrayName }}-template">
    @include('widget.bootstrap.form.wide-card-image', ['id' => $arrayName, 'cell' => $cell,
        'showDeleteButton' => true,
        'src' => asset('images/base/upload-image-default.png'),
        'i' => '${newItemIterator}',
    ])
</template>

@push('js')
    <script>
        var inputIdSelectImg = null;
        function initializeCheckSelectImg(id)
        {
            inputIdSelectImg = id;
            document.body.onfocus = checkItSelectImg;
        }
        function checkItSelectImg()
        {
            setTimeout(function () {
                if($('#'+inputIdSelectImg+'_img').attr('src') === defaultImageUrl){
                    wideCardDeleteImage(inputIdSelectImg);
                }
            }, 500);
            document.body.onfocus = null;
        }

        function addNewItems{{ $arrayName  }}Form() {

            var newItemIterator = Date.now()+'_';
            $('#add-new-card-photo-{{ $arrayName  }}-template').tmpl({
                newItemIterator: newItemIterator,
            }).appendTo('#items-card-photos-{{ $arrayName  }}-container');

            // $('#image_origin_local_path_array_'+newItemI).trigger('click');
            // initializeCheckSelectImg('image_origin_local_path_array_'+newItemI);

        }
        @if(empty($model->id))
            $(document).ready(function () {
                addNewItems{{ $arrayName  }}Form();
            });
        @endif
    </script>
@endpush
