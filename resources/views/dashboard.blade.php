@extends('layouts.app')

@push('styles')
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css" rel="stylesheet">
    <style>
        .fc-header-toolbar {
            background-color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem !important;
        }
        .fc-button-primary {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
        }
        .fc-button-primary:hover {
            background-color: var(--primary-dark) !important;
            border-color: var(--primary-dark) !important;
        }
        .fc-daygrid-day {
            height: 100px !important;
        }
        .fc-event {
            cursor: pointer;
            border-radius: 4px;
        }
        .fc-event-title {
            font-weight: 500;
            padding: 2px;
        }
        .fc-h-event {
            border: none !important;
        }
        .calendar-container {
            margin-bottom: 2rem;
            background-color: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        #calendar {
            height: 600px;
        }

        .list-group-item {
            position: relative;
            overflow: visible;
            z-index: 0;
        }
        .dropdown-menu {
            position: absolute;
            z-index: 1050;
        }
        .goal-card {
            margin-bottom: 1px;
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
        .action-icon-complete i{
            color: #28a745;
        }
        .action-icon-incomplete i{
            color: #ffc107;
        }
        .action-icon-delete i{
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
    </style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-0">Your Dashboard</h1>
            <p class="text-muted">Track your goals and progress</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('goals.create') }}" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newGoalModal">
                <i class="bi bi-plus-lg"></i> New Goal
            </a>
        </div>
    </div>
    
    <!-- Map for Location -->
    <div class="mb-3">
        <label class="form-label">Your Current Position</label>
        <p id="mapText" class="text-muted"></p>
        <div id="map2" style="height: 300px"></div>
    </div>    

    <!-- Calendar Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="calendar-container">
                <h5 class="mb-3">Goal Calendar</h5>
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card h-100 dashboard-stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3 dashboard-stat-icon">
                            <i class="bi bi-bullseye fs-4 text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Active Goals</h5>
                            <p class="mb-0 text-muted">{{ $activeGoals->count() }} in progress</p>
                        </div>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $activeGoalsPercentage }}%;" aria-valuenow="{{ $activeGoalsPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card h-100 dashboard-stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3 dashboard-stat-icon">
                            <i class="bi bi-check2-circle fs-4 text-success"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Completed</h5>
                            <p class="mb-0 text-muted">{{ $completedGoals->count() }} goals</p>
                        </div>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 dashboard-stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3 dashboard-stat-icon">
                            <i class="bi bi-clock fs-4 text-warning"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Upcoming</h5>
                            <p class="mb-0 text-muted">{{ $upcomingGoals->count() }} goals</p>
                        </div>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $upcomingGoalsPercentage }}%;" aria-valuenow="{{ $upcomingGoalsPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Current Goals</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="goalFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Filter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="goalFilterDropdown">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">All Goals</a></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard', ['filter' => 'in-progress']) }}">In Progress</a></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard', ['filter' => 'completed']) }}">Completed</a></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard', ['filter' => 'not-started']) }}">Not Started</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($goals->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($goals as $goal)
                                <div class="list-group-item p-4 goal-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-{{ $goal->category_color ?? 'primary' }} bg-opacity-10 p-2 me-3 goal-icon">
                                                <i class="bi bi-{{ $goal->category_icon ?? 'bullseye' }} fs-5 text-{{ $goal->category_color ?? 'primary' }}"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $goal->title }}</h6>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="height: 6px; width: 100px;">
                                                        <div class="progress-bar {{ $goal->status == 'completed' ? 'bg-success' : '' }}" role="progressbar" style="width: {{ $goal->progress ?? 0 }}%;" aria-valuenow="{{ $goal->progress ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <span class="small text-muted">{{ $goal->progress ?? 0 }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-{{ $goal->category_color ?? 'primary' }} bg-opacity-10 text-{{ $goal->category_color ?? 'primary' }} me-3">{{ $goal->category }}</span>
                                            <span class="text-{{ $goal->status == 'completed' ? 'success' : 'muted' }} small me-3">
                                                @if($goal->status == 'completed')
                                                    Completed
                                                @elseif($goal->deadline)
                                                    Due {{ \Carbon\Carbon::parse($goal->deadline)->diffForHumans() }}
                                                @else
                                                    Ongoing
                                                @endif
                                            </span>
                                            <div class="d-flex align-items-center">
                                                <!-- Edit Icon -->
                                                <a href="{{ route('goals.edit', $goal) }}" class="action-icon action-icon-edit me-2" title="Edit Goal">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                
                                                <!-- Complete/Incomplete Icon -->
                                                @if($goal->status == 'completed')
                                                    <form action="{{ route('goals.uncomplete', $goal) }}" method="POST" class="action-form">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="action-button action-icon action-icon-incomplete me-2" title="Mark as Incomplete">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('goals.complete', $goal) }}" method="POST" class="action-form">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="action-button action-icon action-icon-complete me-2" title="Mark as Complete">
                                                            <i class="bi bi-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <!-- Delete Icon -->
                                                <form action="{{ route('goals.destroy', $goal) }}" method="POST" class="action-form" onsubmit="return confirm('Are you sure you want to delete this goal?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-button action-icon action-icon-delete" title="Delete Goal">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-clipboard-check text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">No goals yet</h5>
                            <p class="text-muted">Start by creating your first goal</p>
                            <a href="{{ route('goals.create') }}" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#newGoalModal">
                                <i class="bi bi-plus-lg"></i> Create Goal
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Motivation Tracker</h5>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#logMotivationModal">Log Today</button>
                </div>
                <div class="card-body">
                    @if($motivationEntries->count() > 0)
                        <div class="d-flex justify-content-between mb-4">
                            <div class="text-center">
                                <h6 class="mb-1">Weekly Average</h6>
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-emoji-smile text-success me-2"></i>
                                    <span class="h4 mb-0">{{ number_format($weeklyMotivationAverage, 1) }}/5</span>
                                </div>
                            </div>
                            <div class="text-center">
                                <h6 class="mb-1">Monthly Trend</h6>
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-graph-up-arrow text-primary me-2"></i>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="motivationChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-graph-up text-muted" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">No motivation data yet</p>
                            <button class="btn btn-sm btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#logMotivationModal">Log Your First Entry</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Goal Modal -->
