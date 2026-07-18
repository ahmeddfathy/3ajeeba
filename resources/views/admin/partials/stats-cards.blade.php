@props(['stats' => []])

@if(count($stats))
<div class="ad-stats">
    @foreach($stats as $stat)
        <div class="ad-stat">
            <div class="ad-stat__icon ad-stat__icon--{{ $stat['tone'] ?? 'brand' }}">
                <i class="bi {{ $stat['icon'] }}" aria-hidden="true"></i>
            </div>
            <div>
                <div class="ad-stat__value">{{ $stat['value'] }}</div>
                <div class="ad-stat__label">{{ $stat['label'] }}</div>
            </div>
        </div>
    @endforeach
</div>
@endif
