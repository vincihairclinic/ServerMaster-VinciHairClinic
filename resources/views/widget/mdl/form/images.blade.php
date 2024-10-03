<div class="mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--4-col-phone mdl-grid" id="items-card-photos-div" style="padding: 0; margin: 0; width: 100%;">
    @if(!empty($model->{'url_'.$id}))
        @foreach($model->{'url_'.$id} as $i => $item)
            @include('widget.mdl.form.wide-card-image', ['id' => $id, 'mdlCell' => $mdlCell,
                'showDeleteButton' => true,
                'src' => $item,
                'i' => $i,
            ])
        @endforeach
    @endif

    <div id="add-new-items-form-btn"
         class="mdl-cell mdl-cell--{{ $mdlCell[0] }}-col mdl-cell--{{ $mdlCell[1] }}-col-tablet mdl-cell--{{ $mdlCell[2] }}-col-phone"
         style="background-color: rgba(0,0,0,.1); margin-top: 30px; position: relative;  min-height: 150px;">
        <div class="mdl-button mdl-js-button mdl-button--accent"
             style="position:absolute; height: 100%; width: 100%; padding: 0; margin: 0;"
             onclick="addNewItemsForm()">
            <span class="material-icons" style="font-size: 36px; color: #fff;">playlist_add</span>
        </div>
    </div>
</div>

<template id="add-new-card-photo-template">
    @include('widget.mdl.form.wide-card-image', ['id' => $id, 'mdlCell' => $mdlCell,
        'showDeleteButton' => true,
        'src' => asset('images/base/upload-image-default.png'),
        'i' => '${newItemI}',
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

        function addNewItemsForm() {
            var addNewItemsFormBtn = $('#add-new-items-form-btn').clone();
            $('#add-new-items-form-btn').remove();
            var newItemI = Date.now()+'_';
            $('#add-new-card-photo-template').tmpl({
                newItemI: newItemI,
            }).appendTo('#items-card-photos-div');
            $('#items-card-photos-div').append(addNewItemsFormBtn);

            $('#{{ $id }}_'+newItemI).trigger('click');
            initializeCheckSelectImg('{{ $id }}_'+newItemI);
        }

        function wideCardDeleteImage(id, clearOnly) {
            var itemId = $('#'+id+'_wide-card');
            var urlDeleteImage = itemId.find('img').attr('src');
            urlDeleteImage = urlDeleteImage.split("?")[0];
            if(urlDeleteImage && urlDeleteImage !== '' && urlDeleteImage !== defaultImageUrl){
                var val = $('#images_deleted_input').val();
                $('#images_deleted_input').val((val !== '' ? val+','+urlDeleteImage : urlDeleteImage));
            }
            if(clearOnly){
                itemId.find('img').attr('src', defaultImageUrl);
            }else {
                itemId.remove();
            }
        }

    </script>
@endpush