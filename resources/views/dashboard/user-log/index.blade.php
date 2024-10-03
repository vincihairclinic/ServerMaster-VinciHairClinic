@extends('dashboard.layouts.content')
@include('widget.bootstrap.datatable-push-resources')

@section('head_title') User Logs @endsection
@section('title') User Logs @endsection
@section('global-search-input')
    @include('widget.bootstrap.global-search-input')
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
        </div>
        <div class="row col-12">
            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'action_id-filter-select', 'title' => 'Filter by Action', 'cell' => [4, 6, 12],
                   'options' => collect(\App\Models\Datasets\UserLogAction::$data)->prepend(['id' => null, 'name' => null]),
                   'style' => 'height: 66px; padding-bottom: 0;',
               ])
            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'label-filter-select', 'title' => 'Filter by Label', 'cell' => [4, 6, 12],
                       'options' => \App\Models\UserLog::select(['label'])->distinct()->get()->map(function ($el) {
                       $item['id'] = $el->label;
                       $item['name'] = $el->label;
                       return $item;
                   }),
                   'style' => 'height: 66px; padding-bottom: 0;',
           ])
            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'model-filter-select', 'title' => 'Filter by Model', 'cell' => [4, 6, 12],
                   'options' => \App\Models\UserLog::select(['model'])->distinct()->get()->map(function ($el) {
                       $item['id'] = $el->model;
                       $item['name'] = $el->model;
                       return $item;
                   })->prepend(['id' => null, 'name' => null]),
                   'style' => 'height: 66px; padding-bottom: 0;',
           ])
            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'controller-filter-select', 'title' => 'Filter by Controller', 'cell' => [4, 6, 12],
                   'options' => \App\Models\UserLog::select(['controller'])->distinct()->get()->map(function ($el) {
                       $item['id'] = $el->controller;
                       $item['name'] = $el->controller;
                       return $item;
                   })->prepend(['id' => null, 'name' => null]),
                   'style' => 'height: 66px; padding-bottom: 0;',
           ])
            @include('widget.bootstrap.form.chosen-select-single', ['id' => 'user_id-filter-select', 'title' => 'Filter by User', 'optionName' => 'username','cell' => [4, 6, 12],
                   'options' => \App\Models\UserLog::select('user_id')->distinct()->with('user')->get()->map(function ($el) {
                       return  $el['user']->toArray();
                   })->prepend(['id' => null, 'username' => null]),
                   'style' => 'height: 66px; padding-bottom: 0;',
           ])
            <div class="col-12 col-md-4 col-sm-6 ">
                @include('widget.bootstrap.form.range-date-time-picker',['id' => 'created_at', 'color' => '#942056'])
            </div>
        </div>
        <div class="col-12 mt-3">
            <table id="data-table" class="table table-bordered" style="width:100%"></table>
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
                    url: '{{ route('dashboard.user-log.index-json') }}',
                    type: 'post'
                },
                order: [
                    [1, "desc"],
                ],
                columns: [
                    {
                        data: null,
                        className: 'details-control',
                        defaultContent: '+',
                        searchable: false,
                        orderable: false,
                        width: "1px",
                        render: function (data, type, row) {
                            return '+';
                        }
                    },{
                        data: 'id',
                        title: 'id',
                        searchable: false,
                        className: 'd-none',
                    },{
                        data: 'user_id',
                        title: '',
                        className: 'd-none datatable-row-user_id-filter',
                    },{
                        data: 'user.username',
                        title: 'User',
                        width: '1%',
                    },{
                        data: 'action_id',
                        title: 'Action',
                        width: '1%',
                        className: 'datatable-row-action_id-filter',
                        render: function (data, type, row) {
                            return datasets.getNameById('UserLogAction', row.action_id);
                        }
                    },{
                        data: 'model',
                        title: 'Model',
                        className: 'datatable-row-model-filter',
                        width: '1%',
                    },{
                        data: 'model_id',
                        title: 'Model Id',
                        width: '1%',
                        className: 'text-truncate'
                    },{
                        data: 'controller',
                        title: 'Controller',
                        width: '1%',
                        orderable: false,
                        className: 'datatable-row-controller-filter',
                    },{
                        data: 'label',
                        title: 'Label',
                        className: 'datatable-row-label-filter',
                        width: '100%',
                        orderable: false,
                    },{
                        data: 'created_at',
                        title: 'Date of edit',
                        width: '1%',
                        className: 'text-truncate'
                    },{
                        data: null,
                        className: 'd-none datatable-row-created_at-from-filter',
                        searchable: false,
                        orderable: false,
                    },{
                        data: null,
                        className: 'd-none datatable-row-created_at-to-filter',
                        searchable: false,
                        orderable: false,
                    },
                ],
                initComplete: function () {
                    dataTablesFunc.globalSearchFilter.initComplete(dataTable);
                    dataTablesFunc.selectFilter.initComplete(dataTable, 'user_id');
                    dataTablesFunc.dateRangeFilterOnClick.initComplete(dataTable, 'created_at', initLightPickercreated_at);
                    dataTablesFunc.selectFilter.initComplete(dataTable, 'action_id');
                    dataTablesFunc.selectFilter.initComplete(dataTable, 'controller');
                    dataTablesFunc.selectFilter.initComplete(dataTable, 'label');
                    dataTablesFunc.selectFilter.initComplete(dataTable, 'model');
                    defaultChosenInit();
                },
                processing: true,
                serverSide: true,
                stateSave: true,
                dom: 'rtipl',
                lengthMenu: [[10, 25, 50], [10, 25, 50]]
            });

            dataTablesFunc.dateRangeFilterOnClick.clickEventInit(dataTable, 'created_at', changeDateInputcreated_at);
            dataTablesFunc.detailsRow.init(dataTable, showDetailsRow, true);
            dataTablesFunc.selectFilter.clickEventInit(dataTable, 'user_id');
            dataTablesFunc.selectFilter.clickEventInit(dataTable, 'action_id');
            dataTablesFunc.selectFilter.clickEventInit(dataTable, 'controller');
            dataTablesFunc.selectFilter.clickEventInit(dataTable, 'label');
            dataTablesFunc.selectFilter.clickEventInit(dataTable, 'model');
        });

        function showDetailsRow(d) {
            if(empty(d) || empty(d.changes)){
                return '';
            }
            return logChanges.render(d.changes);
        }

        var logChanges = {
            render: function (changes){
                var html = '<table class="diff_table">';
                for (const [i, v] of Object.entries(changes)) {
                    html += '<tr>';
                    html += '<td class="column">'+(!empty(v.column) ? replaceAll(v.column, '_', ' ') : '')+'</td>';
                    if(v.column.endsWith('image')){
                        html += '<td class="value_befor">'+(!empty(v.value_befor) ? '<img src="'+pathImage+''+v.value_befor+'" />' : '')+'</td>';
                        html += '<td class="value_after">'+(!empty(v.value_after) ? '<img src="'+pathImage+''+v.value_after+'" />' : '')+'</td>';
                    }else if(v.column.endsWith('images')){
                        html += '<td class="value_befor">' + logChanges.renderListImages(v.value_befor) + '</td>';
                        html += '<td class="value_after">' + logChanges.renderListImages(v.value_after) + '</td>';
                    }else if(!empty(v.is_array)) {
                        html += '<td class="value_befor">' + (!empty(v.is_object) ? logChanges.renderArray(v.value_befor) : logChanges.renderList(v.value_befor)) + '</td>';
                        html += '<td class="value_after">' + (!empty(v.is_object) ? logChanges.renderArray(v.value_after) : logChanges.renderList(v.value_after)) + '</td>';
                    }else if(!empty(v.is_object)){
                        html += '<td class="value_befor">' + logChanges.renderObject(v.value_befor) + '</td>';
                        html += '<td class="value_after">' + logChanges.renderObject(v.value_after) + '</td>';
                    }else if(!empty(v.is_date)){
                        html += '<td class="value_befor">'+(!empty(v.value_befor) ? moment(v.value_befor.replace(/&quot;/g,"")).format("YYYY-MM-DD | hh:mm a") : '')+'</td>';
                        html += '<td class="value_after">'+(!empty(v.value_after) ? moment(v.value_after.replace(/&quot;/g,"")).format("YYYY-MM-DD | hh:mm a") : '')+'</td>';
                    }else {
                        html += '<td class="value_befor">'+(!empty(v.value_befor) ? v.value_befor : '')+'</td>';
                        html += '<td class="value_after">'+(!empty(v.value_after) ? v.value_after : '')+'</td>';
                    }
                    html += '</tr>';
                }
                html += '</table>';
                return html;
            },

            renderArray: function (v){
                if(empty(v)){
                    return '';
                }
                var tmp = '';
                (v).forEach(function (v1, i1) {
                    tmp += '<tr><td>'+(v1.id ?? v1[Object.keys(v1)[0]])+'</td><td>'+(v1.name ?? v1[Object.keys(v1)[1]])+'</td></tr>';
                });
                return '<table class="sub_table">' + tmp + '</table>';
            },

            renderObject: function (v){
                if(empty(v)){
                    return '';
                }
                var tmp = '';
                for (const [i1, v1] of Object.entries(v)) {
                    tmp += '<tr><td>'+(v1.id ?? v1[Object.keys(v1)[0]])+'</td><td>'+(v1.name ?? v1[Object.keys(v1)[1]])+'</td></tr>';
                }
                return '<table class="sub_table">' + tmp + '</table>';
            },

            renderList: function (v){
                if(empty(v)){
                    return '';
                }
                var tmp = '';
                (v).forEach(function (v1, i1) {
                    tmp += v1 + '<br>';
                });
                return '<table class="sub_table">' + tmp + '</table>';
            },

            renderListImages: function (v){
                if(empty(v)){
                    return '';
                }
                var tmp = '';
                (v).forEach(function (v1, i1) {
                    tmp += '<img src="' + pathImage + '' + v1 + '" />';
                })
                return '<table class="sub_table">' + tmp + '</table>';
            },
        };

    </script>
    <script src="{{ asset('js/base/datatable.js') }}"></script>
@endpush

@push('css_2')
    <style>
        .table.table-bordered.dataTable > tbody > tr > td{
            border-bottom: 1px solid #dee2e6 !important;
        }

        .diff_table{
            width: 100%;
            border: none;
            border-bottom: 1px solid #dee2e6 !important;
            font-size: 13px;
        }
        .diff_table td{
            background-color: #fff;
            text-align: left !important;
            overflow: hidden;
            vertical-align: top !important;
        }
        .diff_table td:last-of-type{
            border-right: none;
        }
        .diff_table td.column{
            white-space: nowrap;
        }
        .diff_table td.value_befor{
            background-color: #ffeef0;
            width: 50%;
            word-wrap: anywhere;
            word-break: break-word;
        }
        .diff_table td.value_after{
            background-color: #e6ffec;
            width: 50%;
            word-wrap: anywhere;
            word-break: break-word;
        }
        .diff_table td img{
            max-height: 50px;
            padding-right: 20px;
        }
        .diff_table .sub_table{
            font-size: 13px;
        }
        .diff_table .sub_table td{
            padding: 2px 10px 2px 0;
            margin: 0;
            background-color: transparent;
        }



    </style>
@endpush


