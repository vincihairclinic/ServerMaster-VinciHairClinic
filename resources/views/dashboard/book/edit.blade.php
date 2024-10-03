@extends('dashboard.layouts.content')
@include('widget.bootstrap.datatable-form-push-resources')

@section('head_title') {{ $model->id ? 'Edit ID:'.$model->id : 'Add book' }} @endsection
@section('title')
    <a href="{{ route('dashboard.book.index') }}" class="arrow-back"><i class="material-icons">arrow_back</i></a>
    {{ $model->id ? 'Edit ID:'.$model->id : 'Add book' }}
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
        <form action="{{ route($model->id ? 'dashboard.book.update' : 'dashboard.book.store', ['model' => $model->id]) }}" autocomplete="off"  method="post" enctype="multipart/form-data" class="row">
            @csrf
            <input type="hidden" name="id" value="{{ !empty($model->id) ? $model->id : '' }}">

            <div class="col-12">&nbsp</div>
{{--{{ dd($model->faqs_en) }}--}}

            @include('widget.bootstrap.form.wide-card-image', ['id' => 'image', 'cell' => [4, 4, 12],
                'showClearButton' => true,
            ])


            <div class="col-12 pt-5"></div>



            @include('widget.bootstrap.form.wide-card-media', ['id' => 'video_en', 'title' => 'English video', 'mediaType' => 'video', 'cell' => [4, 4, 12]])

            @include('widget.bootstrap.form.wide-card-media', ['id' => 'video_pt', 'title' => 'Portugal video', 'mediaType' => 'video', 'cell' => [4, 4, 12]])


            <div class="col-12 pt-5"></div>


            @include('widget.bootstrap.form.text-input', ['id' => 'name_en', 'title' => 'English name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.text-input', ['id' => 'name_pt', 'title' => 'Portuguese name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])

            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'about_en', 'title' => 'English about', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'about_pt', 'title' => 'Portugal about', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])

            <h3 class="col-12 pb-4">English FAQs</h3>
            @include('widget.bootstrap.form.array.two-textarea-container', ['id' => 'faqs_en', 'firstProperty' => 'question', 'secondProperty' => 'answer', 'buttonLabel' => 'Add english FAQ', 'errorLabel' => 'FAQs', 'firstTitle' => 'Question', 'secondTitle' => 'Answer', 'selectId' => 'question_id', 'inputId' => 'answer'])

            <h3 class="col-12 pb-4">Portugal FAQs</h3>
            @include('widget.bootstrap.form.array.two-textarea-container', ['id' => 'faqs_pt', 'firstProperty' => 'question', 'secondProperty' => 'answer', 'buttonLabel' => 'Add portugal FAQ', 'errorLabel' => 'FAQs', 'firstTitle' => 'Question', 'secondTitle' => 'Answer', 'selectId' => 'question_id', 'inputId' => 'answer'])


            <div class="col-12 pt-5"></div>

            @include('widget.bootstrap.form.wide-card-image', ['id' => 'pre_image', 'cell' => [4, 4, 12],
                'showClearButton' => true,
            ])

            <div class="col-12"></div>
            @include('widget.bootstrap.form.text-input', ['id' => 'pre_name_en', 'title' => 'English pre name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.text-input', ['id' => 'pre_name_pt', 'title' => 'Portugal pre name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])

            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'pre_content_en', 'title' => 'English pre about', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'pre_content_pt', 'title' => 'Portugal pre about', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])
            @if(!empty($model->id))
                <h3 class="col-12 mt-4 mb-0">Book pre instruction</h3>
                <div class="col-12 py-0 text-right">
                    <button type="button" onclick="openCreatePreInstructionModal()" class="btn btn-self-primary">Add book pre instruction</button>
                </div>
                <div class="col-12 mt-2">
                    <table id="pre_instruction_data-table" class="table table-striped table-bordered" style="width:100%"></table>
                </div>
                <h3 class="col-12 mt-4 mb-0">Book pre additional</h3>
                <div class="col-12 py-0 text-right">
                    <button type="button" onclick="openCreatePreAdditionalModal()" class="btn btn-self-primary">Add book pre additional</button>
                </div>
                <div class="col-12 mt-2">
                    <table id="pre_additional_data-table" class="table table-striped table-bordered" style="width:100%"></table>
                </div>
            @endif

            <div class="col-12 pt-5"></div>

            @include('widget.bootstrap.form.wide-card-image', ['id' => 'post_image', 'cell' => [4, 4, 12],
                'showClearButton' => true,
            ])

            <div class="col-12"></div>
            @include('widget.bootstrap.form.text-input', ['id' => 'post_name_en', 'title' => 'English post name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.text-input', ['id' => 'post_name_pt', 'title' => 'Portugal post name', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])

            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'post_content_en', 'title' => 'English post about', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])
            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'post_content_pt', 'title' => 'Portugal post about', 'cell' => [6, 6, 12], 'inputAttr' => 'autocomplete = off required'])
            @if(!empty($model->id))
                <h3 class="col-12 mt-4 mb-0">Book post instruction</h3>
                <div class="col-12 py-0 text-right">
                    <button type="button" onclick="openCreatePostInstructionModal()" class="btn btn-self-primary">Add book post instruction</button>
                </div>
                <div class="col-12 mt-2">
                    <table id="post_instruction_data-table" class="table table-striped table-bordered" style="width:100%"></table>
                </div>
                <h3 class="col-12 mt-4 mb-0">Book post additional</h3>
                <div class="col-12 py-0 text-right">
                    <button type="button" onclick="openCreatePostAdditionalModal()" class="btn btn-self-primary">Add book post additional</button>
                </div>
                <div class="col-12 mt-2">
                    <table id="post_additional_data-table" class="table table-striped table-bordered" style="width:100%"></table>
                </div>
            @endif
            <div class="col-12 row pt-4 mt-4 mx-0 px-0"></div>

            @if(!empty($model->id))
                <h3 class="col-12 mt-4 mb-0">Book video</h3>
                <div class="col-12 py-0 text-right">
                    <button type="button" onclick="openCreateReviewModal()" class="btn btn-self-primary">Add book video</button>
                </div>
                <div class="col-12 mt-2 mb-4">
                    <table id="review_data-table" class="table table-striped table-bordered" style="width:100%"></table>
                </div>

                <h3 class="col-12 mt-4 mb-0">Book information</h3>
                <div class="col-12 py-0 text-right">
                    <button type="button" onclick="openCreateInformationModal()" class="btn btn-self-primary">Add book information</button>
                </div>
                <div class="col-12 mt-2">
                    <table id="information_data-table" class="table table-striped table-bordered" style="width:100%"></table>
                </div>
            @endif

            <div class="col-12 footer">
                @include('widget.bootstrap.form.action-buttons', [
                    'cancelUrl' => route('dashboard.book.index'),
                ])
                @include('widget.bootstrap.form.time-log')
            </div>
        </form>

        @if(!empty($model->id))
            <div class="modal-bg" id="modal_review">
                <div class="modal">
                    <div class="modal-header">
                        <h3 class="" id="modal_title_product_review">Book video</h3>
                        <div class="close-modal" onclick="closeReviewModal()">
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-12 pb-4"></div>
                            @include('widget.bootstrap.form.wide-card-media', ['id' => 'review_video', 'mediaType' => 'video', 'cell' => [4, 4, 12]])
                            <div class="col-12 pt-4"></div>
                            @include('widget.bootstrap.form.text-input', ['id' => 'review_name', 'title' => 'Review title', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
                            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'review_language_key', 'title' => 'Language key', 'cell' => [6, 6, 12],
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
            <div class="modal-bg" id="modal_information">
                <div class="modal">
                    <div class="modal-header">
                        <h3 class="" id="modal_title_product_review">Book information</h3>
                        <div class="close-modal" onclick="closeInformationModal()">
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-12 pb-4"></div>
                            @include('widget.bootstrap.form.wide-card-image', ['id' => 'information_image', 'cell' => [4, 4, 12],
                                'showClearButton' => true,
                            ])
                            <div class="col-12 pt-4"></div>
                            @include('widget.bootstrap.form.text-input', ['id' => 'information_name', 'title' => 'Information title', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
                            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'information_language_key', 'title' => 'Language key', 'cell' => [6, 6, 12],
                                   'options' => \App\Models\Language::all(),
                                   'optionName' => 'key',
                                   'targetId' => 'key'
                            ])
                            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'information_content', 'title' => 'Information content', 'cell' => 12, 'inputAttr' => 'autocomplete = off required'])
                            <div class="col-12 pb-4"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-12 pb-4"></div>
                        <div class="col-12 text-right">
                            <button id="save_product_information_button" class="btn btn-self-primary px-5">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-bg" id="modal_pre_instruction">
                <div class="modal">
                    <div class="modal-header">
                        <h3 class="" id="modal_title_product_review">Book pre instruction</h3>
                        <div class="close-modal" onclick="closePreInstructionModal()">
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-12 pb-4"></div>
                            @include('widget.bootstrap.form.text-input', ['id' => 'pre_instruction_title', 'title' => 'Pre instruction title', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
                            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'pre_instruction_language_key', 'title' => 'Language key', 'cell' => [6, 6, 12],
                                   'options' => \App\Models\Language::all(),
                                   'optionName' => 'key',
                                   'targetId' => 'key'
                            ])
                            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'pre_instruction_content', 'title' => 'Pre instruction content', 'cell' => 12, 'inputAttr' => 'autocomplete = off required'])
                            <div class="col-12 pb-4"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-12 pb-4"></div>
                        <div class="col-12 text-right">
                            <button id="save_pre_instruction_button" class="btn btn-self-primary px-5">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-bg" id="modal_post_instruction">
                <div class="modal">
                    <div class="modal-header">
                        <h3 class="" id="modal_title_product_review">Book post instruction</h3>
                        <div class="close-modal" onclick="closePostInstructionModal()">
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-12 pb-4"></div>
                            @include('widget.bootstrap.form.text-input', ['id' => 'post_instruction_title', 'title' => 'Information title', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
                            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'post_instruction_language_key', 'title' => 'Language key', 'cell' => [6, 6, 12],
                                   'options' => \App\Models\Language::all(),
                                   'optionName' => 'key',
                                   'targetId' => 'key'
                            ])
                            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'post_instruction_content', 'title' => 'Information content', 'cell' => 12, 'inputAttr' => 'autocomplete = off required'])
                            <div class="col-12 pb-4"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-12 pb-4"></div>
                        <div class="col-12 text-right">
                            <button id="save_post_instruction_button" class="btn btn-self-primary px-5">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-bg" id="modal_pre_additional">
                <div class="modal">
                    <div class="modal-header">
                        <h3 id="modal_title_product_review">Book pre additional</h3>
                        <div class="close-modal" onclick="closePreAdditionalModal()">
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-12 pb-4"></div>
                            <h3>Images</h3>
                            @include('widget.bootstrap.form.medias', ['id' => 'pre_additional_images', 'title' => -1,'cell' => [4, 4, 12]])
                        <div class="col-12 pb-4"></div>
                            @include('widget.bootstrap.form.text-input', ['id' => 'pre_additional_title', 'title' => 'Pre additional title', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
                            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'pre_additional_language_key', 'title' => 'Language key', 'cell' => [6, 6, 12],
                                   'options' => \App\Models\Language::all(),
                                   'optionName' => 'key',
                                   'targetId' => 'key'
                            ])
                            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'pre_additional_content', 'title' => 'Pre additional content', 'cell' => 12, 'inputAttr' => 'autocomplete = off required'])
                            <div class="col-12 pb-4"></div>
                            <div class="additional-item-table-container col-12 p-0" id="pre_additional_item_table_container">
                                <h3 class="col-12 mt-4 mb-0">Additional items</h3>
                                <div class="col-12 py-0 text-right">
                                    <button type="button" onclick="addPreAdditionalItemRow()" class="btn btn-self-primary">Add additional item</button>
                                </div>
                                <div class="col-12 mt-2">
                                    <table id="pre_additional_item_data-table" class="table table-striped table-bordered" style="width:100%"></table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-12 pb-4"></div>
                        <div class="col-12 text-right">
                            <button id="save_pre_additional_button" class="btn btn-self-primary px-5">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-bg" id="modal_post_additional">
                <div class="modal">
                    <div class="modal-header">
                        <h3 class="" id="modal_title_product_review">Book post additional</h3>
                        <div class="close-modal" onclick="closePostAdditionalModal()">
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-12 pb-4"></div>
                            <h3>Images</h3>
                            @include('widget.bootstrap.form.medias', ['id' => 'post_additional_images', 'title' => -1, 'cell' => [4, 4, 12]])
                        <div class="col-12 pb-4"></div>
                            @include('widget.bootstrap.form.text-input', ['id' => 'post_additional_title', 'title' => 'Post additional title', 'cell' => [6, 6, 12], 'maxlength' => 150, 'inputAttr' => 'autocomplete = off required'])
                            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'post_additional_language_key', 'title' => 'Language key', 'cell' => [6, 6, 12],
                                   'options' => \App\Models\Language::all(),
                                   'optionName' => 'key',
                                   'targetId' => 'key'
                            ])
                            @include('widget.bootstrap.form.textarea-jqte', ['id' => 'post_additional_content', 'title' => 'Post additional content', 'cell' => 12, 'inputAttr' => 'autocomplete = off required'])
                            <div class="col-12 pb-4"></div>
                            <div class="additional-item-table-container col-12 p-0" id="post_additional_item_table_container">
                                <h3 class="col-12 mt-4 mb-0">Additional items</h3>
                                <div class="col-12 py-0 text-right">
                                    <button type="button" onclick="addPostAdditionalItemRow()" class="btn btn-self-primary">Add additional item</button>
                                </div>
                                <div class="col-12 mt-2">
                                    <table id="post_additional_item_data-table" class="table table-striped table-bordered" style="width:100%"></table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-12 pb-4"></div>
                        <div class="col-12 text-right">
                            <button id="save_post_additional_button" class="btn btn-self-primary px-5">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($model->id)
            <form id="destroy-form" action="{{ route('dashboard.book.destroy', ['model' => $model->id]) }}" method="POST" style="display: none;"> @csrf </form>
        @endif
    </div>
