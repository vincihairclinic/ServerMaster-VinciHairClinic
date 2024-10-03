@extends('dashboard.layouts.content')
@include('widget.bootstrap.datatable-form-push-resources')

@section('head_title') {{ $model->id ? 'Edit ID:'.$model->id : 'Add podcast' }} @endsection
@section('title')
    <a href="{{ route('dashboard.podcast.index') }}" class="arrow-back"><i class="material-icons">arrow_back</i></a>
    {{ $model->id ? 'Edit ID:'.$model->id : 'Add product' }}
@endsection

@php
    $country = \App\Models\Country::all();
@endphp

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
        <form action="{{ route($model->id ? 'dashboard.podcast.update' : 'dashboard.podcast.store', ['model' => $model->id]) }}" autocomplete="off"  method="post" enctype="multipart/form-data" class="row">
            @csrf
            <input type="hidden" name="id" value="{{ !empty($model->id) ? $model->id : '' }}">

            <div class="col-12">&nbsp</div>
{{--{{ dd($model->faqs_en) }}--}}
            <h3 class="col-12 pb-4">Images</h3>

            @include('widget.bootstrap.form.wide-card-image', ['id' => 'image', 'cell' => [4, 4, 12],
                'showClearButton' => true,
                'title' => -1
            ])


            <div class="col-12 pt-5"></div>


            @include('widget.bootstrap.form.text-input', ['id' => 'name_en', 'title' => 'English name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.text-input', ['id' => 'name_pt', 'title' => 'Portuguese name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])

            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'content_en', 'title' => 'English content', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'content_pt', 'title' => 'Portugal content', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])

            <div class="col-12 row pt-4 mt-4 mx-0 px-0"></div>

            @if(!empty($model->id))
                <h3 class="col-12 mt-4">Podcast episode</h3>

                <div class="col-12 py-4 text-right">
                    <button type="button" onclick="openCreateReviewModal()" class="btn btn-self-primary">Add podcast episode</button>
                </div>
                <div class="col-12 mt-3">
                    <table id="data-table" class="table table-striped table-bordered" style="width:100%"></table>
                </div>
            @endif

            <div class="col-12 footer">
                @include('widget.bootstrap.form.action-buttons', [
                    'cancelUrl' => route('dashboard.podcast.index'),
                ])
                @include('widget.bootstrap.form.time-log')
            </div>
        </form>

        @if(!empty($model->id))
            <div class="modal-bg" id="podcast_episode">
                <div class="ds-circle-progress d-none" role="progressbar" style="--value:0" data-dz-uploadprogress></div>
                <div class="modal">
                    <div class="modal-header">
                        <h3 class="" id="modal_title_product_review">Podcast episode</h3>
                        <div class="close-modal" onclick="closeEpisodeModal()">
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-12 pb-4"></div>
                            @include('widget.bootstrap.form.wide-card-image', ['id' => 'episode_image', 'cell' => [4, 4, 12]])
                            @include('widget.bootstrap.form.wide-card-media', ['id' => 'episode_audio', 'mediaType' => 'audio', 'cell' => [4, 4, 12]])
                            <div class="col-12 pt-4"></div>
                            @include('widget.bootstrap.form.text-input', ['id' => 'episode_name_en', 'title' => 'English name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
                            @include('widget.bootstrap.form.text-input', ['id' => 'episode_name_pt', 'title' => 'Portuguese name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
                            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'episode_content_en', 'title' => 'English content', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])
                            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'episode_content_pt', 'title' => 'Portuguese content', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])

                            <div class="col-12 pb-4"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-12 pb-4"></div>
                        <div class="col-12 text-right">
                            <button id="save_product_review_button" class="btn btn-self-primary px-5">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($model->id)
            <form id="destroy-form" action="{{ route('dashboard.podcast.destroy', ['model' => $model->id]) }}" method="POST" style="display: none;"> @csrf </form>
        @endif
    </div>
@endsection

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

            div.ds-circle-progress {
                position: absolute!important;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 999999;
            }
        </style>
    @endpush

    @push('js')
        <script src="{{ asset('js/plugins/dataTables.rowReorder.min.js') }}"></script>
        <script type="text/javascript">
            $(function () {
                $.fn.dataTable.ext.errMode = 'throw';
                $.fn.DataTable.ext.pager.numbers_length = 7;

                dataTable = $('#data-table').DataTable({
                    ajax: {
                        url: '{{ route('dashboard.podcast-episode.index-json', ['model' => $model->id]) }}',
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
                            data: 'name_en',
                            title: 'English name',
                            width: '50%',
                            className: 'text-truncate align-middle sort-row-target',
                        },{
                            data: 'name_pt',
                            title: 'Portuguese name',
                            width: '50%',
                            className: 'text-truncate align-middle sort-row-target',
                        },{
                            data: null,
                            width: '1px',
                            searchable: false,
                            orderable: false,
                            className: 'text-truncate py-2 px-2 align-middle text-right',
                            render: function (data, type, row) {
                                return `<a class="btn btn-self-info py-1 px-0 text-center width-60" href="javascript:void(0);" onclick="openEditEpisodeModal(\'{{ route('dashboard.podcast-episode.index') }}/${row.id}/edit\')">Edit</button>
                                    <a class="btn btn-self-danger py-1 ml-2 px-0 text-center width-60" href="javascript:void(0);" onclick="removeDataTableRow(\'{{ route('dashboard.podcast-episode.index') }}/${row.id}/destroy\')">Delete</a>`;
                            }
                        }
                    ],
                    initComplete: function () {
                        dataTablesFunc.globalSearchFilter.initComplete(dataTable);
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
                dataTable.on('row-reorder', function (e, diff, edit) {
                    let data = [];
                    for (let i = 0; i < diff.length; i++) {
                        data.push([dataTable.row(diff[i].node).data().id, diff[i].newPosition]);
                    }
                    if (data[0] !== undefined) {
                        $.ajax({
                            url: '{{ route('dashboard.podcast-episode.sort-update') }}',
                            method: 'post',
                            data: {
                                data: data
                            }
                        }).done(function (response) {
                            dataTable.ajax.reload();
                        }).fail(function () {
                            dataTable.ajax.reload();
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
                            dataTable.ajax.reload();
                        },
                        error: function (){
                            dataTable.ajax.reload();
                        }
                    })
                }
            }

            function openCreateReviewModal() {
                $('#episode_name_en').val('');
                $('#episode_name_pt').val('');
                $('#episode_content_en').val('');
                $('#episode_content_pt').val('');
                $('#episode_audio').val('');
                $('#urls_deleted_episode_audio').val('');
                $('#episode_image').val('');
                $('#urls_deleted_episode_image').val('');
                $('#episode_audio_wide-card audio').attr('src', '');
                $('#episode_image_wide-card .base-img-fit-contain').attr('src', '');
                $('#save_product_review_button').attr('onclick', 'createPodcastEpisode();');
                openEpisodeModal();
            }

            function openEditEpisodeModal(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        $('#episode_name_en').val(data?.name_en ?? '');
                        $('#episode_name_pt').val(data?.name_pt ?? '');
                        $('#episode_content_en').jqteVal(data?.content_en ?? '');
                        $('#episode_content_pt').jqteVal(data?.content_pt ?? '');
                        $('#episode_audio').val('');
                        $('#urls_deleted_episode_audio').val('');
                        $('#episode_image').val('');
                        $('#urls_deleted_episode_image').val('');
                        $('#episode_audio_wide-card audio').attr('src', data?.url_file ?? '');
                        $('#episode_image_wide-card .base-img-fit-contain').attr('src', data?.url_image ?? '');
                        $('#save_product_review_button').attr('onclick', `updatePodcastEpisode(${data.id});`);
                        openEpisodeModal();
                    },
                    error: function (data) {
                        modal.warning('Error', 'This review does not exist or cannot be processed!');
                    },
                })
            }

            function openEpisodeModal() {
                $('#podcast_episode').addClass('show');
                document.querySelector('body').style.overflow = 'hidden';
            }

            function closeEpisodeModal() {
                $('#podcast_episode').removeClass('show');
                document.querySelector('body').style.overflow = '';
            }

            function createPodcastEpisode() {
                let name_en = $('#episode_name_en').val();
                let name_pt = $('#episode_name_pt').val();
                let content_en = $('#episode_content_en').val();
                let content_pt = $('#episode_content_pt').val();
                let audio = $('#episode_audio') .prop('files')[0] ?? '';
                let image = $('#episode_image') .prop('files')[0] ?? '';
                if (empty(name_en) || name_en.length < 2 || name_en.length > 150) {
                    modal.warning('Error', 'The name is required, and must contain from 2 to 150 characters!');
                    return;
                }
                if (empty(name_pt) || name_pt.length < 2 || name_pt.length > 150) {
                    modal.warning('Error', 'The name is required, and must contain from 2 to 150 characters!');
                    return;
                }
                if (empty(content_en) || content_en.length < 2 || content_en.length > 150) {
                    modal.warning('Error', 'The name is required, and must contain from 2 to 150 characters!');
                    return;
                }
                if (empty(content_pt) || content_pt.length < 2 || content_pt.length > 150) {
                    modal.warning('Error', 'The name is required, and must contain from 2 to 150 characters!');
                    return;
                }
                if (empty(audio)) {
                    modal.warning('Error', 'The video is required!');
                    return;
                }
                if (empty(image)) {
                    modal.warning('Error', 'The video is required!');
                    return;
                }

                let duration_min = Math.ceil($('#episode_audio_wide-card audio').get(0).duration / 60);

                let data = new FormData();
                data.append('name_en', name_en);
                data.append('name_pt', name_pt);
                data.append('content_en', content_en);
                data.append('content_pt', content_pt);
                data.append('image', image);
                data.append('file', audio);
                data.append('duration_min', duration_min);
                data.append('podcast_id', {{ $model->id }});

                document.querySelectorAll('#podcast_episode div.ds-circle-progress[role="progressbar"]').forEach(el => {
                    el.classList.remove('d-none');
                });
                document.querySelector('#podcast_episode > .modal')?.classList.add('disabled-container');
                $.ajax({
                    url : `{{ route('dashboard.podcast-episode.store') }}`,
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
                                document.querySelectorAll('#podcast_episode div.ds-circle-progress[role="progressbar"]').forEach(el => {
                                    el.style.setProperty('--value', parseInt(percentComplete * 100));
                                });
                            }
                        }, false);
                        return xhr;
                    },
                    success : function (data) {
                        dataTable.draw(false);
                        document.querySelector('#podcast_episode > .modal')?.classList.remove('disabled-container');
                        document.querySelectorAll('#podcast_episode div.ds-circle-progress[role="progressbar"]').forEach(el => {
                            el.classList.add('d-none');
                        });
                        closeEpisodeModal();
                    },
                    error : function (err) {
                        document.querySelector('#podcast_episode > .modal')?.classList.remove('disabled-container');
                        document.querySelectorAll('#podcast_episode div.ds-circle-progress[role="progressbar"]').forEach(el => {
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

            function updatePodcastEpisode(id) {
                let name_en = $('#episode_name_en').val();
                let name_pt = $('#episode_name_pt').val();
                let content_en = $('#episode_content_en').val();
                let content_pt = $('#episode_content_pt').val();
                let audio = $('#episode_audio') .prop('files')[0] ?? '';
                let urls_deleted_audio = $('#urls_deleted_episode_audio').val();
                let image = $('#episode_image') .prop('files')[0] ?? '';
                let urls_deleted_image = $('#urls_deleted_episode_image').val();

                if (empty(name_en) || name_en.length < 2 || name_en.length > 150) {
                    modal.warning('Error', 'The name is required, and must contain from 2 to 150 characters!');
                    return;
                }
                if (empty(name_pt) || name_pt.length < 2 || name_pt.length > 150) {
                    modal.warning('Error', 'The name is required, and must contain from 2 to 150 characters!');
                    return;
                }
                if (empty(content_en) || content_en.length < 2 || content_en.length > 150) {
                    modal.warning('Error', 'The name is required, and must contain from 2 to 150 characters!');
                    return;
                }
                if (empty(content_pt) || content_pt.length < 2 || content_pt.length > 150) {
                    modal.warning('Error', 'The name is required, and must contain from 2 to 150 characters!');
                    return;
                }
                if (!empty(urls_deleted_audio) && empty(audio)) {
                    modal.warning('Error', 'The audio is required!');
                    return;
                }
                if (!empty(urls_deleted_image) && empty(image)) {
                    modal.warning('Error', 'The image is required!');
                    return;
                }

                let duration_min = Math.ceil($('#episode_audio_wide-card audio').get(0).duration / 60);

                let data = new FormData();
                data.append('name_en', name_en);
                data.append('name_pt', name_pt);
                data.append('content_en', content_en);
                data.append('content_pt', content_pt);
                data.append('image', image);
                data.append('file', audio);
                data.append('duration_min', duration_min);
                data.append('urls_deleted_image', urls_deleted_image);
                data.append('urls_deleted_file', urls_deleted_audio);
                data.append('podcast_id', {{ $model->id }});

                document.querySelectorAll('#podcast_episode div.ds-circle-progress[role="progressbar"]').forEach(el => {
                    el.classList.remove('d-none');
                });
                document.querySelector('#podcast_episode > .modal')?.classList.add('disabled-container');
                $.ajax({
                    url : `{{ route('dashboard.podcast-episode.index') }}/${id}/update`,
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
                                document.querySelectorAll('#podcast_episode div.ds-circle-progress[role="progressbar"]').forEach(el => {
                                    el.style.setProperty('--value', parseInt(percentComplete * 100));
                                });
                            }
                        }, false);
                        return xhr;
                    },
                    success : function (data) {
                        dataTable.draw(false);
                        document.querySelector('#podcast_episode > .modal')?.classList.remove('disabled-container');
                        document.querySelectorAll('#podcast_episode div.ds-circle-progress[role="progressbar"]').forEach(el => {
                            el.classList.add('d-none');
                        });
                        closeEpisodeModal();
                    },
                    error : function (err) {
                        document.querySelector('#podcast_episode > .modal')?.classList.remove('disabled-container');
                        document.querySelectorAll('#podcast_episode div.ds-circle-progress[role="progressbar"]').forEach(el => {
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

        </style>
    @endpush
@endif