@php
    if (isset($type)) {
       $type = is_array($type)? $type : [$type];
    }
    $cell = !is_array($cell) ? [$cell, $cell, $cell] : $cell;
@endphp
<div class="form-group {{ !empty($cell) ? 'col-xl-'.$cell[0].' col-md-'.$cell[1].' col-'.$cell[2] : ''}} {{ !empty($cssClass) ? $cssClass : '' }}">
    <div class="ds-dropzone dropzone {{ !empty($cssDzContainer) ? $cssDzContainer : '' }}" id="{{ $id }}">
        <div class="dz-message custom" data-dz-message>
            @include('svg.video-play-pink')
            <div class="message-container">
                <div class="main">
                    Drag and drop a video, or <span>Browse</span>
                </div>
                <div class="description">
                    The video has to be in .mp4, .wav, .mov or .mkv format
                </div>
                <div class="small-main">
                    {{ $smallMsg ?? '' }}
                </div>
            </div>
        </div>
    </div>
</div>

@once
    @push('css_1')
        <link href="{{ asset('/css/plugins/dropzone.min.css') }}" rel="stylesheet">
    @endpush
    @push('js')
        <script src="{{ asset('js/plugins/dropzone.min.js') }}"></script>
        <script>
            Dropzone.autoDiscover = false;
        </script>
    @endpush
@endonce
@push('js')
    <script>
        $(function () {
            let previewNode = document.querySelector('#{{ $id }}-template');
            previewNode.removeAttribute('id');
            let previewTemplate = previewNode.parentElement.innerHTML;
            previewNode.parentElement.remove();

            let dropzone = new Dropzone("#{{ $id }}", {
                autoProcessQueue: false,
                maxFiles: {{ $maxFiles ?? 1 }},
                maxFilesize: 50000,
                thumbnailWidth: 80,
                thumbnailHeight: 80,
                parallelUploads: 20,
                previewTemplate: previewTemplate,
                previewsContainer: '#{{ $id }}',
                url: '/',
                // addedfile: function (file) {

                    // setTimeout(function () {
                    //     console.log(this.files);
                    // }, 2000);
                    // console.log(Dropzone.createElement(this.options.previewTemplate));
                    // file.previewElement = Dropzone.createElement(this.options.previewTemplate);
                // },
                // thumbnail: function(file, dataUrl) {
                // Display the image in your file.previewElement
                // },
                uploadprogress: function(file, progress, bytesSent) {
                    if (file.previewElement) {
                        let progressElement = file.previewElement.querySelector("[data-dz-uploadprogress]");
                        progressElement.style.setProperty('--value', parseInt(progress));
                    }
                },
                maxfilesexceeded: function (file) {
                    this.removeAllFiles();
                    this.addFile(file);
                },
                error: function (file, message) {
                    this.removeFile(file);
                },
                accept: function (file, done) {
                    let type = file.type.split('/');
                    @if(!empty($type))
                        if (!{!! json_encode($type) !!}.includes(type[0])) {
                            modal.warning('Error', 'Invalid file type');
                            this.removeFile(file);
                            return;
                        }
                    @endif
                    @if(!empty($extension))
                        if (!{!! json_encode($extension) !!}.includes(type[1])) {
                            modal.warning('Error', 'Invalid file type');
                            this.removeFile(file);
                            return;
                        }
                    @endif
                    done();
                }
            });
            window['{{ !empty($var) ? $var : 'dropzone' }}'] = dropzone;
        });
    </script>
@endpush