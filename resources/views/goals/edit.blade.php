@extends('layouts.app')

@push('styles')
<link href="{{ asset('css/goals.css') }}" rel="stylesheet">
<style>
    .step-item {
        transition: all 0.2s;
    }
    .step-item:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    .step-item.completed {
        background-color: rgba(40, 167, 69, 0.05);
    }
    .step-complete {
        width: 20px;
        height: 20px;
        border: 2px solid #6c757d;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.2s;
    }
    .step-complete.completed {
        background-color: #28a745;
        border-color: #28a745;
    }
    .action-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s;
        text-decoration: none;
    }
    .action-icon:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }
    .action-icon-edit {
        color: #4e9af1;
    }
    .action-icon-complete {
        color: #28a745;
    }
    .action-icon-incomplete {
        color: #ffc107;
    }
    .action-icon-delete {
        color: #dc3545;
    }
    .action-form {
        display: inline-block;
        margin: 0;
        padding: 0;
    }
    .action-button {
        background: none;
        border: none;
        padding: 0;
        margin: 0;
        cursor: pointer;
        font: inherit;
        color: inherit;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }
    .ai-suggestion-card {
        border-left: 4px solid #4e9af1;
        transition: all 0.2s;
    }
    .ai-suggestion-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .loading-spinner {
        display: none;
    }
    .loading .loading-spinner {
        display: inline-block;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-0">Edit Goal</h1>
            <p class="text-muted">Update your goal details and manage steps</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Goal Edit Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Goal Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('goals.update', $goal) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Goal Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $goal->title }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="visibility" class="form-label">Visibility</label>
                            <select class="form-select" id="visibility" name="visibility">
                                <option value="private" {{ $goal->visibility == 'private' ? 'selected' : '' }}>Private</option>
                                <option value="public" {{ $goal->visibility == 'public' ? 'selected' : '' }}>Public</option>
                                <option value="friends" {{ $goal->visibility == 'friends' ? 'selected' : '' }}>Friends</option>

                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $goal->description }}</textarea>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="deadline" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="deadline" name="deadline" value="{{ $goal->deadline ? $goal->deadline : '' }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusUpcoming" value="upcoming" {{ $goal->status == 'upcoming' ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusUpcoming">Not Started</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusActive" value="active" {{ $goal->status == 'active' ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusActive">In Progress</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusCompleted" value="completed" {{ $goal->status == 'completed' ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusCompleted">Completed</label>
                            </div>
                        </div>

                        <!-- Map for Location -->
                        <div class="mb-3">
                            <label class="form-label">Choose Location</label>
                            <div id="map" style="height: 300px;"></div>
                        </div>

                        <!-- Hidden Latitude/Longitude -->
                        <input type="hidden" name="location_latitude" id="location_latitude">
                        <input type="hidden" name="location_longitude" id="location_longitude">
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update Goal</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Goal Steps Section -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Goal Steps</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addStepModal">
                        <i class="bi bi-plus-lg"></i> Add Step
                    </button>
                </div>
                <div class="card-body">
                    @if($goal->steps->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($goal->steps as $step)
                                <li class="list-group-item d-flex justify-content-between align-items-center p-3 step-item {{ $step->completed ? 'completed' : '' }}">
                                    <div class="d-flex align-items-center">
                                        <form action="{{ route('steps.toggle', $step) }}" method="POST" class="action-form me-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="action-button" title="{{ $step->completed ? 'Mark as incomplete' : 'Mark as complete' }}">
                                                <div class="step-complete {{ $step->completed ? 'completed' : '' }}">
                                                    @if($step->completed)
                                                        <i class="bi bi-check text-white" style="font-size: 0.8rem;"></i>
                                                    @endif
                                                </div>
                                            </button>
                                        </form>
                                        <span class="{{ $step->completed ? 'text-decoration-line-through text-muted' : '' }}">
                                            {{ $step->description }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <a href="#" class="action-icon action-icon-edit me-2" data-bs-toggle="modal" data-bs-target="#editStepModal" 
                                           data-step-id="{{ $step->id }}" data-step-description="{{ $step->description }}" title="Edit Step">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('steps.destroy', $step) }}" method="POST" class="action-form" onsubmit="return confirm('Are you sure you want to delete this step?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-button action-icon action-icon-delete" title="Delete Step">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-list-check text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">No steps yet</h5>
                            <p class="text-muted">Break down your goal into manageable steps</p>
                            <div class="mt-3">
                                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addStepModal">
                                    <i class="bi bi-plus-lg"></i> Add Step Manually
                                </button>
                                <button class="btn btn-outline-primary" id="getAiSuggestions">
                                    <i class="bi bi-lightbulb"></i> Get AI Suggestions
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- AI Suggestions Section -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">AI Step Suggestions</h5>
                    <button class="btn btn-sm btn-outline-primary" id="getAiSuggestions2">
                        <i class="bi bi-lightbulb"></i> Generate
                    </button>
                </div>
                <div class="card-body">
                    <div id="aiSuggestionsLoading" class="text-center py-4 d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 mb-0">Generating suggestions...</p>
                    </div>
                    
                    <div id="aiSuggestionsEmpty" class="text-center py-4">
                        <i class="bi bi-robot text-muted" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">Click "Generate" to get AI-powered step suggestions based on your goal</p>
                    </div>
                    
                    <div id="aiSuggestionsList" class="d-none">
                        <!-- AI suggestions will be populated here -->
                    </div>
                </div>
            </div>
            
            <!-- Goal Progress Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Goal Progress</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Overall Progress</span>
                        <span class="fw-bold">{{ $goal->progress }}%</span>
                    </div>
                    <div class="progress mb-4" style="height: 10px;">
                        <div class="progress-bar {{ $goal->progress == 100 ? 'bg-success' : '' }}" role="progressbar" style="width: {{ $goal->progress }}%;" aria-valuenow="{{ $goal->progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Steps Completed</span>
                        <span class="fw-bold">
                            {{ $goal->steps->where('completed', true)->count() }}/{{ $goal->steps->count() }}
                        </span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        @php
                            $stepProgress = $goal->steps->count() > 0 
                                ? ($goal->steps->where('completed', true)->count() / $goal->steps->count()) * 100 
                                : 0;
                        @endphp
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $stepProgress }}%;" aria-valuenow="{{ $stepProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
            
            <!-- Goal Timeline Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <p class="text-muted mb-1">Start Date</p>
                            <p class="mb-0 fw-bold">{{ $goal->created_at ? $goal->created_at->format('M d, Y') : 'Not set' }}</p>
                        </div>
                        <div class="text-end">
                            <p class="text-muted mb-1">Due Date</p>
                            <p class="mb-0 fw-bold">{{ $goal->deadline ? $goal->deadline : 'Not set' }}</p>
                        </div>
                    </div>
                    
                    @if($goal->start_date && $goal->deadline)
                        @php
                            $totalDays = $goal->start_date->diffInDays($goal->deadline);
                            $daysLeft = now()->diffInDays($goal->deadline, false);
                            $timeProgress = $totalDays > 0 ? 100 - (($daysLeft / $totalDays) * 100) : 0;
                            if ($timeProgress < 0) $timeProgress = 0;
                            if ($timeProgress > 100) $timeProgress = 100;
                        @endphp
                        
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $timeProgress }}%;" aria-valuenow="{{ $timeProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">{{ $goal->start_date->format('M d') }}</small>
                            <small class="text-muted">{{ $goal->deadline->format('M d') }}</small>
                        </div>
                        
                        <div class="alert {{ $daysLeft > 0 ? 'alert-info' : 'alert-danger' }} mt-3 mb-0">
                            @if($daysLeft > 0)
                                <i class="bi bi-clock me-2"></i> {{ $daysLeft }} days left
                            @elseif($daysLeft == 0)
                                <i class="bi bi-exclamation-circle me-2"></i> Due today
                            @else
                                <i class="bi bi-exclamation-triangle me-2"></i> Overdue by {{ abs($daysLeft) }} days
                            @endif
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-info-circle me-2"></i> Set start and due dates to track timeline
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Step Modal -->
<div class="modal fade" id="addStepModal" tabindex="-1" aria-labelledby="addStepModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStepModalLabel">Add New Step</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('steps.store') }}" method="POST">
                @csrf
                <input type="hidden" name="goal_id" value="{{ $goal->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="step_description" class="form-label">Step Description</label>
                        <textarea class="form-control" id="step_description" name="description" rows="3" placeholder="Describe what needs to be done" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Step</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Step Modal -->
<div class="modal fade" id="editStepModal" tabindex="-1" aria-labelledby="editStepModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStepModalLabel">Edit Step</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editStepForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_step_description" class="form-label">Step Description</label>
                        <textarea class="form-control" id="edit_step_description" name="description" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Step</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Edit step modal functionality
        const editStepModal = document.getElementById('editStepModal');
        if (editStepModal) {
            editStepModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const stepId = button.getAttribute('data-step-id');
                const stepDescription = button.getAttribute('data-step-description');
                
                const form = this.querySelector('#editStepForm');
                const textarea = this.querySelector('#edit_step_description');
                
                form.action = `/steps/${stepId}`;
                textarea.value = stepDescription;
            });
        }
        
        // AI Suggestions functionality
        const getAiSuggestionsBtn = document.getElementById('getAiSuggestions');
        const getAiSuggestionsBtn2 = document.getElementById('getAiSuggestions2');
        const aiSuggestionsLoading = document.getElementById('aiSuggestionsLoading');
        const aiSuggestionsEmpty = document.getElementById('aiSuggestionsEmpty');
        const aiSuggestionsList = document.getElementById('aiSuggestionsList');
        
        const getAiSuggestions = () => {
                // Show loading state
                aiSuggestionsLoading.classList.remove('d-none');
                aiSuggestionsEmpty.classList.add('d-none');
                aiSuggestionsList.classList.add('d-none');
                
                const URL = "https://openrouter.ai/api/v1/chat/completions";
                

                fetch(URL, {
                    method: 'POST',
                    headers: {
                        "Authorization": "Bearer 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",
                        'Content-Type': 'application/json',
                        //'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        model: "qwen/qwen3-30b-a3b:free",
                        "messages": [
                        {
                            "role": "user",
                            "content": "Suggestion steps to achieve  {{$goal->title}} objective. Give me only the steps in the response and format the response in a way I can breakdown the string using Javascript using & symbol"
                        }
                    ],
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Hide loading state
                    aiSuggestionsLoading.classList.add('d-none');
                    console.log(data);
                    console.log(data.choices[0].message.content.split('&'));
                    suggestions = data.choices[0].message.content.split('&');
                    if (suggestions && suggestions.length > 0) {
                        // Populate suggestions
                        aiSuggestionsList.innerHTML = '';
                        
                        suggestions.forEach((suggestion, index) => {
                            const suggestionCard = document.createElement('div');
                            suggestionCard.className = 'card ai-suggestion-card mb-2';
                            
                            suggestionCard.innerHTML = `
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-0">${suggestion}</p>
                                        <form action="{{ route('steps.store') }}" method="POST" class="ms-2">
                                            @csrf
                                            <input type="hidden" name="goal_id" value="{{ $goal->id }}">
                                            <input type="hidden" name="description" value="${suggestion}">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-plus"></i> Add
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            `;
                            
                            aiSuggestionsList.appendChild(suggestionCard);
                        });
                        
                        aiSuggestionsList.classList.remove('d-none');
                    } else {
                        // Show empty state with error message
                        aiSuggestionsEmpty.innerHTML = `
                            <i class="bi bi-exclamation-circle text-warning" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">Couldn't generate suggestions. Please try again with more details.</p>
                        `;
                        aiSuggestionsEmpty.classList.remove('d-none');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    aiSuggestionsLoading.classList.add('d-none');
                    aiSuggestionsEmpty.innerHTML = `
                        <i class="bi bi-exclamation-triangle text-danger" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">An error occurred. Please try again later.</p>
                    `;
                    aiSuggestionsEmpty.classList.remove('d-none');
                });
            }

        if (getAiSuggestionsBtn) {
            getAiSuggestionsBtn.addEventListener('click', getAiSuggestions);
        }

        if (getAiSuggestionsBtn2) {
            getAiSuggestionsBtn2.addEventListener('click', getAiSuggestions);
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    var map = L.map('map').setView(['{{ $goal->location_latitude}}', '{{ $goal->location_latitude}}'], 2); // Default world center

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker;

    marker = L.marker(['{{ $goal->location_latitude}}', '{{ $goal->location_latitude}}']).addTo(map);

    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        document.getElementById('location_latitude').value = lat;
        document.getElementById('location_longitude').value = lng;

        if (marker) {
            map.removeLayer(marker);
        }

        marker = L.marker([lat, lng]).addTo(map);
    });
});
</script>
@endpush
