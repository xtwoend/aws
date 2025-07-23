@extends('layouts.tabler')

@section('title', 'Device Management')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Device Management</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <button class="btn btn-outline-secondary" onclick="syncWithAwsLogger()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                        Sync with AWS Logger
                    </button>
                    <a href="{{ route('devices.export') }}" class="btn btn-outline-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg>
                        Export CSV
                    </a>
                    <a href="{{ route('devices.create') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Add Device
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Statistics Cards -->
        <div class="row row-deck row-cards mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm mb-3">
                    <div class="card-body dashboard-stats-card">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-10z" /><path d="M7 10h10" /><path d="M7 14h10" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $stats['total_devices'] }}</div>
                                <div class="text-secondary">Total Devices</div>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $stats['active_devices'] }}</div>
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
                                <span class="bg-info text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M12 1l0 6" /><path d="M12 17l0 6" /><path d="M5.636 5.636l4.243 4.243" /><path d="M14.121 14.121l4.243 4.243" /><path d="M1 12l6 0" /><path d="M17 12l6 0" /><path d="M5.636 18.364l4.243 -4.243" /><path d="M14.121 9.879l4.243 -4.243" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $stats['online_devices'] }}</div>
                                <div class="text-secondary">Online Devices</div>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12h4l3 8l4-16l3 8h4" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ number_format($stats['total_logs_today']) }}</div>
                                <div class="text-secondary">Logs Today</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Filters</h3>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('devices.index') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Search</label>
                                        <input type="text" class="form-control" name="search" 
                                               value="{{ request('search') }}" 
                                               placeholder="Search by code, name, or location">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="">All Status</option>
                                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Type</label>
                                        <select class="form-select" name="type">
                                            <option value="">All Types</option>
                                            @foreach($deviceTypes as $type)
                                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="btn-list">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                            <a href="{{ route('devices.index') }}" class="btn btn-outline-secondary">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Devices Table -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Devices ({{ $devices->total() }} total)</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Online Status</th>
                                        <th>Last Data</th>
                                        <th>Serial Number</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($devices as $device)
                                    <tr>
                                        <td>
                                            <strong>{{ $device->code }}</strong>
                                        </td>
                                        <td>{{ $device->name ?: 'N/A' }}</td>
                                        <td>{{ $device->location ?: 'N/A' }}</td>
                                        <td>
                                            @if($device->type)
                                                <span class="badge bg-secondary">{{ $device->type }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $device->status_color }} cursor-pointer" 
                                                  onclick="toggleStatus('{{ $device->id }}')">
                                                {{ $device->status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $device->online_status_color }}">
                                                {{ $device->online_status_text }}
                                            </span>
                                        </td>
                                        <td class="text-secondary">
                                            <div>{{ $device->last_seen }}</div>
                                            @if($device->latestLog)
                                                <small class="text-muted">{{ $device->latestLog->terminal_time->format('M j, H:i') }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $device->serial_number ?: 'N/A' }}</td>
                                        <td>
                                            <div class="btn-list">
                                                <a href="{{ route('devices.show', $device) }}" class="btn btn-sm btn-outline-primary">
                                                    View
                                                </a>
                                                <a href="{{ route('devices.edit', $device) }}" class="btn btn-sm btn-outline-secondary">
                                                    Edit
                                                </a>
                                                <a href="{{ route('aws-logger.show', $device->code) }}" class="btn btn-sm btn-outline-info">
                                                    AWS Logs
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteDevice('{{ $device->id }}')">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">No devices found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($devices->hasPages())
                    <div class="card-footer d-flex align-items-center">
                        {{ $devices->links() }}
                    </div>
                    @endif
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
                <p>Are you sure you want to delete this device?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
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
function toggleStatus(deviceId) {
    fetch(`/devices/${deviceId}/toggle-status`, {
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

function deleteDevice(deviceId) {
    document.getElementById('deleteForm').action = `/devices/${deviceId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function syncWithAwsLogger() {
    if (confirm('This will create devices for any AWS Logger device IDs that don\'t have corresponding device records. Continue?')) {
        fetch('/devices/sync-aws-logger', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error syncing with AWS Logger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error syncing with AWS Logger');
        });
    }
}
</script>
@endpush
