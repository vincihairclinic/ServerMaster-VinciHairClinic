@push('css_2')
    <link rel="stylesheet" href="{{ asset('css/plugins/bootstrap/dataTables.bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/bootstrap/data-table.css')}}">
@endpush
@push('css_1')
    <link rel="stylesheet" href="{{ asset('css/plugins/jquery-te-1.4.0.css')}}">
    <link rel="stylesheet" href="{{asset('css/plugins/bootstrap/bootstrap-chosen.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/bootstrap/chosen.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/form.css')}}">
    <link rel="stylesheet" href="{{ asset('css/plugins/bootstrap/bootstrap-slider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/bootstrap-datepicker.min.css') }}">
@endpush
@push('js')
    <script src="{{ asset('js/plugins/autosize.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/moment.min.js') }}"></script>
    <script src="{{ asset('js/base/chosen.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery.tmpl.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-te-1.4.0.min.js') }}"></script>
    <script src="{{ asset('js/plugins/HtmlSanitizer.js') }}"></script>
    <script src="{{ asset('js/plugins/jqteClear.js') }}"></script>
    <script src="{{ asset('js/base/form.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap/bootstrap-slider.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-datepicker.min.js') }}"></script>


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
            rule:false,
            status:true,
            title: false,
            b: true,
            i: false,
            remove: false,
            u: false,
            formats:false,
            // format:false,
            source:false,
            // formats: [
            //     ["p","p"],
                //["h1","Header 1"],
                // ["h2","h2"],
                //["h3","Header 3"],
                //["h4","Header 4"],
                //["h5","Header 5"],
                //["h6","Header 6"],
            // ]
        });

        // jqteClear.init();
    </script>
    <script src="{{ asset('js/base/datatable.js') }}"></script>
@endpush