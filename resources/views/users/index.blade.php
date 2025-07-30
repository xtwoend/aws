@extends('layouts.tabler')

@section('title', 'User Management')



@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Management</div>
                <h2 class="page-title">User Management</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                @if(Auth::user()->role === 'admin')
                <div class="btn-list">
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Add New User
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Menu Tabs -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="userTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="all-users-tab" data-bs-toggle="tab" data-bs-target="#all-users" type="button" role="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                    </svg>
                                    All Users
                                    <span class="badge bg-primary ms-2" id="total-users">0</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="verified-users-tab" data-bs-toggle="tab" data-bs-target="#verified-users" type="button" role="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M9 12l2 2l4 -4" />
                                        <path d="M21 12c-1 0 -3 -1 -3 -3s2 -3 3 -3s3 1 3 3s-2 3 -3 3" />
                                        <path d="M3 12c1 0 3 -1 3 -3s-2 -3 -3 -3s-3 1 -3 3s2 3 3 3" />
                                        <path d="M12 3c0 1 1 3 3 3s3 -2 3 -3s-1 -3 -3 -3s-3 2 -3 3" />
                                        <path d="M12 21c0 -1 -1 -3 -3 -3s-3 2 -3 3s1 3 3 3s3 -2 3 -3" />
                                    </svg>
                                    Verified Users
                                    <span class="badge bg-success ms-2" id="verified-count">0</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="unverified-users-tab" data-bs-toggle="tab" data-bs-target="#unverified-users" type="button" role="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 9v4" />
                                        <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                                        <path d="M12 16h.01" />
                                    </svg>
                                    Unverified Users
                                    <span class="badge bg-warning ms-2" id="unverified-count">0</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="recent-users-tab" data-bs-toggle="tab" data-bs-target="#recent-users" type="button" role="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 2l0 20" />
                                        <path d="M17 7l-5 5l-5 -5" />
                                    </svg>
                                    Recent Joins
                                    <span class="badge bg-info ms-2" id="recent-count">0</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="userTabsContent">
            <!-- All Users Tab -->
            <div class="tab-pane fade show active" id="all-users" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All System Users</h3>
                        <div class="card-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="refreshTable()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                    <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                </svg>
                                Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="usersTable" class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Joined</th>
                                        <th width="150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables will populate this -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verified Users Tab -->
            <div class="tab-pane fade" id="verified-users" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Verified Users</h3>
                        <div class="card-actions">
                            <span class="text-success">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 12l5 5l10 -10" />
                                </svg>
                                Email verified accounts
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="verifiedUsersTable" class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Verified At</th>
                                        <th>Joined</th>
                                        <th width="150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables will populate this -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Unverified Users Tab -->
            <div class="tab-pane fade" id="unverified-users" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Unverified Users</h3>
                        <div class="card-actions">
                            <span class="text-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 9v4" />
                                    <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                                    <path d="M12 16h.01" />
                                </svg>
                                Pending email verification
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="unverifiedUsersTable" class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Registered</th>
                                        <th>Days Pending</th>
                                        <th width="150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables will populate this -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Users Tab -->
            <div class="tab-pane fade" id="recent-users" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Registrations</h3>
                        <div class="card-actions">
                            <span class="text-info">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                    <path d="M12 7v5l3 3" />
                                </svg>
                                Last 30 days
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="recentUsersTable" class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Registered</th>
                                        <th width="150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables will populate this -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal modal-blur fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 9v4" />
                    <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                    <path d="M12 16h.01" />
                </svg>
                <h3>Are you sure?</h3>
                <div class="text-muted">Do you really want to delete this user? This action cannot be undone.</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn w-100" data-bs-dismiss="modal">Cancel</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-danger w-100" id="confirmDelete">Delete User</button>
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
let usersTable;
let userIdToDelete = null;

