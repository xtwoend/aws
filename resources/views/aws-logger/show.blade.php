@extends('layouts.tabler')

@section('title', 'AWS Logger Details - ' . $deviceId)

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">AWS Logger</div>                                    
                    <h2 class="page-title">
                        @if($deviceRecord && $deviceRecord->name)
                            {{ $deviceRecord->name }} ({{ $deviceId }})
                            @if($deviceRecord->status)
                                <span class="badge bg-success ms-2">Active</span>
                            @else
                                <span class="badge bg-secondary ms-2">Inactive</span>
                            @endif
                        @else
                            Device {{ $deviceId }}
                        @endif
                    </h2>
                @if($deviceRecord && $deviceRecord->location)
                    <div class="page-subtitle">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-4.5 -7l-7 -4.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
                        {{ $deviceRecord->location }}
                        @if($deviceRecord->type)
                            • {{ $deviceRecord->type }}
                        @endif
                    </div>
                @endif
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    @if($deviceRecord)
                        <a href="{{ route('devices.show', $deviceRecord) }}" class="btn btn-outline-info">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-10z" /><path d="M7 10h10" /><path d="M7 14h10" /></svg>
                            Device Settings
                        </a>
                    @endif
                    <a href="{{ route('aws-logger.index') }}" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                        Back to Dashboard
                    </a>
                    <a href="{{ route('aws-logger.export', ['device_id' => $deviceId]) }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg>
                        Export Data
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <!-- Device Info -->
            <div class="col-12 mb-4">
                <div class="card device-status-card @if($deviceStats['last_record'] && $deviceStats['last_record']->terminal_time->diffInMinutes() >= 60) offline @endif">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-10z" /><path d="M7 10h10" /><path d="M7 14h10" /></svg>
                            Device Information
                        </h3>
                        @if($deviceRecord)
                            <div class="card-actions">
                                @if($deviceRecord->status)
                                    <span class="badge bg-success-lt text-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                        Active Device
                                    </span>
                                @else
                                    <span class="badge bg-secondary-lt text-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 18l6 -6l6 6" /><path d="M6 6l6 6l6 -6" /></svg>
                                        Inactive Device
                                    </span>
                                @endif
                                <span class="badge bg-{{ $deviceRecord->online_status_color }}-lt text-{{ $deviceRecord->online_status_color }} ms-2">
                                    @if($deviceRecord->online_status_color == 'success')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M12 1l0 6" /><path d="M12 17l0 6" /><path d="M5.636 5.636l4.243 4.243" /><path d="M14.121 14.121l4.243 4.243" /><path d="M1 12l6 0" /><path d="M17 12l6 0" /><path d="M5.636 18.364l4.243 -4.243" /><path d="M14.121 9.879l4.243 -4.243" /></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12l6 0" /><path d="M15 12l6 0" /><path d="M12 3l0 6" /><path d="M12 15l0 6" /></svg>
                                    @endif
                                    {{ $deviceRecord->online_status_text }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 9h8" /><path d="M8 13h6" /><path d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z" /></svg>
                                        Device Code
                                    </label>
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-primary-lt text-primary fs-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                            {{ $deviceId }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                        Device Name
                                    </label>
                                    <div class="form-control-plaintext">
                                        @if($deviceRecord && $deviceRecord->name)
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-indigo-lt text-indigo me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" /></svg>
                                                    Device
                                                </span>
                                                <strong>{{ $deviceRecord->name }}</strong>
                                            </div>
                                        @else
                                            <span class="text-muted d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9h.01" /><path d="M11 12h1v4h1" /><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" /></svg>
                                                Not configured
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" /></svg>
                                        Location
                                    </label>
                                    <div class="form-control-plaintext">
                                        @if($deviceRecord && $deviceRecord->location)
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-orange-lt text-orange me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 7l5 5l-5 5h9a2 2 0 0 0 2 -2v-6a2 2 0 0 0 -2 -2h-9z" /></svg>
                                                    Location
                                                </span>
                                                {{ $deviceRecord->location }}
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-gray-lt text-gray me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M12.5 21.755a2 2 0 0 1 -1.5 -1.755v-1l-1 -1v-1l1 -1v-1l1 -1v-1l1 -1l1 1l1 1v1l1 1v1l-1 1v1" /><path d="M12 18l-2 -2l2 -2l2 2z" /></svg>
                                                    AWS
                                                </span>
                                                {{ $awsDevice->device_location ?? 'Not specified' }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                        Device Type
                                    </label>
                                    <div class="form-control-plaintext">
                                        @if($deviceRecord && $deviceRecord->type)
                                            <span class="badge bg-azure-lt text-azure">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-10z" /><path d="M7 10h10" /><path d="M7 14h10" /></svg>
                                                {{ $deviceRecord->type }}
                                            </span>
                                        @else
                                            <span class="text-muted d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9h.01" /><path d="M11 12h1v4h1" /><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" /></svg>
                                                Not configured
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" /></svg>
                                        GPS Coordinates
                                    </label>
                                    <div class="form-control-plaintext">
                                        @if($awsDevice && $awsDevice->lat && $awsDevice->lng)
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-teal-lt text-teal me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M12 1l0 6" /><path d="M12 17l0 6" /><path d="M5.636 5.636l4.243 4.243" /><path d="M14.121 14.121l4.243 4.243" /><path d="M1 12l6 0" /><path d="M17 12l6 0" /><path d="M5.636 18.364l4.243 -4.243" /><path d="M14.121 9.879l4.243 -4.243" /></svg>
                                                    GPS
                                                </span>
                                                <div>
                                                    <div class="text-sm">{{ number_format($awsDevice->lat, 6) }}, {{ number_format($awsDevice->lng, 6) }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M12.5 21.755a2 2 0 0 1 -1.5 -1.755v-1l-1 -1v-1l1 -1v-1l1 -1v-1l1 -1l1 1l1 1v1l1 1v1l-1 1v1" /><path d="M12 18l-2 -2l2 -2l2 2z" /></svg>
                                                Not available
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Connection Status</label>
                                    <div class="d-flex align-items-center">
                                        @if($deviceStats['last_record'] && $deviceStats['last_record']->terminal_time->diffInMinutes() < 60)
                                            <div class="status-dot status-dot-animated bg-success me-2"></div>
                                            <div>
                                                <span class="badge bg-success-lt text-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M12 1l0 6" /><path d="M12 17l0 6" /><path d="M5.636 5.636l4.243 4.243" /><path d="M14.121 14.121l4.243 4.243" /><path d="M1 12l6 0" /><path d="M17 12l6 0" /><path d="M5.636 18.364l4.243 -4.243" /><path d="M14.121 9.879l4.243 -4.243" /></svg>
                                                    Online
                                                </span>
                                                <div class="small text-success mt-1">
                                                    Last data: {{ $deviceStats['last_record']->terminal_time->diffForHumans() }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="status-dot bg-danger me-2"></div>
                                            <div>
                                                <span class="badge bg-danger-lt text-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12l6 0" /><path d="M15 12l6 0" /><path d="M12 3l0 6" /><path d="M12 15l0 6" /></svg>
                                                    Offline
                                                </span>
                                                <div class="small text-danger mt-1">
                                                    @if($deviceStats['last_record'])
                                                        Last seen: {{ $deviceStats['last_record']->terminal_time->diffForHumans() }}
                                                    @else
                                                        No data received
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Device Statistics -->
            <div class="col-12 mb-4">
                <div class="row row-cards">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm mb-3">
                            <div class="card-body dashboard-stats-card">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-warning text-white avatar avatar-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 13.5a4 4 0 1 0 4 0v-8.5a2 2 0 0 0 -4 0v8.5" /><path d="M10 9l4 0" /></svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">{{ number_format($deviceStats['avg_temperature'], 1) }}°C</div>
                                        <div class="text-secondary">Avg Temperature</div>
                                        <div class="small text-muted mt-1">
                                            <div>Min: {{ number_format($deviceStats['min_temperature'], 1) }}°C</div>
                                            <div>Max: {{ number_format($deviceStats['max_temperature'], 1) }}°C</div>
                                        </div>
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
                                        <span class="bg-success text-white avatar avatar-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5.636 5.636a9 9 0 1 0 12.728 12.728" /><path d="M16.95 9l3 3l-3 3" /><path d="M11 12h9" /></svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">{{ number_format($deviceStats['avg_wind_speed'], 1) }} m/s</div>
                                        <div class="text-secondary">Avg Wind Speed</div>
                                        <div class="small text-muted mt-1">
                                            <div>Min: {{ number_format($deviceStats['min_wind_speed'], 1) }} m/s</div>
                                            <div>Max: {{ number_format($deviceStats['max_wind_speed'], 1) }} m/s</div>
                                        </div>
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
                                        <span class="bg-info text-white avatar avatar-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-12" /></svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">{{ number_format($deviceStats['total_rainfall'], 1) }}mm</div>
                                        <div class="text-secondary">Total Rainfall</div>
                                        <div class="small text-muted mt-1">
                                            <div>Min: {{ number_format($deviceStats['min_rainfall'], 1) }} mm</div>
                                            <div>Max: {{ number_format($deviceStats['max_rainfall'], 1) }} mm</div>
                                        </div>
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
                                        <span class="bg-orange text-white avatar avatar-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">{{ number_format($deviceStats['avg_solar_radiation'], 1) }} W/m²</div>
                                        <div class="text-secondary">Avg Solar Radiation</div>
                                        <div class="small text-muted mt-1">
                                            <div>Min: {{ number_format($deviceStats['min_solar_radiation'], 1) }} W/m²</div>
                                            <div>Max: {{ number_format($deviceStats['max_solar_radiation'], 1) }} W/m²</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Temperature & Humidity</h3>
                    </div>
                    <div class="card-body">
                        <div id="temp-humidity-chart"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Wind Speed & Direction</h3>
                    </div>
                    <div class="card-body">
                        <div id="wind-chart"></div>
                    </div>
                </div>
            </div>

            <!-- Recent Data -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Data</h3>
                        <div class="card-actions">
                            <span class="text-muted">{{ number_format($deviceStats['total_records']) }} total records</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="recentDataTable" class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Temperature</th>
                                        <th>Humidity</th>
                                        <th>Wind Speed</th>
                                        <th>Wind Direction</th>
                                        <th>Pressure</th>
                                        <th>Rainfall</th>
                                        <th>Solar Radiation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via server-side processing -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.status-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
    position: relative;
}

.status-dot-animated {
    animation: pulse 2s infinite;
}

.status-dot-animated::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: inherit;
    opacity: 0.6;
    animation: pulse-ring 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
    }
    
    70% {
        transform: scale(1);
        box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
    }
    
    100% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
    }
}

@keyframes pulse-ring {
    0% {
        transform: scale(0.33);
    }
    80%, 100% {
        opacity: 0;
        transform: scale(1.33);
    }
}

.badge.bg-success-lt {
    background-color: rgba(34, 197, 94, 0.1) !important;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.badge.bg-danger-lt {
    background-color: rgba(239, 68, 68, 0.1) !important;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.badge.bg-secondary-lt {
    background-color: rgba(107, 114, 128, 0.1) !important;
    border: 1px solid rgba(107, 114, 128, 0.2);
}

.badge.bg-primary-lt {
    background-color: rgba(59, 130, 246, 0.1) !important;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.badge.bg-indigo-lt {
    background-color: rgba(99, 102, 241, 0.1) !important;
    border: 1px solid rgba(99, 102, 241, 0.2);
}

.badge.bg-orange-lt {
    background-color: rgba(249, 115, 22, 0.1) !important;
    border: 1px solid rgba(249, 115, 22, 0.2);
}

.badge.bg-azure-lt {
    background-color: rgba(3, 169, 244, 0.1) !important;
    border: 1px solid rgba(3, 169, 244, 0.2);
}

.badge.bg-teal-lt {
    background-color: rgba(20, 184, 166, 0.1) !important;
    border: 1px solid rgba(20, 184, 166, 0.2);
}

.badge.bg-gray-lt {
    background-color: rgba(156, 163, 175, 0.1) !important;
    border: 1px solid rgba(156, 163, 175, 0.2);
}

/* Text colors */
.text-azure { color: #03a9f4 !important; }
.text-indigo { color: #6366f1 !important; }
.text-orange { color: #f97316 !important; }
.text-teal { color: #14b8a6 !important; }
.text-gray { color: #9ca3af !important; }

.device-status-card {
    /* background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); */
    border-left: 4px solid #22c55e;
    transition: all 0.3s ease;
}

.device-status-card.offline {
    border-left-color: #ef4444;
}

.device-status-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Consistent card spacing */
.row-deck .col-12 {
    margin-bottom: 1.5rem;
}

.row-cards .card {
    margin-bottom: 1rem;
}

/* Stats cards specific spacing */
.dashboard-stats-card .card {
    height: 100%;
    margin-bottom: 0;
}

/* Chart cards responsive spacing */
@media (max-width: 991.98px) {
    .col-lg-6.mb-4 {
        margin-bottom: 1.5rem !important;
    }
}

/* Last card no margin bottom */
.row-deck .col-12:last-child {
    margin-bottom: 0;
}

/* DataTable Tabler Theme Integration */
.dataTables_wrapper {
    margin-top: 1rem;
    color: var(--tblr-body-color);
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    color: var(--tblr-body-color);
}

.dataTables_wrapper .dataTables_length select {
    padding: 0.375rem 1.75rem 0.375rem 0.75rem !important;
    border: 1px solid var(--tblr-border-color) !important;
    border-radius: var(--tblr-border-radius) !important;
    background-color: var(--tblr-bg-surface) !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right 0.75rem center !important;
    background-size: 16px 12px !important;
    min-width: 60px;
    color: var(--tblr-body-color) !important;
}

.dataTables_wrapper .dataTables_filter input {
    padding: 0.375rem 0.75rem !important;
    border: 1px solid var(--tblr-border-color) !important;
    border-radius: var(--tblr-border-radius) !important;
    background-color: var(--tblr-bg-surface) !important;
    color: var(--tblr-body-color) !important;
    margin-left: 0.5rem !important;
    width: 250px !important;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: var(--tblr-primary) !important;
    box-shadow: 0 0 0 0.2rem rgba(var(--tblr-primary-rgb), 0.25) !important;
    outline: 0;
}

.dataTables_wrapper .dt-buttons {
    margin-bottom: 0.75rem;
}

.dataTables_wrapper .dt-buttons .btn {
    margin-right: 0.25rem;
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
    padding: 0.25rem 0.75rem;
    border-radius: var(--tblr-border-radius);
}

.dataTables_wrapper .dataTables_info {
    padding-top: 0.75rem;
    font-size: 0.875rem;
    color: var(--tblr-secondary);
}

/* DataTable Table Styling */
#recentDataTable {
    background-color: var(--tblr-bg-surface) !important;
    color: var(--tblr-body-color) !important;
}

#recentDataTable thead th {
    background-color: var(--tblr-bg-surface-secondary) !important;
    border-bottom: 2px solid var(--tblr-border-color) !important;
    color: var(--tblr-body-color) !important;
    font-weight: 600;
    padding: 0.75rem;
}

#recentDataTable tbody td {
    vertical-align: middle;
    border-top: 1px solid var(--tblr-border-color) !important;
    color: var(--tblr-body-color) !important;
    padding: 0.75rem;
}

#recentDataTable tbody tr:hover {
    background-color: var(--tblr-bg-surface-secondary) !important;
}

#recentDataTable tbody tr:nth-child(odd) {
    background-color: var(--tblr-bg-surface) !important;
}

#recentDataTable tbody tr:nth-child(even) {
    background-color: var(--tblr-bg-surface-tertiary, var(--tblr-bg-surface)) !important;
}

/* DataTable Controls Wrapper */
#recentDataTable_wrapper .row {
    margin: 0 -0.5rem;
}

#recentDataTable_wrapper .row > div {
    padding: 0.5rem;
}

#recentDataTable_wrapper .row:first-child {
    background: var(--tblr-bg-surface-secondary) !important;
    border-radius: var(--tblr-border-radius) var(--tblr-border-radius) 0 0;
    border-bottom: 1px solid var(--tblr-border-color);
    margin-bottom: 0.5rem;
    padding: 1rem 0;
}

