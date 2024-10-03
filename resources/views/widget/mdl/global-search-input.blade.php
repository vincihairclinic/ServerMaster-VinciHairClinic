@php
    $searchIn = !isset($searchIn) ? 'datatable' : $searchIn;
@endphp

    <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
        <label class="mdl-button mdl-js-button mdl-button--icon" for="global-search-input">
            <i class="material-icons">search</i>
        </label>
        <div class="mdl-textfield__expandable-holder">
            <input class="mdl-textfield__input form-control input-sm" type="search" id="global-search-input" style="color: #fff !important;">
            <label class="mdl-textfield__label" for="sample-expandable">Expandable Input</label>
        </div>
    </div>


@push('js')
<script>
    $(function () {
        @if($searchIn == 'jstree')
            $('#global-search-input').change(function () {
                jstreeGlobalSearch(this);
            }).on('search', function () {
                jstreeGlobalSearch(this);
            });
        @else
            $('#global-search-input').change(function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                dataTable.search(val ? val : '', true, true).draw();
            }).on('search', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                dataTable.search(val ? val : '', true, true).draw();
            });
        @endif
    });

    function jstreeGlobalSearch(e) {
        var val = $(e).val();
        if(val && val != ''){
            $('#jstree').jstree('search', val);
            $("#jstree").jstree("open_all");
        }else {
            $('#jstree').jstree('clear_search');
        }
    }
</script>
@endpush