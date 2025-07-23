@extends('layouts.tabler')

@section('title', 'Dashboard')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Overview</div>
                <h2 class="page-title">AWS Logger Dashboard</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <button class="btn btn-primary" onclick="refreshDashboard()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <!-- Main Statistics Cards -->
            <div class="col-12 mb-4">
                <div class="row row-cards">
                    <!-- Total Devices -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm dashboard-stats-card mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-primary text-white avatar avatar-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-10z" /><path d="M7 10h10" /><path d="M7 14h10" /></svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium h2 mb-0">{{ number_format($totalDevices) }}</div>
                                        <div class="text-secondary">Total Devices</div>
                                        <div class="small text-muted mt-1">
                                            <span class="text-success">{{ $activeDevices }}</span> active
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total AWS Records -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm dashboard-stats-card mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-success text-white avatar avatar-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 12h6" /><path d="M9 16h6" /></svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium h2 mb-0">{{ number_format($totalAwsRecords) }}</div>
                                        <div class="text-secondary">Total Records</div>
                                        <div class="small text-muted mt-1">
                                            <span class="text-info">{{ number_format($todayRecords) }}</span> today
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Online Devices -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm dashboard-stats-card mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-warning text-white avatar avatar-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M12 1l0 6" /><path d="M12 17l0 6" /><path d="M5.636 5.636l4.243 4.243" /><path d="M14.121 14.121l4.243 4.243" /><path d="M1 12l6 0" /><path d="M17 12l6 0" /><path d="M5.636 18.364l4.243 -4.243" /><path d="M14.121 9.879l4.243 -4.243" /></svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium h2 mb-0">{{ $onlineDevicesCount }}</div>
                                        <div class="text-secondary">Devices Online</div>
                                        <div class="small text-muted mt-1">
                                            Last 60 minutes
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Users -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm dashboard-stats-card mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-info text-white avatar avatar-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium h2 mb-0">{{ $totalUsers }}</div>
                                        <div class="text-secondary">Total Users</div>
                                        <div class="small text-muted mt-1">
                                            System users
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Latest Data Section -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Latest Data Entry</h3>
                        @if($latestData)
                            <div class="card-actions">
                                <span class="badge bg-success-lt text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                    {{ $latestData->terminal_time->diffForHumans() }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($latestData)
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ $latestData->device_id }}</h4>
                                    <p class="text-muted">
                                        @if($latestData->device && $latestData->device->name)
                                            {{ $latestData->device->name }}
                                        @else
                                            Unknown Device
                                        @endif
                                        @if($latestData->device_location)
                                            • {{ $latestData->device_location }}
                                        @endif
                                    </p>
                                    <dl class="row">
                                        <dt class="col-5">Temperature:</dt>
                                        <dd class="col-7"><span class="badge bg-orange-lt">{{ $latestData->formatted_temperature }}</span></dd>
                                        <dt class="col-5">Humidity:</dt>
                                        <dd class="col-7"><span class="badge bg-blue-lt">{{ $latestData->formatted_humidity }}</span></dd>
                                        <dt class="col-5">Pressure:</dt>
                                        <dd class="col-7"><span class="badge bg-purple-lt">{{ $latestData->formatted_pressure }}</span></dd>
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-6">Wind Speed:</dt>
                                        <dd class="col-6"><span class="badge bg-green-lt">{{ $latestData->formatted_wind_speed }}</span></dd>
                                        <dt class="col-6">Wind Direction:</dt>
                                        <dd class="col-6"><span class="badge bg-teal-lt">{{ $latestData->wind_direction }}°</span></dd>
                                        <dt class="col-6">Rainfall:</dt>
                                        <dd class="col-6"><span class="badge bg-cyan-lt">{{ $latestData->formatted_rainfall }}</span></dd>
                                        <dt class="col-6">Solar Radiation:</dt>
                                        <dd class="col-6"><span class="badge bg-yellow-lt">{{ $latestData->formatted_solar_radiation }}</span></dd>
                                        <dt class="col-6">Recorded:</dt>
                                        <dd class="col-6"><small class="text-muted">{{ $latestData->terminal_time->format('M d, Y H:i') }}</small></dd>
                                    </dl>
                                </div>
                            </div>
                        @else
                            <div class="empty">
                                <div class="empty-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" /></svg>
                                </div>
                                <p class="empty-title">No data available</p>
                                <p class="empty-subtitle text-muted">No weather data has been received yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Stats</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="h2 mb-0 text-primary">{{ number_format($thisWeekRecords) }}</div>
                                    <div class="text-muted">This Week</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="h2 mb-0 text-success">
                                        @if($avgTempToday)
                                            {{ number_format($avgTempToday, 1) }}°C
                                        @else
                                            --
                                        @endif
                                    </div>
                                    <div class="text-muted">Avg Temp Today</div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h4 class="mb-3">Quick Actions</h4>
                                <div class="btn-list">
                                    <a href="{{ route('aws-logger.index') }}" class="btn btn-primary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4h7a1 1 0 0 1 1 1v7a1 1 0 0 1 -1 1h-7a1 1 0 0 1 -1 -1v-7a1 1 0 0 1 1 -1z" /><path d="M13 4h7a1 1 0 0 1 1 1v7a1 1 0 0 1 -1 1h-7a1 1 0 0 1 -1 -1v-7a1 1 0 0 1 1 -1z" /><path d="M3 14h7a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-7a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" /><path d="M13 14h7a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-7a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" /></svg>
                                        AWS Logger
                                    </a>
                                    <a href="{{ route('devices.index') }}" class="btn btn-secondary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-10z" /><path d="M7 10h10" /><path d="M7 14h10" /></svg>
                                        Devices
                                    </a>
                                    <a href="{{ route('api-test') }}" class="btn btn-info btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 8l-4 4l4 4" /><path d="M17 8l4 4l-4 4" /><path d="M14 4l-4 16" /></svg>
                                        API Test
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Device Activity -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Device Activity</h3>
                        <div class="card-actions">
                            <small class="text-muted">Last 5 active devices</small>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($recentDeviceActivity->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>Device ID</th>
                                            <th>Last Activity</th>
                                            <th>Avg Temperature</th>
                                            <th>Record Count</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentDeviceActivity as $activity)
                                            <tr>
                                                <td>
                                                    <strong>{{ $activity->device_id }}</strong>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ Carbon\Carbon::parse($activity->last_activity)->diffForHumans() }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-orange-lt">{{ number_format($activity->avg_temp, 1) }}°C</span>
                                                </td>
                                                <td>
                                                    {{ number_format($activity->record_count) }}
                                                </td>
                                                <td>
                                                    @if(Carbon\Carbon::parse($activity->last_activity)->diffInMinutes() < 60)
                                                        <span class="badge bg-success-lt text-success">Online</span>
                                                    @else
                                                        <span class="badge bg-danger-lt text-danger">Offline</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('aws-logger.show', $activity->device_id) }}" class="btn btn-sm btn-outline-primary">
                                                        View Details
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty">
                                <div class="empty-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-10z" /><path d="M7 10h10" /><path d="M7 14h10" /></svg>
                                </div>
                                <p class="empty-title">No device activity</p>
                                <p class="empty-subtitle text-muted">No devices have reported data yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Welcome Message -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Welcome, {{ Auth::user()->name }}!</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="mb-3">Welcome to the AWS Logger Dashboard. This system monitors weather stations and collects environmental data in real-time.</p>
                                <p class="text-muted mb-0">Use the navigation menu to access different sections: AWS Logger data, Device Management, and API testing tools.</p>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h4 class="card-title">Your Account</h4>
                                        <dl class="row">
                                            <dt class="col-5">Email:</dt>
                                            <dd class="col-7 small">{{ Auth::user()->email }}</dd>
                                            <dt class="col-5">Joined:</dt>
                                            <dd class="col-7 small">{{ Auth::user()->created_at->format('M d, Y') }}</dd>
                                        </dl>
                                        <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                                            Edit Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let autoRefreshEnabled = true;
