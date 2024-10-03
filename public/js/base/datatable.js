// $(function () {
//     $(window).on('resize', function(){
//         dataTablesFunc.getWidth();
//     });
//
//     $('.dataTables_length select').addClass('mdl-textfield__input').css({width: '50px', display:'inline'});
// });

var dataTablesFunc = {
    getWidth: function () {
        $('.data-table_width').width($('#data-table').parent().width()-20);
    },

    globalSearchFilter: {
        initComplete: function (dataTable) {
            var data = dataTable.ajax.params();
            if(data.search.value){
                console.log(data);
                $('#global-search-input').val(data.search.value).parent().parent().addClass('is-dirty');
            }
        }
    },

    tabBarFilter: {
        clickEventInit: function (dataTable) {
            $('.tab-bar-link').on('click', function (e) {
                e.preventDefault();
                var column = dataTable.column('.datatable-row-tab-bar-filter').index();
                var val = $(this).data('id');
                dataTable.column(column).search( val ? val : '', false, false ).draw();
                if(hideColumn = dataTable.column('.datatable-row-tab-bar-hide-filter').index()){
                    column = dataTable.column(hideColumn);
                    val !== '' || val !== -1 ? column.visible(false) : column.visible(true);
                }
            });
        },

        initComplete: function (dataTable) {
            var data = dataTable.ajax.params();
            var column = dataTable.column('.datatable-row-tab-bar-filter').index();
            if(data.columns[column].search.value){
                $('.tab-bar-link[data-id="'+data.columns[column].search.value+'"]').addClass('is-active');
            }else {
                $('.tab-bar-link[data-id=""]').addClass('is-active');
            }
        }
    },

    checkboxFilter: {
        clickEventInit: function (dataTable, field) {
            $('#'+field+'-filter-checkbox').on('change', function (){
                var column = dataTable.column('.datatable-row-'+field+'-filter');
                var val = $(this).is(':checked') ? 1 : '';
                column.search(val, false, false).draw();
                if(hideColumn = dataTable.column('.datatable-row-' + field + '-hide-filter').index()) {
                    column = dataTable.column(hideColumn);
                    val !== '' ? column.visible(false) : column.visible(true);
                }
            });
        },

        initComplete: function (dataTable, field) {
            var data = dataTable.ajax.params();
            if(data.columns[dataTable.column('.datatable-row-'+field+'-filter').index()].search.value){
                $('#'+field+'-filter-checkbox').attr('checked', true).parent().addClass('is-checked');
            }
        }
    },

    mdlDateTimePickerRangeFilter: {
        from : null,
        fromValue : '',
        to : null,
        toValue : '',

        clickEventInit: function (dataTable, field) {
            $('#'+field+'-from-filter-input').on('change', function (){
                if($(this).val() == ''){
                    dataTablesFunc.mdlDateTimePickerRangeFilter.fromValue = '';
                }
                if(dataTablesFunc.mdlDateTimePickerRangeFilter.fromValue == ''){
                    $(this).parent().removeClass('is-dirty');
                    dataTablesFunc.mdlDateTimePickerRangeFilter.to._past = moment(0);
                }
                $(this).val(dataTablesFunc.mdlDateTimePickerRangeFilter.fromValue);
                var column = dataTable.column('.datatable-row-'+field+'-from-filter');
                column.search(dataTablesFunc.mdlDateTimePickerRangeFilter.fromValue ? dataTablesFunc.mdlDateTimePickerRangeFilter.fromValue : '', false, false).draw();
            });

            $('#'+field+'-to-filter-input').on('change', function (){
                if($(this).val() == ''){
                    dataTablesFunc.mdlDateTimePickerRangeFilter.toValue = '';
                }
                if(dataTablesFunc.mdlDateTimePickerRangeFilter.toValue == ''){
                    $(this).parent().removeClass('is-dirty');
                    dataTablesFunc.mdlDateTimePickerRangeFilter.from._future = moment();
                }
                $(this).val(dataTablesFunc.mdlDateTimePickerRangeFilter.toValue);
                var column = dataTable.column('.datatable-row-'+field+'-to-filter');
                column.search(dataTablesFunc.mdlDateTimePickerRangeFilter.toValue ? dataTablesFunc.mdlDateTimePickerRangeFilter.toValue : '', false, false).draw();
            });
        },

        initComplete: function (dataTable, field) {
            var data = dataTable.ajax.params();

            dataTablesFunc.mdlDateTimePickerRangeFilter.from = mdDateTimePickerInit(field+'-from-filter-btn', field+'-from-filter-input', function (_this, dialog) {
                _this.value = moment(dialog.time.toString(), 'ddd MMM D YYYY HH:mm:ss ZZ').format("MMM DD, YYYY");
                dataTablesFunc.mdlDateTimePickerRangeFilter.fromValue = _this.value;
                $(_this).parent().addClass('is-dirty');
                $(_this).trigger('change');
                dataTablesFunc.mdlDateTimePickerRangeFilter.to._past = moment(dataTablesFunc.mdlDateTimePickerRangeFilter.fromValue, "MMM DD, YYYY");
            });

            dataTablesFunc.mdlDateTimePickerRangeFilter.to = mdDateTimePickerInit(field+'-to-filter-btn', field+'-to-filter-input', function (_this, dialog) {
                _this.value = moment(dialog.time.toString(), 'ddd MMM D YYYY HH:mm:ss ZZ').format("MMM DD, YYYY");
                dataTablesFunc.mdlDateTimePickerRangeFilter.toValue = _this.value;
                $(_this).parent().addClass('is-dirty');
                $(_this).trigger('change');
                dataTablesFunc.mdlDateTimePickerRangeFilter.from._future = moment(dataTablesFunc.mdlDateTimePickerRangeFilter.toValue, "MMM DD, YYYY");
            });


            if(data.columns[dataTable.column('.datatable-row-'+field+'-from-filter').index()].search.value){
                dataTablesFunc.mdlDateTimePickerRangeFilter.fromValue = data.columns[dataTable.column('.datatable-row-'+field+'-from-filter').index()].search.value;
                $('#'+field+'-from-filter-input').val(dataTablesFunc.mdlDateTimePickerRangeFilter.fromValue).parent().addClass('is-dirty');
                dataTablesFunc.mdlDateTimePickerRangeFilter.from._init = moment(dataTablesFunc.mdlDateTimePickerRangeFilter.fromValue, "MMM DD, YYYY");
                dataTablesFunc.mdlDateTimePickerRangeFilter.to._past = moment(dataTablesFunc.mdlDateTimePickerRangeFilter.fromValue, "MMM DD, YYYY");
            }
            if(data.columns[dataTable.column('.datatable-row-'+field+'-to-filter').index()].search.value){
                dataTablesFunc.mdlDateTimePickerRangeFilter.toValue = data.columns[dataTable.column('.datatable-row-'+field+'-to-filter').index()].search.value;
                $('#'+field+'-to-filter-input').val(dataTablesFunc.mdlDateTimePickerRangeFilter.toValue).parent().addClass('is-dirty');
                dataTablesFunc.mdlDateTimePickerRangeFilter.to._init = moment(dataTablesFunc.mdlDateTimePickerRangeFilter.toValue, "MMM DD, YYYY");
                dataTablesFunc.mdlDateTimePickerRangeFilter.from._future = moment(dataTablesFunc.mdlDateTimePickerRangeFilter.toValue, "MMM DD, YYYY");
            }
        }
    },

    checkboxTrigger: {
        clickEventInit: function (dataTable, field) {
            $('#'+field+'-trigger-checkbox').on('change', function (){
                var column = dataTable.column('.datatable-row-'+field);
                $(this).is(':checked') ? column.visible(true) : column.visible(false);
            });
        },

        initComplete: function (dataTable, field) {
            if(dataTable.column('.datatable-row-'+field).visible()){
                $('#'+field+'-trigger-checkbox').attr('checked', true).parent().addClass('is-checked');
            }
        }
    },

    dateRangeFilter: {
        clickEventInit: function (dataTable, field, callback) {
            $('#'+field+'-from-filter-input').on('change', function (){
                var column = dataTable.column('.datatable-row-'+field+'-from-filter');
                var val = $(this).val();

                dataTable.column(column).search( val ? val : '', true, false).draw();

                if (callback && typeof (callback) === 'function') {
                    callback(val);
                }
            });

            $('#'+field+'-to-filter-input').on('change', function (){
                var column = dataTable.column('.datatable-row-'+field+'-to-filter');
                var val = $(this).val();

                dataTable.column(column).search( val ? val : '', true, false).draw();

                if (callback && typeof (callback) === 'function') {
                    callback(val);
                }
            });
        },

        initComplete: function (dataTable, field) {
            var data = dataTable.ajax.params();
            if(data.columns[dataTable.column('.datatable-row-'+field+'-from-filter').index()].search.value !== ''){
                $('#'+field+'-from-filter-input')
                    .val(data.columns[dataTable.column('.datatable-row-'+field+'-from-filter').index()].search.value);
            }
            if(data.columns[dataTable.column('.datatable-row-'+field+'-to-filter').index()].search.value !== ''){
                $('#'+field+'-to-filter-input')
                    .val(data.columns[dataTable.column('.datatable-row-'+field+'-to-filter').index()].search.value);
            }
        }
    },
    dateRangeFilterOnClick: {
        clickEventInit: function (dataTable, field, callback) {
            $('#click-event-' + field + '-filter-input').on('click',function () {
                let column_from = dataTable.column('.datatable-row-'+field+'-from-filter');
                let val_from = $('#' + field + '-from-filter-input').val();
                let column_to = dataTable.column('.datatable-row-'+field+'-to-filter');
                let val_to = $('#' + field + '-to-filter-input').val();

                dataTable.column(column_from).search( val_from ? val_from : '', true, false);
                dataTable.column(column_to).search( val_to ? val_to : '', true, false).draw();
                if (callback && typeof (callback) === 'function') {
                    callback(val_from, val_to);
                }

            });
        },

        initComplete: function (dataTable, field, callback) {
            let data = dataTable.ajax.params();
            if(data.columns[dataTable.column('.datatable-row-'+field+'-from-filter').index()].search.value !== ''){
                $('#'+field+'-from-filter-input')
                    .val(data.columns[dataTable.column('.datatable-row-'+field+'-from-filter').index()].search.value);
            }
            if(data.columns[dataTable.column('.datatable-row-'+field+'-to-filter').index()].search.value !== ''){
                $('#'+field+'-to-filter-input')
                    .val(data.columns[dataTable.column('.datatable-row-'+field+'-to-filter').index()].search.value);
            }

            if (callback && typeof (callback) === 'function') {
                callback($('#' + field + '-from-filter-input').val(), $('#' + field + '-to-filter-input').val());
            }
        }
    },
    inputFilter: {
        clickEventInit: function (dataTable, field, callback, regExp, defaultValue) {
            $('#'+field+'-filter-input').on('change', function (){
                var column = dataTable.column('.datatable-row-'+field+'-filter');
                var val = $(this).val();
                if(!val && defaultValue){
                    val = defaultValue;
                }
                if(regExp){
                    dataTable.column(column).search( val ? val : '', true, false).draw();
                }else {
                    dataTable.column(column).search( val ? val : '', false, false).draw();
                }
                //dataTable.column(column).search( val ? val : '', false, false ).draw();

                if (callback && typeof (callback) === 'function') {
                    callback(val);
                }

                if(hideColumn = dataTable.column('.datatable-row-' + field + '-hide-filter').index()) {
                    column = dataTable.column(hideColumn);
                    val !== '' ? column.visible(false) : column.visible(true);
                }
            });
        },

        initComplete: function (dataTable, field, regExp, defaultValue) {
            var data = dataTable.ajax.params();
            var val = data.columns[dataTable.column('.datatable-row-'+field+'-filter').index()].search.value;
            if(regExp){
                val = val.slice(1,-1);
            }

            if(val === '' && defaultValue){
                val = defaultValue;
            }
            if(val !== ''){
                $('#'+field+'-filter-input')
                    .val(val)
                    .parent().addClass('is-dirty');
            }
        }
    },

    selectFilter: {
        clickEventInit: function (dataTable, field, callback, regExp) {
            $('#'+field+'-filter-select').on('change', function (){
                var column = dataTable.column('.datatable-row-'+field+'-filter');
                var val = $(this).val();

                if(regExp){
                    dataTable.column(column).search( val ? val : '', true, false).draw();
                }else {
                    dataTable.column(column).search( val ? val : '', false, false).draw();
                }

                if (callback && typeof (callback) === 'function') {
                    callback(val);
                }

                if(hideColumn = dataTable.column('.datatable-row-' + field + '-hide-filter').index()) {
                    column = dataTable.column(hideColumn);
                    val !== '' ? column.visible(false) : column.visible(true);
                }
            });
        },

        initComplete: function (dataTable, field, callback) {
            var data = dataTable.ajax.params();
            var val = data.columns[dataTable.column('.datatable-row-'+field+'-filter').index()].search.value;
            if(val !== ''){
                $('#'+field+'-filter-select')
                    .val(val).trigger("chosen:updated")
                    .parent().addClass('is-dirty');
                if (callback && typeof (callback) === 'function') {
                    callback(val);
                }
            }
        }
    },

    detailsRow: {
        init: function (dataTable, showDetailsRow, clickOnRow) {
            $('body').on('click', !empty(clickOnRow) ? '#data-table > tbody > tr' : '#data-table > tbody > td.details-control', function () {
                var tr = $(this).closest('tr');

                if(tr.hasClass('details-tr')){
                    return;
                }

                var row = dataTable.row(tr);

                if (row.child.isShown()) {
                    $(tr.find('.details-control')).text('+').parent();
                    row.child.hide();
                    tr.removeClass('shown');
                }else {
                    $(tr.find('.details-control')).text('-');
                    row.child(showDetailsRow(row.data())).show();
                    tr.addClass('shown');
                    tr.next().addClass('details-tr');
                    dataTablesFunc.getWidth();
                }
            });
        },

        expandAll: function (flag, dataTable, showDetailsRow) {
            $('#data-table tbody td.details-control').each(function () {
                var tr = $(this).closest('tr');
                var row = dataTable.row(tr);

                if (flag && row.child.isShown()) {
                    $(this).text('+');
                    row.child.hide();
                    tr.removeClass('shown');
                }else if(!flag && !row.child.isShown()){
                    $(this).text('-');
                    row.child(showDetailsRow(row.data())).show();
                    tr.addClass('shown');
                    tr.next().addClass('details-tr');
                    dataTablesFunc.getWidth();
                }
            });
        },
    }
};

