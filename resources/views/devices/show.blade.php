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