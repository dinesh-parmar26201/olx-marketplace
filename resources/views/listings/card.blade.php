<div class="col-6 col-md-4 col-lg-3">
    <a href="{{ route('listings.show', $item->slug) }}" class="ad-card">
        <div class="ad-card-img-wrap">
            <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="ad-card-img" loading="lazy">
            
            @if($item->featured)
                <span class="ad-badge-featured"><i class="fa-solid fa-bolt me-1"></i> Featured</span>
            @endif

            <span class="ad-badge-type">
                {{ $item->type === 'service' ? 'Service' : 'Product' }}
            </span>
        </div>

        <div class="ad-card-body">
            <div class="ad-card-price">{{ $item->formatted_price }}</div>
            <h3 class="ad-card-title" title="{{ $item->title }}">{{ $item->title }}</h3>
            
            <div class="ad-card-meta">
                <span class="text-truncate me-2" title="{{ $item->location_text }}">
                    <i class="fa-solid fa-location-dot me-1 text-muted"></i> {{ $item->location_text }}
                </span>
                <span class="text-nowrap">{{ $item->created_at->diffForHumans(null, true) }}</span>
            </div>
        </div>
    </a>
</div>
