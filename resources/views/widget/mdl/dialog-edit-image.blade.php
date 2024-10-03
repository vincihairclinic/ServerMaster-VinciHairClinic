<dialog id="dialog-edit-image" class="mdl-dialog" style="min-width: {{ isset($minWidth) ? $minWidth : '50%' }}; top: 60px;">
    <div class="mdl-dialog__content">
        {{--<img class="base-img-fit-contain image-preview" src="{{ asset('images/base/upload-image-default.png') }}">--}}

        <label for="dialog-img-file-input">
            <img class="base-img-fit-contain image-preview" src="{{ asset('images/base/upload-image-default.png') }}">
        </label>
    </div>
    <div class="mdl-dialog__actions">
        <button class="mdl-button mdl-js-button close-btn">cancel</button>

        @if(!empty($isRemove))
            <button id="dialog-img-remove-btn" type="button" class="mdl-button mdl-js-button mdl-button--accent color-red" data-action="" data-id-img="" onclick="deleteImageDialog(this)">remove</button>
        @endif
        @if(!empty($isUpload))
            <input type="file" accept="image/*" id="dialog-img-file-input" data-action="" data-id-img="" style="display: none;" onchange="uploadImageDialog(this)">
            {{--<label for="dialog-img-file-input" type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">upload</label>--}}
            @if(!empty($isUploadByUrl))
                <input type="text" id="dialog-img-url-input"><button id="dialog-img-url-btn" onclick="uploadByUrlImageDialog(this)">upload by url</button>
            @endif
        @endif
        @if(!empty($customBtn))
            {!! $customBtn !!}
        @endif
    </div>
</dialog>


@push('js')
    <script>
        var dialogEditImage = document.querySelector('#dialog-edit-image');
        if (! dialogEditImage.showModal) {
            dialogPolyfill.registerDialog(dialogEditImage);
        }
        dialogEditImage.querySelector('#dialog-edit-image .close-btn').addEventListener('click', function() {
            dialogEditImage.close();
        });

        @if(!isset($isBackdropClickCloseDialog) || $isBackdropClickCloseDialog)
            dialogEditImage.addEventListener('click', function (event) {
                var rect = dialogEditImage.getBoundingClientRect();
                var isInDialog=(rect.top <= event.clientY && event.clientY <= rect.top + rect.height
                    && rect.left <= event.clientX && event.clientX <= rect.left + rect.width);
                if (!isInDialog) {
                    dialogEditImage.close();
                }
            });
        @endif

        function showDialogEditImage(e, id, notOpenIfDefault, src) {
            src = !empty(src) ? src : $(e).attr('src');
            if(notOpenIfDefault){
                if(src === defaultImageUrl){
                    return;
                }
            }
            $('#dialog-edit-image .image-preview').attr('src', src);
            if(action = $(e).data('action-upload')){
                $('#dialog-img-file-input').data('action', action).data('id-img', id);
                $('#dialog-img-url-btn').data('action', action).data('id-img', id);
            }
            if(action = $(e).data('action-remove')){
                $('#dialog-img-remove-btn').data('action', action).data('id-img', id);
            }
            dialogEditImage.showModal();
        }

        /*function deleteImageDialog(e) {
            if (confirm("Remove image?")) {
                dialogEditImage.close();
                $('.dialog-edit-image .image-preview').attr('src', '').hide();

                $.ajax({
                    url: $(e).data('action'),
                    method: "POST"
                }).done(function (response) {
                    if(response){
                        $('#'+$(e).data('id-img')).attr('src', defaultImageUrl);
                    }
                    showSnackbarDefault(response);
                }).fail(function () {
                    showSnackbarDefault(false);
                });
            }
        }

        function uploadImageDialog(e) {

        }

        function uploadByUrlImageDialog(e) {
            var val = $('#dialog-img-url-input').val();
            if(!empty(val)){

            }
        }*/
    </script>
@endpush