function showSavedDataStatusSnackbar(success, e) {
    if(success != 0){
        showSnackbarDefault(true);
        if(e){
            $(e).text('SAVE');
            setTimeout(function () {
                $(e).css({backgroundColor:'#ececec', color:'#000'});
            }, 200);
        }
    }else {
        showSnackbarDefault(false);
        if(e){
            $(e).text('SAVE');
            setTimeout(function () {
                $(e).css({backgroundColor:'#f44336', color:'#000'});
            }, 200);
        }
    }
}

function updateStatusIdRowDataTable(e, status_id, url2, url1) {
    if(!confirm('Are you sure?')){
        return;
    }

    var tr = $(e).closest('tr');
    var row = dataTable.row(tr);
    var data = row.data() ? row.data() : tempDataTable[$(e).data('id')];
    $.ajax({
        url: (url1 === undefined ? base_repository.getFullUrl() : url1)+'/'+data.id + (url2 === undefined ? '/update-status-id' : '/'+url2),
        method: "POST",
        data: {
            'status_id': status_id
        }
    }).done(function (response) {
        if(response != 0 && e){
            dataTable.ajax.reload();
        }
        showSnackbarDefault(response);
    }).fail(function () {
        showSnackbarDefault(false);
    });
}

function deleteRowDataTable(e, url2, url1) {
    if (confirm("Delete the record?")) {
        var tr = $(e).closest('tr');
        var row = dataTable.row(tr);
        var data = row.data() ? row.data() : tempDataTable[$(e).data('id')];
        $.ajax({
            url: (url1 === undefined ? base_repository.getFullUrl() : url1)+'/'+data.id + (url2 === undefined ? '/destroy' : '/'+url2),
            method: "POST"
        }).done(function (response) {
            if(response != 0 && e){
                $(e).parent().parent().hide();
            }
            hideDeleteRowButton();
            showSnackbarDefault(response);
        }).fail(function () {
            showSnackbarDefault(false);
        });
    }
}

function changeRowDataTable(e, name) {
    var tr = $(e).closest('tr');
    var row = dataTable.row(tr);
    if(!row.data()){
        tempDataTable[$(e).data('id')] = !tempDataTable[$(e).data('id')] ? {} : tempDataTable[$(e).data('id')];
        tempDataTable[$(e).data('id')][name] = $(e).hasClass('mdl-switch__input') ? ($(e).is(':checked') === true ? 1 : 0) : $(e).val();
    }else {
        row.data()[name] = $(e).hasClass('mdl-switch__input') ? ($(e).is(':checked') ? 1 : 0) : $(e).val();
    }
    $(e).parent().parent().parent().find('button.save-button').each(function () {
        $(this).css({color: '#00c108'});
    });
}

function hideDeleteRowButton() {
    $('.delete-row-button').each(function () {
        if(!$(this).hasClass('hidden-element')){
            $(this).show();
        }else {
            $(this).hide();
        }
    });
}