<div class="modal fade" id="newGoalModal" tabindex="-1" aria-labelledby="newGoalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newGoalModalLabel">Create New Goal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('goals.store') }}" method="POST">
                    @csrf

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Goal Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                    </div>

                    <!-- Map for Location -->
                    <div class="mb-3">
                        <label class="form-label">Choose Location</label>
                        <div id="map" style="height: 300px;"></div>
                    </div>

                    <!-- Hidden Latitude/Longitude -->
                    <input type="hidden" name="location_latitude" id="location_latitude">
                    <input type="hidden" name="location_longitude" id="location_longitude">

                    <!-- Deadline -->
                    <div class="mb-3">
                        <label for="deadline" class="form-label">Deadline</label>
                        <input type="date" name="deadline" id="deadline" class="form-control">
                    </div>

                    <!-- Visibility -->
                    <div class="mb-3">
                        <label for="visibility" class="form-label">Visibility</label>
                        <select name="visibility" id="visibility" class="form-control">
                            <option value="private" selected>Private</option>
                            <option value="friends">Friends</option>
                            <option value="public">Public</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Create Goal</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Log Motivation Modal -->
<div class="modal fade" id="logMotivationModal" tabindex="-1" aria-labelledby="logMotivationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logMotivationModalLabel">Log Today's Motivation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('motivation.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">How motivated do you feel today?</label>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-center">
                                <input type="radio" class="btn-check" name="rating" id="rating1" value="1" autocomplete="off">
                                <label class="btn btn-outline-secondary px-3 py-2" for="rating1">
                                    <i class="bi bi-emoji-frown fs-4 d-block mb-1"></i>
                                    1
                                </label>
                            </div>
                            <div class="text-center">
                                <input type="radio" class="btn-check" name="rating" id="rating2" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary px-3 py-2" for="rating2">
                                    <i class="bi bi-emoji-expressionless fs-4 d-block mb-1"></i>
                                    2
                                </label>
                            </div>
                            <div class="text-center">
                                <input type="radio" class="btn-check" name="rating" id="rating3" value="3" autocomplete="off" checked>
                                <label class="btn btn-outline-secondary px-3 py-2" for="rating3">
                                    <i class="bi bi-emoji-neutral fs-4 d-block mb-1"></i>
                                    3
                                </label>
                            </div>
                            <div class="text-center">
                                <input type="radio" class="btn-check" name="rating" id="rating4" value="4" autocomplete="off">
                                <label class="btn btn-outline-secondary px-3 py-2" for="rating4">
                                    <i class="bi bi-emoji-smile fs-4 d-block mb-1"></i>
                                    4
                                </label>
                            </div>
                            <div class="text-center">
                                <input type="radio" class="btn-check" name="rating" id="rating5" value="5" autocomplete="off">
                                <label class="btn btn-outline-secondary px-3 py-2" for="rating5">
                                    <i class="bi bi-emoji-laughing fs-4 d-block mb-1"></i>
                                    5
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="What's affecting your motivation today?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize FullCalendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: [
                    @foreach($goals as $goal)
                        @if($goal->deadline)
                        {
                            id: '{{ $goal->id }}',
                            title: '{{ $goal->title }}',
                            start: '{{ \Carbon\Carbon::parse($goal->deadline)->format('Y-m-d') }}',
                            url: '{{ route('goals.show', $goal) }}',
                            backgroundColor: '{{ $goal->status == 'completed' ? '#28a745' : ($goal->category_color == 'primary' ? '#4e9af1' : ($goal->category_color == 'warning' ? '#ffc107' : ($goal->category_color == 'info' ? '#17a2b8' : '#6ad5cb'))) }}',
                            borderColor: '{{ $goal->status == 'completed' ? '#28a745' : ($goal->category_color == 'primary' ? '#4e9af1' : ($goal->category_color == 'warning' ? '#ffc107' : ($goal->category_color == 'info' ? '#17a2b8' : '#6ad5cb'))) }}',
                            textColor: '{{ $goal->category_color == 'warning' ? '#212529' : '#ffffff' }}',
                            allDay: true
                        },
                        @endif
                    @endforeach
                ],
                eventClick: function(info) {
                    if (info.event.url) {
                        info.jsEvent.preventDefault();
                        window.location.href = info.event.url;
                    }
                }
            });
            calendar.render();
            
            // Initialize Motivation Chart if data exists
            @if($motivationEntries->count() > 0)
                const ctx = document.getElementById('motivationChart').getContext('2d');
                const motivationChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($motivationDates) !!},
                        datasets: [{
                            label: 'Motivation Level',
                            data: {!! json_encode($motivationValues) !!},
                            backgroundColor: 'rgba(78, 154, 241, 0.2)',
                            borderColor: 'rgba(78, 154, 241, 1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 5,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            @endif
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    var map = L.map('map').setView([20, 0], 2); // Default world center

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var goalIcon = L.icon({
            iconUrl: 'https://i.imgur.com/Fam1t45.png',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, 0],
        });

    function success(position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;

        var marker = L.marker([latitude, longitude], {icon: goalIcon}).addTo(map);
    }

    function error() {
        status.innerHTML = "Unable to retrieve your location";
    }


    if (!navigator.geolocation) {
        status.innerHTML = "Geolocation is not supported by your browser";
    } else {
        status.innerHTML = "This is your current position:";
        navigator.geolocation.getCurrentPosition(success, error);
    }

    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        document.getElementById('location_latitude').value = lat;
        document.getElementById('location_longitude').value = lng;

        if (marker) {
            map.removeLayer(marker);
        }

        marker = map.whenReady(() => {L.marker([lat, lng], {icon:goalIcon}).addTo(map);});
    });

    // Fix the map rendering issue when modal is opened
    $('#goalModal').on('shown.bs.modal', function () {
        map.invalidateSize();  // Refresh the map
    });
});
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
    
    const status = document.getElementById('mapText');
    var map2 = L.map('map2').setView([20, 0], 2); // Default world center

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map2);

    function success(position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;

        var humanIcon = L.icon({
            iconUrl: 'https://i.imgur.com/ImLS4Bv.png',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, 0],
        });

        var marker = map2.whenReady(() => {L.marker([latitude, longitude], {icon: humanIcon}).addTo(map2);});
    }

    function error() {
        status.innerHTML = "Unable to retrieve your location";
    }


    if (!navigator.geolocation) {
        status.innerHTML = "Geolocation is not supported by your browser";
    } else {
        status.innerHTML = "This is your current position:";
        navigator.geolocation.getCurrentPosition(success, error);
    }

    var goalIcon = L.icon({
        iconUrl: 'https://i.imgur.com/Fam1t45.png',
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -16],
    });

    @if ($goals->count() > 0)
        @foreach($goals as $goal)
            console.log("{{ $goal->location_latitude }}, {{ $goal->location_longitude }}");
            var goalMarker = L.marker([{{ $goal->location_latitude }}, {{ $goal->location_longitude }}], {icon: goalIcon}).addTo(map2);
            goalMarker.bindPopup('<strong>{{ $goal->title }}</strong><br>{{ $goal->description }}');
        @endforeach
    @endif
    
});
</script>
@endpush
