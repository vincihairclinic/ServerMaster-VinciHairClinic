@extends('dashboard.layouts.content')
@include('widget.bootstrap.datatable-form-push-resources')

@section('head_title') {{ $settingsListModels['plural'] }} @endsection
@section('title') {{ $settingsListModels['plural'] }} @endsection
@section('global-search-input')
    @include('widget.bootstrap.global-search-input')
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            @if($settingsListModels['Modal'] ?? false)
                <div id="edit_modal_container"></div>
                @include('widget.bootstrap.list-models.modal', ['modalId' => 'create_modal', 'title' => 'Add '.$settingsListModels['name'], 'pushJs' => true, 'actionUrl' => (route('dashboard.list-models.store', ['settingsListModels' => $settingsListModels['id']])) ])
                <button class="btn btn-self-primary" data-toggle="modal" data-target="#create_modal" >Add {{ $settingsListModels['name'] }}</button>
            @else
                <a href="{{ route('dashboard.list-models.create', ['settingsListModels' => $settingsListModels['id']]) }}" class="btn btn-self-primary">Add {{ $settingsListModels['name'] }}</a>
            @endif
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
                    url: '{{ route('dashboard.list-models.index-json', ['settingsListModels' => $settingsListModels['id']]) }}',
                    type: 'post'
                },
                order: [
                    [0, "desc"],
                ],
                columns: [
                    {
                        data: 'id',
                        title: 'id',
                        searchable: 'false',
                        className: 'd-none',
                    },
                        @foreach($settingsListModels['dataTable'] as $item)
                    {
                        data: '{{ $item['data'] }}',
                        title: '{{ $item['title'] }}',
                        searchable: {{ $item['searchable'] ?? 'true' }},
                        orderable: {{ $item['orderable'] ?? 'true' }},
                        className: '{{ $item['className'] ?? '' }}',
                        width: '{{ $item['width'] ?? '1px' }}',
                    },
                        @endforeach
                    {
                        data: null,
                        width: '120px',
                        searchable: false,
                        orderable: false,
                        className: 'text-truncate py-2 px-2',
                        render: function (data, type, row) {
                            return '' +
                                    @if($settingsListModels['Modal'] ?? false)
                                        '<button class="btn btn-self-info py-1 d-inline-block px-0 text-center width-70" onclick=editListModelsModal("{{ route('dashboard.list-models.index', ['settingsListModels' => $settingsListModels['id']]) }}/'+row.id+'/edit")>Edit</button>' +
                                    @else
                                        '<a class="btn btn-self-info py-1 px-0 d-inline-block text-center width-70" href="{{ route('dashboard.list-models.index', ['settingsListModels' => $settingsListModels['id']]) }}/'+row.id+'/edit">Edit</a>'+
                                    @endif
                                        ' <a class="btn btn-self-danger py-1 ml-2 d-inline-block px-0 text-center width-70" href="javascript:void(0);" onclick="removeDataTableRow(\'{{ route('dashboard.index') }}/{{ $settingsListModels['id'] }}/' + row.id + '/destroy\')">Delete</a>';
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
                lengthMenu: [[10, 25, 50], [10, 25, 50]]
            });

            // dataTable.on('draw.dt', function () {
            //     componentHandler.upgradeDom();
            // });
        });
        function removeDataTableRow(url){
            if(confirm('Do you want delete this?')){
                $.ajax({
                    url: url,
                    method: 'POST',
                    success: function (){
                        dataTable.ajax.reload();
                    },
                    error: function (){
                        dataTable.ajax.reload();
                    }
                })
            }
        }
        function editListModelsModal(url){
            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    ajax : true
                },
                success: function (data){
                    $('#edit_modal_container').empty();
                    $('#edit_modal_container').append(data);
                    $('#edit_modal').modal('show');
                },
                error: function (data){
                    console.log('err', data);
                },
            });
        }
    </script>
    <script src="{{ asset('js/base/datatable.js') }}"></script>
@endpush
