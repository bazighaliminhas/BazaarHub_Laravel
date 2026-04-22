@extends('layouts.app')
@section('title', 'Register - BazaarHub')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 p-4">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">Create Account</h2>
                    <p class="text-muted">Join BazaarHub and start shopping!</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger rounded-3">
                        @foreach($errors->all() as $error)
                            <div><i class="bi bi-x-circle me-1"></i>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('customer.register.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-person" style="color:var(--primary)"></i>
                            </span>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="form-control border-start-0 ps-0"
                                   placeholder="Your full name" required>
                        </div>
                    </div>

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

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-lock" style="color:var(--primary)"></i>
                            </span>
                            <input type="password" name="password"
                                   class="form-control border-start-0 ps-0"
                                   placeholder="Min 6 characters" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-lock-fill" style="color:var(--primary)"></i>
                            </span>
                            <input type="password" name="password_confirmation"
                                   class="form-control border-start-0 ps-0"
                                   placeholder="Repeat password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2 fs-6">
                        <i class="bi bi-person-plus me-2"></i>Create My Account
                    </button>
                </form>

                <p class="text-center mt-3 text-muted">
                    Already have an account?
                    <a href="{{ route('customer.login') }}" style="color:var(--primary)">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
