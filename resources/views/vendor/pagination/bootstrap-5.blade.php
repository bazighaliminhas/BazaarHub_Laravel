@if ($paginator->hasPages())
<nav aria-label="Product pagination" class="my-5">
    <div class="d-flex flex-column align-items-center gap-3">

        {{-- Page Info --}}
        <p class="text-muted small mb-0">
            Showing
            <strong style="color:#FF6B35;">{{ $paginator->firstItem() }}</strong>
            to
            <strong style="color:#FF6B35;">{{ $paginator->lastItem() }}</strong>
            of
            <strong style="color:#FF6B35;">{{ $paginator->total() }}</strong>
            results
        </p>

        {{-- Pagination Buttons --}}
        <ul class="pagination mb-0" style="gap: 6px;">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link rounded-pill px-4"
                          style="border:2px solid #eee; color:#ccc; background:white; font-weight:600;">
                        <i class="bi bi-chevron-left me-1"></i> Prev
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link rounded-pill px-4"
                       href="{{ $paginator->previousPageUrl() }}"
                       style="border:2px solid #FF6B35; color:#FF6B35; background:white; font-weight:600; transition:all 0.2s;"
                       onmouseover="this.style.background='#FF6B35'; this.style.color='white';"
                       onmouseout="this.style.background='white'; this.style.color='#FF6B35';">
                        <i class="bi bi-chevron-left me-1"></i> Prev
                    </a>
                </li>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link rounded-pill px-3"
                              style="border:2px solid #eee; color:#999; background:white;">
                            ...
                        </span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link rounded-pill px-3 fw-bold"
                                      style="background:linear-gradient(135deg,#FF6B35,#FF8C42);
                                             border:none;
                                             color:white;
                                             box-shadow:0 4px 15px rgba(255,107,53,0.4);
                                             min-width:42px;
                                             text-align:center;">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link rounded-pill px-3 fw-semibold"
                                   href="{{ $url }}"
                                   style="border:2px solid #eee;
                                          color:#2C3E50;
                                          background:white;
                                          min-width:42px;
                                          text-align:center;
                                          transition:all 0.2s;"
                                   onmouseover="this.style.borderColor='#FF6B35'; this.style.color='#FF6B35'; this.style.background='#fff3ee';"
                                   onmouseout="this.style.borderColor='#eee'; this.style.color='#2C3E50'; this.style.background='white';">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link rounded-pill px-4"
                       href="{{ $paginator->nextPageUrl() }}"
                       style="border:2px solid #FF6B35; color:#FF6B35; background:white; font-weight:600; transition:all 0.2s;"
                       onmouseover="this.style.background='#FF6B35'; this.style.color='white';"
                       onmouseout="this.style.background='white'; this.style.color='#FF6B35';">
                        Next <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link rounded-pill px-4"
                          style="border:2px solid #eee; color:#ccc; background:white; font-weight:600;">
                        Next <i class="bi bi-chevron-right ms-1"></i>
                    </span>
                </li>
            @endif

        </ul>
    </div>
</nav>
@endif
