@extends('layouts.app')

@section('title', $pageTitle . ' - OLX Marketplace')

@section('content')
<div class="container py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('listings.index') }}" class="text-decoration-none">Categories</a></li>
            <li class="breadcrumb-item {{ $subcategory ? '' : 'active' }}">
                @if($subcategory)
                    <a href="{{ route('listings.category', $category->slug) }}" class="text-decoration-none">{{ $category->name }}</a>
                @else
                    {{ $category->name }}
                @endif
            </li>
            @if($subcategory)
                <li class="breadcrumb-item active" aria-current="page">{{ $subcategory->name }}</li>
            @endif
        </ol>
    </nav>

    <!-- Category Header Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.6rem;">
                        <i class="fa-solid {{ $category->icon ?? 'fa-tag' }}"></i>
                    </div>
                </div>
                <div class="col">
                    <h1 class="h3 fw-bold text-dark mb-1">
                        {{ $subcategory ? $subcategory->name : $category->name }}
                    </h1>
                    <p class="text-muted small mb-0">
                        {{ $category->description ?? 'Browse all listings and verified sellers in this category' }}
                    </p>
                </div>
                <div class="col-auto d-none d-md-block text-end">
                    <span class="badge bg-dark px-3 py-2 fs-6 rounded-pill">
                        {{ $listings->total() }} Ads Found
                    </span>
                </div>
            </div>

            <!-- Subcategory Navigation Pills -->
            @if($category->subcategories->count() > 0)
                <div class="mt-3 pt-3 border-top d-flex flex-wrap gap-2">
                    <a href="{{ route('listings.category', $category->slug) }}" class="btn btn-sm rounded-pill fw-semibold {{ !$subcategory ? 'btn-dark' : 'btn-outline-secondary' }}">
                        All in {{ $category->name }}
                    </a>
                    @foreach($category->subcategories as $sub)
                        <a href="{{ route('listings.category', [$category->slug, $sub->slug]) }}" class="btn btn-sm rounded-pill fw-semibold {{ ($subcategory && $subcategory->id === $sub->id) ? 'btn-dark' : 'btn-outline-secondary' }}">
                            {{ $sub->name }}
                        </a>
                    @endforeach
                </div>
            @endif
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
            <!-- Sorting Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <div class="text-muted small">
                    Showing {{ $listings->firstItem() ?? 0 }}-{{ $listings->lastItem() ?? 0 }} of {{ $listings->total() }} ads in <strong>{{ $subcategory ? $subcategory->name : $category->name }}</strong>
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
                    <div class="fs-1 text-muted mb-3"><i class="fa-solid fa-folder-open"></i></div>
                    <h5 class="fw-bold text-dark">No Ads in this Category yet</h5>
                    <p class="text-muted small">Be the first person to post an ad in this category!</p>
                    <a href="{{ route('listings.create') }}" class="btn btn-warning btn-sm px-4 rounded-pill fw-bold text-dark">
                        <i class="fa-solid fa-plus me-1"></i> Post Ad Now
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
