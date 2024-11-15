@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="paginator-menu">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="link-previous">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="elselink-previous">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="link-next">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="elselink-next">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </nav>
@endif
