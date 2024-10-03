@extends('dashboard.layouts.content')
@include('widget.bootstrap.datatable-push-resources')
@section('head_title') Request contact from this clinic @endsection
@section('title') Request contact from this clinic @endsection
@section('global-search-input')
    @include('widget.bootstrap.global-search-input')
@endsection

@section('content')
    @include('widget.bootstrap.dialog-edit-image', ['isUpload' => false, 'isRemove' => false])
    <div class="row">
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
                    url: '{{ route('dashboard.request-contact-from-clinic.index-json') }}',
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
                        data: 'email',
                        title: 'Email',
                        width: '30%',
                        className: 'text-truncate align-middle sort-row-target',
                    },{
                        data: 'full_name',
                        title: 'Full name',
                        width: '30%',
                        className: 'text-truncate align-middle sort-row-target',
                    },{
                        data: 'phone_number',
                        title: 'Phone number',
                        width: '30%',
                        className: 'text-truncate align-middle sort-row-target',
                    },{
                        data: 'is_book_consultation_checked',
                        title: 'Added to Sales Force',
                        width: '30%',
                        className: 'text-truncate align-middle sort-row-target text-center',
                        render: function (data, type, row) {
                            return data ? 'true' : 'false';
                        }
                    },{
                        data: null,
                        width: '1px',
                        searchable: false,
                        orderable: false,
                        className: 'text-truncate py-2 px-2 align-middle text-right',
                        render: function (data, type, row) {
                            return `<a class="btn btn-self-info py-1 px-0 text-center width-60" href="{{ route('dashboard.request-contact-from-clinic.index') }}/${row.id}/edit">Check</a>
                                    <button class="btn btn-self-primary py-1 px-0 text-center width-120" onclick="updateSalesForce(${row.id})">Sales Force</button>`;
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
                lengthMenu: [[10, 25, 50], [10, 25, 50]],
            });


            {{--dataTable.on('row-reorder', function (e, diff, edit) {--}}
            {{--    let data = [];--}}
            {{--    for (let i = 0; i < diff.length; i++) {--}}
            {{--        data.push([dataTable.row(diff[i].node).data().id, diff[i].newPosition]);--}}
            {{--    }--}}
            {{--    if (data[0] !== undefined) {--}}
            {{--        $.ajax({--}}
            {{--            url: '{{ route('dashboard.request-contact-from-clinic.sort-update') }}',--}}
            {{--            method: 'post',--}}
            {{--            data: {--}}
            {{--                data: data--}}
            {{--            }--}}
            {{--        }).done(function (response) {--}}
            {{--            dataTable.ajax.reload();--}}
            {{--        }).fail(function () {--}}
            {{--            dataTable.ajax.reload();--}}
            {{--        });--}}
            {{--    }--}}
            {{--});--}}
        });

        function updateSalesForce(id) {
            $.ajax({
                url: `{{ route('dashboard.request-contact-from-clinic.index') }}/${id}/update`,
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