<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OLX Marketplace - Buy & Sell Cars, Mobiles, Properties & Services')</title>
    
    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --olx-teal: #002f34;
            --olx-teal-dark: #001f22;
            --olx-teal-light: #00454c;
            --olx-yellow: #ffce32;
            --olx-yellow-hover: #f5c220;
            --olx-cyan: #23e5db;
            --olx-gray-bg: #f2f4f5;
            --olx-card-border: #e2e8f0;
            --olx-text-main: #002f34;
            --olx-text-muted: #64748b;
        }

        body, button, input, select, textarea, .form-control, .form-select, .btn, .badge {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        h1, h2, h3, h4, h5, h6, .display-5, .h1, .h2, .h3, .h4, .h5, .h6 {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            letter-spacing: -0.3px;
        }

        body {
            background-color: var(--olx-gray-bg);
            color: var(--olx-text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Hero Snapshot Container & Glass Stat Cards */
        .hero-snapshot-container {
            background: rgba(0, 25, 28, 0.45);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
        }

        .hero-stat-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 12px;
            padding: 14px 16px;
            transition: all 0.2s ease;
        }
        .hero-stat-card:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-2px);
        }

        .hero-stat-num {
            font-size: 1.65rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 2px;
            letter-spacing: -0.5px;
        }

        .hero-stat-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #e2e8f0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
        }

        /* City Banner Header & Stat Counter */
        .city-hero-card {
            background: linear-gradient(135deg, #002f34 0%, #004d56 100%) !important;
            color: #ffffff;
            border-radius: 16px;
            border: none;
            box-shadow: 0 8px 24px rgba(0, 47, 52, 0.12);
        }

        .city-stat-box {
            background: rgba(0, 25, 28, 0.55);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 14px;
            padding: 12px 22px;
            text-align: center;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            min-width: 140px;
        }

        .city-stat-box .stat-num {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--olx-yellow);
            line-height: 1.1;
            letter-spacing: -0.5px;
        }

        .city-stat-box .stat-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: #e2e8f0;
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        /* City Filter Buttons in Banner */
        .city-cat-pill {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }
        .city-cat-pill:hover {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
            border-color: #ffffff;
        }
        .city-cat-pill.active {
            background: var(--olx-yellow);
            color: var(--olx-teal);
            border-color: var(--olx-yellow);
            font-weight: 700;
        }
        .city-cat-pill .pill-badge {
            background: rgba(0, 47, 52, 0.25);
            padding: 2px 7px;
            border-radius: 50px;
            font-size: 0.75rem;
        }
        .city-cat-pill.active .pill-badge {
            background: rgba(0, 47, 52, 0.15);
            color: var(--olx-teal);
            font-weight: 800;
        }

        /* Top Header */
        .olx-navbar {
            background-color: #ffffff;
            border-bottom: 2px solid #e2e8f0;
            padding: 12px 0;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 2px 8px rgba(0, 47, 52, 0.04);
        }

        .brand-logo {
            font-weight: 800;
            font-size: 1.85rem;
            color: var(--olx-teal);
            letter-spacing: -1px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .brand-logo span.dot {
            color: var(--olx-yellow);
        }
        .brand-badge {
            font-size: 0.65rem;
            font-weight: 700;
            background-color: var(--olx-teal);
            color: #ffffff;
            padding: 3px 7px;
            border-radius: 4px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Search & Location Bar */
        .search-box-container {
            display: flex;
            gap: 10px;
            width: 100%;
        }

        .location-select-wrap {
            position: relative;
            min-width: 190px;
        }
        .location-select-wrap i.loc-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--olx-teal);
            font-size: 1rem;
        }
        .location-select-wrap select {
            padding-left: 36px;
            border: 2px solid var(--olx-teal);
            border-radius: 6px;
            font-weight: 600;
            height: 48px;
            color: var(--olx-teal);
            background-color: #ffffff;
        }

        .search-input-wrap {
            position: relative;
            flex-grow: 1;
            display: flex;
        }
        .search-input-wrap input {
            border: 2px solid var(--olx-teal);
            border-right: none;
            border-radius: 6px 0 0 6px;
            height: 48px;
            font-size: 0.95rem;
            padding-left: 16px;
        }
        .search-input-wrap input:focus,
        .location-select-wrap select:focus {
            box-shadow: none;
            border-color: var(--olx-teal-light);
        }
        .btn-search {
            background-color: var(--olx-teal);
            color: #ffffff;
            border: 2px solid var(--olx-teal);
            border-radius: 0 6px 6px 0;
            padding: 0 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .btn-search:hover {
            background-color: var(--olx-teal-dark);
            color: #ffffff;
        }

        /* SELL Button (Signature OLX border) */
        .btn-sell {
            background: #ffffff;
            color: var(--olx-teal);
            font-weight: 800;
            font-size: 0.95rem;
            padding: 8px 22px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            border: 4px solid;
            border-color: var(--olx-cyan) var(--olx-teal) var(--olx-yellow) var(--olx-teal);
            box-shadow: 0 3px 10px rgba(0, 47, 52, 0.12);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            white-space: nowrap;
        }
        .btn-sell:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 47, 52, 0.2);
            color: var(--olx-teal);
        }

        /* Category Ribbon */
        .category-ribbon {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 8px 0;
            overflow-x: auto;
            white-space: nowrap;
        }
        .category-ribbon-inner {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .ribbon-link {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--olx-teal);
            text-decoration: none;
            padding: 4px 6px;
            border-radius: 4px;
            transition: color 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .ribbon-link:hover, .ribbon-link.active {
            color: #0d6efd;
            background-color: #f1f5f9;
        }
        .ribbon-link-all {
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Listing Cards */
        .ad-card {
            background: #ffffff;
            border: 1px solid var(--olx-card-border);
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
            text-decoration: none;
            color: inherit;
            position: relative;
        }
        .ad-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(0, 47, 52, 0.1);
            border-color: #cbd5e1;
            color: inherit;
        }
        .ad-card-img-wrap {
            position: relative;
            width: 100%;
            padding-top: 65%; /* 16:10 aspect ratio */
            background-color: #f8fafc;
            overflow: hidden;
        }
        .ad-card-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.35s ease;
        }
        .ad-card:hover .ad-card-img {
            transform: scale(1.04);
        }
        .ad-badge-featured {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--olx-yellow);
            color: var(--olx-teal);
            font-size: 0.72rem;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.12);
        }
        .ad-badge-type {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 47, 52, 0.85);
            backdrop-filter: blur(4px);
            color: #ffffff;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .ad-card-body {
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .ad-card-price {
            font-size: 1.28rem;
            font-weight: 800;
            color: var(--olx-teal);
            margin-bottom: 4px;
        }
        .ad-card-title {
            font-size: 0.94rem;
            font-weight: 600;
            color: #334155;
            line-height: 1.35;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.7em;
        }
        .ad-card-meta {
            margin-top: auto;
            padding-top: 10px;
            border-top: 1px solid #f1f5f9;
            font-size: 0.78rem;
            color: #64748b;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Filter Sidebar */
        .filter-card {
            background: #ffffff;
            border: 1px solid var(--olx-card-border);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .filter-title {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--olx-teal);
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Hero Banner */
        .hero-banner {
            background: linear-gradient(135deg, var(--olx-teal) 0%, #00454c 100%);
            color: #ffffff;
            padding: 36px 0 44px;
            position: relative;
            overflow: hidden;
        }
        .hero-banner::after {
            content: '';
            position: absolute;
            right: -80px;
            bottom: -80px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 206, 50, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Category Cards on Home */
        .cat-icon-card {
            background: #ffffff;
            border: 1px solid var(--olx-card-border);
            border-radius: 12px;
            padding: 18px 12px;
            text-align: center;
            text-decoration: none;
            color: var(--olx-teal);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
            height: 100%;
        }
        .cat-icon-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 47, 52, 0.08);
            border-color: var(--olx-teal);
            color: var(--olx-teal);
        }
        .cat-icon-circle {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background-color: #f0fdfa;
            color: var(--olx-teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            transition: background-color 0.2s;
        }
        .cat-icon-card:hover .cat-icon-circle {
            background-color: var(--olx-yellow);
            color: var(--olx-teal);
        }
        .cat-icon-card span.name {
            font-size: 0.85rem;
            font-weight: 700;
            line-height: 1.25;
        }

        /* Footer */
        .olx-footer {
            background-color: #ebeeef;
            padding: 48px 0 24px;
            margin-top: auto;
            border-top: 1px solid #d8dee2;
            color: #002f34;
        }
        .footer-heading {
            font-size: 0.88rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 14px;
            color: var(--olx-teal);
        }
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .footer-links li {
            margin-bottom: 8px;
        }
        .footer-links a {
            color: #4b6366;
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.15s;
        }
        .footer-links a:hover {
            color: var(--olx-teal);
            text-decoration: underline;
        }
        .footer-bottom {
            background-color: var(--olx-teal);
            color: #ffffff;
            padding: 18px 0;
            font-size: 0.82rem;
        }

        /* Flash Alerts */
        .alert-dismissible .btn-close {
            padding: 1rem;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- TOP HEADER -->
    <header class="olx-navbar">
        <div class="container">
            <div class="row align-items-center g-3">
                <!-- Logo -->
                <div class="col-auto">
                    <a href="{{ route('home') }}" class="brand-logo">
                        <span>OLX</span><span class="dot">●</span>
                        <span class="brand-badge">MARKETPLACE</span>
                    </a>
                </div>

                <!-- Search and Location Form -->
                <div class="col">
                    <form action="{{ route('listings.index') }}" method="GET" class="search-box-container">
                        <!-- City Selector Dropdown -->
                        <div class="location-select-wrap d-none d-md-block">
                            <i class="fa-solid fa-location-dot loc-icon"></i>
                            <select name="city" class="form-select" onchange="this.form.submit()">
                                <option value="">All India</option>
                                <option value="mumbai" {{ request('city') == 'mumbai' ? 'selected' : '' }}>Mumbai</option>
                                <option value="delhi" {{ request('city') == 'delhi' ? 'selected' : '' }}>Delhi</option>
                                <option value="bangalore" {{ request('city') == 'bangalore' ? 'selected' : '' }}>Bangalore</option>
                                <option value="ahmedabad" {{ request('city') == 'ahmedabad' ? 'selected' : '' }}>Ahmedabad</option>
                                <option value="pune" {{ request('city') == 'pune' ? 'selected' : '' }}>Pune</option>
                                <option value="hyderabad" {{ request('city') == 'hyderabad' ? 'selected' : '' }}>Hyderabad</option>
                                <option value="chennai" {{ request('city') == 'chennai' ? 'selected' : '' }}>Chennai</option>
                                <option value="kolkata" {{ request('city') == 'kolkata' ? 'selected' : '' }}>Kolkata</option>
                            </select>
                        </div>

                        <!-- Keyword Input -->
                        <div class="search-input-wrap">
                            <input type="text" name="q" class="form-control" placeholder="Find Cars, Mobile Phones, Flats, Jobs, Services..." value="{{ request('q') }}">
                            <button type="submit" class="btn btn-search">
                                <i class="fa-solid fa-magnifying-glass me-1"></i>
                                <span class="d-none d-sm-inline">Search</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Menu: Auth & SELL Button -->
                <div class="col-auto d-flex align-items-center gap-3">
                    @auth
                        <!-- User Profile Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-outline-dark dropdown-toggle d-flex align-items-center gap-2 rounded-pill px-3 py-2 fw-bold" type="button" data-bs-toggle="dropdown">
                                <i class="fa-regular fa-user-circle fs-5 text-primary"></i>
                                <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                                <li class="px-3 py-2 border-bottom">
                                    <div class="fw-bold text-dark">{{ Auth::user()->name }}</div>
                                    <small class="text-muted">{{ Auth::user()->email }}</small>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('dashboard') }}">
                                        <i class="fa-solid fa-chart-line text-muted me-2"></i> Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('dashboard.my-ads') }}">
                                        <i class="fa-solid fa-list-check text-muted me-2"></i> My Ads
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('listings.create') }}">
                                        <i class="fa-solid fa-plus-circle text-muted me-2"></i> Post New Ad
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger py-2">
                                            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <!-- Login / Register Links -->
                        <a href="{{ route('login') }}" class="text-decoration-none fw-bold text-dark px-2">
                            Login
                        </a>
                        <span class="text-muted d-none d-sm-inline">|</span>
                        <a href="{{ route('register') }}" class="text-decoration-none fw-bold text-dark px-2 d-none d-sm-inline">
                            Register
                        </a>
                    @endauth

                    <!-- + SELL Button -->
                    <a href="{{ route('listings.create') }}" class="btn-sell">
                        <i class="fa-solid fa-plus"></i>
                        <span>SELL</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- CATEGORIES RIBBON (OLX SUB-NAVBAR) -->
    <nav class="category-ribbon">
        <div class="container">
            <div class="category-ribbon-inner">
                <a href="{{ route('listings.index') }}" class="ribbon-link ribbon-link-all">
                    <i class="fa-solid fa-bars me-1"></i> All Categories
                </a>
                
                @php
                    $navCategories = \App\Models\Category::whereNull('parent_id')->orderBy('sort_order')->take(8)->get();
                @endphp
                @foreach($navCategories as $navCat)
                    <a href="{{ route('listings.category', $navCat->slug) }}" class="ribbon-link {{ request()->is('category/' . $navCat->slug . '*') ? 'active' : '' }}">
                        <i class="fa-solid {{ $navCat->icon ?? 'fa-tag' }} me-1 opacity-75"></i>
                        {{ $navCat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </nav>

    <!-- FLASH MESSAGES -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                <i class="fa-solid fa-triangle-exclamation fs-5 me-2"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                <i class="fa-solid fa-circle-info fs-5 me-2"></i>
                <div>{{ session('info') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <div class="fw-bold mb-1"><i class="fa-solid fa-circle-exclamation me-1"></i> Please check the following errors:</div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="olx-footer">
        <div class="container">
            <div class="row g-4">
                <!-- Popular Locations -->
                <div class="col-6 col-md-3">
                    <h6 class="footer-heading">Popular Locations</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('listings.city', 'mumbai') }}">Kolkata</a></li>
                        <li><a href="{{ route('listings.city', 'mumbai') }}">Mumbai</a></li>
                        <li><a href="{{ route('listings.city', 'delhi') }}">Delhi</a></li>
                        <li><a href="{{ route('listings.city', 'bangalore') }}">Bangalore</a></li>
                        <li><a href="{{ route('listings.city', 'ahmedabad') }}">Ahmedabad</a></li>
                        <li><a href="{{ route('listings.city', 'pune') }}">Pune</a></li>
                    </ul>
                </div>

                <!-- Trending Categories -->
                <div class="col-6 col-md-3">
                    <h6 class="footer-heading">Trending Categories</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('listings.category', 'cars-and-vehicles') }}">Cars & Vehicles</a></li>
                        <li><a href="{{ route('listings.category', 'properties') }}">Properties & Flats</a></li>
                        <li><a href="{{ route('listings.category', 'mobiles-and-tablets') }}">Mobiles & Accessories</a></li>
                        <li><a href="{{ route('listings.category', 'electronics-and-appliances') }}">Electronics & Laptops</a></li>
                        <li><a href="{{ route('listings.category', 'services') }}">Local Services</a></li>
                        <li><a href="{{ route('listings.category', 'furniture-and-decor') }}">Furniture & Home</a></li>
                    </ul>
                </div>

                <!-- City + Category Quick Links -->
                <div class="col-6 col-md-3">
                    <h6 class="footer-heading">Top Combinations</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('listings.city-category', ['city_slug' => 'mumbai', 'category_slug' => 'cars-and-vehicles']) }}">Cars in Mumbai</a></li>
                        <li><a href="{{ route('listings.city-category', ['city_slug' => 'bangalore', 'category_slug' => 'electronics-and-appliances']) }}">Electronics in Bangalore</a></li>
                        <li><a href="{{ route('listings.city-category', ['city_slug' => 'delhi', 'category_slug' => 'mobiles-and-tablets']) }}">Mobiles in Delhi</a></li>
                        <li><a href="{{ route('listings.city-category', ['city_slug' => 'ahmedabad', 'category_slug' => 'properties']) }}">Properties in Ahmedabad</a></li>
                        <li><a href="{{ route('listings.city-category', ['city_slug' => 'pune', 'category_slug' => 'services']) }}">Services in Pune</a></li>
                    </ul>
                </div>

                <!-- About & Sell -->
                <div class="col-6 col-md-3">
                    <h6 class="footer-heading">OLX Marketplace</h6>
                    <p class="text-muted small">India's favorite online marketplace to buy and sell products and services. Post free ads in seconds.</p>
                    <a href="{{ route('listings.create') }}" class="btn btn-warning fw-bold text-dark px-3 py-2 rounded-pill w-100 shadow-sm">
                        <i class="fa-solid fa-plus-circle me-1"></i> Post Your Free Ad
                    </a>
                    <div class="mt-3 text-muted small">
                        <i class="fa-solid fa-shield-halved text-success me-1"></i> 100% Verified Community
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div class="footer-bottom">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                © {{ date('Y') }} OLX Marketplace Clone. All rights reserved.
            </div>
            <div>
                Built with <span class="text-warning">♥</span> using Laravel & MySQL
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
