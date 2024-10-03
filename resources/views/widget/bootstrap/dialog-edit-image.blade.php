<div class="modal fade" id="dialog-edit-image" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
            {{--<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>--}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img class="image-preview mw-100 mh-100" onerror="this.src='{{ asset('images/base/upload-image-default.png') }}'"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $('#dialog-edit-image').on('show.bs.modal', function (event) {
            let image_id = $(event.relatedTarget).data('image');
            let image = document.getElementById(image_id).src;
            let modal = $(this);
            let imageContainers = $(modal).find('.image-preview');
            Array.from(imageContainers).forEach(el => {
                el.src = image;
            })
        })
    </script>
@endpush
