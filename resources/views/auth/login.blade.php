@extends('layouts.app')

@section('title', 'Login - OLX Marketplace')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <div class="brand-logo mb-2">
                        <span>OLX</span><span class="dot">●</span>
                    </div>
                    <h1 class="h4 fw-bold text-dark">Welcome Back</h1>
                    <p class="text-muted small">Sign in to manage your ads and messages.</p>
                </div>

                <form action="{{ route('login.submit') }}" method="POST">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-dark small">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-envelope text-muted"></i></span>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="name@example.com" value="{{ old('email', 'rahul@example.com') }}" required autofocus>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label fw-semibold text-dark small mb-0">Password</label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" value="password123" required>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                        <label class="form-check-label small text-muted" for="remember">
                            Keep me logged in
                        </label>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 py-3 fw-extrabold text-dark rounded-pill fs-6 shadow-sm mb-3">
                        <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Log In
                    </button>

                    <div class="text-center small text-muted mb-4">
                        Don't have an account yet? 
                        <a href="{{ route('register') }}" class="fw-bold text-primary text-decoration-none">Register Now</a>
                    </div>

                    <!-- Quick Demo Accounts -->
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="small fw-bold text-dark mb-2">
                            <i class="fa-solid fa-key text-warning me-1"></i> Quick Demo Logins:
                        </div>
                        <div class="d-flex flex-column gap-1 small">
                            <button type="button" class="btn btn-sm btn-outline-secondary text-start" onclick="fillLogin('rahul@example.com', 'password123')">
                                <strong>Rahul Sharma</strong> (rahul@example.com)
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary text-start" onclick="fillLogin('priya@example.com', 'password123')">
                                <strong>Priya Patel</strong> (priya@example.com)
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary text-start" onclick="fillLogin('vikram@example.com', 'password123')">
                                <strong>Vikram Reddy</strong> (vikram@example.com)
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function fillLogin(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endpush
@endsection
