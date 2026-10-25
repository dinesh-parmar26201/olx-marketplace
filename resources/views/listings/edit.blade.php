@extends('layouts.app')

@section('title', 'Edit Ad - ' . $listing->title)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold text-dark mb-1">Edit Ad Listing</h1>
                    <p class="text-muted small mb-0">Update information for "{{ $listing->title }}"</p>
                </div>
                <a href="{{ route('listings.show', $listing->slug) }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Ad
                </a>
            </div>

            <!-- Card Form -->
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <form action="{{ route('listings.update', $listing->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- 0. AD STATUS -->
                    <div class="mb-4 pb-4 border-bottom">
                        <label class="form-label fw-bold text-dark fs-6 mb-2">Ad Status</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusActive" value="active" {{ old('status', $listing->status) === 'active' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-success" for="statusActive">
                                    <i class="fa-solid fa-circle-check me-1"></i> Active (Visible to public)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusSold" value="sold" {{ old('status', $listing->status) === 'sold' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-danger" for="statusSold">
                                    <i class="fa-solid fa-tag me-1"></i> Sold Out
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusInactive" value="inactive" {{ old('status', $listing->status) === 'inactive' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-muted" for="statusInactive">
                                    <i class="fa-solid fa-eye-slash me-1"></i> Inactive / Hidden
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 1. PRODUCT / SERVICE TYPE -->
                    <div class="mb-4 pb-4 border-bottom">
                        <label class="form-label fw-bold text-dark fs-6 mb-2">Offering Type <span class="text-danger">*</span></label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeProduct" value="product" {{ old('type', $listing->type) === 'product' ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="typeProduct">Product</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeService" value="service" {{ old('type', $listing->type) === 'service' ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="typeService">Service</label>
                            </div>
                        </div>
                    </div>

                    <!-- 2. CATEGORY & SUBCATEGORY -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="fw-bold text-dark mb-3">Category Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="category_id" class="form-label fw-semibold text-dark">Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required onchange="handleCategoryChange(this.value)">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $listing->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="subcategory_id" class="form-label fw-semibold text-dark">Subcategory</label>
                                <select name="subcategory_id" id="subcategory_id" class="form-select @error('subcategory_id') is-invalid @enderror">
                                    <option value="">Select Subcategory</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- 3. TITLE & DESCRIPTION -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="fw-bold text-dark mb-3">Item Details</h5>
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold text-dark">Ad Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $listing->title) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold text-dark">Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="5" class="form-control" required>{{ old('description', $listing->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Condition</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="condition" id="condUsed" value="used" {{ old('condition', $listing->condition) === 'used' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="condUsed">Used</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="condition" id="condNew" value="new" {{ old('condition', $listing->condition) === 'new' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="condNew">Brand New</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. PRICE -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="fw-bold text-dark mb-3">Price</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label fw-semibold text-dark">Price (₹ INR) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text fw-bold">₹</span>
                                    <input type="number" step="1" min="0" name="price" id="price" class="form-control" value="{{ old('price', (int)$listing->price) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="price_type" class="form-label fw-semibold text-dark">Price Type</label>
                                <select name="price_type" id="price_type" class="form-select">
                                    <option value="fixed" {{ old('price_type', $listing->price_type) === 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                                    <option value="negotiable" {{ old('price_type', $listing->price_type) === 'negotiable' ? 'selected' : '' }}>Negotiable</option>
                                    <option value="free" {{ old('price_type', $listing->price_type) === 'free' ? 'selected' : '' }}>Free / Contact for Price</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- 5. LOCATION -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="fw-bold text-dark mb-3">Location</h5>
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-3">
                                <label for="country" class="form-label fw-semibold text-dark">Country</label>
                                <input type="text" name="country" id="country" class="form-control" value="{{ old('country', $listing->country) }}" required>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label for="state" class="form-label fw-semibold text-dark">State</label>
                                <input type="text" name="state" id="state" class="form-control" value="{{ old('state', $listing->state) }}" required>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label for="city" class="form-label fw-semibold text-dark">City</label>
                                <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $listing->city) }}" required>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label for="area" class="form-label fw-semibold text-dark">Area</label>
                                <input type="text" name="area" id="area" class="form-control" value="{{ old('area', $listing->area) }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- 6. CURRENT & NEW IMAGES -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="fw-bold text-dark mb-2">Photos</h5>
                        @if($listing->images->count() > 0)
                            <div class="small text-muted mb-2">Current Photos:</div>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach($listing->images as $img)
                                    <div class="rounded overflow-hidden border shadow-sm" style="width: 100px; height: 80px;">
                                        <img src="{{ $img->image_url }}" alt="Photo" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <label class="form-label fw-semibold text-dark">Add More Photos</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                    </div>

                    <!-- 7. CONTACT -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="fw-bold text-dark mb-3">Contact Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="contact_phone" class="form-label fw-semibold text-dark">Phone Number</label>
                                <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="{{ old('contact_phone', $listing->contact_phone) }}">
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="hide_phone" id="hide_phone" value="1" {{ old('hide_phone', $listing->hide_phone) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="hide_phone">
                                        Hide phone number on my ad
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('dashboard.my-ads') }}" class="btn btn-outline-secondary px-4 rounded-pill">Cancel</a>
                        <button type="submit" class="btn btn-warning fw-extrabold text-dark px-5 py-2 rounded-pill shadow">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const categorySubcategories = {
        @foreach($categories as $cat)
            {{ $cat->id }}: [
                @foreach($cat->subcategories as $sub)
                    { id: {{ $sub->id }}, name: "{{ addslashes($sub->name) }}" },
                @endforeach
            ],
        @endforeach
    };

    function handleCategoryChange(catId) {
        const subSelect = document.getElementById('subcategory_id');
        subSelect.innerHTML = '<option value="">Select Subcategory</option>';

        if (catId && categorySubcategories[catId]) {
            categorySubcategories[catId].forEach(function(sub) {
                const opt = document.createElement('option');
                opt.value = sub.id;
                opt.textContent = sub.name;
                subSelect.appendChild(opt);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const currentCat = "{{ old('category_id', $listing->category_id) }}";
        const currentSub = "{{ old('subcategory_id', $listing->subcategory_id) }}";
        if (currentCat) {
            handleCategoryChange(currentCat);
            if (currentSub) {
                document.getElementById('subcategory_id').value = currentSub;
            }
        }
    });
</script>
@endpush
@endsection
