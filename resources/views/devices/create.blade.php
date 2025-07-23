@extends('layouts.tabler')

@section('title', 'Add Device')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('devices.index') }}">Devices</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Device</li>
                    </ol>
                </nav>
                <h2 class="page-title">Add New Device</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
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
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('devices.store') }}">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Device Code</label>
                                        <input type="text" 
                                               class="form-control @error('code') is-invalid @enderror" 
                                               name="code" 
                                               value="{{ old('code') }}" 
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
                                               value="{{ old('name') }}" 
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
                                               value="{{ old('location') }}" 
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
                                            <option value="Weather Station" {{ old('type') == 'Weather Station' ? 'selected' : '' }}>Weather Station</option>
                                            <option value="Environmental Monitor" {{ old('type') == 'Environmental Monitor' ? 'selected' : '' }}>Environmental Monitor</option>
                                            <option value="Temperature Sensor" {{ old('type') == 'Temperature Sensor' ? 'selected' : '' }}>Temperature Sensor</option>
                                            <option value="Humidity Sensor" {{ old('type') == 'Humidity Sensor' ? 'selected' : '' }}>Humidity Sensor</option>
                                            <option value="Air Quality Monitor" {{ old('type') == 'Air Quality Monitor' ? 'selected' : '' }}>Air Quality Monitor</option>
                                            <option value="IoT Gateway" {{ old('type') == 'IoT Gateway' ? 'selected' : '' }}>IoT Gateway</option>
                                            <option value="Data Logger" {{ old('type') == 'Data Logger' ? 'selected' : '' }}>Data Logger</option>
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
                                               value="{{ old('serial_number') }}" 
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
                                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
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
                                          placeholder="Additional information about the device...">{{ old('description') }}</textarea>
                                <div class="form-hint">Optional description or notes about the device.</div>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-footer">
                                <div class="row">
                                    <div class="col">
                                        <a href="{{ route('devices.index') }}" class="btn btn-outline-secondary">
                                            Cancel
                                        </a>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                            Create Device
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tips Card -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9h.01" /><path d="M11 12h1v4h1" /><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" /></svg>
                            Tips
                        </h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <strong>Device Code:</strong> Should match the device_id used in AWS Logger data. This is used to link device records with logged data.
                            </li>
                            <li class="mb-2">
                                <strong>Unique Codes:</strong> Each device code must be unique across all devices.
                            </li>
                            <li class="mb-2">
                                <strong>AWS Logger Integration:</strong> After creating a device, you can view its logged data by clicking "AWS Logs" in the device list.
                            </li>
                            <li class="mb-0">
                                <strong>Auto-sync:</strong> Use the "Sync with AWS Logger" button on the devices list to automatically create device records for any AWS Logger device_ids that don't have corresponding devices.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-generate device code based on name and type
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.querySelector('input[name="name"]');
    const typeSelect = document.querySelector('select[name="type"]');
    const codeInput = document.querySelector('input[name="code"]');
    
    function generateCode() {
        if (codeInput.value) return; // Don't override if user has entered a code
        
        let code = '';
        const name = nameInput.value.trim();
        const type = typeSelect.value;
        
        if (name) {
            // Take first word of name
            code = name.split(' ')[0].toUpperCase();
        }
        
        if (type) {
            // Add type prefix
            const typePrefix = type.split(' ')[0].substring(0, 3).toUpperCase();
            code = typePrefix + (code ? '-' + code : '');
        }
        
        if (code) {
            // Add random number
            code += '-' + Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            codeInput.value = code;
        }
    }
    
    nameInput.addEventListener('blur', generateCode);
    typeSelect.addEventListener('change', generateCode);
});
</script>
@endpush
