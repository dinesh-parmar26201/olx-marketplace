<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display the OLX Marketplace homepage.
     */
    public function index()
    {
        // Parent categories with subcategories
        $categories = Category::whereNull('parent_id')
            ->with(['subcategories'])
            ->orderBy('sort_order')
            ->get();

        // Featured listings
        $featuredListings = Listing::active()
            ->featured()
            ->with(['category', 'subcategory', 'images', 'user'])
            ->latest()
            ->take(8)
            ->get();

        // Recent / Fresh recommendations
        $recentListings = Listing::active()
            ->with(['category', 'subcategory', 'images', 'user'])
            ->latest()
            ->take(12)
            ->get();

        // Popular cities with active ad counts
        $popularCities = Listing::active()
            ->select('city', 'city_slug', DB::raw('count(*) as count'))
            ->groupBy('city', 'city_slug')
            ->orderByDesc('count')
            ->take(8)
            ->get();

        // Total counters
        $stats = [
            'total_listings' => Listing::active()->count(),
            'total_products' => Listing::active()->where('type', 'product')->count(),
            'total_services' => Listing::active()->where('type', 'service')->count(),
            'total_cities' => Listing::active()->distinct('city')->count('city'),
        ];

        return view('home', compact('categories', 'featuredListings', 'recentListings', 'popularCities', 'stats'));
    }
}
