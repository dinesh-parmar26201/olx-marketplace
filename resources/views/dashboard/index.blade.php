@extends('layouts.app')

@section('title', 'User Dashboard - OLX Marketplace')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>

    <!-- Welcome Header -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-3" style="width: 58px; height: 58px;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Hello, {{ Auth::user()->name }}!</h1>
                    <div class="text-muted small">
                        <span><i class="fa-solid fa-envelope me-1"></i> {{ Auth::user()->email }}</span>
                        @if(Auth::user()->phone)
                            <span class="ms-3"><i class="fa-solid fa-phone me-1"></i> {{ Auth::user()->phone }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <a href="{{ route('listings.create') }}" class="btn btn-warning fw-extrabold text-dark px-4 py-2 rounded-pill shadow-sm">
                    <i class="fa-solid fa-plus-circle me-1"></i> Post New Ad
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold">Total Ads Posted</span>
                    <i class="fa-solid fa-bullhorn text-primary fs-5"></i>
                </div>
                <div class="fs-2 fw-bold text-dark">{{ $stats['total_ads'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold">Active Ads</span>
                    <i class="fa-solid fa-circle-check text-success fs-5"></i>
                </div>
                <div class="fs-2 fw-bold text-success">{{ $stats['active_ads'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold">Sold Ads</span>
                    <i class="fa-solid fa-handshake text-warning fs-5"></i>
                </div>
                <div class="fs-2 fw-bold text-warning">{{ $stats['sold_ads'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold">Total Ad Views</span>
                    <i class="fa-solid fa-eye text-info fs-5"></i>
                </div>
                <div class="fs-2 fw-bold text-dark">{{ $stats['total_views'] }}</div>
            </div>
        </div>
    </div>

    <!-- Recent Ads Table -->
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark mb-0">Your Recent Listings</h5>
            <a href="{{ route('dashboard.my-ads') }}" class="btn btn-sm btn-outline-dark rounded-pill fw-semibold">
                Manage All Ads <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>

        @if($recentListings->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light small text-muted text-uppercase">
                        <tr>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentListings as $listing)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $listing->thumbnail_url }}" alt="Ad" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <a href="{{ route('listings.show', $listing->slug) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ Str::limit($listing->title, 45) }}
                                            </a>
                                            <div class="small text-muted">{{ $listing->city }}, {{ $listing->area }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $listing->category->name }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $listing->formatted_price }}</span>
                                </td>
                                <td>
                                    @if($listing->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($listing->status === 'sold')
                                        <span class="badge bg-danger">Sold</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <i class="fa-regular fa-eye me-1 text-muted"></i> {{ $listing->views_count }}
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('listings.show', $listing->slug) }}" class="btn btn-sm btn-outline-dark" title="View Ad">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>
                                        <a href="{{ route('listings.edit', $listing->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Ad">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <p class="text-muted mb-3">You haven't posted any ads yet.</p>
                <a href="{{ route('listings.create') }}" class="btn btn-warning btn-sm px-4 rounded-pill fw-bold text-dark">
                    <i class="fa-solid fa-plus me-1"></i> Post Your First Ad
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
