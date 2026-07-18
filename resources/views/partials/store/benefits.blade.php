@php
    $benefits = config('store.benefits', []);
@endphp

<section class="store-section store-section--tight" aria-labelledby="benefits-title">
    <h2 id="benefits-title" class="sr-only">مزايا التسوق من عجيبة</h2>
    <div class="store-benefits">
        @foreach ($benefits as $benefit)
            <div class="store-benefits__item">
                <span class="store-benefits__icon" aria-hidden="true">
                    @switch($benefit['icon'])
                        @case('support')
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M4 12a8 8 0 0 1 16 0v4a2 2 0 0 1-2 2h-2v-5h4M4 13h4v5H6a2 2 0 0 1-2-2v-3z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                            </svg>
                            @break
                        @case('gift')
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M4 10h16v10H4V10zM3 6h18v4H3V6zM12 6V3M8.5 6a2 2 0 1 1 0-4C10 2 12 6 12 6s2-4 3.5-4a2 2 0 1 1 0 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            @break
                        @case('shield')
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M12 3l8 3v6c0 5-3.5 8.5-8 9-4.5-.5-8-4-8-9V6l8-3z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                                <path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            @break
                        @default
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M3 7h13v10H3zM16 10h3l2 3v4h-5V10z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                                <circle cx="7.5" cy="18.5" r="1.5" fill="currentColor"/>
                                <circle cx="17.5" cy="18.5" r="1.5" fill="currentColor"/>
                            </svg>
                    @endswitch
                </span>
                <div>
                    <h3>{{ $benefit['title'] }}</h3>
                    <p>{{ $benefit['text'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
