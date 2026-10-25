@extends('layouts.app')

@section('title', $pageTitle . ' - OLX Marketplace')

@section('content')
<div class="container py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('listings.index') }}" class="text-decoration-none">Cities</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $cityName }}</li>
        </ol>
    </nav>

    <!-- City Hero Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 city-hero-card">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center shadow-sm" style="width: 64px; height: 64px; font-size: 1.8rem;">
                        <i class="fa-solid fa-city"></i>
                    </div>
                </div>
                <div class="col">
                    <span class="badge bg-warning text-dark text-uppercase px-2 py-1 fw-bold small">City Listings</span>
                    <h1 class="h2 fw-bold text-white mt-1 mb-1">
                        {{ $cityName }} Marketplace
                    </h1>
                    <p class="text-white-50 small mb-0">
                        Buy and sell used cars, phones, flats, and discover local professional services across {{ $cityName }}.
                    </p>
                </div>
                <div class="col-auto d-none d-md-block text-end">
                    <div class="city-stat-box">
                        <div class="stat-num">{{ $listings->total() }}</div>
                        <div class="stat-label">Active Ads in {{ $cityName }}</div>
                    </div>
                </div>
            </div>

            <!-- City Categories Shortcuts (Links directly to City + Category combination!) -->
            <div class="mt-4 pt-3 border-top border-white border-opacity-20">
                <div class="small fw-semibold text-white-50 mb-2">Browse by category in {{ $cityName }}:</div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('listings.city', $citySlug) }}" class="city-cat-pill {{ !request('category') ? 'active' : '' }}">
                        <i class="fa-solid fa-shapes"></i> All in {{ $cityName }}
                    </a>
                    @foreach($categories as $catItem)
                        @php
                            $catCount = $cityCategoryCounts[$catItem->id] ?? 0;
                        @endphp
                        <a href="{{ route('listings.city-category', ['city_slug' => $citySlug, 'category_slug' => $catItem->slug]) }}" class="city-cat-pill">
                            <i class="fa-solid {{ $catItem->icon }}"></i>
                            {{ $catItem->name }}
                            @if($catCount > 0)
                                <span class="pill-badge">{{ $catCount }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- Sidebar Filter -->
        <div class="col-lg-3">
            @include('listings.sidebar-filter')
        </div>

        <!-- Listings Column -->
        <div class="col-lg-9">
            <!-- Results Header & Sort Controls -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <div>
                    <h2 class="h5 fw-bold text-dark mb-1">
                        All Deals in {{ $cityName }}
                    </h2>
                    <small class="text-muted">Showing {{ $listings->firstItem() ?? 0 }}-{{ $listings->lastItem() ?? 0 }} of {{ $listings->total() }} results</small>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <label class="small text-muted text-nowrap fw-semibold">Sort By:</label>
                    <select class="form-select form-select-sm" style="width: 170px;" onchange="location = this.value;">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" {{ request('sort') == 'latest' || !request('sort') ? 'selected' : '' }}>Newly Listed</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </div>
            </div>

            <!-- Listings Grid -->
            @if($listings->count() > 0)
                <div class="row g-3">
                    @foreach($listings as $item)
                        @include('listings.card', ['item' => $item])
                    @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $listings->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5 bg-white rounded-3 border">
                    <div class="fs-1 text-muted mb-3"><i class="fa-solid fa-location-crosshairs"></i></div>
                    <h5 class="fw-bold text-dark">No Ads in {{ $cityName }} Yet</h5>
                    <p class="text-muted small">Be the first seller to post an ad in {{ $cityName }}!</p>
                    <a href="{{ route('listings.create') }}" class="btn btn-warning btn-sm px-4 rounded-pill fw-bold text-dark">
                        <i class="fa-solid fa-plus me-1"></i> Post Free Ad in {{ $cityName }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
