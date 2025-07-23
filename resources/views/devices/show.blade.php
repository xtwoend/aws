@extends('layouts.tabler')

@section('title', 'Device Details')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('devices.index') }}">Devices</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $device->code }}</li>
                    </ol>
                </nav>
                <h2 class="page-title">
                    {{ $device->name ?: $device->code }}
                    <span class="badge bg-{{ $device->status_color }} ms-2">{{ $device->status_text }}</span>
                    <span class="badge bg-{{ $device->online_status_color }} ms-1">{{ $device->online_status_text }}</span>
                </h2>
                @if($device->location)
                    <div class="page-subtitle">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-4.5 -7l-7 -4.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
                        {{ $device->location }}
                    </div>
                @endif
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('aws-logger.show', $device->code) }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3v18h18" /><path d="M9 9l4 4l4 -4" /></svg>
                        View AWS Logs
                    </a>
                    <a href="{{ route('devices.edit', $device) }}" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                        Edit Device
                    </a>
                    <a href="{{ route('devices.index') }}" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 0" /><path d="M11 18l6 -6" /><path d="M11 6l6 6" /></svg>
                        Back to Devices
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Device Info Card -->
        <div class="row row-cards mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Device Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-5">Device Code:</dt>
                                    <dd class="col-7"><code>{{ $device->code }}</code></dd>
                                    
                                    <dt class="col-5">Name:</dt>
                                    <dd class="col-7">{{ $device->name ?: 'N/A' }}</dd>
                                    
                                    <dt class="col-5">Location:</dt>
                                    <dd class="col-7">{{ $device->location ?: 'N/A' }}</dd>
                                    
                                    <dt class="col-5">Type:</dt>
                                    <dd class="col-7">
                                        @if($device->type)
                                            <span class="badge bg-secondary">{{ $device->type }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-5">Serial Number:</dt>
                                    <dd class="col-7">{{ $device->serial_number ?: 'N/A' }}</dd>
                                    
                                    <dt class="col-5">Status:</dt>
                                    <dd class="col-7">
                                        <span class="badge bg-{{ $device->status_color }}">{{ $device->status_text }}</span>
                                    </dd>
                                    
                                    <dt class="col-5">Created:</dt>
                                    <dd class="col-7">{{ $device->created_at->format('M j, Y g:i A') }}</dd>
                                    
                                    <dt class="col-5">Updated:</dt>
                                    <dd class="col-7">{{ $device->updated_at->format('M j, Y g:i A') }}</dd>
                                </dl>
                            </div>
                        </div>
                        
                        @if($device->description)
                        <div class="row mt-3">
                            <div class="col-12">
                                <dt>Description:</dt>
                                <dd class="mt-2">{{ $device->description }}</dd>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row row-cards mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm mb-3">
                    <div class="card-body dashboard-stats-card">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12h4l3 8l4-16l3 8h4" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ number_format($stats['total_logs']) }}</div>
                                <div class="text-secondary">Total Logs</div>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 7l0 5l3 3" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ number_format($stats['logs_today']) }}</div>
                                <div class="text-secondary">Logs Today</div>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10l5 -6l5 6" /><path d="M21 10l-2 8a2 2.18 0 0 1 -2 2h-10a2 2.18 0 0 1 -2 -2l-2 -8z" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ number_format($stats['logs_this_week']) }}</div>
                                <div class="text-secondary">This Week</div>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ number_format($stats['logs_this_month']) }}</div>
                                <div class="text-secondary">This Month</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Data & Actions -->
        <div class="row row-cards mb-4">
            <!-- Latest Log Data -->
            @if($device->latestLog)
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Latest Data</h3>
                        <div class="card-actions">
                            <span class="text-secondary">{{ $device->latestLog->terminal_time->format('M j, Y g:i A') }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-lg-3">
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="avatar avatar-sm bg-blue-lt">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 13.5a4 4 0 1 0 4 0v-8.5a2 2 0 0 0 -4 0v8.5" /><path d="M10 9l4 0" /></svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="text-secondary">Temperature</div>
                                            <div class="h3 text-blue mb-0">{{ $device->latestLog->formatted_temperature }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="avatar avatar-sm bg-green-lt">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-green" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546z" /></svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="text-secondary">Humidity</div>
                                            <div class="h3 text-green mb-0">{{ $device->latestLog->formatted_humidity }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="avatar avatar-sm bg-yellow-lt">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-yellow" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 12a6 6 0 1 1 12 0" /><path d="M6 12h12" /><path d="M9 12v3a3 3 0 0 0 6 0v-3" /></svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="text-secondary">Pressure</div>
                                            <div class="h3 text-yellow mb-0">{{ $device->latestLog->formatted_pressure }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="avatar avatar-sm bg-red-lt">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-red" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 8a5 5 0 1 1 10 0v1a5 5 0 0 1 -5 5a5 5 0 0 1 -5 -5v-1z" /><path d="M10 14v6" /></svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="text-secondary">PM2.5</div>
                                            <div class="h3 text-red mb-0">{{ $device->latestLog->formatted_pm25 }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-sm-6 col-lg-3">
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="avatar avatar-sm bg-azure-lt">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-azure" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 8h8.5a2.5 2.5 0 1 0 -2.34 -3.24" /><path d="M3 12h15.5a2.5 2.5 0 1 1 -2.34 3.24" /><path d="M4 16h5.5a2.5 2.5 0 1 1 -2.34 3.24" /></svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="text-secondary">Wind Speed</div>
                                            <div class="h4 text-azure mb-0">{{ $device->latestLog->formatted_wind_speed }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="avatar avatar-sm bg-purple-lt">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-purple" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /><path d="M17 4a2 2 0 0 0 2 2a2 2 0 0 0 -2 2a2 2 0 0 0 -2 -2a2 2 0 0 0 2 -2" /><path d="M19 11h2m-1 -1v2" /></svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="text-secondary">Wind Direction</div>
                                            <div class="h4 text-purple mb-0">{{ $device->latestLog->formatted_wind_direction }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="avatar avatar-sm bg-cyan-lt">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-cyan" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 16v-8a5 5 0 0 1 10 0v8" /><path d="M8 13h8" /><path d="M12 16l-2 3" /><path d="M12 16l2 3" /><path d="M10 19l2 -3l2 3" /></svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="text-secondary">Rainfall</div>
                                            <div class="h4 text-cyan mb-0">{{ $device->latestLog->formatted_rainfall }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="avatar avatar-sm bg-orange-lt">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-orange" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="text-secondary">Solar Radiation</div>
                                            <div class="h4 text-orange mb-0">{!! $device->latestLog->formatted_solar_radiation !!}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Latest Data</h3>
                    </div>
                    <div class="card-body">
                        <div class="empty">
                            <div class="empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3v18h18" /><path d="M9 9l4 4l4 -4" /></svg>
                            </div>
                            <p class="empty-title">No data available</p>
                            <p class="empty-subtitle text-secondary">This device hasn't logged any data yet.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Actions -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success" onclick="toggleStatus()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                                {{ $device->status ? 'Deactivate' : 'Activate' }} Device
                            </button>
                            
                            <a href="{{ route('devices.edit', $device) }}" class="btn btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                Edit Device
                            </a>
                            
                            <button type="button" class="btn btn-outline-danger" onclick="deleteDevice()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                Delete Device
                            </button>
                        </div>
                        
                        <hr>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('aws-logger.show', $device->code) }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3v18h18" /><path d="M9 9l4 4l4 -4" /></svg>
                                View All AWS Logs
                            </a>
                            
                            @if($stats['total_logs'] > 0)
                            <a href="{{ route('aws-logger.export', ['device' => $device->code]) }}" class="btn btn-outline-info">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg>
                                Export Device Data
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Online Status Card -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Status Information</h3>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-6">Online Status:</dt>
                            <dd class="col-6">
                                <span class="badge bg-{{ $device->online_status_color }}">{{ $device->online_status_text }}</span>
                            </dd>
                            
                            <dt class="col-6">Last Seen:</dt>
                            <dd class="col-6">
                                <div>{{ $device->last_seen }}</div>
                                @if($device->latestLog)
                                    <small class="text-muted">{{ $device->latestLog->terminal_time->format('M j, Y H:i') }}</small>
                                @endif
                            </dd>
                            
                            <dt class="col-6">Active Since:</dt>
                            <dd class="col-6">{{ $device->created_at->format('M j, Y') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Logs -->
        @if($recentLogs->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Logs (Last 10)</h3>
                        <div class="card-actions">
                            <a href="{{ route('aws-logger.show', $device->code) }}" class="btn btn-sm btn-primary">
                                View All Logs
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Temperature</th>
                                        <th>Humidity</th>
                                        <th>Pressure</th>
                                        <th>PM2.5</th>
                                        <th>Wind Speed</th>
                                        <th>Rainfall</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentLogs as $log)
                                    <tr>
                                        <td>
                                            <strong>{{ $log->terminal_time->format('M j, g:i A') }}</strong>
                                            <br>
                                            <small class="text-secondary">{{ $log->terminal_time->diffForHumans() }}</small>
                                        </td>
                                        <td>{{ $log->formatted_temperature }}</td>
                                        <td>{{ $log->formatted_humidity }}</td>
                                        <td>{{ $log->formatted_pressure }}</td>
                                        <td>{{ $log->formatted_pm25 }}</td>
                                        <td>{{ $log->formatted_wind_speed }}</td>
                                        <td>{{ $log->formatted_rainfall }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete device <strong>{{ $device->code }}</strong>?</p>
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="{{ route('devices.destroy', $device) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus() {
    fetch(`/devices/{{ $device->id }}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error updating device status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating device status');
    });
}

function deleteDevice() {
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
