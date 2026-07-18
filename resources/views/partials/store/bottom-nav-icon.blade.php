@switch($icon)
    @case('home')
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4 10.5L12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1v-9.5z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
        </svg>
        @break
    @case('grid')
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <rect x="4" y="4" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.6"/>
            <rect x="13" y="4" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.6"/>
            <rect x="4" y="13" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.6"/>
            <rect x="13" y="13" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.6"/>
        </svg>
        @break
    @case('bag')
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M6 8h12l-1 12H7L6 8z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="M9 8V7a3 3 0 0 1 6 0v1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
        @break
    @default
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M7 8h10l-1 11H8L7 8z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="M9 8a3 3 0 0 1 6 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
@endswitch
