<?php
    $title = !empty($title) ? ($title != -1 ? $title : '') : (str_replace('_', ' ', ucfirst($id)));
    $cell = !is_array($cell) ? [$cell, $cell, $cell] : $cell;
?>
<div class="col-12 row" id="items-card-{{ $id }}s-div" style="padding: 0; margin: 0; width: 100%;">
    @if(!empty($title))
        <div class="title col-12">
            <label>{{ $title }}</label>
        </div>
    @endif
    @if(!empty($model->{'url_'.$id}))
        @foreach($model->{'url_'.$id} as $i => $item)
            @include('widget.bootstrap.form.wide-card-media', ['id' => $id, 'cell' => $cell,
                'showDeleteButton' => true,
                'src' => $item,
                'i' => $i,
                'title' => -1,
                'withoutWideCardDelete' => true,
                'showBaseUploadImage' => true,
            ])
        @endforeach
    @endif

    @if(empty(\App\W::$tmp['urls_deleted_'.$id]))
        @php(\App\W::$tmp['urls_deleted_'.$id] = true)

        <input type="hidden" name="urls_deleted_{{ $id }}" id="urls_deleted_{{ $id }}" value="">
    @endif


        <div class="form-group {{ !empty($cell) ? 'col-xl-'.$cell[0].' col-md-'.$cell[1].' col-'.$cell[2] : ''}}" id="add-new-items-form-{{ $id }}-btn">
        <div class="card p-2">
            <div class="w-auto row no-gutters">
                <div class="w-100">
                    <span style="line-height: 32px;">&nbsp;</span>
                </div>

                <div class="col-12 text-center">
                    <button style="width: 100%;" type="button" class="p-0 border-0" onclick="addNewItemsForm{{ $id }}()">
                        @if(!empty($showBaseUploadImage))
                            <img class="base-img-fit-contain"
                                 src="{{ asset('images/base/upload-'.(!empty($mediaType) ? $mediaType : 'image').'-default.png') }}"
                                 style="cursor: pointer; {{ !empty($imgStyle) ? $imgStyle : '' }}"
                            />
                        @else
                            <span class="material-icons" style="font-size: 36px; color: #c9c9c9; line-height: 150px">playlist_add</span>
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<template id="add-new-card-{{ $id }}-template">
    @include('widget.bootstrap.form.wide-card-media', ['id' => $id, 'cell' => $cell,
        'showDeleteButton' => true,
        'src' => asset('images/base/upload-image-default.png'),
        'i' => '${newItemI}',
        'title' => -1,
        'withoutWideCardDelete' => true,
        'showBaseUploadImage' => true,
    ])
</template>

@push('js')
    <script>
        var inputIdSelect{{ $id }} = null;
        function initializeCheckSelect{{ $id }}(id)
        {
            inputIdSelect{{ $id }} = id;
            document.body.onfocus = checkItSelect{{ $id }};
        }

        function checkItSelect{{ $id }}()
        {
            setTimeout(function () {
                if($('#'+inputIdSelect{{ $id }}+'_image').attr('src').includes('{{ asset('images/base/upload-'.(!empty($mediaType) ? $mediaType : 'image').'-default.png') }}')){
                    wideCardDelete{{ $id }}(inputIdSelect{{ $id }}, false, '{{ $id }}');
                }
            }, 500);
            document.body.onfocus = null;
        }

        function addNewItemsForm{{ $id }}()
        {
            var addNewItemsFormBtn = $('#add-new-items-form-{{ $id }}-btn').clone();
            $('#add-new-items-form-{{ $id }}-btn').remove();
            var newItemI = Date.now()+'_';
            $('#add-new-card-{{ $id }}-template').tmpl({
                newItemI: newItemI,
            }).appendTo('#items-card-{{ $id }}s-div');
            $('#items-card-{{ $id }}s-div').append(addNewItemsFormBtn);

            $('#{{ $id }}_'+newItemI).trigger('click');
            initializeCheckSelect{{ $id }}('{{ $id }}_'+newItemI);
        }

        function wideCardDelete{{ $id }}(id, clearOnly, modelId) {
            var itemId = $('#'+id+'_wide-card');
            var urlDeleteImage = itemId.find('img.base-img-fit-contain').attr('src');
            urlDeleteImage = urlDeleteImage ? urlDeleteImage : '#';
            var urlDeleteMedia = itemId.find('{{ !empty($mediaType) ? $mediaType : 'img' }}.base-img-fit-contain').attr('src');
            if(!empty(urlDeleteMedia) && urlDeleteMedia.includes('{{ config('app.url') }}')){
                urlDeleteMedia = urlDeleteMedia.split("?")[0];
                if(urlDeleteMedia && urlDeleteMedia !== '' && urlDeleteMedia !== '{{ asset('images/base/upload-'.(!empty($mediaType) ? $mediaType : 'image').'-default.png') }}'){
                    urlDeleteImage = urlDeleteMedia;
                }else{
                    urlDeleteImage = urlDeleteImage.split("?")[0];
                }
                if(urlDeleteImage && urlDeleteImage !== '' && urlDeleteImage !== '{{ asset('images/base/upload-'.(!empty($mediaType) ? $mediaType : 'image').'-default.png') }}'){
                    var val = $('#urls_deleted_'+modelId).val();
                    $('#urls_deleted_'+modelId).val((val !== '' ? val+','+urlDeleteMedia : urlDeleteMedia));
                }
            }

            if(clearOnly){
                itemId.find('img').attr('src', '{{ asset('images/base/upload-'.(!empty($mediaType) ? $mediaType : 'image').'-default.png') }}').css({display:'block'});
                itemId.find('{{ !empty($mediaType) ? $mediaType : 'img' }}.base-img-fit-contain').css({display:'none'}).attr('src', '#');
            }else {
                itemId.remove();
            }
        }

    </script>
@endpush