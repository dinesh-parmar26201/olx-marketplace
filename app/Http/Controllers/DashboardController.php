<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard overview.
     */
    public function index()
    {
        $userId = Auth::id();

        $stats = [
            'total_ads' => Listing::where('user_id', $userId)->count(),
            'active_ads' => Listing::where('user_id', $userId)->where('status', 'active')->count(),
            'sold_ads' => Listing::where('user_id', $userId)->where('status', 'sold')->count(),
            'total_views' => Listing::where('user_id', $userId)->sum('views_count'),
        ];

        $recentListings = Listing::where('user_id', $userId)
            ->with(['category', 'images'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentListings'));
    }

    /**
     * User's listings management page.
     */
    public function myAds(Request $request)
    {
        $userId = Auth::id();

        $query = Listing::where('user_id', $userId)->with(['category', 'subcategory', 'images']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $listings = $query->latest()->paginate(10)->withQueryString();

        return view('dashboard.my-ads', compact('listings'));
    }

    /**
     * Quick status update (e.g. toggle Sold/Active).
     */
    public function toggleStatus(Request $request, int $id)
    {
        $listing = Listing::where('user_id', Auth::id())->findOrFail($id);

        $newStatus = $request->input('status');
        if (in_array($newStatus, ['active', 'sold', 'inactive'])) {
            $listing->status = $newStatus;
            $listing->save();
        }

        return back()->with('success', "Ad status updated to {$newStatus}.");
    }
}
