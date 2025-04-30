@extends('layouts.app')

@section('content')
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">Map Your Goals, <span class="text-primary">Focus</span> Your Journey</h1>
                <p class="lead mb-4">FocusMap helps you visualize your goals, track your progress, and stay motivated on your path to success.</p>
                <div class="d-flex gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-4">Go to Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4">Get Started</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg px-4">Sign In</a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <img src="https://placehold.co/600x400/e4ecfb/4e9af1?text=FocusMap" alt="FocusMap Illustration" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Why Choose FocusMap?</h2>
        <p class="lead text-muted">Our platform is designed to help you achieve your goals with clarity and purpose.</p>
    </div>
    
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 p-4 text-center">
                <div class="feature-icon mx-auto">
                    <i class="bi bi-bullseye"></i>
                </div>
                <h3 class="h4 mb-2">Set Clear Goals</h3>
                <p class="text-muted">Define your goals with precision and break them down into achievable steps.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 p-4 text-center">
                <div class="feature-icon mx-auto">
                    <i class="bi bi-diagram-3"></i>
                </div>
                <h3 class="h4 mb-2">Mind Mapping</h3>
                <p class="text-muted">Visualize connections between your goals and create a roadmap to success.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 p-4 text-center">
                <div class="feature-icon mx-auto">
                    <i class="bi bi-graph-up"></i>
                </div>
                <h3 class="h4 mb-2">Track Progress</h3>
                <p class="text-muted">Monitor your achievements and stay motivated with visual progress tracking.</p>
            </div>
        </div>
    </div>
    
    <div class="row mt-5 pt-5 align-items-center">
        <div class="col-lg-6">
            <h2 class="fw-bold mb-3">Stay Motivated, Achieve More</h2>
            <p class="mb-4">FocusMap combines goal setting with mind mapping techniques to help you visualize your path to success. Our intuitive tools make it easy to:</p>
            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item bg-transparent ps-0"><i class="bi bi-check-circle-fill text-primary me-2"></i> Create detailed goal maps</li>
                <li class="list-group-item bg-transparent ps-0"><i class="bi bi-check-circle-fill text-primary me-2"></i> Track your progress visually</li>
                <li class="list-group-item bg-transparent ps-0"><i class="bi bi-check-circle-fill text-primary me-2"></i> Set reminders and deadlines</li>
                <li class="list-group-item bg-transparent ps-0"><i class="bi bi-check-circle-fill text-primary me-2"></i> Celebrate your achievements</li>
            </ul>
            <a href="{{ route('register') }}" class="btn btn-primary px-4">Start Your Journey</a>
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <div class="card bg-secondary-subtle border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white rounded-circle p-2 me-3">
                            <i class="bi bi-quote fs-3 text-primary"></i>
                        </div>
                        <h4 class="mb-0">User Success Story</h4>
                    </div>
                    <p class="lead fst-italic mb-3">"FocusMap transformed how I approach my goals. The visual mapping helped me see connections I was missing, and the progress tracking keeps me motivated every day."</p>
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <span class="fw-bold">JD</span>
                        </div>
                        <div>
                            <h5 class="mb-0">Jane Doe</h5>
                            <p class="mb-0 text-muted">Entrepreneur & FocusMap User</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-5 pt-5">
        <h2 class="fw-bold mb-4">Ready to Focus on What Matters?</h2>
        <p class="lead mb-4">Join thousands of users who have transformed their goal-setting process with FocusMap.</p>
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5">Get Started Today</a>
    </div>
</div>
@endsection