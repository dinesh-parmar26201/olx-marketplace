<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'parent_id',
        'description',
        'sort_order',
    ];

    /**
     * Parent category relationship.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Subcategories relationship.
     */
    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Listings under this category.
     */
    public function listings()
    {
        return $this->hasMany(Listing::class, 'category_id');
    }

    /**
     * Listings under this subcategory.
     */
    public function subcategoryListings()
    {
        return $this->hasMany(Listing::class, 'subcategory_id');
    }

    /**
     * Count active listings including subcategories.
     */
    public function activeListingsCount(): int
    {
        if ($this->parent_id === null) {
            $subcategoryIds = $this->subcategories->pluck('id')->toArray();
            return Listing::where('status', 'active')
                ->where(function ($query) use ($subcategoryIds) {
                    $query->where('category_id', $this->id)
                          ->orWhereIn('subcategory_id', $subcategoryIds);
                })->count();
        }

        return Listing::where('status', 'active')
            ->where('subcategory_id', $this->id)
            ->count();
    }
}
