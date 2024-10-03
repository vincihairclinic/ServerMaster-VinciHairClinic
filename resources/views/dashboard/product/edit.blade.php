@extends('dashboard.layouts.content')
@include('widget.bootstrap.datatable-form-push-resources')

@section('head_title') {{ $model->id ? 'Edit ID:'.$model->id : 'Add product' }} @endsection
@section('title')
    <a href="{{ route('dashboard.product.index') }}" class="arrow-back"><i class="material-icons">arrow_back</i></a>
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
        <form action="{{ route($model->id ? 'dashboard.product.update' : 'dashboard.product.store', ['model' => $model->id]) }}" autocomplete="off"  method="post" enctype="multipart/form-data" class="row">
            @csrf
            <input type="hidden" name="id" value="{{ !empty($model->id) ? $model->id : '' }}">

            <div class="col-12">&nbsp</div>
{{--{{ dd($model->faqs_en) }}--}}
            <h3 class="col-12 pb-4">Images</h3>

            @include('widget.bootstrap.form.wide-card-images', ['arrayName' => 'images', 'cell' => [4, 4, 12],
                'showClearButton' => true,
                'title' => -1,
                'array' => $model->url_images
            ])

            <div class="col-12 pt-5"></div>


            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'product_category_id', 'title' => 'Product category', 'cell' => [6, 6, 12],
                'options' => \App\Models\ProductCategory::all()
            ])

            <div class="col-12 pb-5"></div>

            <h3 class="col-12 pb-4">Countries shop urls</h3>

            @foreach($model->shop_now_urls as $v => $item)
                <input type="hidden" name="shop_now_urls[{{ $v }}][country_id]" value="{{ $item->country_id }}">
                @include('widget.bootstrap.form.text-input', ['id' => 'shop_now_urls['.$v.'][shop_now_url]', 'title' => $country->where('id', $item->country_id)->first()->name, 'value' => $item->shop_now_url, 'cell' => [4, 6, 12]])
            @endforeach

            <div class="col-12 pt-5"></div>

            @include('widget.bootstrap.form.text-input', ['id' => 'video_name_en', 'title' => 'English video name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off'])
            @include('widget.bootstrap.form.text-input', ['id' => 'video_name_pt', 'title' => 'Portugal video name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off'])
            @include('widget.bootstrap.form.wide-card-media', ['id' => 'video_en', 'title' => 'English video', 'showDeleteButton' => 1, 'removeContainer' => 1, 'mediaType' => 'video', 'cell' => [4, 4, 12]])
            <div class="col-2"></div>
            @include('widget.bootstrap.form.wide-card-media', ['id' => 'video_pt', 'title' => 'Portugal video', 'showDeleteButton' => 1, 'removeContainer' => 1, 'mediaType' => 'video', 'cell' => [4, 4, 12]])


            <div class="col-12 pt-5"></div>


            @include('widget.bootstrap.form.text-input', ['id' => 'name_en', 'title' => 'English name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.text-input', ['id' => 'name_pt', 'title' => 'Portuguese name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])

            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'about_en', 'title' => 'English about', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'about_pt', 'title' => 'Portugal about', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])

            <h3 class="col-12 pb-4">English FAQs</h3>
            @include('widget.bootstrap.form.array.two-textarea-container', ['id' => 'faqs_en', 'firstProperty' => 'question', 'secondProperty' => 'answer', 'buttonLabel' => 'Add english FAQ', 'errorLabel' => 'FAQs', 'firstTitle' => 'Question', 'secondTitle' => 'Answer', 'selectId' => 'question_id', 'inputId' => 'answer'])

            <h3 class="col-12 pb-4">Portugal FAQs</h3>
            @include('widget.bootstrap.form.array.two-textarea-container', ['id' => 'faqs_pt', 'firstProperty' => 'question', 'secondProperty' => 'answer', 'buttonLabel' => 'Add portugal FAQ', 'errorLabel' => 'FAQs', 'firstTitle' => 'Question', 'secondTitle' => 'Answer', 'selectId' => 'question_id', 'inputId' => 'answer'])

            <div class="col-12 row pt-4 mt-4 mx-0 px-0"></div>

            @if(!empty($model->id))
                <h3 class="col-12 mt-4">Product review</h3>

                <div class="col-12 py-4 text-right">
                    <button type="button" onclick="openCreateReviewModal()" class="btn btn-self-primary">Add product review</button>
                </div>
                <div class="col-12 mt-3">
                    <table id="data-table" class="table table-striped table-bordered" style="width:100%"></table>
                </div>
            @endif

            <div class="col-12 footer">
                @include('widget.bootstrap.form.action-buttons', [
                    'cancelUrl' => route('dashboard.product.index'),
                ])
                @include('widget.bootstrap.form.time-log')
            </div>
        </form>

        @if(!empty($model->id))
            <div class="modal-bg" id="modal_review">
                <div class="ds-circle-progress d-none" role="progressbar" style="--value:0" data-dz-uploadprogress></div>
                <div class="modal">
                    <div class="modal-header">
                        <h3 class="" id="modal_title_product_review">Product review</h3>
                        <div class="close-modal" onclick="closeReviewModal()">
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-12 pb-4"></div>
                            @include('widget.bootstrap.form.wide-card-media', ['id' => 'video', 'mediaType' => 'video', 'cell' => [4, 4, 12]])
                            <div class="col-12 pt-4"></div>
                            @include('widget.bootstrap.form.text-input', ['id' => 'name', 'title' => 'Review title', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
                            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'language_key', 'title' => 'Language key', 'cell' => [6, 6, 12],
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
                            <button id="save_product_review_button" class="btn btn-self-primary px-5">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($model->id)
            <form id="destroy-form" action="{{ route('dashboard.product.destroy', ['model' => $model->id]) }}" method="POST" style="display: none;"> @csrf </form>
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
                        url: '{{ route('dashboard.product-review.index-json', ['model' => $model->id]) }}',
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
                            title: 'name',
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
                                return `<a class="btn btn-self-info py-1 px-0 text-center width-60" href="javascript:void(0);" onclick="openEditReviewModal(\'{{ route('dashboard.product-review.index') }}/${row.id}/edit\')">Edit</button>
                                    <a class="btn btn-self-danger py-1 ml-2 px-0 text-center width-60" href="javascript:void(0);" onclick="removeDataTableRow(\'{{ route('dashboard.product-review.index') }}/${row.id}/destroy\')">Delete</a>`;
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
                            url: '{{ route('dashboard.product-review.sort-update') }}',
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
                $('#name').val('');
                $('#language_key').val($('#language_key option:first').val()).trigger('chosen:updated');
                $('#video_wide-card video').attr('src', '');
                $('#video').val('');
                $('#urls_deleted_video').val('');
                $('#save_product_review_button').attr('onclick', 'createProductReview();');
                openReviewModal();
            }

            function openEditReviewModal(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        $('#name').val(data?.name ?? '');
                        $('#language_key').val(data?.language_key ?? $('#language_key option:first').val()).trigger('chosen:updated');
                        $('#video').val('');
                        $('#urls_deleted_video').val('');
                        $('#video_wide-card video').attr('src', data?.url_video ?? '');
                        $('#save_product_review_button').attr('onclick', `updateProductReview(${data.id});`);
                        openReviewModal();
                    },
                    error: function (data) {
                        modal.warning('Error', 'This review does not exist or cannot be processed!');
                    },
                })
            }

            function openReviewModal() {
                $('#modal_review').addClass('show');
                document.querySelector('body').style.overflow = 'hidden';
            }

            function closeReviewModal() {
                $('#modal_review').removeClass('show');
                document.querySelector('body').style.overflow = '';
            }

            function createProductReview() {
                let name = $('#name').val();
                let language_key = $('#language_key').val();
                let video = $('#video') .prop('files')[0] ?? '';
                if (empty(name) || name.length < 2 || name.length > 150) {
                    modal.warning('Error', 'The name is required, and must contain from 2 to 150 characters!');
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
                data.append('name', name);
                data.append('language_key', language_key);
                data.append('video', video);
                data.append('product_id', {{ $model->id }});

                document.querySelectorAll('#modal_review div.ds-circle-progress[role="progressbar"]').forEach(el => {
                    el.classList.remove('d-none');
                });
                document.querySelector('#modal_review > .modal')?.classList.add('disabled-container');
                $.ajax({
                    url : `{{ route('dashboard.product-review.store') }}`,
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
                                document.querySelectorAll('#modal_review div.ds-circle-progress[role="progressbar"]').forEach(el => {
                                    el.style.setProperty('--value', parseInt(percentComplete * 100));
                                });
                            }
                        }, false);
                        return xhr;
                    },
                    success : function (data) {
                        dataTable.draw(false);
                        document.querySelector('#modal_review > .modal')?.classList.remove('disabled-container');
                        document.querySelectorAll('#modal_review div.ds-circle-progress[role="progressbar"]').forEach(el => {
                            el.classList.add('d-none');
                        });
                        closeReviewModal();
                    },
                    error : function (err) {
                        document.querySelector('#modal_review > .modal')?.classList.remove('disabled-container');
                        document.querySelectorAll('#modal_review div.ds-circle-progress[role="progressbar"]').forEach(el => {
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

            function updateProductReview(id) {
                let name = $('#name').val();
                let language_key = $('#language_key').val();
                let video = $('#video') .prop('files')[0] ?? '';
                let urls_deleted_video = $('#urls_deleted_video').val();
                if (empty(name) || name.length < 2 || name.length > 150) {
                    modal.warning('Error', 'The name is required, and must contain from 2 to 150 characters!');
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
                data.append('name', name);
                data.append('language_key', language_key);
                data.append('video', video);
                data.append('urls_deleted_video', urls_deleted_video);
                data.append('product_id', {{ $model->id }});

                document.querySelectorAll('#modal_review div.ds-circle-progress[role="progressbar"]').forEach(el => {
                    el.classList.remove('d-none');
                });
                document.querySelector('#modal_review > .modal')?.classList.add('disabled-container');
                $.ajax({
                    url : `{{ route('dashboard.product-review.index') }}/${id}/update`,
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
                                document.querySelectorAll('#modal_review div.ds-circle-progress[role="progressbar"]').forEach(el => {
                                    el.style.setProperty('--value', parseInt(percentComplete * 100));
                                });
                            }
                        }, false);
                        return xhr;
                    },
                    success : function (data) {
                        dataTable.draw(false);
                        document.querySelector('#modal_review > .modal')?.classList.remove('disabled-container');
                        document.querySelectorAll('#modal_review div.ds-circle-progress[role="progressbar"]').forEach(el => {
                            el.classList.add('d-none');
                        });
                        closeReviewModal();
                    },
                    error : function (err) {
                        document.querySelector('#modal_review > .modal')?.classList.remove('disabled-container');
                        document.querySelectorAll('#modal_review div.ds-circle-progress[role="progressbar"]').forEach(el => {
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