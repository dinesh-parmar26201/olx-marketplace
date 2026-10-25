@extends('layouts.app')

@section('title', 'OLX Marketplace - Buy and Sell Anything in India')

@section('content')

    <!-- HERO SECTION -->
    <section class="hero-banner">
        <div class="container py-3">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-3">
                        <i class="fa-solid fa-fire me-1"></i> India's #1 Classifieds & Marketplace
                    </span>
                    <h1 class="display-5 fw-extrabold text-white mb-3">
                        Buy, Sell & Find Anything Near You
                    </h1>
                    <p class="lead text-light opacity-90 mb-4">
                        Discover thousands of verified products & professional services from trusted sellers in your city.
                    </p>

                    <!-- Quick City Pills -->
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="text-white-50 small fw-semibold me-1">Popular Cities:</span>
                        @foreach($popularCities->take(5) as $popCity)
                            <a href="{{ route('listings.city', $popCity->city_slug) }}" class="badge bg-white bg-opacity-25 text-white text-decoration-none px-3 py-2 rounded-pill hover-shadow">
                                <i class="fa-solid fa-location-dot me-1 text-warning"></i> {{ $popCity->city }} ({{ $popCity->count }})
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-5 text-center d-none d-lg-block">
                    <div class="hero-snapshot-container p-4 text-start">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="text-white fw-bold mb-0">
                                <i class="fa-solid fa-chart-pie text-warning me-2"></i> Marketplace Snapshot
                            </h5>
                            <span class="badge bg-warning text-dark fw-bold px-2 py-1 small">
                                <i class="fa-solid fa-bolt me-1"></i> Live
                            </span>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="hero-stat-card">
                                    <div class="hero-stat-num text-warning">{{ $stats['total_listings'] }}+</div>
                                    <span class="hero-stat-label">Active Ads</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="hero-stat-card">
                                    <div class="hero-stat-num" style="color: var(--olx-cyan);">{{ $stats['total_cities'] }}+</div>
                                    <span class="hero-stat-label">Cities Covered</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="hero-stat-card">
                                    <div class="hero-stat-num text-white">{{ $stats['total_products'] }}</div>
                                    <span class="hero-stat-label">Products Listed</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="hero-stat-card">
                                    <div class="hero-stat-num text-white">{{ $stats['total_services'] }}</div>
                                    <span class="hero-stat-label">Services Listed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- EXPLORE POPULAR CATEGORIES -->
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 fw-bold text-dark mb-0">
                    <i class="fa-solid fa-shapes text-warning me-2"></i> All Categories
                </h2>
                <a href="{{ route('listings.index') }}" class="text-decoration-none fw-bold text-primary small">
                    View All Ads <i class="fa-solid fa-chevron-right ms-1"></i>
                </a>
            </div>

            <div class="row g-3">
                @foreach($categories as $cat)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <a href="{{ route('listings.category', $cat->slug) }}" class="cat-icon-card">
                            <div class="cat-icon-circle">
                                <i class="fa-solid {{ $cat->icon ?? 'fa-tag' }}"></i>
                            </div>
                            <span class="name">{{ $cat->name }}</span>
                            <small class="text-muted" style="font-size: 0.72rem;">{{ $cat->subcategories->count() }} subcategories</small>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FEATURED LISTINGS -->
    @if($featuredListings->count() > 0)
        <section class="pb-5">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="badge bg-warning text-dark text-uppercase px-2 py-1 fw-bold small">Handpicked</span>
                        <h2 class="h4 fw-bold text-dark mt-1 mb-0">Featured Ads</h2>
                    </div>
                    <a href="{{ route('listings.index', ['featured' => 1]) }}" class="text-decoration-none fw-bold text-primary small">
                        See All Featured <i class="fa-solid fa-chevron-right ms-1"></i>
                    </a>
                </div>

                <div class="row g-3">
                    @foreach($featuredListings as $item)
                        @include('listings.card', ['item' => $item])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- FRESH RECOMMENDATIONS -->
    <section class="pb-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 fw-bold text-dark mb-0">
                    Fresh Recommendations
                </h2>
                <a href="{{ route('listings.index') }}" class="text-decoration-none fw-bold text-primary small">
                    Browse All <i class="fa-solid fa-chevron-right ms-1"></i>
                </a>
            </div>

            <div class="row g-3">
                @foreach($recentListings as $item)
                    @include('listings.card', ['item' => $item])
                @endforeach
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('listings.index') }}" class="btn btn-outline-dark px-4 py-2 fw-bold rounded-pill">
                    Load More Listings <i class="fa-solid fa-arrow-down ms-1"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- EXPLORE BY CITY (CITY-WISE LISTING ENTRY POINTS) -->
    <section class="py-5 bg-white border-top border-bottom">
        <div class="container">
            <div class="text-center max-w-xl mx-auto mb-4">
                <h2 class="h4 fw-bold text-dark">Find Used Goods & Services in Top Cities</h2>
                <p class="text-muted small">Select your city to browse hyper-local deals near you</p>
            </div>

            <div class="row g-3 justify-content-center">
                @foreach($popularCities as $cityItem)
                    <div class="col-6 col-sm-4 col-md-3">
                        <a href="{{ route('listings.city', $cityItem->city_slug) }}" class="card border text-decoration-none text-dark h-100 shadow-sm hover-shadow transition">
                            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-light p-2 text-primary">
                                        <i class="fa-solid fa-city fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $cityItem->city }}</div>
                                        <small class="text-muted">{{ $cityItem->count }} ads available</small>
                                    </div>
                                </div>
                                <i class="fa-solid fa-chevron-right text-muted small"></i>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- POST AD BANNER -->
    <section class="py-5">
        <div class="container">
            <div class="p-4 p-md-5 rounded-4 bg-dark text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #002f34 0%, #004d56 100%) !important;">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-2">Free Ad Posting</span>
                        <h2 class="fw-bold text-white mb-2">Want to sell your Car, Mobile, or Offer a Service?</h2>
                        <p class="text-light opacity-90 mb-4 mb-lg-0">
                            Reach thousands of buyers in your city today. Posting an ad takes less than 60 seconds!
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('listings.create') }}" class="btn btn-warning text-dark fw-extrabold px-4 py-3 rounded-pill fs-6 shadow">
                            <i class="fa-solid fa-camera me-2"></i> Post Ad For Free
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
