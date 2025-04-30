@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h1>Profile Settings</h1>
            <p class="text-muted">Manage your account settings and preferences</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center text-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                        <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#profile-info" class="list-group-item list-group-item-action active px-3 py-2 d-flex align-items-center">
                            <i class="bi bi-person me-2"></i> Profile Information
                        </a>
                        <a href="#update-password" class="list-group-item list-group-item-action px-3 py-2 d-flex align-items-center">
                            <i class="bi bi-lock me-2"></i> Update Password
                        </a>
                        <a href="#delete-account" class="list-group-item list-group-item-action px-3 py-2 d-flex align-items-center text-danger">
                            <i class="bi bi-trash me-2"></i> Delete Account
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card mb-4" id="profile-info">
                <div class="card-header">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Update your account's profile information and email address.</p>
                    
                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Profile updated successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required autofocus>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mb-4" id="update-password">
                <div class="card-header">
                    <h5 class="mb-0">Update Password</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Ensure your account is using a long, random password to stay secure.</p>
                    
                    @if (session('status') === 'password-updated')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Password updated successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            @error('password_confirmation')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card" id="delete-account">
                <div class="card-header">
                    <h5 class="mb-0 text-danger">Delete Account</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                    
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                        Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="confirmDeleteModalLabel">Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                
                <form method="post" action="{{ route('profile.destroy') }}" id="delete-account-form">
                    @csrf
                    @method('delete')
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('delete-account-form').submit();">Delete Account</button>
            </div>
        </div>
    </div>
</div>
@endsection