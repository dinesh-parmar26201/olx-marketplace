<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Public Marketplace Listings Routes
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');

// City + Category Wise Listing (Must come BEFORE /city/{city_slug} to avoid conflict)
Route::get('/city/{city_slug}/category/{category_slug}', [ListingController::class, 'cityCategory'])->name('listings.city-category');

// City Wise Listing
Route::get('/city/{city_slug}', [ListingController::class, 'city'])->name('listings.city');

// Category Wise Listing (and Subcategory)
Route::get('/category/{category_slug}/{subcategory_slug?}', [ListingController::class, 'category'])->name('listings.category');

// Single Listing Detail
Route::get('/listing/{slug}', [ListingController::class, 'show'])->name('listings.show');

// Dynamic AJAX helper for category dropdowns
Route::get('/api/subcategories/{categoryId}', [ListingController::class, 'getSubcategories'])->name('api.subcategories');

// Authenticated User Routes (Post Ad, Edit, Delete, Dashboard)
Route::middleware('auth')->group(function () {
    // Create Ad
    Route::get('/post-ad', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/post-ad', [ListingController::class, 'store'])->name('listings.store');

    // Manage Ad
    Route::get('/ad/{id}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::put('/ad/{id}/update', [ListingController::class, 'update'])->name('listings.update');
    Route::delete('/ad/{id}/delete', [ListingController::class, 'destroy'])->name('listings.destroy');

    // User Dashboard & My Ads
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/my-ads', [DashboardController::class, 'myAds'])->name('dashboard.my-ads');
    Route::post('/dashboard/ads/{id}/status', [DashboardController::class, 'toggleStatus'])->name('dashboard.ads.status');
});
