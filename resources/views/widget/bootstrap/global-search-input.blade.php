@php
    $searchIn = !isset($searchIn) ? 'datatable' : $searchIn;
@endphp

    <div class="search_field">
        <div class="global-search-input-container w-100 d-block position-relative">
            <input class="w-100 py-1 pl-5 pr-3 global-search-input bg-white" type="search" id="global-search-input" placeholder="{{ $placeholder ?? 'Search' }}">
        </div>
    </div>

@push('css_1')
    @if(!\App\W::$isAmp) <style> @endif
        .global-search-input-container:before{
            content: '';
            background-color: #999;
            position: absolute;
            top: 7px;
            left: 15px;
            width: 25px;
            height: 25px;
            display: block;
            z-index: 5;
            -webkit-mask-position: center;
            -webkit-mask-size: contain;
            -webkit-mask-image: url('/images/icon/search_white.svg');
        }
        .global-search-input {
            outline: none;
            border-radius: 40px;
            color: #86898E;
            font-size: 16px;
            height: 40px;
            background: transparent;
            border: 1px solid var(--dashboard-search-border-color);
            /*box-shadow: 0 0 2px 0 #fff;*/
            font-weight: 400;
            position: relative;
        }
        .global-search-input:active,.global-search-input:focus {
            box-shadow: 0 0 3px 1px #fff;
        }
        /*.global-search-input-container:after {*/
        /*    content: '';*/
        /*    display: block;*/
        /*    position: absolute;*/
        /*    top: 0;*/
        /*    bottom: 0;*/
        /*    left: 0;*/
        /*    right: 0;*/
        /*    border-radius: 40px;*/
        /*    border: 1px solid #64C5B1;*/
        /*    !*border: 2px solid #000000;*!*/
        /*    box-shadow: 0 0 2px 0 #fff;*/
        /*    !*box-shadow: 0 0 0 1px #fff;*!*/
        /*}*/
        .global-search-input::-webkit-search-cancel-button{
            -webkit-mask-image: url('/images/icon/close_bold_gray.svg');
            -webkit-mask-repeat: no-repeat;
            -webkit-mask-position: center;
            -webkit-mask-size: contain;
            background-color: var(--dashboard-search-close-icon-color);
            top: 50%;
            right: 10px;
            width: 22px;
            height: 22px;
            appearance: none;
            cursor: pointer;
            font-size: 20px;
        }
    @if(!\App\W::$isAmp) </style> @endif
@endpush

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
                    // var val = $.fn.dataTable.util.escapeRegex($(this).val());
                    var val = $(this).val();
                    dataTable.search(val ? val : '', true, true).draw();
                }).on('search', function () {
                    // var val = $.fn.dataTable.util.escapeRegex($(this).val());
                    var val = $(this).val();
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