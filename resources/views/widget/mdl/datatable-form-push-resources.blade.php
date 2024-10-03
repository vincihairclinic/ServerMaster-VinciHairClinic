@push('css_2')
    <link rel="stylesheet" href="{{ asset('css/plugins/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/data-table.css')}}">
@endpush
@push('css_1')
    <link rel="stylesheet" href="{{ asset('css/plugins/jquery-te-1.4.0.css')}}">
    <link rel="stylesheet" href="{{asset('css/plugins/chosen.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/chosen.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/form.css')}}">
@endpush
@push('js')
    <script src="{{ asset('js/plugins/autosize.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/moment.min.js') }}"></script>
    <script src="{{ asset('js/base/chosen.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery.tmpl.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-te-1.4.0.min.js') }}"></script>
    <script src="{{ asset('js/plugins/HtmlSanitizer.js') }}"></script>
    <script src="{{ asset('js/plugins/jqteClear.js') }}"></script>
    <script src="{{ asset('js/base/form.js') }}"></script>

    <script>
        $('.jqte_textarea').jqte({
            placeholder: false,
            fsize: false,
            link: false,
            unlink: false,
            strike: false,
            ul: false,
            ol: false,
            color: false,
            sub: false,
            sup: false,
            outdent:false,
            indent:false,
            left:false,
            center:false,
            right:false,
            rule:true,
            status:true,
            title: false,
            b: true,
            i: true,
            remove: false,
            u: false,
            //formats:false,
            formats: [
                ["p","p"],
                //["h1","Header 1"],
                ["h2","h2"],
                //["h3","Header 3"],
                //["h4","Header 4"],
                //["h5","Header 5"],
                //["h6","Header 6"],
            ]
        });

        jqteClear.init();
    </script>
    <script src="{{ asset('js/base/datatable.js') }}"></script>
@endpush