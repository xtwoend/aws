@extends('layouts.tabler')

@section('title', 'Edit Device')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('devices.index') }}">Devices</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('devices.show', $device) }}">{{ $device->code }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
                <h2 class="page-title">Edit Device: {{ $device->code }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('devices.show', $device) }}" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 12l2 2l4 -4" /><path d="M21 12c-1.889 -2.889 -6.708 -7 -9 -7s-7.111 4.111 -9 7c1.889 2.889 6.708 7 9 7s7.111 -4.111 9 -7z" /></svg>
                        View Device
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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Device Information</h3>
                        <div class="card-actions">
                            <span class="badge bg-{{ $device->status_color }}">{{ $device->status_text }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('devices.update', $device) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Device Code</label>
                                        <input type="text" 
                                               class="form-control @error('code') is-invalid @enderror" 
                                               name="code" 
                                               value="{{ old('code', $device->code) }}" 
                                               placeholder="e.g., DEV001, SENSOR-A1" 
                                               required>
                                        <div class="form-hint">Unique identifier for the device. Should match AWS Logger device_id.</div>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Device Name</label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               name="name" 
                                               value="{{ old('name', $device->name) }}" 
                                               placeholder="e.g., Weather Station Alpha">
                                        <div class="form-hint">Friendly name for the device.</div>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Location</label>
                                        <input type="text" 
                                               class="form-control @error('location') is-invalid @enderror" 
                                               name="location" 
                                               value="{{ old('location', $device->location) }}" 
                                               placeholder="e.g., Building A - Rooftop">
                                        <div class="form-hint">Physical location of the device.</div>
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Device Type</label>
                                        <select class="form-select @error('type') is-invalid @enderror" name="type">
                                            <option value="">Select Device Type</option>
                                            <option value="Weather Station" {{ old('type', $device->type) == 'Weather Station' ? 'selected' : '' }}>Weather Station</option>
                                            <option value="Environmental Monitor" {{ old('type', $device->type) == 'Environmental Monitor' ? 'selected' : '' }}>Environmental Monitor</option>
                                            <option value="Temperature Sensor" {{ old('type', $device->type) == 'Temperature Sensor' ? 'selected' : '' }}>Temperature Sensor</option>
                                            <option value="Humidity Sensor" {{ old('type', $device->type) == 'Humidity Sensor' ? 'selected' : '' }}>Humidity Sensor</option>
                                            <option value="Air Quality Monitor" {{ old('type', $device->type) == 'Air Quality Monitor' ? 'selected' : '' }}>Air Quality Monitor</option>
                                            <option value="IoT Gateway" {{ old('type', $device->type) == 'IoT Gateway' ? 'selected' : '' }}>IoT Gateway</option>
                                            <option value="Data Logger" {{ old('type', $device->type) == 'Data Logger' ? 'selected' : '' }}>Data Logger</option>
                                        </select>
                                        <div class="form-hint">Type or category of the device.</div>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Serial Number</label>
                                        <input type="text" 
                                               class="form-control @error('serial_number') is-invalid @enderror" 
                                               name="serial_number" 
                                               value="{{ old('serial_number', $device->serial_number) }}" 
                                               placeholder="e.g., SN123456789">
                                        <div class="form-hint">Hardware serial number of the device.</div>
                                        @error('serial_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-select @error('status') is-invalid @enderror" name="status">
                                            <option value="1" {{ old('status', $device->status) == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('status', $device->status) == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        <div class="form-hint">Whether the device is currently active.</div>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          name="description" 
                                          rows="3" 
                                          placeholder="Additional information about the device...">{{ old('description', $device->description) }}</textarea>
                                <div class="form-hint">Optional description or notes about the device.</div>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-footer">
                                <div class="row">
                                    <div class="col">
                                        <a href="{{ route('devices.show', $device) }}" class="btn btn-outline-secondary">
                                            Cancel
                                        </a>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                            Update Device
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Device Statistics -->
        @if($device->awsLogs()->count() > 0)
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3v18h18" /><path d="M9 9l4 4l4 -4" /></svg>
                            Current Device Stats
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-lg-3">
                                <div class="mb-3">
                                    <div class="text-secondary">Total Logs</div>
                                    <div class="h3 text-primary">{{ number_format($device->awsLogs()->count()) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="mb-3">
                                    <div class="text-secondary">Logs Today</div>
                                    <div class="h3 text-success">{{ number_format($device->awsLogs()->whereDate('terminal_time', today())->count()) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="mb-3">
                                    <div class="text-secondary">Last Data</div>
                                    <div class="h3 text-info">
                                        @if($device->latestLog)
                                            {{ $device->latestLog->terminal_time->diffForHumans() }}
                                        @else
                                            Never
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="mb-3">
                                    <div class="text-secondary">Online Status</div>
                                    <div class="h3">
                                        <span class="badge bg-{{ $device->online_status_color }}">
                                            {{ $device->online_status_text }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Quick Actions -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="btn-list">
                            <a href="{{ route('aws-logger.show', $device->code) }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3v18h18" /><path d="M9 9l4 4l4 -4" /></svg>
                                View AWS Logs
                            </a>
                            <button type="button" class="btn btn-success" onclick="toggleStatus()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                                {{ $device->status ? 'Deactivate' : 'Activate' }} Device
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="deleteDevice()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                Delete Device
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
