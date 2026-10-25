<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MarketplaceFlowTest extends TestCase
{
    /**
     * Test homepage loads successfully.
     */
    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('OLX');
        $response->assertSee('All Categories');
    }

    /**
     * Test user registration.
     */
    public function test_user_can_register(): void
    {
        $email = 'seller.' . time() . '@example.com';

        $response = $this->post('/register', [
            'name' => 'Amit Sharma',
            'email' => $email,
            'phone' => '+91 91234 56789',
            'city' => 'Mumbai',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'email' => $email,
            'name' => 'Amit Sharma',
        ]);
    }

    /**
     * Test user can add a product listing and it shows immediately on the frontend.
     */
    public function test_user_can_add_product_and_shows_on_frontend(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'phone' => '+91 98765 00000',
            'city' => 'Mumbai',
        ]);

        $category = Category::where('slug', 'electronics-and-appliances')->first();
        $subcategory = Category::where('slug', 'computers-laptops')->first();

        $image = UploadedFile::fake()->image('macbook.jpg', 600, 400);

        $response = $this->actingAs($user)->post('/post-ad', [
            'type' => 'product',
            'title' => 'MacBook Air M3 Test Listing For Marketplace',
            'description' => 'Brand new sealed box with Apple 1-year official warranty and genuine accessories.',
            'category_id' => $category->id,
            'subcategory_id' => $subcategory ? $subcategory->id : null,
            'country' => 'India',
            'state' => 'Maharashtra',
            'city' => 'Mumbai',
            'area' => 'Andheri East',
            'price' => 99999,
            'price_type' => 'negotiable',
            'condition' => 'new',
            'contact_phone' => '+91 98765 00000',
            'images' => [$image],
        ]);

        // Find the created listing
        $listing = Listing::where('title', 'MacBook Air M3 Test Listing For Marketplace')->first();
        $this->assertNotNull($listing);

        // Verify redirect to frontend listing page
        $response->assertRedirect(route('listings.show', $listing->slug));

        // Test frontend single listing page
        $showResponse = $this->get(route('listings.show', $listing->slug));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('MacBook Air M3 Test Listing For Marketplace');
        $showResponse->assertSee('₹ 99,999');
        $showResponse->assertSee('Andheri East, Mumbai');

        // Test frontend Category page
        $catResponse = $this->get(route('listings.category', $category->slug));
        $catResponse->assertStatus(200);
        $catResponse->assertSee('MacBook Air M3 Test Listing For Marketplace');

        // Test frontend City page
        $cityResponse = $this->get(route('listings.city', 'mumbai'));
        $cityResponse->assertStatus(200);
        $cityResponse->assertSee('MacBook Air M3 Test Listing For Marketplace');

        // Test frontend City + Category page
        $cityCatResponse = $this->get(route('listings.city-category', [
            'city_slug' => 'mumbai',
            'category_slug' => $category->slug,
        ]));
        $cityCatResponse->assertStatus(200);
        $cityCatResponse->assertSee('MacBook Air M3 Test Listing For Marketplace');
    }

    /**
     * Test user can add a service listing.
     */
    public function test_user_can_add_service_listing(): void
    {
        $user = User::factory()->create([
            'phone' => '+91 98111 22233',
            'city' => 'Bangalore',
        ]);

        $category = Category::where('slug', 'services')->first();

        $response = $this->actingAs($user)->post('/post-ad', [
            'type' => 'service',
            'title' => 'Professional Web Development & SEO Service In Bangalore',
            'description' => 'We create high performance modern web applications, e-commerce stores and SEO services.',
            'category_id' => $category->id,
            'country' => 'India',
            'state' => 'Karnataka',
            'city' => 'Bangalore',
            'area' => 'Koramangala',
            'price' => 19999,
            'price_type' => 'fixed',
            'condition' => 'not_applicable',
            'contact_phone' => '+91 98111 22233',
        ]);

        $listing = Listing::where('title', 'Professional Web Development & SEO Service In Bangalore')->first();
        $this->assertNotNull($listing);
        $this->assertEquals('service', $listing->type);

        $response->assertRedirect(route('listings.show', $listing->slug));
    }
}
