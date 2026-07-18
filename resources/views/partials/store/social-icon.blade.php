@switch($name)
    @case('facebook')
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M14 8h2.5V5.5H14c-1.9 0-3.5 1.6-3.5 3.5v2H8.5V14H10.5v6h3v-6H16l.5-3h-3V9c0-.6.4-1 1-1z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
            <rect x="3.5" y="3.5" width="17" height="17" rx="4" stroke="currentColor" stroke-width="1.5"/>
        </svg>
        @break
    @case('instagram')
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <rect x="3.5" y="3.5" width="17" height="17" rx="5" stroke="currentColor" stroke-width="1.5"/>
            <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.5"/>
            <circle cx="17.2" cy="6.8" r="1" fill="currentColor"/>
        </svg>
        @break
    @case('snapchat')
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 3c3.2 0 5.5 2.2 5.5 5.4 0 1.8-.5 3.1-.5 4.4 0 .7.8 1.2 1.5 1.6.5.3.8.9.4 1.4-.7.9-2.3.8-3.3 1.4-.7.4-.8 1.4-1.7 1.8-.6.3-1.3.3-1.9.3s-1.3 0-1.9-.3c-.9-.4-1-1.4-1.7-1.8-1-.6-2.6-.5-3.3-1.4-.4-.5-.1-1.1.4-1.4.7-.4 1.5-.9 1.5-1.6 0-1.3-.5-2.6-.5-4.4C6.5 5.2 8.8 3 12 3z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
        </svg>
        @break
    @case('tiktok')
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M14 4v9.2a3.8 3.8 0 1 1-3.2-3.75V12a1.6 1.6 0 1 0 1.2 1.55V4h2zM14 4c.6 2.4 2.2 4 4.5 4.4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break
    @default
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 20c4-5 6-8 6-11a4 4 0 1 1 8 0c0 5-4 8-8 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M9.5 13.5c1.5.8 3.2 1.2 5 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
@endswitch
