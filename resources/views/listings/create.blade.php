@extends('layouts.app')

@section('title', 'Post Your Ad - OLX Marketplace')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Header -->
            <div class="text-center mb-4">
                <span class="badge bg-warning text-dark text-uppercase px-3 py-2 fw-bold mb-2">
                    <i class="fa-solid fa-camera me-1"></i> Quick & Easy
                </span>
                <h1 class="h3 fw-bold text-dark">Post Your Product or Service Ad</h1>
                <p class="text-muted small">Fill in the details below to publish your ad live on OLX Marketplace.</p>
            </div>

            <!-- Card Form -->
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data" id="postAdForm">
                    @csrf

                    <!-- 1. PRODUCT / SERVICE TYPE -->
                    <div class="mb-4 pb-4 border-bottom">
                        <label class="form-label fw-bold text-dark fs-6 mb-2">
                            What are you offering? <span class="text-danger">*</span>
                        </label>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="card p-3 border cursor-pointer h-100 text-center type-selector-label" id="typeLabelProduct" style="cursor: pointer; border-width: 2px !important; border-color: var(--olx-teal) !important;">
                                    <input type="radio" name="type" value="product" class="form-check-input d-none" id="typeProduct" {{ old('type', 'product') === 'product' ? 'checked' : '' }} onchange="toggleTypeUI('product')">
                                    <i class="fa-solid fa-box-open fs-2 mb-2 text-primary"></i>
                                    <div class="fw-bold fs-6">Product / Physical Item</div>
                                    <small class="text-muted">Car, Phone, Furniture, Electronics, Flat, etc.</small>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="card p-3 border cursor-pointer h-100 text-center type-selector-label" id="typeLabelService" style="cursor: pointer; border-width: 2px !important;">
                                    <input type="radio" name="type" value="service" class="form-check-input d-none" id="typeService" {{ old('type') === 'service' ? 'checked' : '' }} onchange="toggleTypeUI('service')">
                                    <i class="fa-solid fa-screwdriver-wrench fs-2 mb-2 text-success"></i>
                                    <div class="fw-bold fs-6">Professional Service</div>
                                    <small class="text-muted">Repair, Movers, Tech, Classes, Maintenance, etc.</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 2. CATEGORY & SUBCATEGORY -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="fw-bold text-dark mb-3">Category Information</h5>
                        <div class="row g-3">
                            <!-- Category -->
                            <div class="col-md-6">
                                <label for="category_id" class="form-label fw-semibold text-dark">
                                    Category <span class="text-danger">*</span>
                                </label>
                                <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required onchange="handleCategoryChange(this.value)">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Subcategory -->
                            <div class="col-md-6">
                                <label for="subcategory_id" class="form-label fw-semibold text-dark">
                                    Subcategory <span class="text-muted">(Optional)</span>
                                </label>
                                <select name="subcategory_id" id="subcategory_id" class="form-select @error('subcategory_id') is-invalid @enderror">
                                    <option value="">Select Subcategory</option>
                                </select>
                                @error('subcategory_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- 3. AD DETAILS (TITLE, DESCRIPTION, CONDITION) -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="fw-bold text-dark mb-3">Item Details</h5>
                        
                        <!-- Name / Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold text-dark">
                                Ad Title / Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. iPhone 14 Pro Max 128GB Deep Purple or Sofa Repair Service" value="{{ old('title') }}" required maxlength="120">
                            <div class="form-text small">Mention key details like brand, model, specs, or service specialty (Max 120 chars).</div>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Detail / Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold text-dark">
                                Description / Details <span class="text-danger">*</span>
                            </label>
                            <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="Describe what you are selling, its condition, age, reasons for selling, or specific features of your service..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Condition (for products) -->
                        <div class="mb-3" id="conditionWrapper">
                            <label class="form-label fw-semibold text-dark">Item Condition</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="condition" id="condUsed" value="used" {{ old('condition', 'used') === 'used' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="condUsed">Used / Pre-owned</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="condition" id="condNew" value="new" {{ old('condition') === 'new' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="condNew">Brand New / Unused</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. PRICE -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="fw-bold text-dark mb-3">Set a Price</h5>
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <label for="price" class="form-label fw-semibold text-dark">
                                    Price (₹ INR) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text fw-bold">₹</span>
                                    <input type="number" step="1" min="0" name="price" id="price" class="form-control @error('price') is-invalid @enderror" placeholder="0" value="{{ old('price') }}" required>
                                </div>
                                @error('price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="price_type" class="form-label fw-semibold text-dark">Price Type</label>
                                <select name="price_type" id="price_type" class="form-select">
                                    <option value="fixed" {{ old('price_type') === 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                                    <option value="negotiable" {{ old('price_type', 'negotiable') === 'negotiable' ? 'selected' : '' }}>Negotiable</option>
                                    <option value="free" {{ old('price_type') === 'free' ? 'selected' : '' }}>Free / Contact for Price</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- 5. LOCATION (COUNTRY, STATE, CITY, AREA) -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="fw-bold text-dark mb-3">Confirm Your Location</h5>
                        <div class="row g-3">
                            <!-- Country -->
                            <div class="col-md-6 col-lg-3">
                                <label for="country" class="form-label fw-semibold text-dark">Country <span class="text-danger">*</span></label>
                                <input type="text" name="country" id="country" class="form-control" value="{{ old('country', 'India') }}" required>
                            </div>

                            <!-- State -->
                            <div class="col-md-6 col-lg-3">
                                <label for="state" class="form-label fw-semibold text-dark">State <span class="text-danger">*</span></label>
                                <input type="text" name="state" id="state" list="stateOptions" class="form-control @error('state') is-invalid @enderror" placeholder="e.g. Maharashtra" value="{{ old('state', 'Maharashtra') }}" required>
                                <datalist id="stateOptions">
                                    <option value="Maharashtra">
                                    <option value="Delhi">
                                    <option value="Karnataka">
                                    <option value="Gujarat">
                                    <option value="Tamil Nadu">
                                    <option value="West Bengal">
                                    <option value="Telangana">
                                    <option value="Uttar Pradesh">
                                    <option value="Rajasthan">
                                    <option value="Punjab">
                                    <option value="Haryana">
                                    <option value="Kerala">
                                </datalist>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- City -->
                            <div class="col-md-6 col-lg-3">
                                <label for="city" class="form-label fw-semibold text-dark">City <span class="text-danger">*</span></label>
                                <input type="text" name="city" id="city" list="cityOptions" class="form-control @error('city') is-invalid @enderror" placeholder="e.g. Mumbai" value="{{ old('city', Auth::user()->city ?? 'Mumbai') }}" required>
                                <datalist id="cityOptions">
                                    <option value="Mumbai">
                                    <option value="Delhi">
                                    <option value="Bangalore">
                                    <option value="Ahmedabad">
                                    <option value="Pune">
                                    <option value="Hyderabad">
                                    <option value="Chennai">
                                    <option value="Kolkata">
                                    <option value="Jaipur">
                                    <option value="Surat">
                                    <option value="Lucknow">
                                    <option value="Chandigarh">
                                </datalist>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Area / Locality -->
                            <div class="col-md-6 col-lg-3">
                                <label for="area" class="form-label fw-semibold text-dark">Area / Locality <span class="text-danger">*</span></label>
                                <input type="text" name="area" id="area" class="form-control @error('area') is-invalid @enderror" placeholder="e.g. Andheri West" value="{{ old('area') }}" required>
                                @error('area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- 6. PHOTOS UPLOAD -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="fw-bold text-dark mb-1">Upload Photos</h5>
                        <p class="text-muted small mb-3">Upload up to 6 clear photos of your product or service. (Max 5MB per photo)</p>

                        <div class="p-4 border-2 border-dashed rounded-3 text-center bg-light" style="border-style: dashed; border-color: #cbd5e1;">
                            <i class="fa-solid fa-cloud-arrow-up fs-1 text-primary mb-2"></i>
                            <div class="fw-bold">Select Images from Computer / Phone</div>
                            <small class="text-muted d-block mb-3">Supports JPG, PNG, WEBP</small>
                            <input type="file" name="images[]" id="imagesInput" class="form-control w-auto mx-auto" multiple accept="image/*" onchange="previewImages(event)">
                        </div>

                        <!-- Preview Container -->
                        <div id="imagePreviewContainer" class="d-flex flex-wrap gap-2 mt-3"></div>
                        @error('images.*')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- 7. SELLER CONTACT PHONE -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="fw-bold text-dark mb-3">Seller Contact Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="contact_phone" class="form-label fw-semibold text-dark">Phone Number</label>
                                <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="{{ old('contact_phone', Auth::user()->phone) }}" placeholder="+91 98765 43210">
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="hide_phone" id="hide_phone" value="1" {{ old('hide_phone') ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="hide_phone">
                                        Hide phone number on my ad (Buyers can chat with me)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-warning fw-extrabold text-dark px-5 py-3 rounded-pill fs-6 shadow">
                            <i class="fa-solid fa-bolt me-1"></i> Post Ad Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Subcategories JSON map
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

    function toggleTypeUI(type) {
        const productLabel = document.getElementById('typeLabelProduct');
        const serviceLabel = document.getElementById('typeLabelService');
        const condWrapper = document.getElementById('conditionWrapper');

        if (type === 'product') {
            productLabel.style.borderColor = 'var(--olx-teal)';
            serviceLabel.style.borderColor = '#e2e8f0';
            condWrapper.style.display = 'block';
        } else {
            serviceLabel.style.borderColor = 'var(--olx-teal)';
            productLabel.style.borderColor = '#e2e8f0';
            condWrapper.style.display = 'none';
        }
    }

    function previewImages(event) {
        const container = document.getElementById('imagePreviewContainer');
        container.innerHTML = '';
        const files = event.target.files;

        if (files) {
            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const wrap = document.createElement('div');
                    wrap.className = 'position-relative rounded overflow-hidden shadow-sm border';
                    wrap.style.width = '100px';
                    wrap.style.height = '80px';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                    
                    if (index === 0) {
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-warning text-dark position-absolute top-0 start-0 m-1 font-monospace';
                        badge.style.fontSize = '0.6rem';
                        badge.innerText = 'Cover';
                        wrap.appendChild(badge);
                    }

                    wrap.appendChild(img);
                    container.appendChild(wrap);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const initialCatId = document.getElementById('category_id').value;
        if (initialCatId) {
            handleCategoryChange(initialCatId);
            const oldSub = "{{ old('subcategory_id') }}";
            if (oldSub) {
                document.getElementById('subcategory_id').value = oldSub;
            }
        }
    });
</script>
@endpush
@endsection
