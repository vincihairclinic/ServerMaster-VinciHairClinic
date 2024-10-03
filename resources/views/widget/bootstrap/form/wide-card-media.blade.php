<?php
$title = !empty($title) ? ($title != -1 ? $title : '') : (str_replace('_', ' ', ucfirst($id)));
$cell = !is_array($cell) ? [$cell, $cell, $cell] : $cell;
?>
<div class="form-group {{ !empty($cell) ? 'col-xl-'.$cell[0].' col-md-'.$cell[1].' col-'.$cell[2] : ''}}" id="{{ $id }}{{ isset($i) ? '_'.$i : '' }}_wide-card">
    @if(!empty($title))
        <div class="title">
            <h4 class="{{ $errors->has($id) ? 'is-invalid' : '' }}">{{ $title }}</h4>
        </div>
    @endif

    <div class="card p-2" style="min-height: 0; {{ !empty($styleBlock) ? $styleBlock : '' }}">
        <div class="w-auto row no-gutters">
            <div class="w-100">
                @if(isset($showDeleteButton) || isset($showClearButton))
                    <span class="cursor-pointer media-delete-button" onclick="wideCardDelete{{ $id }}('{{ $id }}{{ isset($i) ? '_'.$i : '' }}', {{ !empty($removeContainer) ? 'true' : 'false' }}, '{{ $id }}')" style="float: right; min-width: 16px;">
                        <i class="material-icons">{{ !empty($showClearButton) ? 'delete' : 'clear' }}</i>
                    </span>
                @endif
                @if(isset($hideDownloadButton) ? !$hideDownloadButton : true)
                    <label for="{{ $id }}{{ isset($i) ? '_'.$i : '' }}" class="cursor-pointer">
                        <span style="float: left; min-width: 16px;">
                            <i class="material-icons">save_alt</i>
                        </span>
                    </label>
                @endif
            </div>

            @include('widget.bootstrap.form.error-validation-simple')

            @if(!empty($mediaType))
                <div class="col-12 text-center">
                    @if(isset($i))
                        <button style="width: 100%;" type="button" class="p-0 border-0" data-image="{{ $id }}{{ isset($i) ? '_'.$i : '' }}_image">
                            <img style="display: none; cursor: pointer; {{ !empty($imgStyle) ? $imgStyle : '' }}" class="base-img-fit-contain" src="{{ asset('images/base/upload-'.(!empty($mediaType) ? $mediaType : 'image').'-default.png') }}" id="{{ $id }}{{ isset($i) ? '_'.$i : '' }}_image" />

                            <{{ !isset($mediaType) || $mediaType == 'video' ? 'video' : 'audio' }} src="{{ !empty($src) ? $src : ($model->{'url_'.$id} ? $model->{'url_'.$id} : '#') }}" class="base-img-fit-contain" controls></{{ !isset($mediaType) || $mediaType == 'video' ? 'video' : 'audio' }}>
                        </button>
                    @else
                        <label style="width: 100%;" for="{{ $id }}{{ isset($i) ? '_'.$i : '' }}" class="p-0 border-0" data-image="{{ $id }}{{ isset($i) ? '_'.$i : '' }}_image">
                            <{{ !isset($mediaType) || $mediaType == 'video' ? 'video' : 'audio' }} src="{{ !empty($src) ? $src : ($model->{'url_'.$id} ? $model->{'url_'.$id} : '#') }}" class="base-img-fit-contain" controls></{{ !isset($mediaType) || $mediaType == 'video' ? 'video' : 'audio' }}>
                        </label>
                    @endif
                </div>

                <input type="file" accept="{{ !isset($mediaType) ? 'audio/mp3, video/mp4' : ($mediaType == 'video' ? 'video/mp4' : 'audio/mp3') }}" name="{{ $id }}{{ isset($i) ? '['.$i.']' : '' }}" id="{{ $id }}{{ isset($i) ? '_'.$i : '' }}" class="invisible" onchange="showMediaPreview(this)" value="">
            @else
                <div class="col-12 text-center">
                    <button style="width: 100%;" type="button" class="p-0 border-0" data-toggle="modal" data-target="#dialog-edit-image" data-image="{{ $id }}{{ isset($i) ? '_'.$i : '' }}_image">
                        <img class="base-img-fit-contain"
                             src="{{ !empty($src) ? $src : ($model->{'url_'.$id} ? $model->{'url_'.$id} : asset('images/base/upload-image-default.png') )}}?cache={{ \Illuminate\Support\Str::random() }}"
                             style="cursor: pointer; {{ !empty($imgStyle) ? $imgStyle : '' }}"
                             {{--onerror="this.src='{{asset('images/base/upload-image-default.png')}}'"--}}
                             id="{{ $id }}{{ isset($i) ? '_'.$i : '' }}_image"
                        />
                    </button>
                </div>

                <input type="file" accept="image/*" name="{{ $id }}{{ isset($i) ? '['.$i.']' : '' }}" id="{{ $id }}{{ isset($i) ? '_'.$i : '' }}" class="invisible" onchange="wideCardDelete{{ $id }}('{{ $id }}{{ isset($i) ? '_'.$i : '' }}', true, '{{ $id }}'); showImagePreview(this)" value="">
            @endif

        </div>
    </div>
</div>


@if(empty(\App\W::$tmp['wideCardDelete'.$id]) && empty($withoutWideCardDelete))
    @php(\App\W::$tmp['wideCardDelete'.$id] = true)


    @push('js')
        <script>
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
@endif

@if(empty(\App\W::$tmp['urls_deleted_'.$id]))
    @php(\App\W::$tmp['urls_deleted_'.$id] = true)

    <input type="hidden" name="urls_deleted_{{ $id }}" id="urls_deleted_{{ $id }}" value="">
@endif

