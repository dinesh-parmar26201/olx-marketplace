<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    /**
     * Browse / Search all listings with comprehensive filters.
     */
    public function index(Request $request)
    {
        $query = Listing::active()->with(['category', 'subcategory', 'images', 'user']);

        // Search Keyword
        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('area', 'like', "%{$keyword}%")
                  ->orWhere('city', 'like', "%{$keyword}%");
            });
        }

        // Product vs Service Type
        if ($request->filled('type') && in_array($request->input('type'), ['product', 'service'])) {
            $query->where('type', $request->input('type'));
        }

        // Category Filter
        if ($request->filled('category')) {
            $cat = Category::where('slug', $request->input('category'))
                ->orWhere('id', $request->input('category'))
                ->first();

            if ($cat) {
                if ($cat->parent_id === null) {
                    $subIds = $cat->subcategories->pluck('id')->push($cat->id);
                    $query->where(function ($q) use ($cat, $subIds) {
                        $q->where('category_id', $cat->id)
                          ->orWhereIn('subcategory_id', $subIds);
                    });
                } else {
                    $query->where('subcategory_id', $cat->id);
                }
            }
        }

        // Subcategory Filter
        if ($request->filled('subcategory')) {
            $subcat = Category::where('slug', $request->input('subcategory'))
                ->orWhere('id', $request->input('subcategory'))
                ->first();
            if ($subcat) {
                $query->where('subcategory_id', $subcat->id);
            }
        }

        // City Filter
        if ($request->filled('city')) {
            $cityVal = Str::slug($request->input('city'));
            $query->where(function ($q) use ($cityVal, $request) {
                $q->where('city_slug', $cityVal)
                  ->orWhere('city', 'like', '%' . $request->input('city') . '%');
            });
        }

        // State Filter
        if ($request->filled('state')) {
            $query->where('state', $request->input('state'));
        }

        // Price Filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        // Condition Filter (new / used)
        if ($request->filled('condition') && in_array($request->input('condition'), ['new', 'used'])) {
            $query->where('condition', $request->input('condition'));
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $listings = $query->paginate(12)->withQueryString();
        $categories = Category::whereNull('parent_id')->with('subcategories')->orderBy('sort_order')->get();
        $popularCities = Listing::active()->select('city', 'city_slug', DB::raw('count(*) as count'))
            ->groupBy('city', 'city_slug')
            ->orderByDesc('count')
            ->get();

        return view('listings.index', [
            'listings' => $listings,
            'categories' => $categories,
            'popularCities' => $popularCities,
            'pageTitle' => 'All Marketplace Listings',
            'activeCategory' => null,
            'activeCity' => $request->input('city'),
        ]);
    }

    /**
     * Category Page (and Subcategory Page).
     * Route: /category/{category_slug}/{subcategory_slug?}
     */
    public function category(Request $request, string $categorySlug, ?string $subcategorySlug = null)
    {
        $category = Category::where('slug', $categorySlug)->whereNull('parent_id')->firstOrFail();
        $subcategory = null;

        $query = Listing::active()->with(['category', 'subcategory', 'images', 'user']);

        if ($subcategorySlug) {
            $subcategory = Category::where('slug', $subcategorySlug)
                ->where('parent_id', $category->id)
                ->firstOrFail();
            $query->where('subcategory_id', $subcategory->id);
            $pageTitle = $subcategory->name . ' in ' . $category->name;
        } else {
            $subIds = $category->subcategories->pluck('id')->push($category->id);
            $query->where(function ($q) use ($category, $subIds) {
                $q->where('category_id', $category->id)
                  ->orWhereIn('subcategory_id', $subIds);
            });
            $pageTitle = $category->name . ' - Browse Ad Listings';
        }

        // Apply additional filters (city, price, sort)
        if ($request->filled('city')) {
            $cityVal = Str::slug($request->input('city'));
            $query->where(function ($q) use ($cityVal, $request) {
                $q->where('city_slug', $cityVal)
                  ->orWhere('city', 'like', '%' . $request->input('city') . '%');
            });
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $listings = $query->paginate(12)->withQueryString();
        $categories = Category::whereNull('parent_id')->with('subcategories')->orderBy('sort_order')->get();
        $popularCities = Listing::active()->select('city', 'city_slug', DB::raw('count(*) as count'))
            ->groupBy('city', 'city_slug')
            ->orderByDesc('count')
            ->get();

        return view('listings.category', [
            'category' => $category,
            'subcategory' => $subcategory,
            'listings' => $listings,
            'categories' => $categories,
            'popularCities' => $popularCities,
            'pageTitle' => $pageTitle,
        ]);
    }

    /**
     * City Wise Listing Page.
     * Route: /city/{city_slug}
     */
    public function city(Request $request, string $citySlug)
    {
        // Find existing listing to get official City name capitalization
        $firstListing = Listing::where('city_slug', $citySlug)->first();
        $cityName = $firstListing ? $firstListing->city : ucwords(str_replace('-', ' ', $citySlug));

        $query = Listing::active()
            ->where('city_slug', $citySlug)
            ->with(['category', 'subcategory', 'images', 'user']);

        // Filter by Category if selected
        if ($request->filled('category')) {
            $cat = Category::where('slug', $request->input('category'))->first();
            if ($cat) {
                if ($cat->parent_id === null) {
                    $subIds = $cat->subcategories->pluck('id')->push($cat->id);
                    $query->where(function ($q) use ($cat, $subIds) {
                        $q->where('category_id', $cat->id)
                          ->orWhereIn('subcategory_id', $subIds);
                    });
                } else {
                    $query->where('subcategory_id', $cat->id);
                }
            }
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Price filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        // Search query within this city
        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('area', 'like', "%{$keyword}%");
            });
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $listings = $query->paginate(12)->withQueryString();
        $categories = Category::whereNull('parent_id')->with('subcategories')->orderBy('sort_order')->get();
        $popularCities = Listing::active()->select('city', 'city_slug', DB::raw('count(*) as count'))
            ->groupBy('city', 'city_slug')
            ->orderByDesc('count')
            ->get();

        // Count categories in this specific city
        $cityCategoryCounts = Listing::active()
            ->where('city_slug', $citySlug)
            ->select('category_id', DB::raw('count(*) as count'))
            ->groupBy('category_id')
            ->pluck('count', 'category_id');

        return view('listings.city', [
            'cityName' => $cityName,
            'citySlug' => $citySlug,
            'listings' => $listings,
            'categories' => $categories,
            'popularCities' => $popularCities,
            'cityCategoryCounts' => $cityCategoryCounts,
            'pageTitle' => "Used Products & Services in {$cityName}",
        ]);
    }

    /**
     * City + Category Wise Listing Page.
     * Route: /city/{city_slug}/category/{category_slug}
     */
    public function cityCategory(Request $request, string $citySlug, string $categorySlug)
    {
        $category = Category::where('slug', $categorySlug)->whereNull('parent_id')->firstOrFail();

        $firstListing = Listing::where('city_slug', $citySlug)->first();
        $cityName = $firstListing ? $firstListing->city : ucwords(str_replace('-', ' ', $citySlug));

        $subIds = $category->subcategories->pluck('id')->push($category->id);

        $query = Listing::active()
            ->where('city_slug', $citySlug)
            ->where(function ($q) use ($category, $subIds) {
                $q->where('category_id', $category->id)
                  ->orWhereIn('subcategory_id', $subIds);
            })
            ->with(['category', 'subcategory', 'images', 'user']);

        // Subcategory filter within this city + category
        if ($request->filled('subcategory')) {
            $subcat = Category::where('slug', $request->input('subcategory'))
                ->where('parent_id', $category->id)
                ->first();
            if ($subcat) {
                $query->where('subcategory_id', $subcat->id);
            }
        }

        // Search query within this city + category
        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('area', 'like', "%{$keyword}%");
            });
        }

        // Price filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $listings = $query->paginate(12)->withQueryString();
        $categories = Category::whereNull('parent_id')->with('subcategories')->orderBy('sort_order')->get();
        $popularCities = Listing::active()->select('city', 'city_slug', DB::raw('count(*) as count'))
            ->groupBy('city', 'city_slug')
            ->orderByDesc('count')
            ->get();

        return view('listings.city_category', [
            'cityName' => $cityName,
            'citySlug' => $citySlug,
            'category' => $category,
            'listings' => $listings,
            'categories' => $categories,
            'popularCities' => $popularCities,
            'pageTitle' => "{$category->name} in {$cityName}",
        ]);
    }

    /**
     * Show single listing detail page.
     * Route: /listing/{slug}
     */
    public function show(string $slug)
    {
        $listing = Listing::where('slug', $slug)
            ->with(['category', 'subcategory', 'images', 'user'])
            ->firstOrFail();

        // Increment view count
        $listing->increment('views_count');

        // Related listings in same category or city
        $relatedListings = Listing::active()
            ->where('id', '!=', $listing->id)
            ->where(function ($query) use ($listing) {
                $query->where('category_id', $listing->category_id)
                      ->orWhere('city_slug', $listing->city_slug);
            })
            ->with(['images', 'category'])
            ->latest()
            ->take(4)
            ->get();

        return view('listings.show', compact('listing', 'relatedListings'));
    }

    /**
     * Show ad posting form.
     * Route: /ad/create (auth required)
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->with('subcategories')->orderBy('sort_order')->get();
        return view('listings.create', compact('categories'));
    }

    /**
     * Store new listing.
     * Route: /ad/store (auth required)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:product,service'],
            'title' => ['required', 'string', 'max:255', 'min:5'],
            'description' => ['required', 'string', 'min:15'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:categories,id'],
            'country' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'area' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_type' => ['required', 'in:fixed,negotiable,free'],
            'condition' => ['nullable', 'in:new,used,not_applicable'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'hide_phone' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // max 5MB each
        ]);

        $citySlug = Str::slug($validated['city']);
        $uniqueSlug = Str::slug($validated['title']) . '-' . Str::random(6);

        $listing = Listing::create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'title' => $validated['title'],
            'slug' => strtolower($uniqueSlug),
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'subcategory_id' => $validated['subcategory_id'] ?? null,
            'country' => $validated['country'] ?? 'India',
            'state' => $validated['state'],
            'city' => $validated['city'],
            'city_slug' => $citySlug,
            'area' => $validated['area'],
            'price' => $validated['price'],
            'price_type' => $validated['price_type'],
            'condition' => $validated['condition'] ?? ($validated['type'] === 'service' ? 'not_applicable' : 'used'),
            'status' => 'active',
            'featured' => false,
            'views_count' => 0,
            'contact_phone' => $validated['contact_phone'] ?? Auth::user()->phone,
            'hide_phone' => $request->boolean('hide_phone'),
        ]);

        // Upload images
        if ($request->hasFile('images')) {
            $isFirst = true;
            foreach ($request->file('images') as $index => $imageFile) {
                $path = $imageFile->store('listings', 'public');

                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image_path' => $path,
                    'is_primary' => $isFirst,
                    'sort_order' => $index,
                ]);

                $isFirst = false;
            }
        }

        // Redirect directly to the live frontend listing page as requested!
        return redirect()->route('listings.show', $listing->slug)
            ->with('success', '🎉 Your ad has been published successfully and is now LIVE on the marketplace!');
    }

    /**
     * Show edit form.
     */
    public function edit(int $id)
    {
        $listing = Listing::with(['images', 'category'])->findOrFail($id);

        if ($listing->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::whereNull('parent_id')->with('subcategories')->orderBy('sort_order')->get();
        return view('listings.edit', compact('listing', 'categories'));
    }

    /**
     * Update listing.
     */
    public function update(Request $request, int $id)
    {
        $listing = Listing::findOrFail($id);

        if ($listing->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'type' => ['required', 'in:product,service'],
            'title' => ['required', 'string', 'max:255', 'min:5'],
            'description' => ['required', 'string', 'min:15'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:categories,id'],
            'country' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'area' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_type' => ['required', 'in:fixed,negotiable,free'],
            'condition' => ['nullable', 'in:new,used,not_applicable'],
            'status' => ['required', 'in:active,sold,inactive'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'hide_phone' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        $listing->update([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'subcategory_id' => $validated['subcategory_id'] ?? null,
            'country' => $validated['country'],
            'state' => $validated['state'],
            'city' => $validated['city'],
            'city_slug' => Str::slug($validated['city']),
            'area' => $validated['area'],
            'price' => $validated['price'],
            'price_type' => $validated['price_type'],
            'condition' => $validated['condition'] ?? 'used',
            'status' => $validated['status'],
            'contact_phone' => $validated['contact_phone'],
            'hide_phone' => $request->boolean('hide_phone'),
        ]);

        // Add additional images if uploaded
        if ($request->hasFile('images')) {
            $hasExistingPrimary = $listing->images()->where('is_primary', true)->exists();
            $currentCount = $listing->images()->count();

            foreach ($request->file('images') as $i => $imageFile) {
                $path = $imageFile->store('listings', 'public');
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image_path' => $path,
                    'is_primary' => (!$hasExistingPrimary && $i === 0),
                    'sort_order' => $currentCount + $i,
                ]);
            }
        }

        return redirect()->route('listings.show', $listing->slug)
            ->with('success', 'Ad listing updated successfully!');
    }

    /**
     * Delete listing.
     */
    public function destroy(int $id)
    {
        $listing = Listing::with('images')->findOrFail($id);

        if ($listing->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete image files from storage if stored locally
        foreach ($listing->images as $img) {
            if (!str_starts_with($img->image_path, 'http')) {
                Storage::disk('public')->delete($img->image_path);
            }
        }

        $listing->delete();

        return redirect()->route('dashboard.my-ads')
            ->with('success', 'Listing deleted successfully.');
    }

    /**
     * AJAX Endpoint: Get subcategories by category ID.
     */
    public function getSubcategories(int $categoryId)
    {
        $subcategories = Category::where('parent_id', $categoryId)
            ->select('id', 'name', 'slug')
            ->orderBy('name')
            ->get();

        return response()->json($subcategories);
    }
}
