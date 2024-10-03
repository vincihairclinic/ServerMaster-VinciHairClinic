 <div class="wide-card-image mdl-card {{ !empty($mdlCell) ? 'mdl-cell mdl-cell--'.$mdlCell[0].'-col mdl-cell--'.$mdlCell[1].'-col-tablet mdl-cell--'.$mdlCell[2].'-col-phone' : ''}}" style="min-height: 0; {{ !empty($styleBlock) ? $styleBlock : '' }}" id="{{ $id }}{{ isset($i) ? '_'.$i : '' }}_wide-card">
        @if(!empty($title))
            <div class="mdl-card__title">
                <h2 class="mdl-card__title-text {{ $errors->has($id) ? 'is-invalid' : '' }}">{{ $title }}</h2>
            </div>
        @endif

        <div class="mdl-card__supporting-text mdl-grid" style="padding: 0; width: auto;">
            <div style="width: 100%;">
                @if(isset($showDeleteButton) || isset($showClearButton))
                    <span class="mdl-button mdl-js-button mdl-button--accent" onclick="wideCardDeleteImage('{{ $id }}{{ isset($i) ? '_'.$i : '' }}', {{ !empty($showClearButton) && !isset($i) ? 'true' : 'false' }}, '{{ $id }}')" style="float: right; min-width: 16px;">
                        <i class="material-icons">{{ !empty($showClearButton) ? 'delete' : 'clear' }}</i>
                    </span>
                @endif
                <label for="{{ $id }}{{ isset($i) ? '_'.$i : '' }}">
                    <span class="mdl-button mdl-js-button" style="float: left; min-width: 16px;">
                        <i class="material-icons">save_alt</i>
                    </span>
                </label>
            </div>

            @include('widget.mdl.form.error-validation-simple')
            <div class="mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--4-col-phone">
                <img class="base-img-fit-contain"
                     src="{{ !empty($src) ? $src : ($model->{'url_'.$id} ? $model->{'url_'.$id} : asset('images/base/upload-image-default.png') )}}?cache={{ \Illuminate\Support\Str::random() }}"
                     style="cursor: pointer; {{ !empty($imgStyle) ? $imgStyle : '' }}"
                     id="{{ $id }}{{ isset($i) ? '_'.$i : '' }}_img"
                     onclick="showDialogEditImage(this, true)"
                />
            </div>
            <input type="file" accept="image/*" name="{{ $id }}{{ isset($i) ? '['.$i.']' : '' }}" id="{{ $id }}{{ isset($i) ? '_'.$i : '' }}" class="invisible" onchange="wideCardDeleteImage('{{ $id }}{{ isset($i) ? '_'.$i : '' }}', true, '{{ $id }}'); showImagePreview(this)" value="">
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