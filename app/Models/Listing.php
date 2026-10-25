<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'slug',
        'description',
        'category_id',
        'subcategory_id',
        'country',
        'state',
        'city',
        'city_slug',
        'area',
        'price',
        'price_type',
        'condition',
        'status',
        'featured',
        'views_count',
        'contact_phone',
        'hide_phone',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'featured' => 'boolean',
        'hide_phone' => 'boolean',
        'views_count' => 'integer',
    ];

    /**
     * User who posted the listing.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Subcategory.
     */
    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    /**
     * Listing Images.
     */
    public function images()
    {
        return $this->hasMany(ListingImage::class)->orderBy('is_primary', 'desc')->orderBy('sort_order', 'asc');
    }

    /**
     * Primary or first image.
     */
    public function primaryImage()
    {
        return $this->hasOne(ListingImage::class)->where('is_primary', true);
    }

    /**
     * Helper to get featured image URL or a graceful fallback.
     */
    public function getThumbnailUrlAttribute(): string
    {
        $primary = $this->images->firstWhere('is_primary', true) ?? $this->images->first();

        if ($primary) {
            return $primary->image_url;
        }

        // Return a sleek placeholder SVG
        return 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?auto=format&fit=crop&w=600&q=80';
    }

    /**
     * Formatted price display.
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->price_type === 'free' || $this->price <= 0) {
            return 'Free';
        }

        return '₹ ' . number_format($this->price, 0, '.', ',');
    }

    /**
     * Human-friendly location string: e.g. "Andheri West, Mumbai"
     */
    public function getLocationTextAttribute(): string
    {
        $parts = array_filter([$this->area, $this->city]);
        return implode(', ', $parts);
    }

    /**
     * Full location text: "Andheri West, Mumbai, Maharashtra, India"
     */
    public function getFullLocationTextAttribute(): string
    {
        $parts = array_filter([$this->area, $this->city, $this->state, $this->country]);
        return implode(', ', $parts);
    }

    /**
     * Boot method to auto-generate unique slug and city_slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($listing) {
            if (empty($listing->slug)) {
                $baseSlug = Str::slug($listing->title);
                $uniqueSlug = $baseSlug . '-' . Str::random(6);
                $listing->slug = strtolower($uniqueSlug);
            }

            if (!empty($listing->city)) {
                $listing->city_slug = Str::slug($listing->city);
            }
        });

        static::updating(function ($listing) {
            if (!empty($listing->city) && empty($listing->city_slug)) {
                $listing->city_slug = Str::slug($listing->city);
            }
        });
    }

    /**
     * Scope active listings.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope featured listings.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }
}
