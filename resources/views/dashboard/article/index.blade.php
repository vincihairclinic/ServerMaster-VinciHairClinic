@extends('dashboard.layouts.content')
@include('widget.bootstrap.datatable-push-resources')
@section('head_title') Article @endsection
@section('title') Article @endsection
@section('global-search-input')
    @include('widget.bootstrap.global-search-input')
@endsection

@section('content')
    @include('widget.bootstrap.dialog-edit-image', ['isUpload' => false, 'isRemove' => false])
    <div class="row">
        <div class="col-12">
            <a href="{{ route('dashboard.article.create') }}" class="btn btn-self-primary">Add article</a>
        </div>
        <div class="col-12 mt-3">
            <table id="data-table" class="table table-striped table-bordered" style="width:100%"></table>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(function () {
            $.fn.dataTable.ext.errMode = 'throw';
            $.fn.DataTable.ext.pager.numbers_length = 7;

            dataTable = $('#data-table').DataTable({
                ajax: {
                    url: '{{ route('dashboard.article.index-json') }}',
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
                        className: 'text-truncate center width-70 py-2 align-middle',
                        render: function (data, type, row) {
                            return '<button type="button" class="p-0 border-0" data-toggle="modal" data-target="#dialog-edit-image" data-image="image_'+row.id+'"> ' +
                                '<img onerror="this.src=\''+defaultImageUrl+'\'" class="width-50 rounded'+(row.image ? ' cursor-pointer' : '')+'" id="image_'+row.id+'" src="'+(row.url_image ? row.url_image + '?r='+Math.random() : defaultImageUrl)+'" onclick="showDialogEditImage(this, true)">' +
                                '</button>';
                        }
                    },{
                        data: 'name_en',
                        title: 'English name',
                        width: '50%',
                        className: 'text-truncate align-middle',
                    },{
                        data: 'name_pt',
                        title: 'Portuguese name',
                        width: '50%',
                        className: 'text-truncate align-middle',
                    },{
                        data: null,
                        width: '1px',
                        searchable: false,
                        orderable: false,
                        className: 'text-truncate py-2 px-2 align-middle text-right',
                        render: function (data, type, row) {
                            return '<a class="btn btn-self-info py-1 px-0 text-center width-60" href="{{ route('dashboard.article.index') }}/' + row.id + '/edit">Edit</button>' +
                                ' <a class="btn btn-self-danger py-1 ml-2 px-0 text-center width-60" href="javascript:void(0);" onclick="removeDataTableRow(\'{{ route('dashboard.article.index') }}/' + row.id + '/destroy\')">Delete</a>';
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