/* DataTable Search and Length Controls */
.dataTables_wrapper .dataTables_length label,
.dataTables_wrapper .dataTables_filter label {
    font-weight: 500;
    margin-bottom: 0;
    color: var(--tblr-body-color);
}

/* Dark theme specific adjustments */
[data-bs-theme="dark"] .dataTables_wrapper .dataTables_length select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
}

/* Responsive DataTable Improvements (Tabler Style) */
@media (max-width: 991.98px) {
    .dataTables_wrapper .dataTables_filter input {
        width: 100% !important;
        margin: 0.5rem 0 0 0 !important;
    }
    
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        text-align: center;
        margin-bottom: 0.75rem;
    }
    
    .dataTables_wrapper .dt-buttons {
        text-align: center;
        margin-bottom: 1rem;
    }
    
    .dataTables_wrapper .dt-buttons .btn {
        margin: 0.125rem;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    #recentDataTable_wrapper .row:first-child {
        text-align: center;
    }
}

@media (max-width: 767.98px) {
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        text-align: center;
        margin-top: 0.75rem;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem !important;
        margin: 0 2px;
        font-size: 0.8rem;
        min-width: 2rem;
    }
}

/* Print Styles (Tabler Compatible) */
@media print {
    .print-table {
        font-size: 10pt !important;
        color: #000 !important;
    }
    
    .print-table table {
        width: 100% !important;
        border-collapse: collapse !important;
    }
    
    .print-table th,
    .print-table td {
        border: 1px solid #000 !important;
        padding: 4px !important;
    }
    
    .print-table .badge {
        background: none !important;
        color: #000 !important;
        border: 1px solid #000 !important;
        padding: 2px 4px !important;
        border-radius: 2px !important;
    }
}

