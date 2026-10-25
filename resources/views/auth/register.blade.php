@extends('layouts.app')

@section('title', 'Register - Join OLX Marketplace')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <div class="brand-logo mb-2">
                        <span>OLX</span><span class="dot">●</span>
                    </div>
                    <h1 class="h4 fw-bold text-dark">Create Your Free Account</h1>
                    <p class="text-muted small">Buy, sell, and connect with millions of buyers in India.</p>
                </div>

                <form action="{{ route('register.submit') }}" method="POST">
                    @csrf

                    <!-- Full Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold text-dark small">Full Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-user text-muted"></i></span>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Rajesh Kumar" value="{{ old('name') }}" required autofocus>
                        </div>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-dark small">Email Address <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-envelope text-muted"></i></span>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="name@example.com" value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold text-dark small">Mobile Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-phone text-muted"></i></span>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="+91 98765 43210" value="{{ old('phone') }}" required>
                        </div>
                        @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- City -->
                    <div class="mb-3">
                        <label for="city" class="form-label fw-semibold text-dark small">City</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-location-dot text-muted"></i></span>
                            <input type="text" name="city" id="city" list="cityOptions" class="form-control @error('city') is-invalid @enderror" placeholder="e.g. Mumbai, Delhi, Bangalore" value="{{ old('city') }}">
                            <datalist id="cityOptions">
                                <option value="Mumbai">
                                <option value="Delhi">
                                <option value="Bangalore">
                                <option value="Ahmedabad">
                                <option value="Pune">
                                <option value="Hyderabad">
                                <option value="Chennai">
                                <option value="Kolkata">
                            </datalist>
                        </div>
                        @error('city')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold text-dark small">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="At least 6 characters" required>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold text-dark small">Confirm Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-shield-check text-muted"></i></span>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Re-type password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 py-3 fw-extrabold text-dark rounded-pill fs-6 shadow-sm mb-3">
                        <i class="fa-solid fa-user-plus me-1"></i> Register Free Account
                    </button>

                    <div class="text-center small text-muted">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="fw-bold text-primary text-decoration-none">Log In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