// Function to initialize DataTables after libraries are loaded
function initializeUserManagement() {
    // Check if jQuery and DataTables are available
    if (typeof window.$ === 'undefined' || typeof window.jQuery === 'undefined') {
        console.warn('jQuery not loaded yet, retrying...');
        setTimeout(initializeUserManagement, 100);
        return;
    }
    
    if (typeof window.$.fn.DataTable === 'undefined') {
        console.warn('DataTables not loaded yet, retrying...');
        setTimeout(initializeUserManagement, 100);
        return;
    }
    
    // Ensure table elements exist
    if (!document.getElementById('usersTable')) {
        console.warn('Table element not found, retrying...');
        setTimeout(initializeUserManagement, 100);
        return;
    }
    
    // Use window.$ directly instead of reassigning
    console.log('jQuery loaded:', typeof window.$ !== 'undefined');
    console.log('DataTables loaded:', typeof window.$.fn.DataTable !== 'undefined');
    
    // Initialize DataTable for All Users
    usersTable = window.$('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("users.index") }}',
        columns: [
            {data: 'name', name: 'name', title: 'Name'},
            {data: 'email', name: 'email', title: 'Email'},
            {data: 'role_badge', name: 'role', title: 'Role', orderable: false, searchable: false},
            {data: 'status', name: 'status', orderable: false, searchable: false, title: 'Status'},
            {data: 'joined', name: 'joined', orderable: true, searchable: false, title: 'Joined'},
            {data: 'action', name: 'action', orderable: false, searchable: false, title: 'Actions'}
        ],
        order: [[4, 'desc']],
        pageLength: 25,
        responsive: {
            details: {
                type: 'column',
                target: 'tr'
            }
        },
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            search: '',
            searchPlaceholder: 'Search users...'
        },
        drawCallback: function(settings) {
            updateUserCounts();
        },
        initComplete: function() {
            console.log('DataTable initialized successfully');
        }
    });

    // Initialize other tabs on first click
    window.$('#verified-users-tab').on('shown.bs.tab', function () {
        if (!window.$.fn.DataTable.isDataTable('#verifiedUsersTable')) {
            window.$('#verifiedUsersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("users.index") }}',
                    data: function(d) {
                        d.status = 'verified';
                    }
                },
                columns: [
                    {data: 'name', name: 'name', title: 'Name'},
                    {data: 'email', name: 'email', title: 'Email'},
                    {
                        data: 'email_verified_at',
                        name: 'email_verified_at',
                        title: 'Verified At',
                        render: function(data) {
                            return data ? new Date(data).toLocaleDateString() : 'N/A';
                        }
                    },
                    {data: 'joined', name: 'joined', title: 'Joined'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, title: 'Actions'}
                ],
                pageLength: 25,
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                }
            });
        }
    });

    window.$('#unverified-users-tab').on('shown.bs.tab', function () {
        if (!window.$.fn.DataTable.isDataTable('#unverifiedUsersTable')) {
            window.$('#unverifiedUsersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("users.index") }}',
                    data: function(d) {
                        d.status = 'unverified';
                    }
                },
                columns: [
                    {data: 'name', name: 'name', title: 'Name'},
                    {data: 'email', name: 'email', title: 'Email'},
                    {data: 'created_at', name: 'created_at', title: 'Registered', render: function(data) { return new Date(data).toLocaleDateString(); }},
                    {
                        data: 'created_at',
                        name: 'days_pending',
                        title: 'Days Pending',
                        render: function(data) {
                            const days = Math.floor((new Date() - new Date(data)) / (1000 * 60 * 60 * 24));
                            return days + ' days';
                        }
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false, title: 'Actions'}
                ],
                pageLength: 25,
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                }
            });
        }
    });

    window.$('#recent-users-tab').on('shown.bs.tab', function () {
        if (!window.$.fn.DataTable.isDataTable('#recentUsersTable')) {
            window.$('#recentUsersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("users.index") }}',
                    data: function(d) {
                        d.status = 'recent';
                    }
                },
                columns: [
                    {data: 'name', name: 'name', title: 'Name'},
                    {data: 'email', name: 'email', title: 'Email'},
                    {data: 'status', name: 'status', orderable: false, searchable: false, title: 'Status'},
                    {data: 'joined', name: 'joined', title: 'Registered'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, title: 'Actions'}
                ],
                pageLength: 25,
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                }
            });
        }
    });

    // Delete confirmation modal handlers
    window.$('#confirmDelete').click(function() {
        if (userIdToDelete) {
            window.$.ajax({
                url: '/users/' + userIdToDelete,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    window.$('#deleteModal').modal('hide');
                    if (response.success) {
                        // Show success message
                        showAlert('success', response.message);
                        usersTable.ajax.reload();
                    } else {
                        showAlert('danger', response.message);
                    }
                    userIdToDelete = null;
                },
                error: function(xhr) {
                    window.$('#deleteModal').modal('hide');
                    showAlert('danger', 'An error occurred while deleting the user.');
                    userIdToDelete = null;
                }
            });
        }
    });
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeUserManagement);
} else {
    initializeUserManagement();
}

function refreshTable() {
    if (usersTable && typeof usersTable.ajax !== 'undefined') {
        usersTable.ajax.reload();
    }
}

function updateUserCounts() {
    // This would typically come from an AJAX call to get counts
    // For now, we'll update from the current table data
    if (usersTable && typeof usersTable.page !== 'undefined') {
        const info = usersTable.page.info();
        const totalElement = document.getElementById('total-users');
        if (totalElement) {
            totalElement.textContent = info.recordsTotal;
        }
    }
    
    // Use the PHP variables passed from controller
    const verifiedElement = document.getElementById('verified-count');
    const unverifiedElement = document.getElementById('unverified-count');
    const recentElement = document.getElementById('recent-count');
    
    if (verifiedElement) verifiedElement.textContent = '{{ $verifiedUsers ?? 0 }}';
    if (unverifiedElement) unverifiedElement.textContent = '{{ $unverifiedUsers ?? 0 }}';
    if (recentElement) recentElement.textContent = '{{ $recentUsers ?? 0 }}';
}

function deleteUser(userId) {
    userIdToDelete = userId;
    if (typeof window.bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    } else if (typeof window.$ !== 'undefined') {
        window.$('#deleteModal').modal('show');
    }
}

function showAlert(type, message) {
    const alert = `
        <div class="alert alert-${type} alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        ${type === 'success' ? '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" />' : '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" />'}
                    </svg>
                </div>
                <div>
                    ${message}
                </div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
    `;
    
    const container = document.querySelector('.page-body .container-xl');
    if (container) {
        container.insertAdjacentHTML('afterbegin', alert);
        
        // Auto-remove after 5 seconds
        setTimeout(function() {
            const alertElement = container.querySelector('.alert');
            if (alertElement) {
                alertElement.style.display = 'none';
            }
        }, 5000);
    }
}
</script>
@endpush
