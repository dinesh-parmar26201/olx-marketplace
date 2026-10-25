@extends('layouts.app')

@section('title', 'My Ads - Manage Listings')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">My Ads</li>
        </ol>
    </nav>

    <!-- Header & Filter Tabs -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">My Ad Listings</h1>
            <p class="text-muted small mb-0">Manage, edit, or mark your ads as sold.</p>
        </div>
        <a href="{{ route('listings.create') }}" class="btn btn-warning fw-extrabold text-dark px-4 py-2 rounded-pill shadow-sm">
            <i class="fa-solid fa-plus-circle me-1"></i> Post New Ad
        </a>
    </div>

    <!-- Filter Pills -->
    <div class="mb-4">
        <div class="btn-group" role="group">
            <a href="{{ route('dashboard.my-ads') }}" class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-dark' }}">
                All Ads
            </a>
            <a href="{{ route('dashboard.my-ads', ['status' => 'active']) }}" class="btn btn-sm {{ request('status') == 'active' ? 'btn-success' : 'btn-outline-success' }}">
                Active
            </a>
            <a href="{{ route('dashboard.my-ads', ['status' => 'sold']) }}" class="btn btn-sm {{ request('status') == 'sold' ? 'btn-danger' : 'btn-outline-danger' }}">
                Sold
            </a>
            <a href="{{ route('dashboard.my-ads', ['status' => 'inactive']) }}" class="btn btn-sm {{ request('status') == 'inactive' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                Inactive
            </a>
        </div>
    </div>

    <!-- Listings Table Card -->
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
        @if($listings->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light small text-muted text-uppercase">
                        <tr>
                            <th>Ad Info</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Date Posted</th>
                            <th>Views</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listings as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="rounded shadow-sm" style="width: 60px; height: 50px; object-fit: cover;">
                                        <div>
                                            <a href="{{ route('listings.show', $item->slug) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ Str::limit($item->title, 40) }}
                                            </a>
                                            <div class="small text-muted">
                                                <i class="fa-solid fa-location-dot me-1"></i> {{ $item->city }}, {{ $item->area }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $item->category->name }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $item->formatted_price }}</span>
                                    <div class="small text-muted text-capitalize">{{ $item->price_type }}</div>
                                </td>
                                <td class="small text-muted">
                                    {{ $item->created_at->format('d M, Y') }}
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <i class="fa-regular fa-eye me-1"></i> {{ $item->views_count }}
                                    </span>
                                </td>
                                <td>
                                    <!-- Status Dropdown Quick Change -->
                                    <form action="{{ route('dashboard.ads.status', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm {{ $item->status === 'active' ? 'border-success text-success fw-bold' : ($item->status === 'sold' ? 'border-danger text-danger fw-bold' : '') }}" onchange="this.form.submit()" style="width: 105px;">
                                            <option value="active" {{ $item->status === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="sold" {{ $item->status === 'sold' ? 'selected' : '' }}>Sold</option>
                                            <option value="inactive" {{ $item->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('listings.show', $item->slug) }}" class="btn btn-sm btn-outline-dark" title="View Ad on Frontend">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>
                                        <a href="{{ route('listings.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Ad">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('listings.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this ad?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Ad">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $listings->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="fs-1 text-muted mb-3"><i class="fa-solid fa-inbox"></i></div>
                <h5 class="fw-bold text-dark">No Ads Found</h5>
                <p class="text-muted small">You don't have any ads with this status.</p>
                <a href="{{ route('listings.create') }}" class="btn btn-warning btn-sm px-4 rounded-pill fw-bold text-dark">
                    <i class="fa-solid fa-plus me-1"></i> Post Ad Now
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
