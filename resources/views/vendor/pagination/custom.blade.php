@if ($paginator->hasPages())
    <nav class="custom-pagination" role="navigation" aria-label="Pagination Navigation" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; font-size: 0.85rem; width: 100%;">
        <div style="color: #64748b; font-size: 0.825rem;">
            Menampilkan <strong>{{ $paginator->firstItem() }}</strong> &ndash; <strong>{{ $paginator->lastItem() }}</strong> dari <strong>{{ number_format($paginator->total()) }}</strong> data
        </div>

        <ul style="display: inline-flex; list-style: none; padding: 0; margin: 0; border: 1px solid #cbd5e1; border-radius: 4px; overflow: hidden; background: white; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li style="padding: 6px 12px; color: #cbd5e1; background: #f8fafc; border-right: 1px solid #cbd5e1; cursor: not-allowed;">
                    <i class="fa-solid fa-chevron-left" style="font-size: 0.75rem;"></i>
                </li>
            @else
                <li style="border-right: 1px solid #cbd5e1;">
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="display: block; padding: 6px 12px; color: #0288d1; text-decoration: none;" title="Halaman Sebelumnya">
                        <i class="fa-solid fa-chevron-left" style="font-size: 0.75rem;"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li style="padding: 6px 12px; color: #94a3b8; background: #f8fafc; border-right: 1px solid #cbd5e1;">
                        {{ $element }}
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li style="padding: 6px 12px; background: #0288d1; color: white; font-weight: 700; border-right: 1px solid #cbd5e1;">
                                {{ $page }}
                            </li>
                        @else
                            <li style="border-right: 1px solid #cbd5e1;">
                                <a href="{{ $url }}" style="display: block; padding: 6px 12px; color: #334155; text-decoration: none;">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="display: block; padding: 6px 12px; color: #0288d1; text-decoration: none;" title="Halaman Berikutnya">
                        <i class="fa-solid fa-chevron-right" style="font-size: 0.75rem;"></i>
                    </a>
                </li>
            @else
                <li style="padding: 6px 12px; color: #cbd5e1; background: #f8fafc; cursor: not-allowed;">
                    <i class="fa-solid fa-chevron-right" style="font-size: 0.75rem;"></i>
                </li>
            @endif
        </ul>
    </nav>
@endif
