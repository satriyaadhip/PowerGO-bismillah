<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IoT Dashboard')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .status-online {
            color: #198754;
        }
        .status-offline {
            color: #dc3545;
        }
        .device-card {
            border-left: 4px solid transparent;
        }
        .device-card.online {
            border-left-color: #198754;
        }
        .device-card.offline {
            border-left-color: #dc3545;
        }
        .metric-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .metric-card h3 {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
        }
        .battery-low {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        .chart-container {
            position: relative;
            height: 300px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-microchip me-2"></i>IoT Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}" 
                           href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('devices*') ? 'active' : '' }}" 
                           href="{{ route('devices.index') }}">
                            <i class="fas fa-devices me-1"></i>Devices
                        </a>
                    </li>
                </ul>
                <span class="navbar-text">
                    <i class="fas fa-clock me-1"></i>
                    <span id="current-time">{{ now()->format('H:i:s') }}</span>
                </span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {hour12: false});
            document.getElementById('current-time').textContent = timeString;
        }
        
        setInterval(updateTime, 1000);
        
        // Auto-refresh dashboard data every 30 seconds
        if (window.location.pathname === '/' || window.location.pathname.includes('/dashboard')) {
            setInterval(function() {
                fetch('/api/dashboard-data')
                    .then(response => response.json())
                    .then(data => {
                        // Update timestamp
                        const timestampEl = document.getElementById('last-update');
                        if (timestampEl) {
                            timestampEl.textContent = data.timestamp;
                        }
                        
                        // You could update specific metrics here
                        console.log('Dashboard data refreshed:', data.timestamp);
                    })
                    .catch(error => console.log('Error refreshing data:', error));
            }, 30000);
        }
    </script>
    
    @stack('scripts')
</body>
</html>

<!-- ======================= -->
<!-- DASHBOARD INDEX -->
<!-- resources/views/dashboard/index.blade.php -->
<!-- ======================= -->
@extends('layouts.app')

@section('title', 'IoT Dashboard - Overview')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-3">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard Overview
            <small class="text-muted ms-3">
                Last updated: <span id="last-update">{{ now()->format('H:i:s') }}</span>
            </small>
        </h1>
    </div>
</div>

<!-- Metrics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card metric-card text-center">
            <div class="card-body">
                <i class="fas fa-microchip fa-2x mb-2"></i>
                <h3>{{ $totalDevices }}</h3>
                <p class="mb-0">Total Devices</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white text-center">
            <div class="card-body">
                <i class="fas fa-wifi fa-2x mb-2"></i>
                <h3>{{ $onlineDevices }}</h3>
                <p class="mb-0">Online</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white text-center">
            <div class="card-body">
                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                <h3>{{ $offlineDevices }}</h3>
                <p class="mb-0">Offline</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white text-center">
            <div class="card-body">
                <i class="fas fa-battery-quarter fa-2x mb-2"></i>
                <h3>{{ $lowBatteryDevices }}</h3>
                <p class="mb-0">Low Battery</p>
            </div>
        </div>
    </div>
</div>

<!-- Environment Stats -->
@if($avgTemperature || $avgHumidity)
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-thermometer-half fa-3x text-danger mb-3"></i>
                <h4>Average Temperature</h4>
                <h2 class="text-danger">{{ number_format($avgTemperature, 1) }}°C</h2>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-tint fa-3x text-info mb-3"></i>
                <h4>Average Humidity</h4>
                <h2 class="text-info">{{ number_format($avgHumidity, 1) }}%</h2>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Recent Devices -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Recent Device Activity
                </h5>
                <a href="{{ route('devices.index') }}" class="btn btn-sm btn-outline-primary">
                    View All Devices
                </a>
            </div>
            <div class="card-body">
                @if($recentDevices->count() > 0)
                    <div class="row">
                        @foreach($recentDevices as $device)
                        <div class="col-md-4 mb-3">
                            <div class="card device-card {{ $device->status ? 'online' : 'offline' }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ $device->name }}</h6>
                                        <span class="badge bg-{{ $device->status_color }}">
                                            {{ $device->status_text }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $device->location }}
                                    </p>
                                    
                                    <div class="row small">
                                        @if($device->temperature)
                                        <div class="col-6">
                                            <i class="fas fa-thermometer-half text-danger me-1"></i>
                                            {{ $device->temperature }}°C
                                        </div>
                                        @endif
                                        @if($device->humidity)
                                        <div class="col-6">
                                            <i class="fas fa-tint text-info me-1"></i>
                                            {{ $device->humidity }}%
                                        </div>
                                        @endif
                                    </div>
                                    
                                    @if($device->battery_level)
                                    <div class="mt-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small>Battery:</small>
                                            <small class="{{ $device->battery_level < 30 ? 'battery-low' : '' }}">
                                                <i class="fas fa-battery-{{ $device->battery_level > 75 ? 'full' : ($device->battery_level > 50 ? 'three-quarters' : ($device->battery_level > 25 ? 'half' : 'quarter')) }}"></i>
                                                {{ $device->battery_level }}%
                                            </small>
                                        </div>
                                        <div class="progress mt-1" style="height: 4px;">
                                            <div class="progress-bar bg-{{ $device->battery_color }}" 
                                                 style="width: {{ $device->battery_level }}%"></div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="mt-2">
                                        <a href="{{ route('devices.show', $device) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-circle fa-3x text-muted mb-3"></i>
                        <h5>No devices found</h5>
                        <p class="text-muted">Add some IoT devices to get started.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

