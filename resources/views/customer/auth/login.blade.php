@extends('layouts.app')
@section('title', 'Login - BazaarHub')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 p-4">

                <div class="text-center mb-4">
                    <h2 class="fw-bold">Welcome Back!</h2>
                    <p class="text-muted">Login to your BazaarHub account</p>
                </div>

                @if($errors->any())
                <div class="alert alert-danger rounded-3">
                    @foreach($errors->all() as $error)
                        <div><i class="bi bi-x-circle me-1"></i>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                <form action="{{ route('customer.login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-envelope" style="color:var(--primary)"></i>
                            </span>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control border-start-0 ps-0"
                                   placeholder="you@email.com" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-lock" style="color:var(--primary)"></i>
                            </span>
                            <input type="password" name="password"
                                   class="form-control border-start-0 ps-0"
                                   placeholder="Your password" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="remember" id="remember">
                            <label class="form-check-label text-muted" for="remember">
                                Remember me
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2 fs-6">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </button>
                </form>

                {{-- Links --}}
                <p class="text-center mt-3 text-muted mb-1">
                    New to BazaarHub?
                    <a href="{{ route('customer.register') }}" style="color:var(--primary);">
                        Create account
                    </a>
                </p>

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-center px-2">
                    <p class="text-muted small mb-0">
                        Are you a vendor?
                        <a href="{{ route('vendor.login') }}" style="color:var(--primary);">
                            Vendor Login →
                        </a>
                    </p>
                    <a href="{{ route('admin.login') }}"
                       style="color:#bbb;font-size:12px;text-decoration:none;"
                       title="Admin Access">
                        🔐 Admin
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
