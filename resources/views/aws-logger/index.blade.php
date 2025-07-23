@extends('layouts.tabler')

@section('title', 'AWS Logger Dashboard')

@section('content')
<div class="row row-deck row-cards">
    <!-- Summary Statistics -->
    <div class="col-12 mb-4">
        <div class="row row-cards">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm mb-3">
                    <div class="card-body dashboard-stats-card">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.5 12c0 .8-.7 1.5-1.5 1.5s-1.5-.7-1.5-1.5.7-1.5 1.5-1.5 1.5.7 1.5 1.5z" /><path d="M9.5 12c0 .8-.7 1.5-1.5 1.5s-1.5-.7-1.5-1.5.7-1.5 1.5-1.5 1.5.7 1.5 1.5z" transform="translate(6 0)" /><path d="M9.5 12c0 .8-.7 1.5-1.5 1.5s-1.5-.7-1.5-1.5.7-1.5 1.5-1.5 1.5.7 1.5 1.5z" transform="translate(-3 -6)" /><path d="M9.5 12c0 .8-.7 1.5-1.5 1.5s-1.5-.7-1.5-1.5.7-1.5 1.5-1.5 1.5.7 1.5 1.5z" transform="translate(0 6)" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $stats['total_devices'] }}</div>
                                <div class="text-secondary">Active Devices</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm mb-3">
                    <div class="card-body dashboard-stats-card">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-success text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12h4l3 8l4-16l3 8h4" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ number_format($stats['total_records']) }}</div>
                                <div class="text-secondary">Total Records</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm mb-3">
                    <div class="card-body dashboard-stats-card">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-warning text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 13.5a4 4 0 1 0 4 0v-8.5a2 2 0 0 0 -4 0v8.5" /><path d="M10 9l4 0" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ number_format($stats['avg_temperature'], 1) }}°C</div>
                                <div class="text-secondary">Avg Temperature</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm mb-3">
                    <div class="card-body dashboard-stats-card">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-info text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-12" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ number_format($stats['avg_humidity'], 1) }}%</div>
                                <div class="text-secondary">Avg Humidity</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Device Filter -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filters</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('aws-logger.index') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Device</label>
                                <select class="form-select" name="device_id" onchange="this.form.submit()">
                                    <option value="">All Devices</option>
                                    @foreach($devices as $device)
                                        <option value="{{ $device->code }}" {{ request('device_id') == $device->code ? 'selected' : '' }}>
                                            {{ $device->code }}
                                            @if($device->name)
                                                - {{ $device->name }}
                                            @endif
                                            @if($device->location)
                                                ({{ $device->location }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" onchange="this.form.submit()">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Only</option>
                                    <option value="online" {{ request('status') == 'online' ? 'selected' : '' }}>Online Only</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Time Range</label>
                                <select class="form-select" name="time_range" onchange="this.form.submit()">
                                    <option value="">All Time</option>
                                    <option value="today" {{ request('time_range') == 'today' ? 'selected' : '' }}>Today</option>
                                    <option value="week" {{ request('time_range') == 'week' ? 'selected' : '' }}>This Week</option>
                                    <option value="month" {{ request('time_range') == 'month' ? 'selected' : '' }}>This Month</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Latest Readings -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Latest Weather Readings</h3>
                <div class="card-actions">
                    <button class="btn btn-outline-primary btn-sm" onclick="refreshData()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                        Refresh
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>Device</th>
                                <th>Location</th>
                                <th>Time</th>
                                <th>Temperature</th>
                                <th>Humidity</th>
                                <th>Wind</th>
                                <th>Rainfall</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestRecords as $record)
                            <tr>
                                <td>
                                    <strong>{{ $record->device_id }}</strong>
                                    @if($record->device && $record->device->name)
                                        <br><small class="text-muted">{{ $record->device->name }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($record->device && $record->device->location)
                                        {{ $record->device->location }}
                                        <br><small class="text-muted">{{ $record->device->type ?: 'Unknown Type' }}</small>
                                    @else
                                        {{ $record->device_location ?: 'N/A' }}
                                    @endif
                                </td>
                                <td class="text-secondary">
                                    {{ $record->terminal_time->format('M d, H:i') }}
                                    <br><small class="text-muted">{{ $record->terminal_time->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $record->temperature_status }}">
                                        {{ number_format($record->temperature, 1) }}°C
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $record->humidity_status }}">
                                        {{ number_format($record->humidity, 1) }}%
                                    </span>
                                </td>
                                <td>
                                    {{ number_format($record->wind_speed, 1) }} m/s
                                    <small class="text-muted">{{ $record->wind_direction_text }}</small>
                                </td>
                                <td>
                                    @if($record->rainfall > 0)
                                        <span class="text-blue">{{ number_format($record->rainfall, 1) }}mm</span>
                                    @else
                                        <span class="text-muted">0mm</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-list">
                                        <a href="{{ route('aws-logger.show', $record->device_id) }}" class="btn btn-outline-primary btn-sm">
                                            View Details
                                        </a>
                                        @if($record->device)
                                            <a href="{{ route('devices.show', $record->device) }}" class="btn btn-outline-info btn-sm">
                                                Device Info
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Chart -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Temperature Trends (24h)</h3>
            </div>
            <div class="card-body">
                <div id="temperature-chart"></div>
            </div>
        </div>
    </div>

    <!-- Device Status -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Device Status</h3>
                <div class="card-actions">
                    <a href="#" class="btn btn-outline-success btn-sm" onclick="exportData()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg>
                        Export CSV
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($devices as $device)
                        @php
                            $latestData = $latestRecords->where('device_id', $device->code)->first();
                        @endphp
                        <div class="col-md-4 col-lg-3 mb-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="avatar bg-primary text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M3 7a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-10z"/>
                                                    <path d="M7 10h10"/>
                                                    <path d="M7 14h10"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $device->name ?: $device->code }}
                                            </div>
                                            <div class="text-secondary small">
                                                @if($device->location)
                                                    {{ $device->location }}
                                                @else
                                                    {{ $device->code }}
                                                @endif
                                            </div>
                                            <div class="text-muted small">
                                                @if($latestData)
                                                    Last: {{ $latestData->terminal_time->diffForHumans() }}
                                                @else
                                                    No data received
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            @if($latestData && $latestData->terminal_time->diffInMinutes() < 60)
                                                <div class="badge bg-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"/>
                                                        <path d="M12 1l0 6"/>
                                                        <path d="M12 17l0 6"/>
                                                        <path d="M5.636 5.636l4.243 4.243"/>
                                                        <path d="M14.121 14.121l4.243 4.243"/>
                                                        <path d="M1 12l6 0"/>
                                                        <path d="M17 12l6 0"/>
                                                        <path d="M5.636 18.364l4.243 -4.243"/>
                                                        <path d="M14.121 9.879l4.243 -4.243"/>
                                                    </svg>
                                                    Online
                                                </div>
                                            @else
                                                <div class="badge bg-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M3 12l6 0"/>
                                                        <path d="M15 12l6 0"/>
                                                        <path d="M12 3l0 6"/>
                                                        <path d="M12 15l0 6"/>
                                                    </svg>
                                                    Offline
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col">
                                            <div class="btn-list">
                                                <a href="{{ route('aws-logger.show', $device->code) }}" class="btn btn-outline-primary btn-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M3 3v18h18"/>
                                                        <path d="M9 9l4 4l4 -4"/>
                                                    </svg>
                                                    View Data
                                                </a>
                                                <a href="{{ route('devices.show', $device) }}" class="btn btn-outline-info btn-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/>
                                                        <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"/>
                                                        <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"/>
                                                    </svg>
                                                    Device Info
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="empty">
                                <div class="empty-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 7a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-10z"/>
                                        <path d="M7 10h10"/>
                                        <path d="M7 14h10"/>
                                    </svg>
                                </div>
                                <p class="empty-title">No devices found</p>
                                <p class="empty-subtitle text-muted">
                                    No devices have been registered yet.
                                </p>
                                <div class="empty-action">
                                    <a href="{{ route('devices.create') }}" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 5l0 14"/>
                                            <path d="M5 12l14 0"/>
                                        </svg>
                                        Add your first device
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Temperature chart
    var temperatureData = @json($chartData->map(function($item) {
        return [
            'x' => $item->terminal_time->format('Y-m-d H:i:s'),
            'y' => (float) $item->temperature
        ];
    })->values());

    var temperatureChart = new ApexCharts(document.querySelector("#temperature-chart"), {
        chart: {
            type: 'line',
            height: 200,
            toolbar: {
                show: false
            }
        },
        series: [{
            name: 'Temperature',
            data: temperatureData
        }],
        xaxis: {
            type: 'datetime',
            labels: {
                format: 'HH:mm'
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return val.toFixed(1) + '°C';
                }
            }
        },
        stroke: {
            curve: 'smooth'
        },
        colors: ['#206bc4'],
        tooltip: {
            x: {
                format: 'MMM dd, HH:mm'
            }
        }
    });

    temperatureChart.render();
});

function refreshData() {
    location.reload();
}

function exportData() {
    window.open('{{ route("aws-logger.export") }}', '_blank');
}
</script>
@endpush
