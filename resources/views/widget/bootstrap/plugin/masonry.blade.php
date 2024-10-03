@php
    $id = !empty($id) ? $id : 'masonry_grid' ;
    $itemSelector = !empty($itemClass) ? $itemClass : '.masonry-grid-item' ;
    $itemWidthSelector = !empty($widthClass) ? $widthClass : '.masonry-grid-item' ;
@endphp
    <div class="masonry-grid w-100" id="{{ $id }}">

    </div>
@push('js')
    <script src="{{asset('/js/plugins/bootstrap/imagesloaded.min.js')}}"></script>
    <script src="{{asset('/js/plugins/bootstrap/masonry.min.js')}}"></script>
    <script>

        let grid = $('#{{ $id }}').masonry({
            itemSelector: '{{ $itemSelector }}',
            percentPosition: true,
            columnWidth: '{{ $itemWidthSelector  }}',
        });

        function ImageLoadMasonryGrid(){
            grid.masonry('layout');
            grid.masonry('reloadItems');
        }
    </script>
@endpush
