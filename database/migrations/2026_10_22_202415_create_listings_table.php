<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['product', 'service'])->default('product');
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('subcategory_id')->nullable()->constrained('categories')->onDelete('set null');
            
            // Location fields
            $table->string('country')->default('India');
            $table->string('state');
            $table->string('city');
            $table->string('city_slug')->index();
            $table->string('area');
            
            // Pricing & Details
            $table->decimal('price', 12, 2)->default(0);
            $table->string('price_type')->default('fixed'); // fixed, negotiable, free
            $table->string('condition')->default('used'); // new, used, not_applicable
            $table->string('status')->default('active'); // active, sold, expired, inactive
            $table->boolean('featured')->default(false);
            $table->unsignedBigInteger('views_count')->default(0);
            
            // Seller contact info snapshot
            $table->string('contact_phone')->nullable();
            $table->boolean('hide_phone')->default(false);
            
            $table->timestamps();
            
            // Index for fast search and filtering
            $table->index(['status', 'created_at']);
            $table->index(['city_slug', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
