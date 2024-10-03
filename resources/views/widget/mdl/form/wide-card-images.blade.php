<div class="{{ !empty($mdlCell) ? 'mdl-cell mdl-cell--'.$mdlCell[0].'-col mdl-cell--'.$mdlCell[1].'-col-tablet mdl-cell--'.$mdlCell[2].'-col-phone' : ''}} mdl-grid" id="items-card-photos-div" style="padding: 0; margin: 0; width: 100%;">
    @foreach($model->{$id.'_url'} as $i => $item)
        @include('widget.mdl.form.wide-card-image', ['id' => $id, 'mdlCell' => $mdlCell,
            'showDeleteButton' => true,
            'src' => $item,
            'i' => $i,
        ])
    @endforeach

    <div id="add-new-items-form-btn" class="{{ !empty($mdlCell) ? 'mdl-cell mdl-cell--'.$mdlCell[0].'-col mdl-cell--'.$mdlCell[1].'-col-tablet mdl-cell--'.$mdlCell[2].'-col-phone' : ''}}" style="background-color: rgba(0,0,0,.1); margin-top: 30px;">
        <div class="mdl-button mdl-js-button mdl-button--accent"
             style="height: 100%; width: 100%; padding: 0; margin: 0;" onclick="addNewItemsForm()">
            <span class="material-icons" style="font-size: 36px; line-height: 180px; color: #fff;">playlist_add</span>
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

            $('#image_origin_local_path_array_'+newItemI).trigger('click');
            initializeCheckSelectImg('image_origin_local_path_array_'+newItemI);

        }
    </script>
@endpush
