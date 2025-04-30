@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card auth-card">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-envelope-check text-primary" style="font-size: 3rem;"></i>
                        <h2 class="mt-3 mb-1">Verify Your Email</h2>
                        <p class="text-muted">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?</p>
                    </div>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success" role="alert">
                            A new verification link has been sent to the email address you provided during registration.
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                {{ __('Resend Verification Email') }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection