@extends('layouts.app')

@section('title', ($pageTitle ?? 'Browse Listings') . ' - OLX Marketplace')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">All Listings</li>
            @if(request('q'))
                <li class="breadcrumb-item active">Search: "{{ request('q') }}"</li>
            @endif
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Sidebar Filter -->
        <div class="col-lg-3">
            @include('listings.sidebar-filter')
        </div>

        <!-- Main Listings Content -->
        <div class="col-lg-9">
            <!-- Results Header & Sort Controls -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">
                        {{ $pageTitle ?? 'Marketplace Listings' }}
                    </h1>
                    <small class="text-muted">Showing {{ $listings->firstItem() ?? 0 }}-{{ $listings->lastItem() ?? 0 }} of {{ $listings->total() }} results</small>
                </div>

                <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                    <label class="small text-muted text-nowrap fw-semibold">Sort By:</label>
                    <select class="form-select form-select-sm" style="width: 170px;" onchange="location = this.value;">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" {{ request('sort') == 'latest' || !request('sort') ? 'selected' : '' }}>Newly Listed</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                    </select>
                </div>
            </div>

            <!-- Active Filter Badges -->
            @if(request('q') || request('type') || request('city') || request('min_price') || request('max_price') || request('condition'))
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="small text-muted">Applied Filters:</span>
                    @if(request('q'))
                        <span class="badge bg-light text-dark border">Search: "{{ request('q') }}"</span>
                    @endif
                    @if(request('type'))
                        <span class="badge bg-light text-dark border">Type: {{ ucfirst(request('type')) }}</span>
                    @endif
                    @if(request('city'))
                        <span class="badge bg-light text-dark border">City: {{ ucfirst(request('city')) }}</span>
                    @endif
                    @if(request('min_price') || request('max_price'))
                        <span class="badge bg-light text-dark border">Price: ₹{{ request('min_price', 0) }} - ₹{{ request('max_price', 'Any') }}</span>
                    @endif
                    <a href="{{ route('listings.index') }}" class="btn btn-sm btn-link text-danger p-0 text-decoration-none ms-1">Reset All</a>
                </div>
            @endif

            <!-- Listings Grid -->
            @if($listings->count() > 0)
                <div class="row g-3">
                    @foreach($listings as $item)
                        @include('listings.card', ['item' => $item])
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $listings->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5 bg-white rounded-3 border">
                    <div class="fs-1 text-muted mb-3"><i class="fa-solid fa-box-open"></i></div>
                    <h5 class="fw-bold text-dark">No Listings Found</h5>
                    <p class="text-muted small">We couldn't find any ad matching your filters. Try adjusting your search query or price range.</p>
                    <a href="{{ route('listings.index') }}" class="btn btn-primary btn-sm px-3 rounded-pill fw-bold">
                        Browse All Ads
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
