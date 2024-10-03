@push('css_2')
    <link rel="stylesheet" href="{{ asset('css/plugins/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/data-table.css')}}">
@endpush
@push('css_1')
    <link rel="stylesheet" href="{{asset('css/plugins/chosen.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/chosen.css')}}">
@endpush
@push('js')
    <script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/moment.min.js') }}"></script>
    <script src="{{ asset('js/base/chosen.js') }}"></script>
@endpush