@extends('layouts.tabler')

@section('title', 'Edit User - ' . $user->name)

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Management</div>
                <h2 class="page-title">Edit User - {{ $user->name }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M5 12l14 0" />
                            <path d="M5 12l6 6" />
                            <path d="M5 12l6 -6" />
                        </svg>
                        Back to Details
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        All Users
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Edit User Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name', $user->name) }}" placeholder="Enter full name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email', $user->email) }}" placeholder="Enter email address" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                                    <div class="input-group input-group-flat">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               name="password" placeholder="Enter new password" id="password-field">
                                        <span class="input-group-text">
                                            <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip" onclick="togglePassword('password-field', this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path d="M21 12c-2.4 3.6 -6 6 -9 6c-3 0 -6.6 -2.4 -9 -6c2.4 -3.6 6 -6 9 -6c3 0 6.6 2.4 9 6" />
                                                </svg>
                                            </a>
                                        </span>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">Leave blank to keep current password.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <div class="input-group input-group-flat">
                                        <input type="password" class="form-control" name="password_confirmation" 
                                               placeholder="Confirm new password" id="password-confirm-field">
                                        <span class="input-group-text">
                                            <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip" onclick="togglePassword('password-confirm-field', this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path d="M21 12c-2.4 3.6 -6 6 -9 6c-3 0 -6.6 -2.4 -9 -6c2.4 -3.6 6 -6 9 -6c3 0 6.6 2.4 9 6" />
                                                </svg>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-check">
                                        <input class="form-check-input" type="checkbox" name="verified" value="1" 
                                               {{ old('verified', $user->email_verified_at ? '1' : '') ? 'checked' : '' }}>
                                        <span class="form-check-label">Email is verified</span>
                                        <span class="form-check-description">
                                            @if($user->email_verified_at)
                                                Currently verified on {{ $user->email_verified_at->format('M d, Y \a\t H:i') }}
                                            @else
                                                User has not verified their email address yet
                                            @endif
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <!-- User Stats -->
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="mb-3">Account Information</h4>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card card-sm">
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <div class="text-muted">Member Since</div>
                                                        <div class="h4 mb-0">{{ $user->created_at->format('M d, Y') }}</div>
                                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card card-sm">
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <div class="text-muted">User ID</div>
                                                        <div class="h4 mb-0">#{{ $user->id }}</div>
                                                        <small class="text-muted">Unique identifier</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card card-sm">
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <div class="text-muted">Last Updated</div>
                                                        <div class="h4 mb-0">{{ $user->updated_at->format('M d') }}</div>
                                                        <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <a href="{{ route('users.show', $user) }}" class="btn btn-link">Cancel</a>
                                <button type="submit" class="btn btn-primary ms-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 12l5 5l10 -10" />
                                    </svg>
                                    Update User
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(fieldId, element) {
    const field = document.getElementById(fieldId);
    const icon = element.querySelector('svg');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.innerHTML = '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.09 4.01l.496 -.004a2 2 0 0 1 1.98 1.869l.017 .13l.007 1.05a7.09 7.09 0 0 1 2.586 3.414l.087 .19a2 2 0 0 1 -.087 2.19l-.087 .19a7.09 7.09 0 0 1 -2.586 3.414l-.007 1.05a2 2 0 0 1 -1.98 1.869l-.117 .006l-.498 -.004l-4.965 -.066a2 2 0 0 1 -1.994 -2l-.006 -.124l.006 -10.752a2 2 0 0 1 1.994 -2l.124 -.006l4.965 .066z"/><path d="M3 3l18 18" />';
    } else {
        field.type = 'password';
        icon.innerHTML = '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 3.6 -6 6 -9 6c-3 0 -6.6 -2.4 -9 -6c2.4 -3.6 6 -6 9 -6c3 0 6.6 2.4 9 6" />';
    }
}
</script>
@endpush
