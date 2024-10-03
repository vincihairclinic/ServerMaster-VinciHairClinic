@extends('dashboard.layouts.content')
@include('widget.bootstrap.datatable-push-resources')
@section('head_title') Product category @endsection
@section('title') Product category @endsection
@section('global-search-input')
    @include('widget.bootstrap.global-search-input')
@endsection

@section('content')
    @include('widget.bootstrap.dialog-edit-image', ['isUpload' => false, 'isRemove' => false])
    <div class="row">
        <div class="col-12">
            <a href="{{ route('dashboard.product-category.create') }}" class="btn btn-self-primary">Add product category</a>
        </div>
        <div class="col-12 mt-3">
            <table id="data-table" class="table table-striped table-bordered" style="width:100%"></table>
        </div>
    </div>
@endsection

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
        $(function () {
            $.fn.dataTable.ext.errMode = 'throw';
            $.fn.DataTable.ext.pager.numbers_length = 7;

            dataTable = $('#data-table').DataTable({
                ajax: {
                    url: '{{ route('dashboard.product-category.index-json') }}',
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
                            return '<a class="btn btn-self-info py-1 px-0 text-center width-60" href="{{ route('dashboard.product-category.index') }}/' + row.id + '/edit">Edit</button>' +
                                ' <a class="btn btn-self-danger py-1 ml-2 px-0 text-center width-60" href="javascript:void(0);" onclick="removeDataTableRow(\'{{ route('dashboard.product-category.index') }}/' + row.id + '/destroy\')">Delete</a>';
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
                dom: 'rtipl',
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
                        url: '{{ route('dashboard.product-category.sort-update') }}',
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
        function removeDataTableRow(url){
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
    </script>
    <script src="{{ asset('js/base/datatable.js') }}"></script>
@endpush