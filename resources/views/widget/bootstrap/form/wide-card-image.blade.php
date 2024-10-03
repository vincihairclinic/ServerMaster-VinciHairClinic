<?php
    $title = !empty($title) ? ($title != -1 ? $title : '') : (str_replace('_', ' ', ucfirst($id)));
    $cell = !is_array($cell) ? [$cell, $cell, $cell] : $cell;
    $defaultImg = !empty($defaultImg) ? $defaultImg : asset('images/base/upload-image-default.png');
?>
<div class="form-group {{ !empty($cell) ? 'col-xl-'.$cell[0].' col-md-'.$cell[1].' col-'.$cell[2] : ''}} {{ !empty($cssClass) ? $cssClass : '' }}" id="{{ $id }}{{ isset($i) ? '_'.$i : '' }}_wide-card">
    @if(!empty($title))
        <div class="title">
            <h4 class="{{ $errors->has($id) ? 'is-invalid' : '' }}">{{ $title }}</h4>
        </div>
    @endif
    <div class="card p-2" style="min-height: 0; {{ !empty($styleBlock) ? $styleBlock : '' }}">

        <div class="w-auto row no-gutters">
            <div class="w-100">
                @if(isset($showDeleteButton) || isset($showClearButton))
                    <span class="cursor-pointer" onclick="wideCardDeleteImage('{{ $id }}{{ isset($i) ? '_'.$i : '' }}', {{ !empty($showClearButton) && !isset($removeContainer) ? 'true' : 'false' }}, '{{ $id }}')" style="float: right; min-width: 16px;">
                        <i class="material-icons">{{ !empty($showClearButton) ? 'delete' : 'clear' }}</i>
                    </span>
                @endif
                @if(isset($hideDownloadButton) ? !$hideDownloadButton : true)
                    <label for="{{ $id }}{{ isset($i) ? '_'.$i : '' }}">
                        <span style="float: left; min-width: 16px;">
                            <i class="material-icons">save_alt</i>
                        </span>
                    </label>
                @endif
            </div>

            @include('widget.mdl.form.error-validation-simple')
            <div class="col-12 text-center" >
                <button type="button" class="p-0 border-0 position-relative" data-toggle="modal" data-target="#dialog-edit-image" data-image="{{ $id }}{{ isset($i) ? '_'.$i : '' }}_img">
                    <img class="base-img-fit-contain"
                         src="{{ !empty($src) ? $src : ($model->{'url_'.$id} ? $model->{'url_'.$id} : $defaultImg )}}?cache={{ \Illuminate\Support\Str::random() }}"
                         style="cursor: pointer; {{ !empty($imgStyle) ? $imgStyle : '' }}"
                         onerror="this.src='{{ $defaultImg }}'"
                         id="{{ $id }}{{ isset($i) ? '_'.$i : '' }}_img"
                         {{ !empty($imgAttr) ? $imgAttr : '' }}
                    />
                    <div class="custom"></div>
                </button>
            </div>
            <input type="file" accept="image/*" name="{{ $id }}{{ isset($i) ? '['.$i.']' : '' }}" id="{{ $id }}{{ isset($i) ? '_'.$i : '' }}" class="invisible" onchange="wideCardDeleteImage('{{ $id }}{{ isset($i) ? '_'.$i : '' }}', true, '{{ $id }}'); showImagePreview(this)" value="">
        </div>
    </div>
</div>


@if(empty(\App\W::$tmp['wideCardDeleteImage']))
    @php(\App\W::$tmp['wideCardDeleteImage'] = true)


    @push('js')
        <script>
            function wideCardDeleteImage(id, clearOnly, modelId) {
                var itemId = $('#'+id+'_wide-card');
                var urlDeleteImage = itemId.find('img').attr('src');
                urlDeleteImage = urlDeleteImage.split("?")[0];
                if(urlDeleteImage && urlDeleteImage !== '' && urlDeleteImage !== defaultImageUrl){
                    var val = $('#images_deleted_'+modelId).val();
                    $('#images_deleted_'+modelId).val((val !== '' ? val+','+urlDeleteImage : urlDeleteImage));
                }
                if(clearOnly){
                    itemId.find('img').attr('src', defaultImageUrl);
                }else {
                    itemId.remove();
                }
            }
        </script>
    @endpush
@endif

@if(empty(\App\W::$tmp['images_deleted_'.$id]))
    @php(\App\W::$tmp['images_deleted_'.$id] = true)

    <input type="hidden" name="images_deleted_{{ $id }}" id="images_deleted_{{ $id }}" value="">
@endif