@endsection

@if(!empty($model->id))
    @push('js')
        <script src="{{ asset('js/plugins/dataTables.rowReorder.min.js') }}"></script>
        <script type="text/javascript">
            let reviewDataTable = null;
            let informationDataTable = null;
            let preInstructionDataTable = null;
            let postInstructionDataTable = null;
            let preAdditionalDataTable = null;
            let postAdditionalDataTable = null;
            let preAdditionalId = 0;
            let postAdditionalId = 0;
            let preAdditionalItemDataTable = null;
            let postAdditionalItemDataTable = null;
            $(function () {
                $.fn.dataTable.ext.errMode = 'throw';
                $.fn.DataTable.ext.pager.numbers_length = 7;

                reviewDataTable = $('#review_data-table').DataTable({
                    ajax: {
                        url: '{{ route('dashboard.book-review.index-json', ['book' => $model->id]) }}',
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
                                return `<a class="btn btn-self-info py-1 px-0 text-center width-60" href="javascript:void(0);" onclick="openEditReviewModal(\'{{ route('dashboard.book-review.index') }}/${row.id}/edit\')">Edit</button>
                                    <a class="btn btn-self-danger py-1 ml-2 px-0 text-center width-60" href="javascript:void(0);" onclick="removeDataTableRow(\'{{ route('dashboard.book-review.index') }}/${row.id}/destroy\')">Delete</a>`;
                            }
                        }
                    ],
                    initComplete: function () {
                        dataTablesFunc.globalSearchFilter.initComplete(reviewDataTable);
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

                reviewDataTable.on('row-reorder', function (e, diff, edit) {
                    let data = [];
                    for (let i = 0; i < diff.length; i++) {
                        data.push([reviewDataTable.row(diff[i].node).data().id, diff[i].newPosition]);
                    }
                    if (data[0] !== undefined) {
                        $.ajax({
                            url: '{{ route('dashboard.book-review.sort-update') }}',
                            method: 'post',
                            data: {
                                data: data
                            }
                        }).done(function (response) {
                            reviewDataTable.ajax.reload();
                        }).fail(function () {
                            reviewDataTable.ajax.reload();
                        });
                    }
                });

                informationDataTable = $('#information_data-table').DataTable({
                    ajax: {
                        url: '{{ route('dashboard.book-information.index-json', ['book' => $model->id]) }}',
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
                            data: 'url_image',
                            title: '',
                            searchable: false,
                            orderable: false,
                            className: 'text-truncate center width-70 py-2 align-middle sort-row-target',
                            render: function (data, type, row) {
                                return '<button type="button" class="p-0 border-0" data-toggle="modal" data-target="#dialog-edit-image" data-image="image_'+row.id+'"> ' +
                                    '<img onerror="this.src=\''+defaultImageUrl+'\'" class="width-50 rounded'+(row.image ? ' cursor-pointer' : '')+'" id="image_'+row.id+'" src="'+(row.url_image ? row.url_image + '?r='+Math.random() : defaultImageUrl)+'" onclick="showDialogEditImage(this, true)">' +
                                    '</button>';
                            }
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
                                return `<a class="btn btn-self-info py-1 px-0 text-center width-60" href="javascript:void(0);" onclick="openEditInformationModal(\'{{ route('dashboard.book-information.index') }}/${row.id}/edit\')">Edit</button>
                                    <a class="btn btn-self-danger py-1 ml-2 px-0 text-center width-60" href="javascript:void(0);" onclick="removeDataTableRow(\'{{ route('dashboard.book-information.index') }}/${row.id}/destroy\')">Delete</a>`;
                            }
                        }
                    ],
                    initComplete: function () {
                        dataTablesFunc.globalSearchFilter.initComplete(informationDataTable);
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

                informationDataTable.on('row-reorder', function (e, diff, edit) {
                    let data = [];
                    for (let i = 0; i < diff.length; i++) {
                        data.push([informationDataTable.row(diff[i].node).data().id, diff[i].newPosition]);
                    }
                    if (data[0] !== undefined) {
                        $.ajax({
                            url: '{{ route('dashboard.book-information.sort-update') }}',
                            method: 'post',
                            data: {
                                data: data
                            }
                        }).done(function (response) {
                            informationDataTable.ajax.reload();
                        }).fail(function () {
                            informationDataTable.ajax.reload();
                        });
                    }
                });

                preInstructionDataTable = $('#pre_instruction_data-table').DataTable({
                    ajax: {
                        url: '{{ route('dashboard.book-pre-instruction.index-json', ['book' => $model->id]) }}',
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
                            data: 'title',
                            title: 'Title',
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
                                return `<a class="btn btn-self-info py-1 px-0 text-center width-60" href="javascript:void(0);" onclick="openEditPreInstructionModal(\'{{ route('dashboard.book-pre-instruction.index') }}/${row.id}/edit\')">Edit</button>
                                    <a class="btn btn-self-danger py-1 ml-2 px-0 text-center width-60" href="javascript:void(0);" onclick="removeDataTableRow(\'{{ route('dashboard.book-pre-instruction.index') }}/${row.id}/destroy\')">Delete</a>`;
                            }
                        }
                    ],
                    initComplete: function () {
                        dataTablesFunc.globalSearchFilter.initComplete(preInstructionDataTable);
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

                preInstructionDataTable.on('row-reorder', function (e, diff, edit) {
                    let data = [];
                    for (let i = 0; i < diff.length; i++) {
                        data.push([preInstructionDataTable.row(diff[i].node).data().id, diff[i].newPosition]);
                    }
                    if (data[0] !== undefined) {
                        $.ajax({
                            url: '{{ route('dashboard.book-pre-instruction.sort-update') }}',
                            method: 'post',
                            data: {
                                data: data
                            }
                        }).done(function (response) {
                            preInstructionDataTable.ajax.reload();
                        }).fail(function () {
                            preInstructionDataTable.ajax.reload();
                        });
                    }
                });

                postInstructionDataTable = $('#post_instruction_data-table').DataTable({
                    ajax: {
                        url: '{{ route('dashboard.book-post-instruction.index-json', ['book' => $model->id]) }}',
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
                            data: 'title',
                            title: 'Title',
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
                                return `<a class="btn btn-self-info py-1 px-0 text-center width-60" href="javascript:void(0);" onclick="openEditPostInstructionModal(\'{{ route('dashboard.book-post-instruction.index') }}/${row.id}/edit\')">Edit</button>
                                    <a class="btn btn-self-danger py-1 ml-2 px-0 text-center width-60" href="javascript:void(0);" onclick="removeDataTableRow(\'{{ route('dashboard.book-post-instruction.index') }}/${row.id}/destroy\')">Delete</a>`;
                            }
                        }
                    ],
                    initComplete: function () {
                        dataTablesFunc.globalSearchFilter.initComplete(postInstructionDataTable);
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

                postInstructionDataTable.on('row-reorder', function (e, diff, edit) {
                    let data = [];
                    for (let i = 0; i < diff.length; i++) {
                        data.push([postInstructionDataTable.row(diff[i].node).data().id, diff[i].newPosition]);
                    }
                    if (data[0] !== undefined) {
                        $.ajax({
                            url: '{{ route('dashboard.book-post-instruction.sort-update') }}',
                            method: 'post',
                            data: {
                                data: data
                            }
                        }).done(function (response) {
                            postInstructionDataTable.ajax.reload();
                        }).fail(function () {
                            postInstructionDataTable.ajax.reload();
                        });
                    }
                });

                preAdditionalDataTable = $('#pre_additional_data-table').DataTable({
                    ajax: {
                        url: '{{ route('dashboard.book-pre-additional.index-json', ['book' => $model->id]) }}',
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
                            data: 'title',
                            title: 'Title',
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
                                return `<a class="btn btn-self-info py-1 px-0 text-center width-60" href="javascript:void(0);" onclick="openEditPreAdditionalModal(\'{{ route('dashboard.book-pre-additional.index') }}/${row.id}/edit\')">Edit</button>
                                    <a class="btn btn-self-danger py-1 ml-2 px-0 text-center width-60" href="javascript:void(0);" onclick="removeDataTableRow(\'{{ route('dashboard.book-pre-additional.index') }}/${row.id}/destroy\')">Delete</a>`;
                            }
                        }
                    ],
                    initComplete: function () {
                        dataTablesFunc.globalSearchFilter.initComplete(preAdditionalDataTable);
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

                preAdditionalDataTable.on('row-reorder', function (e, diff, edit) {
                    let data = [];
                    for (let i = 0; i < diff.length; i++) {
                        data.push([preAdditionalDataTable.row(diff[i].node).data().id, diff[i].newPosition]);
                    }
                    if (data[0] !== undefined) {
                        $.ajax({
                            url: '{{ route('dashboard.book-pre-additional.sort-update') }}',
                            method: 'post',
                            data: {
                                data: data
                            }
                        }).done(function (response) {
                            preAdditionalDataTable.ajax.reload();
                        }).fail(function () {
                            preAdditionalDataTable.ajax.reload();
                        });
                    }
                });

                postAdditionalDataTable = $('#post_additional_data-table').DataTable({
                    ajax: {
                        url: '{{ route('dashboard.book-post-additional.index-json', ['book' => $model->id]) }}',
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
                            data: 'title',
                            title: 'Title',
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
                                return `<a class="btn btn-self-info py-1 px-0 text-center width-60" href="javascript:void(0);" onclick="openEditPostAdditionalModal('{{ route('dashboard.book-post-additional.index') }}/${row.id}/edit')">Edit</button>
                                    <a class="btn btn-self-danger py-1 ml-2 px-0 text-center width-60" href="javascript:void(0);" onclick="removeDataTableRow('{{ route('dashboard.book-post-additional.index') }}/${row.id}/destroy')">Delete</a>`;
                            }
                        }
                    ],
                    initComplete: function () {
                        dataTablesFunc.globalSearchFilter.initComplete(postAdditionalDataTable);
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

                postAdditionalDataTable.on('row-reorder', function (e, diff, edit) {
                    let data = [];
                    for (let i = 0; i < diff.length; i++) {
                        data.push([postAdditionalDataTable.row(diff[i].node).data().id, diff[i].newPosition]);
                    }
                    if (data[0] !== undefined) {
                        $.ajax({
                            url: '{{ route('dashboard.book-post-additional.sort-update') }}',
                            method: 'post',
                            data: {
                                data: data
                            }
                        }).done(function (response) {
                            postAdditionalDataTable.ajax.reload();
                        }).fail(function () {
                            postAdditionalDataTable.ajax.reload();
                        });
                    }
                });

                preAdditionalItemDataTable = $('#pre_additional_item_data-table').DataTable({
                    ajax: {
                        url: '{{ route('dashboard.book-pre-additional-item.index-json', ['additional' => \App\Models\BookPreAdditionalItem::first()->id]) }}',
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
                            data: 'id',
                            title: 'Sort',
                            width: '5%',
                            className: 'align-middle sort-row-target',
                            render: function (data, type, row) {
                                return '*';
                            },
                        },{
                            data: 'title',
                            title: 'Content',
                            width: '50%',
                            className: 'align-middle',
                            render: function (data, type, row) {
                                return `<textarea class="additional-title" contenteditable data-additional_id="${row.book_pre_additional_id}" data-id="${row.id}" onchange="updatePreAdditionalItem(this)">${data}</textarea>`
                            },
                        },{
                            data: 'content',
                            title: 'Content',
                            width: '50%',
                            className: 'align-middle',
                            render: function (data, type, row) {
                                return `<textarea class="additional-content" contenteditable data-additional_id="${row.book_pre_additional_id}" data-id="${row.id}" onchange="updatePreAdditionalItem(this)">${data}</textarea>`
                            },
                        },{
                            data: null,
                            width: '1px',
                            searchable: false,
                            orderable: false,
                            className: 'text-truncate py-2 px-2 align-middle text-right',
                            render: function (data, type, row) {
                                return `<a class="btn btn-self-danger py-1 ml-2 px-0 text-center width-60" href="javascript:void(0);" onclick="removeDataTableRow(\'{{ route('dashboard.book-pre-additional-item.index') }}/${row.id}/destroy\')">Delete</a>`;
                            }
                        }
                    ],
                    initComplete: function () {
                        dataTablesFunc.globalSearchFilter.initComplete(preAdditionalItemDataTable);
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

                preAdditionalItemDataTable.on('draw', function () {
                    autosize($('#pre_additional_item_data-table textarea'));
                });

                preAdditionalItemDataTable.on('row-reorder', function (e, diff, edit) {
                    let data = [];
                    for (let i = 0; i < diff.length; i++) {
                        data.push([preAdditionalItemDataTable.row(diff[i].node).data().id, diff[i].newPosition]);
                    }
                    if (data[0] !== undefined) {
                        $.ajax({
                            url: '{{ route('dashboard.book-pre-additional-item.sort-update') }}',
                            method: 'post',
                            data: {
                                data: data
                            }
                        }).done(function (response) {
                            preAdditionalItemDataTable.ajax.reload();
                        }).fail(function () {
                            preAdditionalItemDataTable.ajax.reload();
                        });
                    }
                });

                postAdditionalItemDataTable = $('#post_additional_item_data-table').DataTable({
                    ajax: {
                        url: '{{ route('dashboard.book-post-additional-item.index-json', ['additional' => \App\Models\BookPostAdditionalItem::first()->id]) }}',
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
                            data: 'id',
                            title: 'Sort',
                            width: '5%',
                            className: 'align-middle sort-row-target',
                            render: function (data, type, row) {
                                return '*';
                            },
                        },{
                            data: 'title',
                            title: 'Title',
                            width: '50%',
                            className: 'text-truncate align-middle',
                            render: function (data, type, row) {
                                return `<textarea class="additional-title" data-additional_id="${row.book_post_additional_id}" data-id="${row.id}" onchange="updatePostAdditionalItem(this)">${data}</textarea>`
                            },
                        },{
                            data: 'content',
                            title: 'Content',
                            width: '50%',
                            className: 'text-truncate align-middle',
                            render: function (data, type, row) {
                                return `<textarea class="additional-content" data-additional_id="${row.book_post_additional_id}" data-id="${row.id}" onchange="updatePostAdditionalItem(this)">${data}</textarea>`
                            },
                        },{
                            data: null,
                            width: '1px',
                            searchable: false,
                            orderable: false,
                            className: 'text-truncate py-2 px-2 align-middle text-right',
                            render: function (data, type, row) {
                                return `<a class="btn btn-self-danger py-1 ml-2 px-0 text-center width-60" href="javascript:void(0);" onclick="removeDataTableRow(\'{{ route('dashboard.book-post-additional-item.index') }}/${row.id}/destroy\')">Delete</a>`;
                            }
                        }
                    ],
                    initComplete: function () {
                        dataTablesFunc.globalSearchFilter.initComplete(postAdditionalItemDataTable);
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

                postAdditionalItemDataTable.on('draw', function () {
                    autosize($('#post_additional_item_data-table textarea'));
                });

                postAdditionalItemDataTable.on('row-reorder', function (e, diff, edit) {
                    let data = [];
                    for (let i = 0; i < diff.length; i++) {
                        data.push([postAdditionalItemDataTable.row(diff[i].node).data().id, diff[i].newPosition]);
                    }
                    if (data[0] !== undefined) {
                        $.ajax({
                            url: '{{ route('dashboard.book-post-additional-item.sort-update') }}',
                            method: 'post',
                            data: {
                                data: data
                            }
                        }).done(function (response) {
                            postAdditionalItemDataTable.ajax.reload();
                        }).fail(function () {
                            postAdditionalItemDataTable.ajax.reload();
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
                            reviewDataTable.ajax.reload();
                            informationDataTable.ajax.reload();
                            preInstructionDataTable.ajax.reload();
                            postInstructionDataTable.ajax.reload();
                            preAdditionalDataTable.ajax.reload();
                            postAdditionalDataTable.ajax.reload();
                            preAdditionalItemDataTable.ajax.reload();
                            postAdditionalItemDataTable.ajax.reload();
                        },
                        error: function (){
                            reviewDataTable.ajax.reload();
                            informationDataTable.ajax.reload();
                            preInstructionDataTable.ajax.reload();
                            postInstructionDataTable.ajax.reload();
                            preAdditionalDataTable.ajax.reload();
                            postAdditionalDataTable.ajax.reload();
                            preAdditionalItemDataTable.ajax.reload();
                            postAdditionalItemDataTable.ajax.reload();
                        }
                    })
                }
            }

            function openCreateReviewModal() {
                $('#review_name').val('');
                $('#review_language_key').val($('#review_language_key option:first').val()).trigger('chosen:updated');
                $('#review_video_wide-card video').attr('src', '');
                $('#review_video').val('');
                $('#urls_deleted_review_video').val('');
                $('#save_product_review_button').attr('onclick', 'createProductReview();');
                openReviewModal();
            }

            function openEditReviewModal(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        $('#review_name').val(data?.name ?? '');
                        $('#review_language_key').val(data?.language_key ?? $('#review_language_key option:first').val()).trigger('chosen:updated');
                        $('#review_video').val('');
                        $('#urls_deleted_review_video').val('');
                        $('#review_video_wide-card video').attr('src', data?.url_video ?? '');
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
                let name = $('#review_name').val();
                let language_key = $('#review_language_key').val();
                let video = $('#review_video').prop('files')[0] ?? '';
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
                data.append('book_id', {{ $model->id }});

                $.ajax({
                    url : `{{ route('dashboard.book-review.store') }}`,
                    method : 'POST',
                    data : data,
                    processData: false,
                    contentType: false,

                    success : function (data) {
                        reviewDataTable.draw(false);
                        closeReviewModal();
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

            function updateProductReview(id) {
                let name = $('#review_name').val();
                let language_key = $('#review_language_key').val();
                let video = $('#review_video').prop('files')[0] ?? '';
                let urls_deleted_video = $('#urls_deleted_review_video').val();
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
                data.append('book_id', {{ $model->id }});

                $.ajax({
                    url : `{{ route('dashboard.book-review.index') }}/${id}/update`,
                    method : 'POST',
                    data : data,
                    processData: false,
                    contentType: false,

                    success : function (data) {
                        reviewDataTable.draw(false);
                        closeReviewModal();
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

            function openCreateInformationModal() {
                $('#information_name').val('');
                $('#information_content').jqteVal('');
                $('#information_language_key').val($('#information_language_key option:first').val()).trigger('chosen:updated');
                $('#information_image_img img.base-img-fit-contain').attr('src', '');
                $('#information_image').val('');
                $('#images_deleted_information_image').val('');
                $('#save_product_information_button').attr('onclick', 'createProductInformation();');
                openInformationModal();
            }

            function openEditInformationModal(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        $('#information_name').val(data?.name ?? '');
                        $('#information_content').jqteVal(data?.content ?? '');
                        $('#information_language_key').val(data?.language_key ?? $('#information_language_key option:first').val()).trigger('chosen:updated');
                        $('#information_image').val('');
                        $('#images_deleted_information_image').val('');
                        $('#information_image_wide-card img.base-img-fit-contain').attr('src', data?.url_image ?? '');
                        $('#save_product_information_button').attr('onclick', `updateProductInformation(${data.id});`);
                        openInformationModal();
                    },
                    error: function (data) {
                        modal.warning('Error', 'This review does not exist or cannot be processed!');
                    },
                })
            }

            function openInformationModal() {
                $('#modal_information').addClass('show');
                document.querySelector('body').style.overflow = 'hidden';
            }

            function closeInformationModal() {
                $('#modal_information').removeClass('show');
                document.querySelector('body').style.overflow = '';
            }

            function createProductInformation() {
                let name = $('#information_name').val();
                let content = $('#information_content').val();
                let language_key = $('#information_language_key').val();
                let image = $('#information_image').prop('files')[0] ?? '';
                if (empty(name) || name.length < 2 || name.length > 150) {
                    modal.warning('Error', 'The name is required, and must contain from 2 to 150 characters!');
                    return;
                }
                if (empty(content)) {
                    modal.warning('Error', 'The content is required, and must contain 2 chatacters!');
                    return;
                }
                if (empty(language_key)) {
                    modal.warning('Error', 'The language key is required!');
                    return;
                }
                if (empty(image)) {
                    modal.warning('Error', 'The image is required!');
                    return;
                }
                let data = new FormData();
                data.append('name', name);
                data.append('content', content);
                data.append('language_key', language_key);
                data.append('image', image);
                data.append('book_id', {{ $model->id }});

                $.ajax({
                    url : `{{ route('dashboard.book-information.store') }}`,
                    method : 'POST',
                    data : data,
                    processData: false,
                    contentType: false,
                    success : function (data) {
                        informationDataTable.draw(false);
                        closeInformationModal();
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

            function updateProductInformation(id) {
                let name = $('#information_name').val();
                let content = $('#information_content').val();
                let language_key = $('#information_language_key').val();
                let image = $('#information_image').prop('files')[0] ?? '';
                let images_deleted_image = $('#images_deleted_information_image').val();
                if (empty(name) || name.length < 2 || name.length > 150) {
                    modal.warning('Error', 'The name is required, and must contain from 2 to 150 characters!');
                    return;
                }
                if (empty(content)) {
                    modal.warning('Error', 'The content is required, and must contain 2 chatacters!');
                    return;
                }
                if (empty(language_key)) {
                    modal.warning('Error', 'The language key is required!');
                    return;
                }
                if (!empty(images_deleted_image) && empty(image)) {
                    modal.warning('Error', 'The image is required!');
                    return;
                }
                let data = new FormData();
                data.append('name', name);
                data.append('content', content);
                data.append('language_key', language_key);
                data.append('image', image);
                data.append('images_deleted_image', images_deleted_image);
                data.append('book_id', {{ $model->id }});

                $.ajax({
                    url : `{{ route('dashboard.book-information.index') }}/${id}/update`,
                    method : 'POST',
                    data : data,
                    processData: false,
                    contentType: false,

                    success : function (data) {
                        informationDataTable.draw(false);
                        closeInformationModal();
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


            function openCreatePreInstructionModal() {
                $('#pre_instruction_title').val('');
                $('#pre_instruction_content').jqteVal('');
                $('#pre_instruction_language_key').val($('#pre_instruction_language_key option:first').val()).trigger('chosen:updated');
                $('#save_pre_instruction_button').attr('onclick', 'createBookPreInstruction();');
                openPreInstructionModal();
            }

            function openEditPreInstructionModal(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        $('#pre_instruction_title').val(data?.title ?? '');
                        $('#pre_instruction_content').jqteVal(data?.content ?? '');
                        $('#pre_instruction_language_key').val(data?.language_key ?? $('#pre_instruction_language_key option:first').val()).trigger('chosen:updated');
                        $('#save_pre_instruction_button').attr('onclick', `updateBookPreInstruction(${data.id});`);
                        openPreInstructionModal();
                    },
                    error: function (data) {
                        modal.warning('Error', 'This review does not exist or cannot be processed!');
                    },
                })
            }

            function openPreInstructionModal() {
                $('#modal_pre_instruction').addClass('show');
                document.querySelector('body').style.overflow = 'hidden';
            }

            function closePreInstructionModal() {
                $('#modal_pre_instruction').removeClass('show');
                document.querySelector('body').style.overflow = '';
            }

            function createBookPreInstruction() {
                let title = $('#pre_instruction_title').val();
                let content = $('#pre_instruction_content').val();
                let language_key = $('#pre_instruction_language_key').val();
                if (empty(content) && empty(title)) {
                    modal.warning('Error', 'At least one field must be filled!');
                    return;
                }
                if (empty(language_key)) {
                    modal.warning('Error', 'The language key is required!');
                    return;
                }

                let data = new FormData();
                data.append('title', title);
                data.append('content', content);
                data.append('language_key', language_key);
                data.append('book_id', {{ $model->id }});
                $.ajax({
                    url : `{{ route('dashboard.book-pre-instruction.store') }}`,
                    method : 'POST',
                    data : data,
                    processData: false,
                    contentType: false,
                    success : function (data) {
                        preInstructionDataTable.draw(false);
                        closePreInstructionModal();
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

            function updateBookPreInstruction(id) {
                let title = $('#pre_instruction_title').val();
                let content = $('#pre_instruction_content').val();
                let language_key = $('#pre_instruction_language_key').val();
                if (empty(content) && empty(title)) {
                    modal.warning('Error', 'At least one field must be filled!');
                    return;
                }
                if (empty(language_key)) {
                    modal.warning('Error', 'The language key is required!');
                    return;
                }

                let data = new FormData();
                data.append('title', title);
                data.append('content', content);
                data.append('language_key', language_key);
                data.append('book_id', {{ $model->id }});
                $.ajax({
                    url : `{{ route('dashboard.book-pre-instruction.index', '') }}/${id}/update`,
                    method : 'POST',
                    data : data,
                    processData: false,
                    contentType: false,
                    success : function (data) {
                        preInstructionDataTable.draw(false);
                        closePreInstructionModal();
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

            function openCreatePostInstructionModal() {
                $('#post_instruction_title').val('');
                $('#post_instruction_content').jqteVal('');
                $('#post_instruction_language_key').val($('#post_instruction_language_key option:first').val()).trigger('chosen:updated');
                $('#save_post_instruction_button').attr('onclick', 'createBookPostInstruction();');
                openPostInstructionModal();
            }

            function openEditPostInstructionModal(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        $('#post_instruction_title').val(data?.title ?? '');
                        $('#post_instruction_content').jqteVal(data?.content ?? '');
                        $('#post_instruction_language_key').val(data?.language_key ?? $('#post_instruction_language_key option:first').val()).trigger('chosen:updated');
                        $('#save_post_instruction_button').attr('onclick', `updateBookPostInstruction(${data.id});`);
                        openPostInstructionModal();
                    },
                    error: function (data) {
                        modal.warning('Error', 'This review does not exist or cannot be processed!');
                    },
                })
            }

            function openPostInstructionModal() {
                $('#modal_post_instruction').addClass('show');
                document.querySelector('body').style.overflow = 'hidden';
            }

            function closePostInstructionModal() {
                $('#modal_post_instruction').removeClass('show');
                document.querySelector('body').style.overflow = '';
            }

            function createBookPostInstruction() {
                let title = $('#post_instruction_title').val();
                let content = $('#post_instruction_content').val();
                let language_key = $('#post_instruction_language_key').val();
                if (empty(content) && empty(title)) {
                    modal.warning('Error', 'At least one field must be filled!');
                    return;
                }
                if (empty(language_key)) {
                    modal.warning('Error', 'The language key is required!');
                    return;
                }

                let data = new FormData();
                data.append('title', title);
                data.append('content', content);
                data.append('language_key', language_key);
                data.append('book_id', {{ $model->id }});
                $.ajax({
                    url : `{{ route('dashboard.book-post-instruction.store') }}`,
                    method : 'POST',
                    data : data,
                    processData: false,
                    contentType: false,
                    success : function (data) {
                        postInstructionDataTable.draw(false);
                        closePostInstructionModal();
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

            function updateBookPostInstruction(id) {
                let title = $('#post_instruction_title').val();
                let content = $('#post_instruction_content').val();
                let language_key = $('#post_instruction_language_key').val();
                if (empty(content) && empty(title)) {
                    modal.warning('Error', 'At least one field must be filled!');
                    return;
                }
                if (empty(language_key)) {
                    modal.warning('Error', 'The language key is required!');
                    return;
                }

                let data = new FormData();
                data.append('title', title);
                data.append('content', content);
                data.append('language_key', language_key);
                data.append('book_id', {{ $model->id }});
                $.ajax({
                    url : `{{ route('dashboard.book-post-instruction.index', '') }}/${id}/update`,
                    method : 'POST',
                    data : data,
                    processData: false,
                    contentType: false,
                    success : function (data) {
                        postInstructionDataTable.draw(false);
                        closePostInstructionModal();
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


            function openCreatePreAdditionalModal() {
                $('#pre_additional_title').val('');
                $('#pre_additional_content').jqteVal('');
                $('#pre_additional_language_key').val($('#pre_additional_language_key option:first').val()).trigger('chosen:updated');
                $('#items-card-pre_additional_imagess-div > div:not(#add-new-items-form-pre_additional_images-btn)').remove();
                $('#urls_deleted_pre_additional_images').val('');
                $('#images_deleted_pre_additional_image').val('');
                $('#pre_additional_item_table_container').addClass('d-none');
                $('#save_pre_additional_button').attr('onclick', 'createBookPreAdditional();');
                openPreAdditionalModal();
            }

            function openEditPreAdditionalModal(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        $('#pre_additional_title').val(data?.title ?? '');
                        $('#pre_additional_content').jqteVal(data?.content ?? '');
                        $('#urls_deleted_pre_additional_images').val('');
                        $('#items-card-pre_additional_imagess-div > div:not(#add-new-items-form-pre_additional_images-btn)').remove();
                        Array.from(data.url_images).forEach((el, i) => {
                            let addNewItemsFormBtn = $('#add-new-items-form-pre_additional_images-btn').clone();
                            $('#add-new-items-form-pre_additional_images-btn').remove();
                            $('#add-new-card-pre_additional_images-template').tmpl({
                                newItemI: i,
                            }).appendTo('#items-card-pre_additional_imagess-div');
                            $('#items-card-pre_additional_imagess-div').append(addNewItemsFormBtn);
                            $(`#pre_additional_images_${i}_image`).attr('src', el);
                        });
                        $('#pre_additional_language_key').val(data?.language_key ?? $('#pre_instruction_language_key option:first').val()).trigger('chosen:updated');
                        $('#save_pre_additional_button').attr('onclick', `updateBookPreAdditional(${data.id});`);
                        $('#pre_additional_item_table_container').removeClass('d-none');
                        preAdditionalId = data.id;
                        preAdditionalItemDataTable.ajax.url(`{{ route('dashboard.book-pre-additional-item.index-json', '') }}/${preAdditionalId}`).load();
                        openPreAdditionalModal();
                    },
                    error: function (data) {
                        modal.warning('Error', 'This review does not exist or cannot be processed!');
                    },
                })
            }

            function openPreAdditionalModal() {
                $('#modal_pre_additional').addClass('show');
                document.querySelector('body').style.overflow = 'hidden';
            }

            function closePreAdditionalModal() {
                $('#modal_pre_additional').removeClass('show');
                document.querySelector('body').style.overflow = '';
            }

            function createBookPreAdditional() {
                let title = $('#pre_additional_title').val();
                let content = $('#pre_additional_content').val();
                let language_key = $('#pre_additional_language_key').val();

                let data = new FormData();

                $('#items-card-pre_additional_imagess-div .form-group input.invisible').each((key, el) => {
                    data.append('images[]', $(el).prop('files')[0] ?? '');
                });

                data.append('title', title);
                data.append('content', content);
                data.append('language_key', language_key);
                data.append('book_id', {{ $model->id }});
                $.ajax({
                    url : `{{ route('dashboard.book-pre-additional.store') }}`,
                    method : 'POST',
                    data : data,
                    processData: false,
                    contentType: false,
                    success : function (data) {
                        preAdditionalDataTable.draw(false);
                        closePreAdditionalModal();
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

            function updateBookPreAdditional(id) {
                let title = $('#pre_additional_title').val();
                let content = $('#pre_additional_content').val();
                let language_key = $('#pre_additional_language_key').val();
                let urls_deleted_images = $('#urls_deleted_pre_additional_images').val();

                let data = new FormData();

                $('#items-card-pre_additional_imagess-div .form-group input.invisible').each((key, el) => {
                    data.append('images[]', $(el).prop('files')[0] ?? '');
                });

                data.append('title', title);
                data.append('content', content);
                data.append('language_key', language_key);
                data.append('urls_deleted_images', urls_deleted_images);
                data.append('book_id', {{ $model->id }});

                $.ajax({
                    url : `{{ route('dashboard.book-pre-additional.index', '') }}/${id}/update`,
                    method : 'POST',
                    data : data,
                    processData: false,
                    contentType: false,
                    success : function (data) {
                        preAdditionalDataTable.draw(false);
                        closePreAdditionalModal();
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


            function openCreatePostAdditionalModal() {
                $('#post_additional_title').val('');
                $('#post_additional_content').jqteVal('');
                $('#items-card-post_additional_imagess-div > div:not(#add-new-items-form-post_additional_images-btn)').remove();
                $('#urls_deleted_post_additional_images').val('');
                $('#post_additional_language_key').val($('#post_instruction_language_key option:first').val()).trigger('chosen:updated');
                $('#post_additional_item_table_container').addClass('d-none');
                $('#save_post_additional_button').attr('onclick', 'createBookPostAdditional();');
                openPostAdditionalModal();
            }

            function openEditPostAdditionalModal(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        $('#post_additional_title').val(data?.title ?? '');
                        $('#post_additional_content').jqteVal(data?.content ?? '');
                        $('#urls_deleted_post_additional_images').val('');
                        $('#items-card-post_additional_imagess-div > div:not(#add-new-items-form-post_additional_images-btn)').remove();
                        Array.from(data.url_images).forEach((el, i) => {
                            let addNewItemsFormBtn = $('#add-new-items-form-post_additional_images-btn').clone();
                            $('#add-new-items-form-post_additional_images-btn').remove();
                            $('#add-new-card-post_additional_images-template').tmpl({
                                newItemI: i,
                            }).appendTo('#items-card-post_additional_imagess-div');
                            $('#items-card-post_additional_imagess-div').append(addNewItemsFormBtn);
                            $(`#post_additional_images_${i}_image`).attr('src', el);
                        });
                        $('#post_additional_language_key').val(data?.language_key ?? $('#post_instruction_language_key option:first').val()).trigger('chosen:updated');
                        $('#save_post_additional_button').attr('onclick', `updateBookPostAdditional(${data.id});`);
                        $('#post_additional_item_table_container').removeClass('d-none');
                        postAdditionalId = data.id;
                        postAdditionalItemDataTable.ajax.url(`{{ route('dashboard.book-post-additional-item.index-json', '') }}/${postAdditionalId}`).load();
                        openPostAdditionalModal();
                    },
                    error: function (data) {
                        modal.warning('Error', 'This review does not exist or cannot be processed!');
                    },
                })
            }

            function openPostAdditionalModal() {
                $('#modal_post_additional').addClass('show');
                document.querySelector('body').style.overflow = 'hidden';
            }

            function closePostAdditionalModal() {
                $('#modal_post_additional').removeClass('show');
                document.querySelector('body').style.overflow = '';
            }

            function createBookPostAdditional() {
                let title = $('#post_additional_title').val();
                let content = $('#post_additional_content').val();
                let language_key = $('#post_additional_language_key').val();

                let data = new FormData();

                $('#items-card-post_additional_imagess-div .form-group input.invisible').each((key, el) => {
                    data.append('images[]', $(el).prop('files')[0] ?? '');
                });

                data.append('title', title);
                data.append('content', content);
                data.append('language_key', language_key);
                data.append('book_id', {{ $model->id }});
                $.ajax({
                    url : `{{ route('dashboard.book-post-additional.store') }}`,
                    method : 'POST',
                    data : data,
                    processData: false,
                    contentType: false,
                    success : function (data) {
                        postAdditionalDataTable.draw(false);
                        closePostAdditionalModal();
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

            function updateBookPostAdditional(id) {
                let title = $('#post_additional_title').val();
                let content = $('#post_additional_content').val();
                let language_key = $('#post_additional_language_key').val();
                let urls_deleted_images = $('#urls_deleted_post_additional_images').val();

                let data = new FormData();

                $('#items-card-post_additional_imagess-div .form-group input.invisible').each((key, el) => {
                    data.append('images[]', $(el).prop('files')[0] ?? '');
                });


                data.append('title', title);
                data.append('content', content);
                data.append('language_key', language_key);
                data.append('urls_deleted_images', urls_deleted_images);
                data.append('book_id', {{ $model->id }});
                $.ajax({
                    url : `{{ route('dashboard.book-post-additional.index', '') }}/${id}/update`,
                    method : 'POST',
                    data : data,
                    processData: false,
                    contentType: false,
                    success : function (data) {
                        postAdditionalDataTable.draw(false);
                        closePostAdditionalModal();
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


            function addPreAdditionalItemRow() {
                if ($('#pre_additional_item_data-table tbody .new-row').length < 1) {
                    $('#pre_additional_item_data-table tbody').append($('<tr>').attr('role', 'row').attr('class', 'new-row')
                        .append($('<td>').attr('class', 'align-middle').append('new'))
                        .append($('<td>').attr('class', 'align-middle').append($('<textarea>').attr('class', 'additional-title')))
                        .append($('<td>').attr('class', 'align-middle').append($('<textarea>').attr('class', 'additional-content')))
                        .append($('<td>').attr('class', 'text-truncate py-2 px-2 align-middle text-right')
                            .append($('<button>').attr('class', 'btn btn-self-info py-1 px-0 text-center width-60').attr('onclick', 'createPreAdditionalItem(this)').append('Save'))
                            .append($('<button>').attr('class', 'btn btn-self-danger py-1 ml-2 px-0 text-center width-60').attr('onclick', `$(this).closest('.new-row').remove()`).append('Cancel'))
                        )
                    );
                    autosize($('#pre_additional_item_data-table .new-row textarea'));
                }
            }

            function createPreAdditionalItem(el) {
                let row = $(el).closest('.new-row').get(0);
                let title = $(row)?.find('.additional-title')?.val() ?? '';
                let content = $(row)?.find('.additional-content')?.val() ?? '';
                let data = new FormData();

                data.append('title', title);
                data.append('content', content);
                data.append('book_pre_additional_id', preAdditionalId);

                $.ajax({
                    url: `{{ route('dashboard.book-pre-additional-item.store') }}`,
                    method: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        preAdditionalItemDataTable.ajax.reload();
                    },
                    error: function (data) {
                        preAdditionalItemDataTable.ajax.reload();
                        modal.warning('Error', 'This review does not exist or cannot be processed!');
                    },
                })
            }

            function updatePreAdditionalItem(el) {
                let row = $(el).closest('tr').get(0);
                let title = $(row)?.find('.additional-title')?.val() ?? '';
                let content = $(row)?.find('.additional-content')?.val() ?? '';

                let data = new FormData();

                data.append('title', title);
                data.append('content', content);
                data.append('book_pre_additional_id', preAdditionalId);

                $.ajax({
                    url: `{{ route('dashboard.book-pre-additional-item.index') }}/${el.dataset.id}/update`,
                    data: data,
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        preAdditionalItemDataTable.ajax.reload();
                    },
                    error: function (data) {
                        preAdditionalItemDataTable.ajax.reload();
                        modal.warning('Error', 'This review does not exist or cannot be processed!');
                    },
                })
            }

            function addPostAdditionalItemRow() {
                if ($('#post_additional_item_data-table tbody .new-row').length < 1) {
                    $('#post_additional_item_data-table tbody').append($('<tr>').attr('role', 'row').attr('class', 'new-row')
                        .append($('<td>').attr('class', 'align-middle').append('new'))
                        .append($('<td>').attr('class', 'align-middle').append($('<textarea>').attr('class', 'additional-title')))
                        .append($('<td>').attr('class', 'align-middle').append($('<textarea>').attr('class', 'additional-content')))
                        .append($('<td>').attr('class', 'text-truncate py-2 px-2 align-middle text-right')
                            .append($('<button>').attr('class', 'btn btn-self-info py-1 px-0 text-center width-60').attr('onclick', 'createPostAdditionalItem(this)').append('Save'))
                            .append($('<button>').attr('class', 'btn btn-self-danger py-1 ml-2 px-0 text-center width-60').attr('onclick', `$(this).closest('.new-row').remove()`).append('Cancel'))
                        )
                    );
                    autosize($('#pre_additional_item_data-table .new-row textarea'));
                }
            }

            function createPostAdditionalItem(el) {
                let row = $(el).closest('.new-row').get(0);
                let title = $(row)?.find('.additional-title')?.val() ?? '';
                let content = $(row)?.find('.additional-content')?.val() ?? '';

                let data = new FormData();

                data.append('title', title);
                data.append('content', content);
                data.append('book_post_additional_id', postAdditionalId);

                $.ajax({
                    url: `{{ route('dashboard.book-post-additional-item.store') }}`,
                    method: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        postAdditionalItemDataTable.ajax.reload();
                    },
                    error: function (data) {
                        postAdditionalItemDataTable.ajax.reload();
                        modal.warning('Error', 'This review does not exist or cannot be processed!');
                    },
                })
            }

            function updatePostAdditionalItem(el) {
                let row = $(el).closest('tr').get(0);
                let title = $(row)?.find('.additional-title')?.val() ?? '';
                let content = $(row)?.find('.additional-content')?.val() ?? '';

                let data = new FormData();

                data.append('title', title);
                data.append('content', content);
                data.append('book_post_additional_id', postAdditionalId);

                $.ajax({
                    url: `{{ route('dashboard.book-post-additional-item.index') }}/${el.dataset.id}/update`,
                    data: data,
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        postAdditionalItemDataTable.ajax.reload();
                    },
                    error: function (data) {
                        postAdditionalItemDataTable.ajax.reload();
                        modal.warning('Error', 'This review does not exist or cannot be processed!');
                    },
                })
            }

        </script>
        <script src="{{ asset('js/base/datatable.js') }}"></script>
    @endpush

    @push('css_2')
        <link href="{{ asset('css/plugins/rowReorder.bootstrap.min.css') }}" rel="stylesheet">
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

            .modal .card  button img.base-img-fit-contain {
                display: block !important;
            }

            .additional-title, .additional-content {
                border: 0;
                min-height: 20px;
                outline: 0;
                width: 100%;
                padding: 2px 0;
                background: transparent;
            }
            .new-row .additional-title, .new-row .additional-content {
                outline: #cdcdcd 1px solid;
                padding: 6px;
            }
        </style>
    @endpush
@endif