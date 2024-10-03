@if ($paginator->hasPages())
    <div class="dataTables_paginate paging_simple_numbers">
        <div class="pagination" role="navigation">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button class="mdl-button previous disabled" aria-disabled="true" disabled="disabled" aria-label="@lang('pagination.previous')">Previous</button>
            @else
                <a class="mdl-button previous" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">Previous</a>
            @endif

            @if($paginator->currentPage() > 3)
                <a class="mdl-button min-width-30" href="{{ $paginator->url(1) }}">1</a>
            @endif
            @if($paginator->currentPage() > 4)
                <a class="mdl-button disabled min-width-auto" aria-disabled="true" disabled="disabled">...</a>
            @endif
            @foreach(range(1, $paginator->lastPage()) as $page)
                @if($page >= $paginator->currentPage() - 2 && $page <= $paginator->currentPage() + 2)
                    @if ($page == $paginator->currentPage())
                        <button class="active mdl-button mdl-button--raised mdl-button--colored" aria-current="page">{{ $page }}</button>
                    @else
                        <a class="mdl-button min-width-30" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                    @endif
                @endif
            @endforeach
            @if($paginator->currentPage() < $paginator->lastPage() - 3)
                <a class="mdl-button disabled min-width-auto" aria-disabled="true" disabled="disabled">...</a>
            @endif
            @if($paginator->currentPage() < $paginator->lastPage() - 2)
                <a class="mdl-button min-width-30" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a class="mdl-button" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next</a>
            @else
                <button class="mdl-button disabled" aria-disabled="true" aria-label="@lang('pagination.next')" disabled="disabled">Next</button>
            @endif
        </div>
    </div>
@endif
