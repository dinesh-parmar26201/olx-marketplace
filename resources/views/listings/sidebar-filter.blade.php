<div class="filter-card shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-filter me-2 text-primary"></i> Filters</h5>
        <a href="{{ url()->current() }}" class="text-danger text-decoration-none small fw-semibold">Clear All</a>
    </div>

    <form action="{{ url()->current() }}" method="GET" id="filterForm">
        @if(request('q'))
            <input type="hidden" name="q" value="{{ request('q') }}">
        @endif

        <!-- TYPE FILTER (Product vs Service) -->
        <div class="mb-4">
            <div class="filter-title">Ad Type</div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="type" id="type_all" value="" {{ !request('type') ? 'checked' : '' }} onchange="this.form.submit()">
                <label class="form-check-label fw-semibold" for="type_all">All Listings</label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="type" id="type_product" value="product" {{ request('type') == 'product' ? 'checked' : '' }} onchange="this.form.submit()">
                <label class="form-check-label" for="type_product">Products Only</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type" id="type_service" value="service" {{ request('type') == 'service' ? 'checked' : '' }} onchange="this.form.submit()">
                <label class="form-check-label" for="type_service">Services Only</label>
            </div>
        </div>

        <!-- CATEGORIES LIST -->
        @if(isset($categories) && !isset($category))
            <div class="mb-4">
                <div class="filter-title">Categories</div>
                <div class="list-group list-group-flush small" style="max-height: 240px; overflow-y: auto;">
                    @foreach($categories as $catItem)
                        <a href="{{ route('listings.category', $catItem->slug) }}" class="list-group-item list-group-item-action border-0 px-1 py-1 d-flex justify-content-between align-items-center {{ request('category') == $catItem->slug ? 'fw-bold text-primary' : 'text-dark' }}">
                            <span><i class="fa-solid {{ $catItem->icon }} me-2 text-muted"></i> {{ $catItem->name }}</span>
                            <span class="badge bg-light text-muted rounded-pill">{{ $catItem->subcategories->count() }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- CITY FILTER -->
        @if(!isset($citySlug))
            <div class="mb-4">
                <div class="filter-title">City / Location</div>
                <select name="city" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Locations</option>
                    @foreach($popularCities as $cityOption)
                        <option value="{{ $cityOption->city_slug }}" {{ (request('city') == $cityOption->city_slug || request('city') == $cityOption->city) ? 'selected' : '' }}>
                            {{ $cityOption->city }} ({{ $cityOption->count }})
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- PRICE RANGE -->
        <div class="mb-4">
            <div class="filter-title">Price Range (₹)</div>
            <div class="row g-2">
                <div class="col-6">
                    <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}">
                </div>
                <div class="col-6">
                    <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}">
                </div>
            </div>
            <button type="submit" class="btn btn-sm btn-outline-primary w-100 mt-2 fw-semibold">
                Apply Price
            </button>
        </div>

        <!-- CONDITION -->
        <div class="mb-3">
            <div class="filter-title">Condition</div>
            <div class="form-check mb-1">
                <input class="form-check-input" type="radio" name="condition" id="cond_all" value="" {{ !request('condition') ? 'checked' : '' }} onchange="this.form.submit()">
                <label class="form-check-label" for="cond_all">Any Condition</label>
            </div>
            <div class="form-check mb-1">
                <input class="form-check-input" type="radio" name="condition" id="cond_used" value="used" {{ request('condition') == 'used' ? 'checked' : '' }} onchange="this.form.submit()">
                <label class="form-check-label" for="cond_used">Used</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="condition" id="cond_new" value="new" {{ request('condition') == 'new' ? 'checked' : '' }} onchange="this.form.submit()">
                <label class="form-check-label" for="cond_new">Brand New</label>
            </div>
        </div>

        @if(request('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}">
        @endif
    </form>
</div>
