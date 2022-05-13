@if ($paginator->hasPages())
    <nav class="pagination-links-container">
        <ul class="pagination-list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="pagination-link disabled"><span>«</span></li>
            @else
                <li class="pagination-link"><a href="{{ $paginator->previousPageUrl() }}" rel="prev">«</a></li>
            @endif

            {{-- First and last page link --}}
            @if($paginator->currentPage() > 3)
                <li class="pagination-link"><a href="{{ $paginator->url(1) }}">1</a></li>
            @endif
            @if($paginator->currentPage() > 4)
                <li class="pagination-link"><span>...</span></li>
            @endif
            @foreach(range(1, $paginator->lastPage()) as $i)
                @if($i >= $paginator->currentPage() - 2 && $i <= $paginator->currentPage() + 2)
                    @if ($i == $paginator->currentPage())
                        <li class="pagination-link active"><span>{{ $i }}</span></li>
                    @else
                        <li class="pagination-link"><a href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endif
            @endforeach
            @if($paginator->currentPage() < $paginator->lastPage() - 3)
                <li class="pagination-link"><span>...</span></li>
            @endif
            @if($paginator->currentPage() < $paginator->lastPage() - 2)
                <li class="pagination-link"><a href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="pagination-link"><a href="{{ $paginator->nextPageUrl() }}" rel="next">»</a></li>
            @else
                <li class="pagination-link disabled"><span>»</span></li>
            @endif
        </ul>
    </nav>
@endif
