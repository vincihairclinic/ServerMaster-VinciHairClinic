<div class="wide-card-image mdl-card {{ !empty($mdlCell) ? 'mdl-cell mdl-cell--'.$mdlCell[0].'-col mdl-cell--'.$mdlCell[1].'-col-tablet mdl-cell--'.$mdlCell[2].'-col-phone' : ''}}">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text {{ $errors->has($arrayName.'.'.$i.'.'.$id) ? 'is-invalid' : '' }}">{{ $title }}</h2>
    </div>

    <div class="mdl-card__supporting-text mdl-grid">
        @include('widget.mdl.form.array.error-validation')
        <label class="mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--4-col-phone" for="{{ $arrayName.'.'.$i.'.'.$id }}">
            @if(isset($showUploadButton) && $showUploadButton)
                <div style="text-align: center; padding-bottom: 20px;">
                    <div class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">Choose {{ !isset($mediaType) ? 'media' : $mediaType }} file</div>
                </div>
            @endif
            <img style="display: none;" class="base-img-fit-contain" src="{{ !isset($mediaType) || $mediaType == 'video' ? asset('images/upload-video-default.png') : asset('images/upload-audio-default.png') }}" />
            <{{ !isset($mediaType) || $mediaType == 'video' ? 'video' : 'audio' }} src="{{ isset($item->id) ? $item->{$id} : '' }}" class="base-img-fit-contain audio-preview" controls onerror="this.parentElement.getElementsByTagName('img')[0].style.display = 'block'; this.style.display = 'none';"></{{ !isset($mediaType) || $mediaType == 'video' ? 'video' : 'audio' }}>
        </label>
        <input type="file" accept="{{ !isset($mediaType) ? 'audio/mp3, video/mp4' : ($mediaType == 'video' ? 'video/mp4' : 'audio/mp3') }}" name="{{ $arrayName }}[{{ $i }}][{{ $id }}]" id="{{ $arrayName.'.'.$i.'.'.$id }}" class="invisible" onchange="showMediaPreview(this)" value="">
    </div>
</div>