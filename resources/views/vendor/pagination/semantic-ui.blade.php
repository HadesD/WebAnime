@if ($paginator->hasPages())
    <div class="ui hidden divider"></div>
    <div class="ui pagination menu">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <div class="disabled item">
                <i class="ui chevron left icon"></i>
            </div>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="item" rel="prev">
                <i class="ui chevron left icon"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <div class="disabled item">{{ $element }}</div>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a class="active item">{{ $page }}</a>
                    @else
                        <a href="{{ $url }}" class="item">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="item" rel="next">
                <i class="ui chevron right icon"></i>
            </a>
        @else
            <div class="disabled item">
                <i class="ui chevron right icon"></i>
            </div>
        @endif
    </div>
@endif
