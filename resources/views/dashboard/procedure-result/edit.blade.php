@extends('dashboard.layouts.content')
@include('widget.bootstrap.datatable-form-push-resources')

@section('head_title') {{ $model->id ? 'Edit ID:'.$model->id : 'Add procedure result' }} @endsection
@section('title')
    <a href="{{ route('dashboard.procedure-result.index') }}" class="arrow-back"><i class="material-icons">arrow_back</i></a>
    {{ $model->id ? 'Edit ID:'.$model->id : 'Add procedure-result' }}
@endsection


@section('content')
    @include('widget.bootstrap.dialog-edit-image', ['isUpload' => false, 'isRemove' => false])
    <div class="base-form">
        @if($errors->count())
            <div class="modal fade" id="error_modal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-danger">Please make sure all fields are filled in correctly!</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-light m-0 p-0 text-center" style="font-weight: bold; font-size: 17px" role="alert">
                                @foreach($errors->all() as $i => $error)
                                    <span class="w-100 px-2 d-block pt-1 text-left text-dark">{{ $i + 1 }}. {{ $error }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            @push('js')
                <script>
                    $(function () {
                        $('#error_modal').modal('show');
                    });
                </script>
            @endpush
        @endif
        <form action="{{ route($model->id ? 'dashboard.procedure-result.update' : 'dashboard.procedure-result.store', ['model' => $model->id]) }}" autocomplete="off"  method="post" enctype="multipart/form-data" class="row">
            @csrf
            <input type="hidden" name="id" value="{{ !empty($model->id) ? $model->id : '' }}">

            <div class="col-12">&nbsp</div>

            @include('widget.bootstrap.form.wide-card-image', ['id' => 'image', 'cell' => [4, 4, 12],
                'showClearButton' => true,
            ])

            <div class="col-12 pb-5"></div>
            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'procedure_id', 'title' => 'Procedure', 'cell' => [6, 6, 12],
               'options' => \App\Models\Procedure::all()
            ])
            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'gender_id', 'title' => 'Gender', 'cell' => [6, 6, 12],
               'options' => \App\Models\Datasets\Gender::all()
            ])
            @include('widget.bootstrap.form.text-input', ['id' => 'date', 'title' => 'Date', 'cell' => [6, 6, 12], 'maxlength' => 150,'typeInput' => 'date', 'value' => explode(' ', $model->date)[0], 'inputAttr' => 'autocomplete = off required'])

            <div class="col-12"></div>

            @include('widget.bootstrap.form.text-input', ['id' => 'title_en', 'title' => 'English title', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.text-input', ['id' => 'title_pt', 'title' => 'Portugal title', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.text-input', ['id' => 'subtitle_en', 'title' => 'English subtitle', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.text-input', ['id' => 'subtitle_pt', 'title' => 'Portugal subtitle', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.text-input', ['id' => 'name_en', 'title' => 'English name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.text-input', ['id' => 'name_pt', 'title' => 'Portuguese name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'content_en', 'title' => 'English content', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'content_pt', 'title' => 'Portugal content', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])

            @include('widget.bootstrap.form.chosen-select-multiple', ['id' => 'tag_procedures', 'title' => 'Tag procedures', 'cell' => [4, 6, 12],
                   'options' => \App\Models\Procedure::all(),
            ])


            @include('widget.bootstrap.form.chosen-select-multiple', ['id' => 'tag_hair_types', 'title' => 'Tag hair type', 'cell' => [4, 6, 12],
                   'options' => \App\Models\HairType::all(),
            ])


            @include('widget.bootstrap.form.chosen-select-multiple', ['id' => 'tag_genders', 'title' => 'Tag gender', 'cell' => [4, 6, 12],
                   'options' => \App\Models\Gender::all(),
            ])
            <div class="col-12 pb-5"></div>

            <div class="col-12 pb-5"></div>

            @if(!empty($model->id))
                <h3 class="col-12 mt-4">Procedure result images</h3>
                <div class="col-12 text-right">
                    <button type="button" onclick="openCreateResultImageModal()" class="btn btn-self-primary">Add result images</button>
                </div>
                <div class="col-12 mt-3">
                    <table id="data-table" class="table table-striped table-bordered" style="width:100%"></table>
                </div>

                <h3 class="col-12 mt-4">Procedure result Video</h3>
                <div class="col-12 text-right">
                    <button type="button" onclick="openCreateResultVideoModal()" class="btn btn-self-primary">Add result video</button>
                </div>
                <div class="col-12 mt-3">
                    <table id="video-data-table" class="table table-striped table-bordered" style="width:100%"></table>
                </div>
            @endif


            <div class="col-12 row pt-4 mt-4 mx-0 px-0"></div>
            <div class="col-12 footer">
                @include('widget.bootstrap.form.action-buttons', [
                    'cancelUrl' => route('dashboard.procedure-result.index'),
                ])
                @include('widget.bootstrap.form.time-log')
            </div>
        </form>
            @if(!empty($model->id))
                <div class="modal-bg" id="modal_result_image">
                    <div class="modal">
                        <div class="modal-header">
                            <h3>Procedure result</h3>
                            <div class="close-modal" onclick="closeResultImageModal()">
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 pb-4"></div>
                                @include('widget.bootstrap.form.wide-card-media', ['id' => 'befor_image', 'title' => 'Before image', 'cell' => [4, 4, 12]])
                                @include('widget.bootstrap.form.wide-card-media', ['id' => 'after_image', 'title' => 'After image','cell' => [4, 4, 12]])
                                <div class="col-12 pb-4"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col-12 pb-4"></div>
                            <div class="col-12 text-right">
                                <button id="save_procedure_result_image_button" class="btn btn-self-primary px-5">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-bg" id="modal_result_video">
                    <div class="modal">
                        <div class="ds-circle-progress d-none" role="progressbar" style="--value:0" data-dz-uploadprogress></div>
                        <div class="modal-header">
                            <h3>Procedure result</h3>
                            <div class="close-modal" onclick="closeResultVideoModal()">
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 pb-4"></div>
                                @include('widget.bootstrap.form.wide-card-media', ['id' => 'video', 'mediaType' => 'video', 'title' => -1, 'cell' => [4, 4, 12]])
                                <div class="col-12 pt-4"></div>
                                @include('widget.bootstrap.form.text-input', ['id' => 'name', 'title' => 'Video name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
                                @include('widget.bootstrap.form.chosen-select-single', ['id' => 'video_language_key', 'title' => 'Language key', 'cell' => [6, 6, 12],
                                       'options' => \App\Models\Language::all(),
                                       'optionName' => 'key',
                                       'targetId' => 'key'
                                ])
                                <div class="col-12 pb-4"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col-12 pb-4"></div>
                            <div class="col-12 text-right">
                                <button id="save_procedure_result_video_button" class="btn btn-self-primary px-5">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @if($model->id)
            <form id="destroy-form" action="{{ route('dashboard.procedure-result.destroy', ['model' => $model->id]) }}" method="POST" style="display: none;"> @csrf </form>
        @endif
    </div>
@endsection

@push('css_1')
    <style>
        #modal_result_image .base-img-fit-contain {
            display: block !important;
        }
        #video_image {
            display: block !important;
        }
    </style>
@endpush


@if(!empty($model->id))
    @push('css_2')
        <link href="{{ asset('css/plugins/rowReorder.bootstrap.min.css') }}" rel="stylesheet">
        <style>
            .dt-rowReorder-moving {
                position: relative;
                opacity: 0.2;
            }
            .dt-rowReorder-moving:after {
                content: '';
                inset: 0;
                background-color: var(--dashboard-primary-color);
                position: absolute;
            }
        </style>
    @endpush

    @push('js')
        <script src="{{ asset('js/plugins/dataTables.rowReorder.min.js') }}"></script>
        <script type="text/javascript">
            let imageDataTable = null;
            let videoDataTable = null;
            $(function () {
                $.fn.DataTable.ext.errMode = 'throw';
                $.fn.DataTable.ext.pager.numbers_length = 7;

                imageDataTable = $('#data-table').DataTable({
                    ajax: {
                        url: '{{ route('dashboard.procedure-result-image.index-json', ['model' => $model->id]) }}',
                        type: 'post'
                    },
                    order: [
                        [0, "desc"],
                    ],
                    columns: [
                        {
                            data: 'id',
                            title: 'id',
                            //width: '1px',
                            searchable: false,
                            className: 'd-none',
                        },{
                            data: 'url_befor_image',
                            title: 'Before image',
                            searchable: false,
                            orderable: false,
                            className: 'text-truncate width-120 align-middle sort-row-target',
                            render: function (data, type, row) {
                                return '<button type="button" class="p-0 border-0" data-toggle="modal" data-target="#dialog-edit-image" data-image="image_'+row.id+'"> ' +
                                    '<img onerror="this.src=\''+defaultImageUrl+'\'" class="width-100 rounded'+(row.befor_image ? ' cursor-pointer' : '')+'" id="image_'+row.id+'" src="'+(row.url_befor_image ? row.url_befor_image + '?r='+Math.random() : defaultImageUrl)+'" onclick="showDialogEditImage(this, true)">' +
                                    '</button>';
                            }
                        },{
                            data: 'url_after_image',
                            title: 'After image',
                            searchable: false,
                            orderable: false,
                            className: 'text-truncate width-120 align-middle sort-row-target',
                            render: function (data, type, row) {
                                return '<button type="button" class="p-0 border-0" data-toggle="modal" data-target="#dialog-edit-image" data-image="image_'+row.id+'"> ' +
                                    '<img onerror="this.src=\''+defaultImageUrl+'\'" class="width-100 rounded'+(row.after_image ? ' cursor-pointer' : '')+'" id="image_'+row.id+'" src="'+(row.url_after_image ? row.url_after_image + '?r='+Math.random() : defaultImageUrl)+'" onclick="showDialogEditImage(this, true)">' +
                                    '</button>';
                            }
                        },{
                            data: null,
                            width: '1px',
                            searchable: false,
                            orderable: false,
                            className: 'text-truncate py-2 px-2 align-middle text-right',
                            render: function (data, type, row) {
                                return `<a class="btn btn-self-info py-1 px-0 text-center width-60" href="javascript:void(0);" onclick="openEditResultImageModal(\'{{ route('dashboard.procedure-result-image.index') }}/${row.id}/edit\')">Edit</button>
                                    <a class="btn btn-self-danger py-1 ml-2 px-0 text-center width-60" href="javascript:void(0);" onclick="removeDataTableRow(\'{{ route('dashboard.procedure-result-image.index') }}/${row.id}/destroy\')">Delete</a>`;
                            }
                        }
                    ],
                    initComplete: function () {
                        dataTablesFunc.globalSearchFilter.initComplete(imageDataTable);
                        defaultChosenInit();
                    },
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    dom: 'rt',
                    rowReorder: {
                        selector: '.sort-row-target'
                    },
                    pageLength: -1,
                });
                videoDataTable = $('#video-data-table').DataTable({
                    ajax: {
                        url: '{{ route('dashboard.procedure-result-video.index-json', ['model' => $model->id]) }}',
                        type: 'post'
                    },
                    order: [
                        [0, "desc"],
                    ],
                    columns: [
                        {
                            data: 'id',
                            title: 'id',
                            //width: '1px',
                            searchable: false,
                            className: 'd-none',
                        },{
                            data: 'name',
                            title: 'Name',
                            width: '50%',
                            className: 'text-truncate align-middle sort-row-target',
                        },{
                            data: 'language_key',
                            title: 'Language key',
                            width: '50%',
                            className: 'text-truncate align-middle sort-row-target',
                        },{
                            data: null,
                            width: '1px',
                            searchable: false,
                            orderable: false,
                            className: 'text-truncate py-2 px-2 align-middle text-right',
                            render: function (data, type, row) {
                                return `<a class="btn btn-self-info py-1 px-0 text-center width-60" href="javascript:void(0);" onclick="openEditResultVideoModal(\'{{ route('dashboard.procedure-result-video.index') }}/${row.id}/edit\')">Edit</button>
                                    <a class="btn btn-self-danger py-1 ml-2 px-0 text-center width-60" href="javascript:void(0);" onclick="removeDataTableRow(\'{{ route('dashboard.procedure-result-video.index') }}/${row.id}/destroy\')">Delete</a>`;
                            }
                        }
                    ],
                    initComplete: function () {
                        dataTablesFunc.globalSearchFilter.initComplete(videoDataTable);
                        defaultChosenInit();
                    },
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    dom: 'rt',
                    rowReorder: {
                        selector: '.sort-row-target'
                    },
                    pageLength: -1,
                });
                imageDataTable.on('row-reorder', function (e, diff, edit) {
                    let data = [];
                    for (let i = 0; i < diff.length; i++) {
                        data.push([imageDataTable.row(diff[i].node).data().id, diff[i].newPosition]);
                    }
                    if (data[0] !== undefined) {
                        $.ajax({
                            url: '{{ route('dashboard.procedure-result-image.sort-update') }}',
                            method: 'post',
                            data: {
                                data: data
                            }
                        }).done(function (response) {
                            imageDataTable.ajax.reload();
                        }).fail(function () {
                            imageDataTable.ajax.reload();
                        });
                    }
                });
                videoDataTable.on('row-reorder', function (e, diff, edit) {
                    let data = [];
                    for (let i = 0; i < diff.length; i++) {
                        data.push([videoDataTable.row(diff[i].node).data().id, diff[i].newPosition]);
                    }
                    if (data[0] !== undefined) {
                        $.ajax({
                            url: '{{ route('dashboard.procedure-result-video.sort-update') }}',
                            method: 'post',
                            data: {
                                data: data
                            }
                        }).done(function (response) {
                            videoDataTable.ajax.reload();
                        }).fail(function () {
                            videoDataTable.ajax.reload();
                        });
                    }
                });

            });
            function removeDataTableRow(url) {
                if(confirm('Do you want delete this?')){
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            ajax : true,
                        },
                        success: function (){
                            imageDataTable.ajax.reload();
                            videoDataTable.ajax.reload();
                        },
                        error: function (){
                            imageDataTable.ajax.reload();
                            videoDataTable.ajax.reload();
                        }
                    })
                }
            }

            function openCreateResultImageModal() {
                $('#befor_image_wide-card img').attr('src', '');
                $('#after_image_wide-card img').attr('src', '');
                $('#befor_image').val('');
                $('#after_image').val('');
                $('#urls_deleted_after_image').val('');
                $('#urls_deleted_befor_image').val('');
                $('#save_procedure_result_image_button').attr('onclick', 'createProcedureResultImage();');
                openResultImageModal();
            }
            function openEditResultImageModal(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        $('#befor_image').val('');
                        $('#after_image').val('');
                        $('#urls_deleted_befor_image').val('');
                        $('#urls_deleted_after_image').val('');
                        $('#befor_image_wide-card img').attr('src', data?.url_befor_image ?? '');
                        $('#after_image_wide-card img').attr('src', data?.url_after_image ?? '');
                        $('#save_procedure_result_image_button').attr('onclick', `updateProcedureResultImage(${data.id});`);
                        openResultImageModal();
                    },
                    error: function (data) {
                        modal.warning('Error', 'This procedure result does not exist or cannot be processed!');
                    },
                })
            }
            function openResultImageModal() {
                $('#modal_result_image').addClass('show');
                document.querySelector('body').style.overflow = 'hidden';
            }
            function closeResultImageModal() {
                $('#modal_result_image').removeClass('show');
                document.querySelector('body').style.overflow = '';
            }
            function createProcedureResultImage() {
                let after_image = $('#after_image') .prop('files')[0] ?? '';
                let befor_image = $('#befor_image') .prop('files')[0] ?? '';
                if (empty(after_image)) {
                    modal.warning('Error', 'The after image is required!');
                    return;
                }
                if (empty(befor_image)) {
                    modal.warning('Error', 'The before image is required!');
                    return;
                }
                let data = new FormData();
                data.append('after_image', after_image);
                data.append('befor_image', befor_image);
                data.append('procedure_result_id', {{ $model->id }});

                $.ajax({
                    url : `{{ route('dashboard.procedure-result-image.store') }}`,
                    method : 'POST',
                    data : data,
                    processData: false,
                    contentType: false,

                    success : function (data) {
                        imageDataTable.draw(false);
                        closeResultImageModal();
                    },
                    error : function (err) {
                        if (err.status == 422) {
                            modal.warning('Error', err.responseJSON.errors[Object.keys(err.responseJSON.errors)[0]][0]);

                        } else {
                            modal.warning('Error', 'An error occurred while processing your request!');
                        }
                    }
                });
            }
            function updateProcedureResultImage(id) {
                let befor_image = $('#befor_image').prop('files')[0] ?? '';
                let after_image = $('#after_image').prop('files')[0] ?? '';
                let urls_deleted_befor_image = $('#urls_deleted_befor_image').val();
                let urls_deleted_after_image = $('#urls_deleted_after_image').val();
                if (!empty(urls_deleted_befor_image) && empty(befor_image)) {
                    modal.warning('Error', 'The before image is required!');
                    return;
                }
                if (!empty(urls_deleted_after_image) && empty(after_image)) {
                    modal.warning('Error', 'The after image is required!');
                    return;
                }
                let data = new FormData();
                data.append('befor_image', befor_image);
                data.append('urls_deleted_befor_image', urls_deleted_befor_image);
                data.append('after_image', after_image);
                data.append('urls_deleted_after_image', urls_deleted_after_image);
                data.append('procedure_result_id', {{ $model->id }});

                $.ajax({
                    url : `{{ route('dashboard.procedure-result-image.index') }}/${id}/update`,
                    method : 'POST',
                    data : data,
                    processData: false,
                    contentType: false,

                    success : function (data) {
                        imageDataTable.draw(false);
                        closeResultImageModal();
                    },
                    error : function (err) {
                        if (err.status == 422) {
                            modal.warning('Error', err.responseJSON.errors[Object.keys(err.responseJSON.errors)[0]][0]);

                        } else {
                            modal.warning('Error', 'An error occurred while processing your request!');
                        }
                    }
                });
            }

            function openCreateResultVideoModal() {
                $('#name').val('');
                $('#video_language_key').val($('#video_language_key option:first').val()).trigger('chosen:updated');
                $('#video_wide-card video').attr('src', '');
                $('#video').val('');
                $('#urls_deleted_video').val('');
                $('#save_procedure_result_video_button').attr('onclick', 'createProcedureResultVideo();');
                openResultVideoModal();
            }
            function openEditResultVideoModal(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        $('#name').val(data?.name ?? '');
                        $('#video_language_key').val(data?.language_key ?? $('#video_language_key option:first').val()).trigger('chosen:updated');
                        $('#video').val('');
                        $('#urls_deleted_video').val('');
                        $('#video_wide-card video').attr('src', data?.url_video ?? '');
                        $('#save_procedure_result_video_button').attr('onclick', `updateProcedureResultVideo(${data.id});`);
                        openResultVideoModal();
                    },
                    error: function (data) {
                        modal.warning('Error', 'This procedure result does not exist or cannot be processed!');
                    },
                })
            }
            function openResultVideoModal() {
                $('#modal_result_video').addClass('show');
                document.querySelector('body').style.overflow = 'hidden';
            }
            function closeResultVideoModal() {
                $('#modal_result_video').removeClass('show');
                document.querySelector('body').style.overflow = '';
            }
            function createProcedureResultVideo() {
                let name = $('#name').val();
                let language_key = $('#video_language_key').val();
                let video = $('#video') .prop('files')[0] ?? '';
                if (empty(name)) {
                    modal.warning('Error', 'The name is required!');
                    return;
                }
                if (empty(language_key)) {
                    modal.warning('Error', 'The language key is required!');
                    return;
                }
                if (empty(video)) {
                    modal.warning('Error', 'The video is required!');
                    return;
                }
                let data = new FormData();
                data.append('language_key', language_key);
                data.append('video', video);
                data.append('name', name);
                data.append('procedure_result_id', {{ $model->id }});
                document.querySelectorAll('#modal_result_video div.ds-circle-progress[role="progressbar"]').forEach(el => {
                    el.classList.remove('d-none');
                });
                document.querySelector('#modal_result_video > .modal')?.classList.add('disabled-container');
                $.ajax({
                    url : `{{ route('dashboard.procedure-result-video.store') }}`,
                    method : 'POST',
                    data : data,
                    processData: false,
                    contentType: false,
                    xhr: function () {
                        let xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt){
                            if (evt.lengthComputable) {
                                let percentComplete = evt.loaded / evt.total;
                                document.querySelectorAll('#modal_result_video div.ds-circle-progress[role="progressbar"]').forEach(el => {
                                    el.style.setProperty('--value', parseInt(percentComplete * 100));
                                });
                            }
                        }, false);
                        return xhr;
                    },
                    success : function (data) {
                        videoDataTable.draw(false);
                        document.querySelector('#modal_result_video > .modal')?.classList.remove('disabled-container');
                        document.querySelectorAll('#modal_result_video div.ds-circle-progress[role="progressbar"]').forEach(el => {
                            el.classList.add('d-none');
                        });
                        closeResultVideoModal();
                    },
                    error : function (err) {
                        document.querySelector('#modal_result_video > .modal')?.classList.remove('disabled-container');
                        document.querySelectorAll('#modal_result_video div.ds-circle-progress[role="progressbar"]').forEach(el => {
                            el.classList.add('d-none');
                        });
                        if (err.status == 422) {
                            modal.warning('Error', err.responseJSON.errors[Object.keys(err.responseJSON.errors)[0]][0]);

                        } else {
                            modal.warning('Error', 'An error occurred while processing your request!');
                        }
                    }
                });
            }
            function updateProcedureResultVideo(id) {
                let name = $('#name').val();
                let language_key = $('#video_language_key').val();
                let video = $('#video') .prop('files')[0] ?? '';
                let urls_deleted_video = $('#urls_deleted_video').val();
                if (empty(name)) {
                    modal.warning('Error', 'The name is required!');
                    return;
                }
                if (empty(language_key)) {
                    modal.warning('Error', 'The language key is required!');
                    return;
                }
                if (!empty(urls_deleted_video) && empty(video)) {
                    modal.warning('Error', 'The video is required!');
                    return;
                }
                let data = new FormData();
                data.append('language_key', language_key);
                data.append('name', name);
                data.append('video', video);
                data.append('urls_deleted_video', urls_deleted_video);
                data.append('procedure_result_id', {{ $model->id }});

                document.querySelectorAll('#modal_result_video div.ds-circle-progress[role="progressbar"]').forEach(el => {
                    el.classList.remove('d-none');
                });
                document.querySelector('#modal_result_video > .modal')?.classList.add('disabled-container');
                $.ajax({
                    url : `{{ route('dashboard.procedure-result-video.index') }}/${id}/update`,
                    method : 'POST',
                    data : data,
                    processData: false,
                    contentType: false,
                    xhr: function () {
                        let xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt){
                            console.log(evt.lengthComputable);
                            if (evt.lengthComputable) {
                                let percentComplete = evt.loaded / evt.total;
                                document.querySelectorAll('#modal_result_video div.ds-circle-progress[role="progressbar"]').forEach(el => {
                                    el.style.setProperty('--value', parseInt(percentComplete * 100));
                                });
                            }
                        }, false);
                        return xhr;
                    },
                    success : function (data) {
                        videoDataTable.draw(false);
                        document.querySelector('#modal_result_video > .modal')?.classList.remove('disabled-container');
                        document.querySelectorAll('#modal_result_video div.ds-circle-progress[role="progressbar"]').forEach(el => {
                            el.classList.add('d-none');
                        });
                        closeResultVideoModal();
                    },
                    error : function (err) {
                        document.querySelector('#modal_result_video > .modal')?.classList.remove('disabled-container');
                        document.querySelectorAll('#modal_result_video div.ds-circle-progress[role="progressbar"]').forEach(el => {
                            el.classList.add('d-none');
                        });
                        if (err.status == 422) {
                            modal.warning('Error', err.responseJSON.errors[Object.keys(err.responseJSON.errors)[0]][0]);

                        } else {
                            modal.warning('Error', 'An error occurred while processing your request!');
                        }
                    }
                });
            }

        </script>
        <script src="{{ asset('js/base/datatable.js') }}"></script>
    @endpush

    @push('css_1')
        <style>
            .modal-bg {
                position: fixed;
                width: 100%;
                height: auto;
                overflow: auto;
                min-height: 100%;
                inset: 0;
                z-index: 20;
                padding-top: 50px;
                padding-bottom: 50px;
                background: rgba(0, 0, 0, 0.3);
                opacity: 0;
                visibility: hidden;
                transition: 0.3s ease;
                display: flex;
                align-items: self-start;
                justify-content: center;
            }

            .modal-bg.show {
                opacity: 1;
                visibility: visible;
            }

            .modal-bg .modal {
                background: #fff;
                position: relative;
                display: block;
                min-height: 400px;
                min-width: 500px;
                max-width: 95%;
                width: 95%;
                height: auto;
                border-radius: 0.25em;
                padding: 24px;
            }

            .modal-bg .modal .close-modal {
                width: 24px;
                height: 24px;
                position: relative;
                cursor: pointer;
            }

            .modal-bg .modal .close-modal:hover {
                opacity: 0.75;
                transition: 0.2s ease;
            }

            .modal-bg .modal .close-modal span {
                width: 24px;
                height: 2px;
                background: #333;
                border-radius: 20%;
                position: absolute;
                top: 50%;
            }

            .modal-bg .modal .close-modal span:first-of-type {
                transform: rotate(45deg);
            }

            .modal-bg .modal .close-modal span:last-of-type {
                transform: rotate(-45deg);
            }

            .modal div.ds-circle-progress {
                position: absolute!important;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 999999;
            }


        </style>
    @endpush
@endif