let refreshInterval;

function refreshDashboard() {
    // Show loading state
    const btn = document.querySelector('[onclick="refreshDashboard()"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<svg class="icon icon-sm me-2 spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"/></svg>Refreshing...';
    btn.disabled = true;
    
    // Reload the page after a short delay
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

function updateStatCards() {
    fetch('{{ route("dashboard.data") }}')
        .then(response => response.json())
        .then(data => {
            // Update the stat cards with new data
            console.log('Dashboard data updated:', data);
            
            // You could update specific elements here instead of full page reload
            // For example:
            // document.querySelector('.total-devices-count').textContent = data.total_devices;
            
        })
        .catch(error => {
            console.error('Error fetching dashboard data:', error);
        });
}

function startAutoRefresh() {
    if (autoRefreshEnabled && !refreshInterval) {
        refreshInterval = setInterval(() => {
            console.log('Auto-refreshing dashboard data...');
            updateStatCards();
        }, 300000); // 5 minutes
        
        console.log('Auto-refresh started (every 5 minutes)');
    }
}

function stopAutoRefresh() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
        refreshInterval = null;
        console.log('Auto-refresh stopped');
    }
}

// Initialize auto-refresh when page loads
document.addEventListener('DOMContentLoaded', function() {
    startAutoRefresh();
    
    // Add animation for refresh button
    const style = document.createElement('style');
    style.textContent = `
        .spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
});

// Stop auto-refresh when page is hidden
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        stopAutoRefresh();
    } else {
        startAutoRefresh();
    }
});
</script>
@endpush