<!-- ======================= -->
<!-- DEVICES INDEX -->
<!-- resources/views/devices/index.blade.php -->
<!-- ======================= -->
@extends('layouts.app')

@section('title', 'IoT Dashboard - Devices')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-3">
            <i class="fas fa-devices me-2"></i>Device Management
        </h1>
    </div>
</div>

<!-- Devices Grid -->
<div class="row">
    @forelse($devices as $device)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card device-card {{ $device->status ? 'online' : 'offline' }} h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title mb-1">{{ $device->name }}</h5>
                        <p class="text-muted small mb-0">{{ $device->type }}</p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-{{ $device->status_color }} mb-2">
                            {{ $device->status_text }}
                        </span>
                        @if($device->battery_level)
                        <br>
                        <small class="text-muted {{ $device->battery_level < 30 ? 'battery-low' : '' }}">
                            <i class="fas fa-battery-{{ $device->battery_level > 75 ? 'full' : ($device->battery_level > 50 ? 'three-quarters' : ($device->battery_level > 25 ? 'half' : 'quarter')) }}"></i>
                            {{ $device->battery_level }}%
                        </small>
                        @endif
                    </div>
                </div>
                
                <p class="text-muted mb-3">
                    <i class="fas fa-map-marker-alt me-2"></i>{{ $device->location }}
                </p>
                
                @if($device->temperature || $device->humidity)
                <div class="row mb-3">
                    @if($device->temperature)
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-thermometer-half text-danger me-2"></i>
                            <div>
                                <strong>{{ $device->temperature }}°C</strong>
                                <br><small class="text-muted">Temperature</small>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($device->humidity)
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-tint text-info me-2"></i>
                            <div>
                                <strong>{{ $device->humidity }}%</strong>
                                <br><small class="text-muted">Humidity</small>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
                
                @if($device->last_ping)
                <p class="text-muted small mb-3">
                    <i class="fas fa-clock me-1"></i>
                    Last ping: {{ $device->last_ping->diffForHumans() }}
                </p>
                @endif
                
                <div class="d-flex gap-2 mt-auto">
                    <a href="{{ route('devices.show', $device) }}" 
                       class="btn btn-outline-primary btn-sm flex-fill">
                        <i class="fas fa-eye me-1"></i>Details
                    </a>
                    
                    <form action="{{ route('devices.toggle', $device) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" 
                                class="btn btn-{{ $device->status ? 'outline-danger' : 'outline-success' }} btn-sm">
                            <i class="fas fa-power-off me-1"></i>
                            {{ $device->status ? 'Turn Off' : 'Turn On' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-exclamation-circle fa-4x text-muted mb-3"></i>
                <h4>No devices found</h4>
                <p class="text-muted">Your IoT devices will appear here once they're registered.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($devices->hasPages())
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-center">
            {{ $devices->links() }}
        </div>
    </div>
</div>
@endif
@endsection

<!-- ======================= -->
<!-- DEVICE DETAILS -->
<!-- resources/views/devices/show.blade.php -->
<!-- ======================= -->
@extends('layouts.app')

@section('title', 'Device Details - ' . $device->name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('devices.index') }}">Devices</a></li>
                <li class="breadcrumb-item active">{{ $device->name }}</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">
                <i class="fas fa-microchip me-2"></i>{{ $device->name }}
                <span class="badge bg-{{ $device->status_color }} ms-2">{{ $device->status_text }}</span>
            </h1>
            
            <div class="btn-group">
                <form action="{{ route('devices.toggle', $device) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-{{ $device->status ? 'danger' : 'success' }}">
                        <i class="fas fa-power-off me-1"></i>
                        {{ $device->status ? 'Turn Off' : 'Turn On' }}
                    </button>
                </form>
                <a href="{{ route('devices.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Devices
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Device Info Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Device Information
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Type:</strong></td>
                        <td>{{ $device->type }}</td>
                    </tr>
                    <tr>
                        <td><strong>Location:</strong></td>
                        <td>{{ $device->location }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="badge bg-{{ $device->status_color }}">
                                {{ $device->status_text }}
                            </span>
                        </td>
                    </tr>
                    @if($device->last_ping)
                    <tr>
                        <td><strong>Last Ping:</strong></td>
                        <td>{{ $device->last_ping->format('M d, Y H:i:s') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td><strong>Added:</strong></td>
                        <td>{{ $device->created_at->format('M d, Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Current Readings
                </h5>
            </div>
            <div class="card-body">
                @if($device->temperature)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-thermometer-half text-danger me-2"></i>
                        <span>Temperature</span>
                    </div>
                    <strong class="text-danger">{{ $device->temperature }}°C</strong>
                </div>
                @endif
                
                @if($device->humidity)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-tint text-info me-2"></i>
                        <span>Humidity</span>
                    </div>
                    <strong class="text-info">{{ $device->humidity }}%</strong>
                </div>
                @endif
                
                @if(!$device->temperature && !$device->humidity)
                <div class="text-center text-muted">
                    <i class="fas fa-exclamation-circle mb-2"></i>
                    <p>No sensor readings available</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    @if($device->battery_level)
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-battery-three-quarters me-2"></i>Battery Status
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-battery-{{ $device->battery_level > 75 ? 'full' : ($device->battery_level > 50 ? 'three-quarters' : ($device->battery_level > 25 ? 'half' : 'quarter')) }} fa-3x text-{{ $device->battery_color }}"></i>
                </div>
                <h3 class="text-{{ $device->battery_color }} {{ $device->battery_level < 30 ? 'battery-low' : '' }}">
                    {{ $device->battery_level }}%
                </h3>
                <div class="progress mt-2">
                    <div class="progress-bar bg-{{ $device->battery_color }}" 
                         style="width: {{ $device->battery_level }}%"></div>
                </div>
                @if($device->battery_level < 30)
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Battery level is low!
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Sensor Readings History -->
@if($recentReadings->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Recent Sensor Readings
                </h5>
            </div>
            <div class="card-body">
                @foreach($recentReadings as $sensorType => $readings)
                <div class="mb-4">
                    <h6 class="text-capitalize mb-3">
                        <i class="fas fa-{{ $sensorType == 'temperature' ? 'thermometer-half' : ($sensorType == 'humidity' ? 'tint' : 'chart-line') }} me-2"></i>
                        {{ ucfirst($sensorType) }} Readings
                    </h6>
                    
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Value</th>
                                    <th>Unit</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($readings->take(5) as $reading)
                                <tr>
                                    <td><strong>{{ $reading->value }}</strong></td>
                                    <td>{{ $reading->unit }}</td>
                                    <td class="text-muted">{{ $reading->created_at->format('M d, H:i:s') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
                <h4>No Sensor Data</h4>
                <p class="text-muted">This device hasn't reported any sensor readings yet.</p>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
// Auto-refresh device data every 30 seconds
setInterval(function() {
    // You could implement real-time updates here
    // For now, just refresh the page every 5 minutes
}, 30000);

// Add confirmation for power toggle
document.querySelector('form[action*="toggle"]')?.addEventListener('submit', function(e) {
    const action = this.querySelector('button').textContent.includes('Turn Off') ? 'turn off' : 'turn on';
    if (!confirm(`Are you sure you want to ${action} this device?`)) {
        e.preventDefault();
    }
});
</script>
@endpush