/* Loading Animation (Tabler Style) */
.dataTables_processing {
    font-weight: 500;
}

.dataTables_processing::after {
    content: '';
    display: inline-block;
    width: 16px;
    height: 16px;
    margin-left: 0.5rem;
    border: 2px solid var(--tblr-primary);
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* DataTable Focus States */
.dataTables_wrapper .dataTables_filter input:focus,
.dataTables_wrapper .dataTables_length select:focus {
    outline: 0;
    border-color: var(--tblr-primary);
    box-shadow: 0 0 0 0.2rem rgba(var(--tblr-primary-rgb), 0.25);
}

/* Badge Styling in DataTable */
#recentDataTable .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: var(--tblr-border-radius-sm);
}
</style>
@endpush

@push('scripts')
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    /**
     * Initialize ApexCharts
     */
    if (typeof ApexCharts !== 'undefined') {
        // Temperature & Humidity Chart
        const tempHumidityEl = document.querySelector("#temp-humidity-chart");
        if (tempHumidityEl) {
            const tempHumidityData = @json($records->take(24)->reverse()->values()->map(function($item) {
                return ['time' => $item->terminal_time->format('H:i'), 'temperature' => (float) $item->temperature, 'humidity' => (float) $item->humidity];
            }));
            const tempHumidityChart = new ApexCharts(tempHumidityEl, {
                chart: { type: 'line', height: 300, toolbar: { show: false } },
                series: [
                    { name: 'Temperature (°C)', data: tempHumidityData.map(item => ({x: item.time, y: item.temperature})) },
                    { name: 'Humidity (%)', data: tempHumidityData.map(item => ({x: item.time, y: item.humidity})) }
                ],
                xaxis: { type: 'category', title: { text: 'Time' } },
                yaxis: [
                    { title: { text: 'Temperature (°C)' }, seriesName: 'Temperature (°C)' },
                    { opposite: true, title: { text: 'Humidity (%)' }, seriesName: 'Humidity (%)' }
                ],
                colors: ['#f59f00', '#4299e1'],
                stroke: { curve: 'smooth' }
            });
            tempHumidityChart.render();
        }

        // Wind Chart
        const windChartEl = document.querySelector("#wind-chart");
        if (windChartEl) {
            const windData = @json($records->take(24)->reverse()->values()->map(function($item) {
                return ['time' => $item->terminal_time->format('H:i'), 'speed' => (float) $item->wind_speed];
            }));
            const windChart = new ApexCharts(windChartEl, {
                chart: { type: 'line', height: 300, toolbar: { show: false } },
                series: [{ name: 'Wind Speed (m/s)', data: windData.map(item => ({x: item.time, y: item.speed})) }],
                xaxis: { type: 'category', title: { text: 'Time' } },
                yaxis: { title: { text: 'Wind Speed (m/s)' } },
                colors: ['#2fb344'],
                stroke: { curve: 'smooth' }
            });
            windChart.render();
        }
    } else {
        console.error('ApexCharts library not found.');
    }

    /**
     * Initialize DataTables
     */
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        const dataTableConfig = {
            processing: true,
            serverSide: true,
            ajax: "{{ route('aws-logger.datatables', $deviceId) }}",
            columns: [
                {data: "terminal_time", name: "terminal_time", title: "Time"},
                {data: "temperature", name: "temperature", title: "Temperature"},
                {data: "humidity", name: "humidity", title: "Humidity"},
                {data: "wind_speed", name: "wind_speed", title: "Wind Speed"},
                {data: "wind_direction", name: "wind_direction", title: "Wind Direction"},
                {data: "pressure", name: "pressure", title: "Pressure"},
                {data: "rainfall", name: "rainfall", title: "Rainfall"},
                {data: "solar_radiation", name: "solar_radiation", title: "Solar Radiation"}
            ],
            paging: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[0, "desc"]],
            responsive: { details: { type: 'column', target: 'tr' } },
            searching: true,
            ordering: true,
            info: true,
            lengthChange: true,
            autoWidth: false,
            pagingType: "full_numbers",
            initComplete: function() {
                $('.dataTables_filter input').attr('placeholder', 'Cari data...');
                $('.dataTables_length select').addClass('form-select form-select-sm');
            }
        };

        if ($.fn.DataTable.ext.buttons) {
            dataTableConfig.dom = '<"row"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-4 text-center"B><"col-sm-12 col-md-4"f>>' +
                                  '<"row"<"col-sm-12"t>>' +
                                  '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>';
            dataTableConfig.buttons = [
                { extend: 'excel', text: '<i class="ti ti-file-excel"></i> Excel', className: 'btn btn-success btn-sm me-1', title: 'AWS Logger Data - {{ $deviceId }}', exportOptions: { columns: ':visible' } },
                { extend: 'csv', text: '<i class="ti ti-file-text"></i> CSV', className: 'btn btn-primary btn-sm me-1', title: 'AWS Logger Data - {{ $deviceId }}', exportOptions: { columns: ':visible' } }
            ];
        }

        $('#recentDataTable').DataTable(dataTableConfig);
    } else {
        console.error('jQuery or DataTables library not found.');
    }
});
</script>
@endpush
