@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card auth-card">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-envelope-open text-primary" style="font-size: 3rem;"></i>
                        <h2 class="mt-3 mb-1">Forgot Password</h2>
                        <p class="text-muted">Enter your email to reset your password</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="mb-0">Remember your password? <a href="{{ route('login') }}" class="text-decoration-none">Back to login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection