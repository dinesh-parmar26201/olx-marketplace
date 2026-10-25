@extends('layouts.app')

@section('title', $listing->title . ' - OLX Marketplace')

@section('content')
<div class="container py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('listings.category', $listing->category->slug) }}" class="text-decoration-none">{{ $listing->category->name }}</a></li>
            @if($listing->subcategory)
                <li class="breadcrumb-item"><a href="{{ route('listings.category', [$listing->category->slug, $listing->subcategory->slug]) }}" class="text-decoration-none">{{ $listing->subcategory->name }}</a></li>
            @endif
            <li class="breadcrumb-item active text-truncate" style="max-width: 300px;" aria-current="page">{{ $listing->title }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- LEFT COLUMN: PHOTOS & DESCRIPTION -->
        <div class="col-lg-8">
            <!-- Image Gallery Card -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4 bg-black">
                <!-- Main Featured Photo -->
                <div class="position-relative d-flex align-items-center justify-content-center" style="height: 480px; background-color: #0b1114;">
                    <img id="mainGalleryImage" src="{{ $listing->thumbnail_url }}" alt="{{ $listing->title }}" class="img-fluid" style="max-height: 480px; object-fit: contain;">
                    
                    @if($listing->featured)
                        <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 px-3 py-2 fw-bold text-uppercase">
                            <i class="fa-solid fa-bolt me-1"></i> Featured Ad
                        </span>
                    @endif

                    <span class="badge bg-dark bg-opacity-75 position-absolute top-0 end-0 m-3 px-3 py-2 text-uppercase fw-bold">
                        {{ $listing->type === 'service' ? 'Service' : 'Product' }}
                    </span>
                </div>

                <!-- Thumbnails Strip (if multiple images) -->
                @if($listing->images->count() > 1)
                    <div class="p-2 bg-dark d-flex gap-2 overflow-x-auto">
                        @foreach($listing->images as $img)
                            <div class="thumbnail-item flex-shrink-0 cursor-pointer" onclick="document.getElementById('mainGalleryImage').src = '{{ $img->image_url }}'" style="width: 80px; height: 60px; cursor: pointer; border-radius: 4px; overflow: hidden; border: 2px solid #4a5568;">
                                <img src="{{ $img->image_url }}" alt="Thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Ad Details & Specifications Card -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 bg-white">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <span class="badge bg-light text-dark border px-3 py-2 fw-bold text-uppercase">
                        <i class="fa-solid fa-layer-group me-1 text-primary"></i> {{ $listing->category->name }}
                        @if($listing->subcategory)
                            › {{ $listing->subcategory->name }}
                        @endif
                    </span>

                    <div class="text-muted small">
                        <i class="fa-regular fa-eye me-1"></i> {{ $listing->views_count }} views &nbsp;|&nbsp;
                        <i class="fa-regular fa-clock me-1"></i> Posted {{ $listing->created_at->diffForHumans() }}
                    </div>
                </div>

                <h1 class="h3 fw-bold text-dark mb-3">{{ $listing->title }}</h1>

                <!-- Mobile Only Price Display -->
                <div class="d-lg-none mb-3 p-3 bg-light rounded-3">
                    <div class="fs-2 fw-bold text-primary">{{ $listing->formatted_price }}</div>
                    <span class="badge bg-secondary">{{ ucfirst($listing->price_type) }}</span>
                </div>

                <!-- Key Specifications Table -->
                <h5 class="fw-bold text-dark mt-3 mb-3 border-bottom pb-2">Overview</h5>
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-4">
                        <div class="text-muted small">Ad Type</div>
                        <div class="fw-bold text-capitalize text-dark">{{ $listing->type }}</div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="text-muted small">Condition</div>
                        <div class="fw-bold text-capitalize text-dark">{{ $listing->condition === 'not_applicable' ? 'N/A (Service)' : $listing->condition }}</div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="text-muted small">Price Type</div>
                        <div class="fw-bold text-capitalize text-dark">{{ ucfirst($listing->price_type) }}</div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="text-muted small">City</div>
                        <div class="fw-bold text-dark">
                            <a href="{{ route('listings.city', $listing->city_slug) }}" class="text-decoration-none">
                                {{ $listing->city }}
                            </a>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="text-muted small">Locality / Area</div>
                        <div class="fw-bold text-dark">{{ $listing->area }}</div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="text-muted small">State & Country</div>
                        <div class="fw-bold text-dark">{{ $listing->state }}, {{ $listing->country }}</div>
                    </div>
                </div>

                <!-- Description -->
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Description</h5>
                <div class="text-dark" style="white-space: pre-line; line-height: 1.7; font-size: 0.98rem;">
                    {{ $listing->description }}
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: PRICE & SELLER DETAILS -->
        <div class="col-lg-4">
            <!-- Price Card -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 bg-white">
                <div class="text-muted small fw-semibold text-uppercase mb-1">Asking Price</div>
                <div class="d-flex align-items-baseline gap-2 mb-2">
                    <span class="display-6 fw-bold text-dark" style="color: var(--olx-teal) !important;">
                        {{ $listing->formatted_price }}
                    </span>
                    @if($listing->price_type === 'negotiable')
                        <span class="badge bg-success bg-opacity-10 text-success fw-bold">Negotiable</span>
                    @elseif($listing->price_type === 'fixed')
                        <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold">Fixed</span>
                    @endif
                </div>

                <div class="text-muted small mb-4">
                    <i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $listing->location_text }}
                </div>

                <!-- Contact Seller Phone Button with Click to Reveal -->
                @if(!$listing->hide_phone && $listing->contact_phone)
                    <div class="mb-3">
                        <div id="phoneHiddenBox">
                            <button type="button" class="btn btn-warning w-100 py-3 fw-bold text-dark fs-6 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" onclick="revealPhone()">
                                <i class="fa-solid fa-phone"></i>
                                <span>Show Phone Number</span>
                            </button>
                        </div>
                        <div id="phoneRevealedBox" class="d-none text-center p-3 bg-warning bg-opacity-10 border border-warning rounded-3">
                            <div class="small text-muted mb-1">Seller Phone:</div>
                            <a href="tel:{{ $listing->contact_phone }}" class="fs-5 fw-bold text-dark text-decoration-none">
                                <i class="fa-solid fa-phone me-1 text-success"></i> {{ $listing->contact_phone }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="alert alert-light border small text-muted text-center mb-3">
                        <i class="fa-solid fa-shield-halved me-1 text-primary"></i> Seller preferred not to disclose phone number.
                    </div>
                @endif

                <!-- Chat Button -->
                <button type="button" class="btn btn-outline-dark w-100 py-2 fw-bold rounded-3" onclick="alert('Chat system: Message sent to {{ $listing->user->name }}!')">
                    <i class="fa-regular fa-comment-dots me-2"></i> Chat with Seller
                </button>

                <!-- Edit / Delete (for the ad owner) -->
                @if(Auth::check() && Auth::id() === $listing->user_id)
                    <div class="mt-3 pt-3 border-top">
                        <div class="small text-muted mb-2 fw-semibold">You own this ad:</div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('listings.edit', $listing->id) }}" class="btn btn-sm btn-outline-primary flex-grow-1 fw-bold">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Edit Ad
                            </a>
                            <form action="{{ route('listings.destroy', $listing->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this listing?');" class="flex-grow-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100 fw-bold">
                                    <i class="fa-solid fa-trash me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Seller Profile Card -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 bg-white">
                <h6 class="fw-bold text-dark mb-3">Seller Details</h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 54px; height: 54px;">
                        {{ strtoupper(substr($listing->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-bold fs-6 text-dark">{{ $listing->user->name }}</div>
                        <small class="text-muted d-block">
                            <i class="fa-solid fa-circle-check text-success me-1"></i> Verified User
                        </small>
                        <small class="text-muted">Member since {{ $listing->user->created_at->format('M Y') }}</small>
                    </div>
                </div>

                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Seller Location:</span>
                        <strong class="text-dark">{{ $listing->city }}</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Total Ads Posted:</span>
                        <strong class="text-dark">{{ $listing->user->listings()->count() }} ads</strong>
                    </div>
                </div>
            </div>

            <!-- OLX Safety Tips -->
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="fa-solid fa-shield-halved text-success me-2"></i> Safety Tips
                </h6>
                <ul class="list-unstyled small text-muted mb-0 d-flex flex-direction-column gap-2">
                    <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Meet seller at a safe public place.</li>
                    <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Check the item thoroughly before making payment.</li>
                    <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Beware of unrealistic offers and advance payment requests.</li>
                    <li><i class="fa-solid fa-check text-success me-2"></i> Pay only after receiving the product.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- RELATED LISTINGS -->
    @if(isset($relatedListings) && $relatedListings->count() > 0)
        <div class="mt-5 pt-4 border-top">
            <h4 class="fw-bold text-dark mb-4">Similar Listings You Might Like</h4>
            <div class="row g-3">
                @foreach($relatedListings as $rel)
                    @include('listings.card', ['item' => $rel])
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function revealPhone() {
        document.getElementById('phoneHiddenBox').classList.add('d-none');
        document.getElementById('phoneRevealedBox').classList.remove('d-none');
    }
</script>
@endpush
@endsection
