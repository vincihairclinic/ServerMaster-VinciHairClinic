<div class="{{ !empty($mdlCell) ? 'mdl-cell mdl-cell--'.$mdlCell[0].'-col mdl-cell--'.$mdlCell[1].'-col-tablet mdl-cell--'.$mdlCell[2].'-col-phone' : ''}}">
        @include('widget.mdl.form.array.error-validation')
        <label class="cursor-pointer" for="{{ $arrayName.'.'.$i.'.'.$id }}">
            <img style="display: none; width: 100px; margin: auto;" src="{{ !isset($mediaType) || $mediaType == 'video' ? asset('images/upload-video-default.png') : asset('images/upload-audio-default.png') }}" />
            <{{ !isset($mediaType) || $mediaType == 'video' ? 'video' : 'audio' }} onloadeddata="getMediaDuration(this, '{{ !empty($durationField) ? $arrayName.'['.$i.']['.$durationField.']' : '' }}')" src="{{ isset($item->id) ? $item->{$id} : '' }}" class="base-img-fit-contain audio-preview" controls onerror="this.parentElement.getElementsByTagName('img')[0].style.display = 'block'; this.style.display = 'none';"></{{ !isset($mediaType) || $mediaType == 'video' ? 'video' : 'audio' }}>
            @if(isset($showUploadButton) && $showUploadButton)
                <div style="text-align: center; padding-top: 20px;">
                    <div class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">Choose {{ !isset($mediaType) ? 'media' : $mediaType }} file</div>
                </div>
            @endif
        </label>
        <input type="file" accept="{{ !isset($mediaType) ? 'audio/mp3, video/mp4' : ($mediaType == 'video' ? 'video/mp4' : 'audio/mp3') }}" name="{{ $arrayName }}[{{ $i }}][{{ $id }}]" id="{{ $arrayName.'.'.$i.'.'.$id }}" class="invisible" onchange="showMediaPreview(this)" value="">
</div>
